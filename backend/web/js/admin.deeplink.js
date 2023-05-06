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
        toastr.warning('Dữ liệu không hợp lệ!')
        $('.modal .modal-body').html(xhr.responseText);
    }
    else {
        // $('#waiting').hide();
        $('.modal').modal('hide');
        toastr.warning('Lỗi hệ thống!');
    }

}

function buildParamsForm(paramList) {
    $('#dl-params-form').html('');
    for (var i = 0; i < paramList.length; i ++) {
        console.log(paramList[i]);
        var temp = $('#deeplink-params-template').html()
            .replace('{{label}}', paramList[i].name)
            .replace('{{value}}', getValueByParamName(paramList[i].name))
            .replace('{{name}}', paramList[i].name)
            .replace('{{desc}}', paramList[i].desc)
            ;

        $('#dl-params-form').append(temp);
    }
}
function getValueByParamName(paramName) {
    var data = $('#deeplink-params').val();
    var value = '';
    if (data) {
        var dataObj = JSON.parse(data);
        return dataObj[paramName];
    }
    return value;
}
function configDeeplink(dlid, showModal) {
    if (!dlid)
        return false;

    $.ajax({
        url:  '/deeplink-configure/deeplink',
        data: {
            'deeplink_configure_id': dlid
        },
        method: 'POST',
        dataType: 'json',
        // "sucess" will be executed only if the response status is 200 and get a JSON
        success: function (json) {
            console.log(json);
            var dlcfg = json.data;
            if (dlcfg) {

                $('#deeplink').val(dlcfg.deeplink);

                if (dlcfg.deeplink_params) {
                    var paramList = JSON.parse(dlcfg.deeplink_params);
                    if (paramList.length) {
                        buildParamsForm(paramList);
                        buildDeeplinkUrl(dlcfg.deeplink);
                        if (showModal)
                            $('#deeplink-modal').modal('show');
                    }
                }
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
}
function buildDeeplinkUrl(url)
{
    url = (!url)? $('#deeplink').val(): url;
    var data = $('#deeplink-params').val();
    if (url && data) {
        var dataObj = JSON.parse(data);
        if ( ! $.isEmptyObject(dataObj) )
        {
            var href = new URL(url);
            for (key in dataObj) {
                console.log("test" + key)
                href.searchParams.set(key, dataObj[key]);
            }
            //url += ( url.indexOf('?') >= 0 ? '&' : '?' ) + $.param(dataObj);
            url = href.toString();
            $('#deeplink').val(url);
        }
    }

    console.log(url);
    return url;
}

$(document).ready(function () {
    $('#deeplink-configure-id').on('change', function () {
        var dlid = $(this).val();
        configDeeplink(dlid, true);
    });
    $('#deeplink').on('click', function () {
        var dlid = $('#deeplink-configure-id').val();
        configDeeplink(dlid, true);
    });
    $('#deeplink-param-save').on('click', function () {
        $("#dl-params-form input").each(function() {
            if ($(this).val()) {
                $(this).parent().removeClass('has-error');
            } else {
                $(this).parent().addClass('has-error');
            }

        });

        if (!$('#dl-params-form .form-group.has-error').length) {

            var paramData = $('#dl-params-form').serializeArray();

            var deeplinkParams = {};
            for (var i = 0; i < paramData.length; i++) {
                deeplinkParams[paramData[i].name] = paramData[i].value;
            }
            var data = JSON.stringify(deeplinkParams);

            $('#deeplink-params').val(data);
            buildDeeplinkUrl();
            $('#deeplink-modal').modal('hide');
        } else {
            $('#dl-params-form .form-group.has-error:first input').focus();
        }
    });

});
