/*
* Hiển thị các description và preview theo type
* type icon  -> không hiển thị description và preview
* type banner -> hiển thị description và preview
* */

function showFieldByType(valueType) {
    if (valueType == 3) {
        $(".field-camiddegitalservice-description_en").hide();
        $(".field-camiddegitalservice-description_cam").hide();
        $("#div-preview").hide();
    }
    else if (valueType == 1) {
        //add va remove id cua cac div de thay doi css
        $("#div-preview-1-doc").attr('id','div-preview-1');
        $("#div-preview-1-doc").removeAttr('id','div-preview-1-doc');

        $("#preview-image-color-doc").attr('id','preview-image-color');
        $("#preview-image-color-doc").removeAttr('id','preview-image-color-doc');

        $("#div-img-preview-doc").attr('id','div-img-preview');
        $("#div-img-preview-doc").removeAttr('id','div-img-preview-doc');

        $("#div-title-des-doc").attr('id','div-title-des');
        $("#div-title-des-doc").removeAttr('id','div-title-des-doc');

        $("#list-stroke-doc").attr('id','list-stroke');
        $("#list-stroke-doc").removeAttr('id','list-stroke-doc');

        $("#preview-doc").attr('id','preview');
        $("#preview-doc").removeAttr('id','preview-doc');

        $(".field-camiddegitalservice-description_en").show();
        $(".field-camiddegitalservice-description_cam").show();
        $("#div-preview").show();
    }
    else if (valueType == 2) {
        $('.my-slim').attr('data-ratio','16:9');
        //add va remove id cua cac div de thay doi css
        $("#div-preview-1").attr('id','div-preview-1-doc');
        $("#div-preview-1").removeAttr('id','div-preview-1');

        $("#preview-image-color").attr('id','preview-image-color-doc');
        $("#preview-image-color").removeAttr('id','preview-image-color');

        $("#div-img-preview").attr('id','div-img-preview-doc');
        $("#div-img-preview").removeAttr('id','div-img-preview');

        $("#div-title-des").attr('id','div-title-des-doc');
        $("#div-title-des").removeAttr('id','div-title-des');

        $("#list-stroke").attr('id','list-stroke-doc');
        $("#list-stroke").removeAttr('id','list-stroke');

        $("#preview").attr('id','preview-doc');
        $("#preview").removeAttr('id','preview');


        $(".field-camiddegitalservice-description_en").show();
        $(".field-camiddegitalservice-description_cam").show();
        $("#div-preview").show();
    }
}

function showImagePreview(valueImage) {
    valueImage.slice(0);
    valueImage.slice(0,-1);
    $("#image-preview").attr('src',valueImage);
}

function backgroundPreview(src) {
    $("#preview-image-color").css('background',src.value);
    $("#preview-image-color-doc").css('background',src.value);
}

function borderPreview(src) {
    if (src.value == "1") {
        $("#preview-image-color").css('border','1px solid #FFFFFF')
        $("#preview-image-color-doc").css('border','1px solid #FFFFFF')
    }
    else{
        $("#preview-image-color").css('border','none')
        $("#preview-image-color-doc").css('border','none')
    }
}

function setSrcImgPreview() {
    let logo = document.querySelector('.in');
    if (logo) {
        logo.addEventListener('load', function () {
            var valueImage = logo.getAttribute('src');
            showImagePreview(valueImage);
            $(".slim-btn-cancel").click(function () {
                $("#image-preview").attr('src','/img/no-image-square.jpg');
            })
        });
    }
}

function activeDigitalService() {
    var check_cof=confirm($(this).attr("data-alert"));
    if(check_cof==true){
        var strvalue = "";
        var status=$(this).attr("data-status");
        $('input[name="selection[]"]:checked').each(function() {
            if(strvalue!="")
                strvalue = strvalue + ","+this.value;
            else
                strvalue = this.value;
        });
        $.post( "active", { strvalue,status } );
    }
}

window.addEventListener("load", function () {
    /*thay đổi giao diện theo type */
    showFieldByType($("#camiddegitalservice-cate_id").val());

    $("#camiddegitalservice-cate_id").change(function () {
        showFieldByType($("#camiddegitalservice-cate_id").val());
    })

    setSrcImgPreview();
    // lấy value của image để gán cho img preview
    $("input[name = 'path_image[]']").change(function (){
        setSrcImgPreview();
    })
    /*thêm title cho preview */
    if ($("#camiddegitalservice-title_en").val().length > 19 ) {
        var text = $("#camiddegitalservice-title_en").val().slice(0, 19) + ' ...';
        $("#p-title").text(text);
    }
    else {
        $("#p-title").text($("#camiddegitalservice-title_en").val());
    }
    $("#camiddegitalservice-title_en").change(function () {
        if ($("#camiddegitalservice-title_en").val().length > 19 ) {
            var text = $("#camiddegitalservice-title_en").val().slice(0, 19) + ' ...';
            $("#p-title").text(text);
        }
        else {
            $("#p-title").text($("#camiddegitalservice-title_en").val());
        }
    })

    /*thêm description cho preview */
    if ($("#camiddegitalservice-description_en").val().length > 50 ) {
        var text = $("#camiddegitalservice-description_en").val().slice(0, 50) + ' ...';
        $("#p-description").text(text);
    }
    else {
        $("#p-description").text($("#camiddegitalservice-description_en").val());
    }

    $("#camiddegitalservice-description_en").change(function () {
        if ($("#camiddegitalservice-description_en").val().length > 50 ) {
            var text = $("#camiddegitalservice-description_en").val().slice(0, 50) + ' ...';
            $("#p-description").text(text);
        }
        else {
            $("#p-description").text($("#camiddegitalservice-description_en").val());
        }
    })

    // $("#btn-active").click(function () {
    //     console.log(1)
    //     var check_cof=confirm($(this).attr("data-confirm"));
    //     console.log(check_cof)
    //     if(check_cof==true){
    //         console.log(2)
    //         var strvalue = "";
    //         $('input[name="selection[]"]:checked').each(function() {
    //             if(strvalue!="")
    //                 strvalue = strvalue + ","+this.value;
    //             else
    //                 strvalue = this.value;
    //         });
    //         console.log(strvalue)
    //         $.post( "active", { strvalue } );
    //     }
    // })


})