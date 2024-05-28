jQuery(function($) {


	$('.hit-update').on('click', function(e) {
		e.preventDefault();
		var $this = $(this);
		var postId = $this.data('post_id');
		var new_version = $this.data('new_version');

		$this.text('Updating...');
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
            data: {
                'action' : 'k_version_checking_update_post',
                'post_id' : postId,
                'new_version' : new_version
            },
			timeout: 30000,
			success: function( data, textStatus, jqXHR ) {
				if ( data.status == true ) {
					$this.addClass('button-primary').removeClass('update').text('Updated');
					$this.parent().prev().prev().text(data.version);
				} else {
					$this.text('Update');
					alert(data.message);
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				//alert(jqXHR.responseText);
			}
		});
	});


	$('.hit-delete').on('click', function(e) {
		e.preventDefault();
		if ( confirm('Do you really want to delete?') ) {
			var id = $(this).data('id');
			$(this).parents('tr').remove();
			$.ajax({
				type: 'POST',
				url: ajax.ajax_url,
				data: 'action=k_version_checking_delete_row&id=' + id,
				timeout: 30000,
				success: function( data, textStatus, jqXHR ) {
					alert(data);
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					//$form.find('.spinner').removeClass('is-active');
					//alert(jqXHR.responseText);
				}
			});
		}
	});

	$('.hit-delete-rows').on('click', function(e) {
		e.preventDefault();
		if ( confirm('Do you really want to delete rows?') ) {
			var ids = $('[name="ids"]:checked').map(function() {
                return $(this).val();
            }).get();
            $('[name="ids"]:checked').parents('tr').remove();
			$.ajax({
				type: 'POST',
				url: ajax.ajax_url,
				data: {
					'action' : 'k_version_checking_delete_rows',
					'ids' : ids,
					'all_ids' : $('[name="all_ids"]').val()
				},
				timeout: 30000,
				success: function( data, textStatus, jqXHR ) {
					alert(data);
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseText);
				}
			});
		}
	});

	$('[name="all_ids"]').on('click', function(e) {
		$('[name="ids"]').prop('checked', this.checked);
	});

});