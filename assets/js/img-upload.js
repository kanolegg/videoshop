$(document).ready(function() {
    function readImage(response) {
        photo = JSON.parse(response);
        let newAttachedPhotoWrapper = jQuery('<div>', {
            'id': photo.name,
            'data-photo': response,
            'class': 'col-md-3 img-item'
        }).appendTo(".images-preview-wrapper");

        let newAttachedPhoto = jQuery('<div>', {
            'id': photo.name,
            'data-photo': response,
            'class': 'img-holder my-4'
        }).appendTo(newAttachedPhotoWrapper);

        jQuery('<img>', {
            'src': "/uploads/"+photo.name+'.'+photo.ext,
            'class': 'field-img',
            'alt': 'Preview'
        }).appendTo(newAttachedPhoto);

        jQuery('<i>', {
            'data-name': photo.name,
            'class': 'not-attach fa-regular fa-xmark',
            'title': 'Не прикреплять'
        }).appendTo(newAttachedPhoto);
    }

    $('#image').change(function() {
        let formData = new FormData(document.getElementById("upload-image"));

        $.ajax({
            type: 'POST', // Тип запроса
            url: '/handler.php', // Скрипт обработчика
            data: formData, // Данные которые мы передаем
            cache: false, // В запросах POST отключено по умолчанию, но перестрахуемся
            contentType: false, // Тип кодирования данных мы задали в форме, это отключим
            processData: false, // Отключаем, так как передаем файл
            success: function(data) {
                response = JSON.parse(data);
                if (response.name) {
                    readImage(data);
                    photos.push(response)
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(".images-preview-wrapper").on("click", ".not-attach", function() {
        let photo = $("#"+$(this).data("name")).data("photo");
        $.each(photos, function(i, n){
            if (photo.name == n.name) {
                photos.splice(i,1); return;
            }
        });
        $("#"+photo.name).remove()
    });
});