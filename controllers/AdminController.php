<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 019 19.01.17
 * Time: 9:27
 */

namespace app\controllers;


use app\models\Clients;
use app\models\CreateML;
use app\models\Email;
use app\models\Excel;
use app\models\MailHost;
use app\models\Voditel;
use Symfony\Component\Finder\SplFileInfo;
use yii\db\Connection;
use yii\web\Controller;
use Yii;

class AdminController extends Controller
{
    public $process;

    public $layout = 'admin';

    protected function getAccess()
    {
        if( (!Yii::$app->user->isGuest) && (Yii::$app->user->can('admin'))) {
            return true;
        }
        return false;
    }


    public function actionMail()
    {
        if($this->getAccess()){
            $host = MailHost::findOne(['active' => 1]);

            $model_email = new Email();
            $msg = $model_email->receiveEmail($host);

            return $this->render('mail', ['mail' => $msg]);

        } else {
            return $this->redirect(Yii::$app->user->loginUrl);
        }

    }


    public function actionCreateml()
    {
        if($this->getAccess()) {
            session_start();
            $_SESSION['process'] = 'начало раборты';
            session_write_close();
            // сначала очищаем папки от файлов
            $path_attach = Yii::getAlias('@attach');
            $path_csv = Yii::getAlias('@attach_csv');
            deleteAllFilesFromDirectory($path_attach);
            deleteAllFilesFromDirectory($path_csv);
            $phpexcel_root = Yii::getAlias('@phpexcel_root');

            $kol_new_clients = 0;

            if (Yii::$app->request->post('uid')) {
                $uid = Yii::$app->request->post('uid');
                Yii::$app->db->createCommand()->insert('log_ml', [
                    'id_ml' => Yii::$app->request->post('uid'),
                    'data_ml' => date('Y-M-D'),
                    'status_ml' => 'process',
                    'autor_ml' => Yii::$app->user->id
                ])->execute();

                session_start();
                $_SESSION['process'] = 'получение почты';
                session_write_close();
                $uid = Yii::$app->request->post('uid');
                $host = MailHost::findOne(['active' => 1]); // с какого сервера получать аттачи
                $model_email = new Email();
                // $arr_attach_files  - массив имен сохраненных файлов (аттачей)
                $arr_attach_files = $model_email->loadAttach($host, $uid, $path_attach, 'ml');
                unset($model_email);
                if(!empty($arr_attach_files)) { //есть аттачи
                    $xls = new Excel();
                    session_start();
                    $_SESSION['process'] = 'преобразование формата';
                    session_write_close();
                    $csv = $xls->Attach_to_Csv($arr_attach_files, $path_attach, $path_csv, $phpexcel_root);
                    if (!empty($csv)) {
                        /** разбираем файлы csv
                         *  - заносим клиентов в БД
                         *  - создаем маршрутный лист для водителя
                        */
                        foreach ($csv as $files) {
                            session_start();
                            $_SESSION['process'] = 'обработка файла ' . $files;
                            session_write_close();
                            unset($arr);
                            $file = new \SplFileObject($path_csv . '/' . $files);
                            // читаем csv файл и создаем массив данных для БД
                            while (!$file->eof()) {
                                $data = $file->fgetcsv();
                                $data = array_diff($data, array('', ' ', '  '));
                                $arr[] = $data;
                                if ( strripos($data[0], 'Итоговая стоимость доставляемого товара')   !== FALSE ) {
                                    break;
                                }
                            }

                            if( !empty($arr)) { // добаялем новых пользователей

                                // находим водителя в БД (table voditel)
                                $dr_name = explode('  ', trim($arr[5][0]));;
                                $driver_name = $dr_name[1]; // ФИО водителя
                                $num_avto = explode(' ', trim($arr[4][0]));;
                                $number_avto = $num_avto[1]; // номер авто водителя
                                $find_driver = Voditel::findOne([
                                    'voditel_name' => $driver_name,
                                    'voditel_nomer_auto' => $number_avto
                                ]);
                                //
                                $type = trim($arr[0][33]);  // Рейс № 322509 (ячейка K4)
                                $flight = trim($arr[4][5]); // 34КрРогСР (ячейка S1)

                                //$str = 'Маршрутный лист № ХВ-0176130 от 28.12.2016';
                                $exp = explode(' ', $arr[0][1]);
                                $id_ml = explode('-', $exp[3]);
                                $data_ml = $exp[5];
                                //переформируем Дату МЛ в формат MySql
                                //  23.01.2017 -->> 2017-01-23
                                list($day, $month, $year) = sscanf($data_ml, "%02d.%02d.%04d");
                                $dateSql = $year . '-' . $month . '-' . $day;

                                // такой МЛ может быть уже сформирован
                                // надо проверить поля
                                $find_ml = CreateML::findAll([
                                    'id_ml' => (int) $id_ml[1],
                                    'data_ml' => $dateSql
                                ]);

                                // цикл по строкам файла
                                // ищем именно клинтов по параметру
                                // arr[1] - должно быть 'выгрузка'
                                foreach ($arr as $client) {
                                    if (($client[1] == 'выгрузка') || ($client[1] == 'погрузка')) {

                                        // тут надо создать проверку на дублирование данных
                                        if ($find_ml) { // мл с таким номером был найден в БД

                                        }
                                        //***********************************************

                                        $client_name = trim($client[2]);
                                        $client_region = trim($client[5]);
                                        $client_city = trim($client[7]);
                                        $client_address = trim($client[8]);
                                        $client_contact = trim($client[11]);
                                        $find_client = Clients::findone([
                                                            'name' => $client_name,
                                                            'region' => $client_region,
                                                            'city' => $client_city,
                                                            'address' => $client_address
                                                        ]);

                                        if (!$find_client) {
                                            $new_client = new Clients();
                                            $new_client->name = $client_name;
                                            $new_client->region = $client_region;
                                            $new_client->city = $client_city;
                                            $new_client->address = $client_address;
                                            $new_client->contact = $client_contact;
                                            $new_client->save();
                                            $kol_new_clients++;
                                        }

                                        // формируем сразу Маршрутный лист (МЛ)
                                        $create_ml = new CreateML();

                                        $create_ml->id_ml = (int) $id_ml[1]; // номер ХВ из маршрутного листа
                                        $create_ml->data_ml = $dateSql; // дата МЛ

                                        $create_ml->disch_procedure = (int) $client[0]; //порядок выгрузки
                                        if ($find_client) {
                                            $create_ml->client = $find_client->id; //клиент
                                        } elseif ($new_client) {
                                            $create_ml->client = $new_client->id; //клиент
                                        }
                                        $create_ml->phone_mp = $client[14]; //телефон МП
                                        if (trim($client[17]) == 'да') { // иномарка или нет
                                            $create_ml->inomarka = 1;
                                        } else {
                                            $create_ml->inomarka = 0;
                                        }
                                        if (trim($client[18]) == 'да') { // доставка по ТТ
                                            $create_ml->dostavkaTT = 1;
                                        } else {
                                            $create_ml->dostavkaTT = 0;
                                        }
                                        if (trim($client[19]) == 'да') { // Возврат
                                            $create_ml->vozvrat = 1;
                                        } else {
                                            $create_ml->vozvrat = 0;
                                        }
                                        $create_ml->otp_tov_clnt = $client[20]; // отпуск товара клиенту
                                        $create_ml->numb_nakl = $client[22];    //номер накладной
                                        $create_ml->kol_poz = (int) $client[25];  //колво позиций
                                        $create_ml->kol_mest = (int) $client[26]; //колво мест
                                        $create_ml->massa = (float) $client[27]; //Вес
                                        $create_ml->v = (float) $client[28]; //Объем
                                        $create_ml->time_start = (float) $client[29]; // Гран. вр. нач.
                                        $create_ml->time_end = (float) $client[30]; // Гран. вр. оконч.
                                        $create_ml->time_run = (float) $client[31]; // время действия
                                        $create_ml->type_route = $client[33]; // Маршрут отгрузки.
                                        $create_ml->on_driver = 0; // id водителя, на которого перекинули клиента
                                        $create_ml->from_driver = 0;  // id водителя, от которого перекинули клиента
                                        if(!$find_driver) { //если водителя нет в базе
                                            $create_ml->from_driver = 0; //пишем как неизвестного
                                        } else {
                                            $create_ml->driver = $find_driver->id;
                                        }
                                        $create_ml->flight = $flight; // Рейс № 322509
                                        $create_ml->type = $type; // 34КрРогСР

                                        if(!$create_ml->save()){
                                            $error[$id_ml]['data'] = $dateSql;
                                            $error[$id_ml]['driver'] = $driver_name;
                                            $error[$id_ml]['client'] = $client->id;
                                        };
                                    }
                                }
                            } // конец добавления пользователей
                        }
                    }
                }
                session_start();
                $_SESSION['process'] = 'Завершение обработки';
                session_write_close();
                if(isset($error)) {
                    echo json_encode($error);
                } else {
                    echo $kol_new_clients;
                }
                Yii::$app->db->createCommand()->update('log_ml',
                    [
                        'status_ml' => 'created',
                        'data_ml' => date('Y-M-D'),
                        'autor_ml' => Yii::$app->user->id
                    ],
                    'id_ml = ' . Yii::$app->request->post('uid')
            )->execute();
                unset($create_ml);
                unset($new_client);
                unset($find_ml);
                unset($csv);
                unset($xls);
                unset($model_email);
                return true;
            }
        }
        return false;
    }


