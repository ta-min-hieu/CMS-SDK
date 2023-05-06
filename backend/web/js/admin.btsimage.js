$(document).ready(function () {
	function callAjax(object, url, imgId) {
		var self = object;
		$.ajax({
			url: url,
			data: {
				'id': imgId
			},
			method: 'POST',
			dataType: 'json',
			success: function (resp) {
				console.log(resp);
				if (resp.error_code == 0) {
					if (self.hasClass('icon-like')) {
						//self.addClass('active');
						$('.approve-img-' + imgId).addClass('active');
						$('.disapprove-img-' + imgId).removeClass('active');
						$('#item-image-' + imgId).removeClass('not-approve');
						$('#item-image-' + imgId).attr('img-note', '');
						$('#reason').val('');
						$('#img-note').html('');
						$('#img-note').hide();
						$('#img-note-' + imgId).html('');
						$('#img-note-' + imgId).removeAttr('title');
					} else {
						$('#item-image-' + imgId).remove();
						$('#big-img-modal').modal('hide');

						// cap nhat so luong anh
						var tio = $('#ti-' + self.parent().attr('item-id'));
						var totalImage = parseInt($.trim(tio.html()));
						tio.html(totalImage - 1);

						// Xoa image o box upload
						var thumbid = self.parent().attr('thumbid');

						if (typeof thumbid !== 'undefined' && thumbid !== false) {
							if ($('#' + $.escapeSelector(thumbid)).length) {
								$('#' + $.escapeSelector(thumbid)).remove();
							}
						}

					}
					toastr.info(resp.message);
				} else {
					toastr.warning(resp.message);
				}

			},
			error: function (xhr) {
				toastr.warning("System error");
			}
		});
	}
	$('.page-container').on('click', '.image-action', function() {
		var imgId = $(this).attr('img-id');
		var self = $(this);
		var url = self.attr('ahref');
		var confirmMsg = self.attr('data-confirm');

		if (self.hasClass('btn-disapprove')) {
			$('#disapprove-image-id').val(imgId);
		}

		if (typeof url != 'undefined') {
			if (typeof confirmMsg != 'undefined') {
				if (confirm(confirmMsg)) {
					callAjax(self, url, imgId);
				}
			} else {
				callAjax(self, url, imgId);
			}

		}

	});

	$("#disapprove-form").submit(function( event ) {
		var url = $(this).attr('action');
		var self = $(this);
		console.log(self.serializeArray());
		event.preventDefault();
		$.ajax({
			url: url,
			data: self.serializeArray(),
			method: 'POST',
			dataType: 'json',
			success: function (resp) {
				console.log(resp);
				if (resp.error_code == 0) {
					var imgId = $('#disapprove-image-id').val();
					$('.approve-img-' + imgId).removeClass('active');
					$('.disapprove-img-' + imgId).addClass('active');
					$('#item-image-' + imgId).addClass('not-approve');
					$("#disapprove-modal").modal('hide');

					$('#item-image-' + imgId).attr('img-note', $('#reason').val());
					$('#img-note').html($('#reason').val())
					$('#img-note').show();
					$('#img-note-' + imgId).html($('#reason').val());
					$('#img-note-' + imgId).attr('title', $('#reason').val());
					toastr.info(resp.message);
				} else if (resp.error_code == 400) {
					$('#error-msg').html(resp.message);

					toastr.warning(resp.message);
				}

			},
			error: function (xhr) {
				toastr.warning("System error");
			}
		});
	});

	$('.image-upload-field').each(function() {
		var id = $(this).attr('id');
		$('#' + id).on('fileuploaded', function(event, data, index, fileId) {

			var response = data.response;
			var extra = data.extra;
			var imageId = response.image_id;
			var fileId = response.file_id;

			//console.log(response.image_id)
			var temp = $('#image-template').html()
				.replaceAll('{{image_id}}', response.image_id)
				.replaceAll('{{image_url}}', response.imagePath)
				.replaceAll('{{thumb_url}}', response.imagePath)
				.replaceAll('{{item_id}}', extra.item_id)
				.replaceAll('{{actid}}', 'thumb-image-upload-'+extra.plan_id+'-'+extra.item_id+ '-' + fileId)
				;
			$('#upload-images-' + extra.item_id).append(temp);
			// cap nhat so luong anh
			var tio = $('#ti-' + extra.item_id);
			var totalImage = parseInt($.trim(tio.html()));
			tio.html(totalImage + 1);


		}).on('filesuccessremove', function(e, id) {
			//console.log('file success remove', id);

			var delBtn = $('#act-' + $.escapeSelector(id) + ' .glyphicon-trash:first');

			delBtn.removeAttr('data-confirm');
			delBtn.click();
			// cap nhat so luong anh
			var tio = $('#ti-' + $('#act-' + $.escapeSelector(id)).attr('item-id'));
			var totalImage = parseInt($.trim(tio.html()));
			tio.html(totalImage - 1);
		});

	});

});