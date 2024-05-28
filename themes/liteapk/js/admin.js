jQuery(function($) {

	/**
	 * Custom button to update data
	 */

	var updateImageBtn = '<button class="button button-primary" id="update-image" style="margin-right: 12px">Update Image</button>';
	var updateInfoBtn = '<button class="button button-primary" id="update-info" style="margin-right: 12px">Update Info</button>';
	var customBtn = '<div class="acf-field">'+updateInfoBtn + updateImageBtn + '</div>';

	$('.acf-field--require').after(customBtn);

	$('#update-info').on('click', function() {
		var url = $('#acf-_playstore_url').val();

		if(url) {
			$.ajax({
				type: 'POST',
				url: ajax.ajax_url,
				data : {
					action: 'app_get_data_playstore', //Tên action
					url: url,
				},
				timeout: 10000,
				success: function(data, textStatus, jqXHR) {
					console.log(data);
					if(!$.isEmptyObject(data)){
						$('#acf-app_name').val(data.title); //app_name
						if(data.version){
							$('#acf-_softwareversion').val(data.version); //version
						}
						$('#acf-_require').val(data.android); //require
						$('#acf-internal_note').val( 'Category: ' + data.category + '; Publisher: ' + data.publisher); //require

						if(data.whats_new) {
							var addButton = $('a[data-event="add-row"]').last().trigger('click');
							// // Tìm thẻ tr cuối cùng trong bảng thỏa điều kiện
							var $lastTR = addButton.parent().parent().find('table.acf-table').find('tr.acf-row:not(.acf-clone)').last();

							var $iframe = $lastTR.find('iframe');

							$iframe.on('load', function() {
								// Sau khi iframe load hoàn toàn, truy cập đến phần tử trình soạn thảo bên trong iframe
								var $editor = $iframe.contents().find('#tinymce');
								// Thêm HTML vào trình soạn thảo
								$editor.html(data.whats_new);
							});
						}

					}
				},
				error: function( jqXHR, textStatus, errorThrown  ) {
					alert('Error');
					console.log(jqXHR);
				}
			});
		}
	});

	$('#update-image').on('click', function(e){
		e.preventDefault();
		var urlParams = new URLSearchParams(window.location.search);
		var postID = urlParams.get('post');

		if (postID) {
			$.ajax({
				type: 'POST',
				url: ajax.ajax_url,
				data: 'action=app_update_image&postID='+postID,
				timeout: 30000,
				success: function( data, textStatus, jqXHR ) {
					console.log(data);
					location.reload();
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseText);
					console.log(jqXHR.responseText);
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
				}
			});

		}
	});

	/**
	 *	Grabber Data
	 */
	$('.posts_page_grabber #publish').on('click', function(e) {
		e.preventDefault();	
		var $form = $(this).parents('#post');
		$form.find('.spinner').addClass('is-active');
		
		var message = '<div class="acf-admin-notice notice notice-success">' +
					  	'<p>Grabber is running. Please wait it.</p>' +
					  '</div>';
		$('.posts_page_grabber').find('.acf-admin-notice').remove();
		$('.posts_page_grabber h1').after(message);
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: $form.serialize() + '&action=k_grabber',
			success: function( data, textStatus, jqXHR ) {
				$form.find('.spinner').removeClass('is-active');
				$('.posts_page_grabber').find('.acf-admin-notice p').html(data);
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				$form.find('.spinner').removeClass('is-active');
				alert(jqXHR.responseText);
			}
		});
	});

	/**
	 * Version checking
	 */
	$('.posts_page_version_checking #publish').on('click', function(e) {
		e.preventDefault();
		var message = '<div class="acf-admin-notice notice notice-success">' +
			'<p>Version checking is running. Please wait...</p>' +
			'</div>';
		$('.posts_page_version_checking').find('.acf-admin-notice').remove();
		$('.posts_page_version_checking h1').after(message);
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: {
				'action' : 'app_version_checking',
			},
			timeout: 60000,
			success: function( data, textStatus, jqXHR ) {
				console.log(data);
				console.log('Done');
				$('.posts_page_version_checking').find('.acf-admin-notice p').html('Done, reload page to see results.');
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				console.log(jqXHR.responseText);
				console.log('Error');
				console.log(textStatus);
				console.log(errorThrown);
			}
		});
	});

	$('.posts_page_version_checking a.update, .posts_page_version_checking_playstore a.update').on('click', function(e) {
		e.preventDefault();
		var $this = $(this);
		var postId = $this.data('post_id');
		var new_version = $this.data('new_version');

		$this.text('Updating');

		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data : 'action=version_checking_update_post&post_id='+postId+'&new_version='+new_version, //Tên action
			success: function( data, textStatus, jqXHR ) {
				$this.addClass('button-primary').removeClass('update').text('Updated');
				// console.log(data);
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				console.log(textStatus)
				console.log(jqXHR.responseText)
				alert('errorrrr');
			}
		});
	});

	//delete row
	$('.posts_page_version_checking a.delete').on('click', function(e) {
		e.preventDefault();

		var id = $(this).data('id');
		$(this).parents('tr').remove();
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: 'action=version_checking_delete_row&id=' + id,
			timeout: 30000,
			success: function( data, textStatus, jqXHR ) {
				// alert(data);
				$('#posts-total').text(parseInt($('#posts-total').text()) - 1);
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				//$form.find('.spinner').removeClass('is-active');
				//alert(jqXHR.responseText);
			}
		});
	});

	/**
	 * Version checking PlayStore
	 */
	$('.posts_page_version_checking_playstore #publish').on('click', function(e) {
		e.preventDefault();
		var message = '<div class="acf-admin-notice notice notice-success">' +
			'<p>Version checking is running. Please wait...</p>' +
			'</div>';
		$('.posts_page_version_checking_playstore').find('.acf-admin-notice').remove();
		$('.posts_page_version_checking_playstore h1').after(message);
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: {
				'action' : 'app_version_checking',
			},
			timeout: 60000,
			success: function( data, textStatus, jqXHR ) {
				console.log(data);
				console.log('Done');
				$('.posts_page_version_checking_playstore').find('.acf-admin-notice p').html('Done, reload page to see results.');
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				console.log(jqXHR.responseText);
				console.log('Error');
				console.log(textStatus);
				console.log(errorThrown);
			}
		});
	});

	$('.posts_page_version_checking_playstore a.delete').on('click', function(e) {
		e.preventDefault();

		var id = $(this).data('id');
		$(this).parents('tr').remove();
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: 'action=version_checking_playstore_delete_row&id=' + id,
			timeout: 30000,
			success: function( data, textStatus, jqXHR ) {
				// alert(data);
				$('#posts-total').text(parseInt($('#posts-total').text()) - 1);
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				//$form.find('.spinner').removeClass('is-active');
				//alert(jqXHR.responseText);
			}
		});
	});

	/**
	 * Show versions
	 */
	$('.app-show-versions').on('click', function(e) {
		e.preventDefault();	
		var $this = $(this);
		$('.app-popup-versions').remove();
		$('.app-popup-versions-form').remove();
		$('.app-popover-text-field').remove();
		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'app_show_versions',
				'post_id' : $this.data('post_id')
			},
			success: function( data, textStatus, jqXHR ) {
				$this.after(data);
			}
		});
	});

	/**
	 * Edit versions & download links
	 */
	$('.app-edit-download-links').on('click', function(e) {
		e.preventDefault();	
		var $this = $(this);
		$('.app-popup-versions-form').remove();
		$('.app-popover-text-field').remove();
		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'app_edit_versions',
				'post_id' : $this.data('post_id')
			},
			success: function( data, textStatus, jqXHR ) {
				$this.after(data);
			}
		});
	});

	$('body').on('click', '.app-close-versions-list', function(e) {
		e.preventDefault();	
		$('.app-popup-versions-form').remove();
		$('.app-popup-versions').remove();
	});

	$('body').on('click', '.app-add-versions-link', function(e) {
		e.preventDefault();
		var $this = $(this);

		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'app_add_versions_link'
			},
			success: function( data, textStatus, jqXHR ) {
				$this.closest('tr').before(data);

				var input = $this.closest('table').find('input:first');
				input.val(parseInt(input.val()) + 1);
				console.log(input.val());
			}
		});
	});

	$('body').on('click', '.app-add-versions', function(e) {
		e.preventDefault();
		var $this = $(this);

		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'app_add_versions'
			},
			success: function( data, textStatus, jqXHR ) {
				$this.closest('div').before(data);
			}
		});
	});

	$('body').on('click', '.app-remove-download-link', function(e) {
		e.preventDefault();
		var $this = $(this);

		var input = $this.closest('table').find('input:first');
		input.val(parseInt(input.val()) - 1);
		console.log(input.val());
		$this.closest('tr').remove();
	});

	$('body').on('click', '.app-remove-versions', function(e) {
		e.preventDefault();
		$(this).closest('.versions-item').remove();
	});

	$('body').on('click', '.app-save-versions', function(e) {
		e.preventDefault();	
		var $form = $(this).closest('form.app-popup-versions-form');
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: $form.serialize(),
			success: function( data, textStatus, jqXHR ) {
				console.log(data);
				$('.app-popup-versions-form').remove();
			}
		});
	});

	/**
	 * Edit text field
	 */
	$('body').on('click', '.app-edit-text-field', function(e) {
		e.preventDefault();	
		var $this = $(this);
		$('.app-popup-versions-form').remove();
		$('.app-popover-text-field').remove();
		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'app_edit_text_field',
				'post_id' : $this.data('post_id'),
				'field' : $this.data('field')
			},
			success: function( data, textStatus, jqXHR ) {
				$this.after(data);
			}
		});
	});

	$('body').on('click', '.app-close-text-field', function(e) {
		e.preventDefault();	
		$('.app-popover-text-field').remove();
	});

	$('body').on('click', '.app-save-text-field', function(e) {
		e.preventDefault();	
		var $form = $(this).parents('.app-popover-text-field');
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: $form.serialize(),
			success: function( data, textStatus, jqXHR ) {
				$form.parent().html(data);
			}
		});
	});
});