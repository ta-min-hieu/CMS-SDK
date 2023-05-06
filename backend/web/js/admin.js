$(document).ready(function () {


	function buildAddr() {

		//console.log($('#district-id').find(':selected').text());

		var addrText = $('#address-text').val();
		var province = $('#province-id').find(':selected').text();
		var district = $('#district-id').find(':selected').text(); //($("#district-id").select2("data").length)? $("#district-id").select2("data")[0].text: '';
		var precinct = $('#precinct-id').find(':selected').text(); //($("#precinct-id").select2("data").length)? $("#precinct-id").select2("data")[0].text: '';

		if (precinct && precinct != $('#precinct-id').attr('none-text')) {
			addrText += ', ' + precinct;
		}
		if (district && district != $('#district-id').attr('none-text')) {
			addrText += ', ' + district;
		}
		if (province && province != $('#province-id').attr('none-text')) {
			addrText += ', ' + province;
		}

		$('#addr-display').html(addrText);

	}

	$("#address-text").keyup(function(event) {
		buildAddr();
	});
	$('#province-id, #district-id, #precinct-id').change(function() {
		buildAddr();
	});
	$("#addr-modal").on("shown.bs.modal", function(){
		buildAddr();
	});

	$('#save-addr').click(function() {
		if ($('#province-id').val() && $('#district-id').val() && $('#precinct-id').val()) {
			$('#address').val($('#addr-display').html());
			$('#addr-modal').modal('hide');
		}
	});

	//$('#grid-form').on('submit', function(){
    //
	//	$('#w0').submit();
	//});


	$('#lang-input').on('change', function() {
		var url = $(this).attr('href');
		if(url.indexOf('?') > -1) {
			url += "&content_lang=" + $(this).val();
		}else{
			url += "?content_lang=" + $(this).val();
		}
		document.location = url;
	});




    // Xoa content cua popup lay ket qua cuoc goi
	$("#call-result-modal").on("hidden.bs.modal", function(){
		$(".modal-content", $(this)).html("");
	});


	// Fix ui/ux
	$('#page-loading').hide();
	$('table td a span.glyphicon').parent().css('margin-right', '10px');
	$("form:not(.filter) .control-label:visible:first").click();
	$('#mainGridPjax')
		.on('pjax:start', function() {
			$('#page-loading').show();
		})
		.on('pjax:end',   function() {
			$('#page-loading').hide();
		});
	// $.blockUI.defaults.baseZ = 1005;
	// $.blockUI.defaults.css.background = 'none';
	// $.blockUI.defaults.css.border = 'none';
	// $.blockUI.defaults.boxed = !0;
	// $.blockUI.defaults.message = '<div class="loading-message loading-message-boxed"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> <span>Loading...</span></div>';
	// $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

	$(document).on("click", ".captcha-refresh-icon", function () {
		$(this).prev().click();
		$('#loginform-captcha').focus();
	})
	// Forcus vao input nhap captcha o login
	$('#loginform-captcha-image').click(function () {
		$('#loginform-captcha').focus();
		return true;
	});

	// End fix ui/ux

	$(document).on("click", "#reportCampaignExport", function () {
		var report_date = $('#reportcampaigndailysearch-report_date').val();
		var partner_id = $('#reportcampaigndailysearch-partner_id').val();
		var campaign_id = $('#reportcampaigndailysearch-campaign_id').val();
		var address = $('#reportcampaigndailysearch-address').val();

		var url = "/report-campaign-daily/export" + "?report_date=" + report_date
			+ "&partner_id=" + partner_id
			+ "&campaign_id=" + campaign_id
			+ "&address=" + address;
		window.location.href = url;


	});//end click


//    $(document).keypress(function (e) {
//        if (e.which == 13) {
//            return false;
//        }
//    });


	$('#frm-btn-submit').click(function () {
		$("#list-assigned option").prop("selected", "selected");
		$("form:first").submit();
	});

	$('#frm-btn-song-submit').click(function () {
		$("#list-assigned option").prop("selected", "selected");
		if (!$('#editorTags').val()) {
			alert('Ca sỹ không được để trống');
			$('#editorTags').focus();
			return;
		}
		if (!$('#list-assigned').val()) {
			alert('Thể loại không được để trống');
			$('#list-assigned').focus();
			return;
		}
		$("form:first").submit();
	});



	$('#banneritem-target_type').change(function () {
		var itemId = $(this).val();
		if (itemId == 1) {
			$('#song-item-type').show();
			$('#video-item-type').hide();
			$('#playlist-item-type').hide();
		}
		if (itemId == 2) {
			$('#song-item-type').hide();
			$('#video-item-type').show();
			$('#playlist-item-type').hide();
		}
		if (itemId == 3) {
			$('#song-item-type').hide();
			$('#video-item-type').hide();
			$('#playlist-item-type').show();
		}
	});

	$('#banneritem-song_id').change(function () {
		$('#banneritem-target_id').val($(this).val());
	});

	$('#banneritem-video_id').change(function () {
		$('#banneritem-target_id').val($(this).val());
	});

	$('#banneritem-playlist_id').change(function () {
		$('#banneritem-target_id').val($(this).val());
	});

	var pageClass = '.telesale-call-log-index, .telesale-partner-subscriber-index, .telesale-access-log-index, .telesale-subscriber-index';
	if ($(pageClass).length) {
		setTimeout(function(){
			$('.navbar .sidebar-toggler').click();
		}, 1000);

		$('.page-content').click(function() {
			if (!$('body').hasClass('page-sidebar-closed')) {
				$('.navbar .sidebar-toggler').click();
			}
		});
	}

	$('.pd-print').on('click', function(){
		var itemId = $(this).attr('itemid');
		Popup($('#' + itemId).html());
	});
});
function PrintElem(elem)
{
	Popup($(elem).html());
}

function Popup(data)
{
	var mywindow = window.open('', 'Print', 'height=400,width=600');
	mywindow.document.write('<html><head><title>Print Device Information</title>');
	/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
	mywindow.document.write('</head><body >');
	mywindow.document.write(data);
	mywindow.document.write('</body></html>');

	mywindow.print();
	mywindow.close();

	return true;
}
