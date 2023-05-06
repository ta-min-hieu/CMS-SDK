function disabledImagePath() {
    var link_image = $('#welcomepage-image_path');
    var file_image = $('.kv-fileinput-caption');
    var btn_browse = $('input[type = "file"]');
    link_image.change(function () {
        if (link_image.val() != '') {
            $('.kv-fileinput-caption').addClass("file-caption-disabled");
            $('.kv-fileinput-caption').attr("readonly", "readonly");
            btn_browse.attr("disabled", "disabled");
        } else {
            $('.kv-fileinput-caption').removeClass("file-caption-disabled");
            $('.btn-file').removeClass("disabled");
            $('.btn-file').removeAttr("disabled");
            $('.kv-fileinput-caption').removeAttr("readonly", "readonly");
            $('.fileinput-remove-button').removeAttr("disabled", "disabled");
            btn_browse.removeAttr("disabled", "disabled");
        }
    })
}

function disabledImageLink() {
    var link_image = $('.input-link-image');
    var file_image = $("input[type=file]");
    $("input[type=file]").change(function () {
        link_image.attr("disabled", "disabled");
    })
}


$(document).ready(function () {
    if($('#welcomepage-image_path').val() != '') {
        $('.kv-fileinput-caption').addClass("file-caption-disabled");
        $('.kv-fileinput-caption').attr("readonly", "readonly");
        $('input[type = "file"]').attr("disabled", "disabled");
    }

    disabledImagePath();
    disabledImageLink();
    window.addEventListener('load', function () {
        $(".fileinput-remove").click(function () {
            // if($(".file-caption-name").attr('title') != '') {
                $('.input-link-image').removeAttr("disabled", "disabled");
            // }
        })
    })
});
