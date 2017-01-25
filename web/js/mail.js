var timeId;

function proc(){
    $.ajax({
        url: 'proc',
        type: 'POST',
        success: function (process) {
            console.log('Обработка ' + process);
            $('#process').html(process);
        },
        error: function () {
            console.log('error process data');
        },
    })
}


function createML(id) {

    $.ajax({
        url: 'createml',
        type: 'POST',
        cache: false,
        data: 'uid='+id,
        success: function(data){
            console.log('вернулись данные - ' + data);
            $('#process').html('Обработка завершена');
        },
        beforeSend: function () {
            timeId = setInterval(proc, 500);
            console.log('перед отправкой на сервер');
            $('#img_' + id).css('display','inline');
            //console.log($('#img_' + id));
            $('#process').html('Обработка...');
        },
        complete: function() {
            clearInterval(timeId);
            console.log('запрос завершен');
            $('#img_' + id).css('display','none');
            $('#process').html('Обработка завершена');
        },
        error: function (data) {
            console.log('произошла ошибка в запросе');
            $('#process').html('Возникла ошибка');
        },

    });
}