    public function actionProc()
    {
        session_start();
        if(!empty($_SESSION['process'])) {
            echo $_SESSION['process'];
        } else {
            echo 'нет данных';
        }
    }


    public function actionCsv()
    {
        $path_csv = Yii::getAlias('@attach_csv');
        $file = new \SplFileObject($path_csv . '/ml0176131_5786.csv');
        // читаем csv файл и создаем массив данных для БД
        while (!$file->eof()) {
            $data = $file->fgetcsv();
            $data = array_diff($data, array('', ' ', '  '));
            $arr[] = $data;
            if ( strripos($data[0], 'Итоговая стоимость доставляемого товара')   !== FALSE ) {
                break;
            }
        }
        print_r($arr);

    }

    public function actionMll()
    {
        //  23.01.2017 -->> 2017-01-23
        $date = '23.01.17';
        list($day, $month, $year) = sscanf($date, "%02d.%02d.%04d");
        $dateSql = $year . '-' . $month . '-' . $day;
        echo $dateSql;
    }
    
/*
    public function actionRbac()
    {
        $userRole = Yii::$app->authManager->getRole('admin');
        Yii::$app->authManager->assign($userRole, Yii::$app->user->getId());
    }
*/

public function actionDriver()
{
    $driver = array();
    $i = 0;
    $path_csv = Yii::getAlias('@attach_csv');
    $dir = new \DirectoryIterator($path_csv);
    foreach ($dir as $fileinfo) {
        if ( $fileinfo->isFile()) {
            //echo $fileinfo->getPathname() . '<br>';
            $csv_file = new \SplFileObject($fileinfo->getPathname());
            while (!$csv_file->eof()) {
                $stroka = $csv_file->fgetcsv();
                $csv[] = array_diff($stroka, array('', ' ', '  '));
            }

            //var_dump($csv); exit;

            $dr_name = explode('  ', trim($csv[3][0]));;
            $driver_name = $dr_name[1]; // ФИО водителя
            $num_avto = explode(' ', trim($csv[2][0]));;
            $number_avto = $num_avto[1]; // номер авто водителя

            $ph= explode(' ', trim($csv[3][4]));;
            $phone= $ph[4]; // ФИО водителя

            if (empty($driver)) {
                $driver[$i]['voditel_name'] = $driver_name;
                $driver[$i]['voditel_nomer_auto'] = $number_avto;
                $driver[$i]['voditel_phone'] = $phone;
            }

            $there_is_match = false;
            foreach ($driver as $item) {
                if ($item['voditel_name'] == $driver_name) {
                    $there_is_match = true;
                    break;
                }
            }

            if ($there_is_match == false) {
                $driver[$i]['voditel_name'] = $driver_name;
                $driver[$i]['voditel_nomer_auto'] = $number_avto;
                if (empty($phone)) {
                    $phone = 'не определен';
                }
                $driver[$i]['voditel_phone'] = $phone;

            }

            $csv = null;
            $i++;
        }
    }

    //var_dump($driver); exit;


    foreach ($driver as $item) {
        $model = new Voditel();
        $model->voditel_name = $item['voditel_name'];
        $model->voditel_nomer_auto = $item['voditel_nomer_auto'];
        $model->voditel_phone = $item['voditel_phone'];
        $model->save();
        echo 'записан водитель ' . $item['driver'] . '<br>';
    }


/*
    // читаем csv файл и создаем массив данных для БД
    while (!$file->eof()) {
        $data = $file->fgetcsv();
        $data = array_diff($data, array('', ' ', '  '));
        $arr[] = $data;
        if ( strripos($data[0], 'Итоговая стоимость доставляемого товара')   !== FALSE ) {
            break;
        }
    }
*/

}

}