function processXhrResponse(xhr) {
    console.log(xhr);

    if (xhr.status == 401)
        // Xac thuc
        window.location = window.location;
    else if (xhr.status == 200) {
        $('.modal').modal('hide');
        var jsonResponse = JSON.parse(xhr.responseText);
        toastr.warning(jsonResponse.message);
    }
    else if (xhr.status == 201) {
        $('.modal').modal('hide');
        var jsonResponse = JSON.parse(xhr.responseText);
        toastr.warning(jsonResponse.message);
    }
    else if (xhr.status == 400 || xhr.status == 404){
        toastr.warning('Invalid data!')
        $('.modal .modal-body').html(xhr.responseText);
    }
    else {
        // $('#waiting').hide();
        $('.modal').modal('hide');
        toastr.warning('System error!');
    }

}


$(document).ready(function () {
    $('a[use-ftp="'+$('#is_use_ftp').val()+'"]').click();

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var target = e.target // newly activated tab
        $('#is_use_ftp').val($(target).attr('use-ftp'));
    });

    $('#ftp-upload-modal').on('click', '.file', function () {
        if (confirm('Are you sure?')) {
            var filePath = $(this).attr('filepath');
            $('#video_media_ftp').val(filePath);
            $('.modal').modal('hide');
        }


    });
    $('#ftp-upload-modal').on('click', '.folder, .bfolder', function () {
        var url = $(this).attr('href');
        $.ajax({
            url:  url,
            data: {

            },
            method: 'GET',
            dataType: 'html',
            success: function (resp) {

                if (resp) {
                    $('#ftp-upload-modal .modal-body').html(resp);
                }
            },
            // "error" will run but receive state 200, but if you miss the JSON syntax
            error: function (xhr) {
                if (xhr.status == 400 || xhr.status == 404){
                    toastr.warning('Invalid data!')
                    $('.modal .modal-content').html(xhr.responseText);
                } else
                    processXhrResponse(xhr);
            }
        });
    });


});
