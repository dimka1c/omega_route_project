var timeId;
var error = false;

function proc(){
    if (error == false) {
        $.ajax({
            url: 'proc',
            type: 'POST',
            success: function (process) {
                console.log('Обработка ' + process);
                if(error == false) {
                    $('#process').html(process);
                }
            },
            error: function () {
                console.log('error process data');
            },
        })
    }
}


function createML(id) {

    $.ajax({
        url: 'createml',
        type: 'POST',
        cache: false,
        data: 'uid='+id,
        success: function(data){
            console.log('вернулись данные - ' + data);
            if(data == 'error') {
                clearInterval(timeId);
                data_error = '<div class="alert alert-danger alert-dismissable">';
                data_error = data_error + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                data_error = data_error + '<strong>Внимание!</strong>  Ошибка при работе с базой данных. Повторите пожалуйста позднее.';
                data_error = data_error + '</div>';
                $('#error').html(data_error);
                $('#img_' + id).css('display','none');
                $('#process').html('<b style="color:#f3553a">Ошибка</b>');
                error = true;
            } else {
                $('#process').html('Обработка завершена');
            }
        },
        beforeSend: function () {
            timeId = setInterval(proc, 1000);
            console.log('перед отправкой на сервер');
            $('#img_' + id).css('display','inline');
            //console.log($('#img_' + id));
            $('#process').html('Обработка...');
        },
        complete: function(data) {
            clearInterval(timeId);
            console.log('запрос завершен');
            $('#img_' + id).css('display','none');
            if (error == true) {
                $('#process').html('<b style="color:#f3553a"><span class="glyphicon glyphicon-remove"></span>&nbsp;Ошибка</b>');
            } else {
                $('#process').html('Обработка завершена');
            }
        },
        error: function (data) {
            console.log('произошла ошибка в запросе');
            $('#process').html('Возникла ошибка');
            if(data == 'error') {
                data_error = '<div class="alert alert-danger alert-dismissable">';
                data_error = data_error + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                data_error = data_error + '<strong>Внимание!</strong>  Неизвестная ошибка.';
                data_error = data_error + '</div>';
                $('#error').html(data_error);
            }

        },

    });
}

