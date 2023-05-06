/**
 * voi cate = {Slider,Banner,Icon} ==> thi hien thi so luong item va danh sach item
 * voi cate con lai ==> hien thi thong tin api,khong co so luong item
 */
function displayModeLayout(typeCate) {
    if (typeCate == '1' || typeCate == '3' || typeCate == '4') {
        $('.div-content-module').hide()
        $('.field-camidhomemenudb-quantity_item').show()
        $('.div-content-cate-other').show()
    } else {
        $('.div-content-module').show()
        $('.div-content-cate-other').hide()
        $('.field-camidhomemenudb-quantity_item').hide()
    }
}

// create form upload image by cate type
function createFromWithItemChange() {
    $.ajax({
        url: '/camid-homemenu/form-cate-when-item-change',
        type: 'post',
        data: {
            cateType: $('#id_catemenu').val(),
            quantityItem: $('#camidhomemenudb-quantity_item').val(),
            quantityItemFirst: $(".quantityItemFirst").val(),
        },
        success: function (data) {

            if (data == 'quantityItem < quantityItemFirst') {
                for (let i = $(".quantityItemFirst").val(); i > $('#camidhomemenudb-quantity_item').val(); i--) {
                    $(".center-" + i).remove();
                    $("#script-" + i).remove();
                }
                $(".quantityItemFirst").val($('#camidhomemenudb-quantity_item').val())
            } else {
                $(".div-content-cate-other").append(data);
                $(".quantityItemFirst").val($('#camidhomemenudb-quantity_item').val())
            }
        }
    });
}


function createFromWithCateTypeChange() {
    $.ajax({
        url: '/camid-homemenu/form-cate-when-cate-change',
        type: 'post',
        data: {
            cateType: $('#id_catemenu').val(),
            quantityItem: $('#camidhomemenudb-quantity_item').val(),
            quantityItemFirst: $(".quantityItemFirst").val(),
        },
        success: function (data) {
            if (data) {
                $(".div-content-cate-other").html(data);
                $(".quantityItemFirst").val($('#camidhomemenudb-quantity_item').val())
            }
        }
    });
}


function getValueApi(idCate) {
    $.ajax({
        url: '/camid-homemenu/get-value-api',
        type: 'post',
        data: {
            idCate: idCate,
        },
        success: function (data) {
            if (data) {
                var api = $('#camidcatemenudb-api');
                api.val(data);
            } else {
                console.log('ERROR GET VALUE API')
            }
        }
    });
}

function addValueApiForm(typeCate) {
    if (typeCate !== '1' && typeCate !== '3' && typeCate !== '4') {
        getValueApi(typeCate);
    }
}

/**
 * --------------NOTE------------------------
 * $('#select2-id_catemenu-container').attr('title').trim(); ==> typeCate
 */

window.addEventListener("load", function () {
    var value_cate = $('#id_catemenu').val();
    displayModeLayout(value_cate);

    $('#id_catemenu').on('change', function () {
        var value_cate = $('#id_catemenu').val();
        var quantityItem = Number($('#camidhomemenudb-quantity_item').val());
        displayModeLayout(value_cate)
        addValueApiForm(value_cate); //add value for text input api
        if (quantityItem >= 1 && quantityItem <= 20) {
            if (value_cate == '1' || value_cate == '3' || value_cate == '4') {
                createFromWithCateTypeChange(value_cate);
            }
        }
    })

    // quantity change --> check and create form by cate type
    $('#camidhomemenudb-quantity_item').on('change', function () {
        var quantityItem = Number($('#camidhomemenudb-quantity_item').val());
        if (quantityItem >= 1 && quantityItem <= 20) {
            createFromWithItemChange();
        }
    })

    //disable submit form with press enter
    $('#w0').on('keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

});

