jQuery(function($) {
	/**
	 *	Convert Data
	 */
	$('.posts_page_convert #publish').on('click', function(e) {
		e.preventDefault();	
		var $form = $(this).parents('#post');
		$form.find('.spinner').addClass('is-active');
		
		var message = '<div class="acf-admin-notice notice notice-success">' +
					  	'<p>Grabber is running. Please wait it.</p>' +
					  '</div>';
		$('.posts_page_convert').find('.acf-admin-notice').remove();
		$('.posts_page_convert h1').after(message);
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: $form.serialize() + '&action=k_convert',
			success: function( data, textStatus, jqXHR ) {
				$form.find('.spinner').removeClass('is-active');
				$('.posts_page_convert').find('.acf-admin-notice p').html(data);
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				$form.find('.spinner').removeClass('is-active');
				alert(jqXHR.responseText);
			}
		});
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
	 *	Version Checker
	 */
	var offset = 0;
	$('.posts_page_version_checker #publish').on('click', function(e) {
		e.preventDefault();	
		var $form = $(this).parents('#post');
		$form.find('.spinner').removeClass('is-active');
		var message = '<div class="acf-admin-notice notice notice-success">' +
					  	'<p>Version checker is running. Keep page open to see results.</p>' +
					  '</div>';
		$('.posts_page_version_checker').find('.acf-admin-notice').remove();
		$('.posts_page_version_checker h1').after(message);
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: 'action=k_version_checker&offset=' + offset,
			success: function( data, textStatus, jqXHR ) {
				if ( data.status == true ) {
					offset = data.offset;
					$('#postbox-container-2').html(data.result);
					$('.posts_page_version_checker #publish').trigger('click');
				} else {
					$('.posts_page_version_checker').find('.acf-admin-notice p').html(data.message);
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				offset = offset + 5;
				$('.posts_page_version_checker #publish').trigger('click');
			}
		});
	});

	$('.checking_update_btn').on('click', function(e) {
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

	$(document).on('click', '.posts_page_version_checker .update', function(e) {
		e.preventDefault();	
		var $this = $(this);
		var postId = $this.data('post_id');
		$this.text('Updating...');
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: 'action=k_version_checker_update_post&post_id=' + postId,
			success: function( data, textStatus, jqXHR ) {
				if ( data.status == true ) {
					$this.addClass('button-primary').removeClass('update').text('Updated');
					$this.parent().prev().prev().prev().text(data.version);
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


	$(document).on('click', '.get-file, .hit-get-file', function(e) {
		e.preventDefault();	
		var $this = $(this);
		var postId = $this.data('post_id');
		var file = $this.prev().val();
		var type = $this.prev().prev().val();
		$this.text('Getting...');
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: {
				'action' : 'k_version_checker_get_file',
				'post_id' : postId,
				'file' : file,
				'type' : type
			},
			timeout: 3000,
			success: function( data, textStatus, jqXHR ) {
				$this.text('Get');
				alert(data);

				if ( $('body').hasClass('post-type-post') ) {
					if ( data == 'File uploaded succesfully.' ) {
						location.reload(true);
					}
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				alert(jqXHR.responseText);
			}
		});
	});

	//delete row
	$('.checking_delete_btn').on('click', function(e) {
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

	$(document).on('click', '.posts_page_version_checker .delete', function(e) {
		e.preventDefault();	
		if ( confirm('Do you really want to delete?') ) {
			var id = $(this).data('id');
			$(this).parents('tr').remove();
			$.ajax({
				type: 'POST',
				url: ajax.ajax_url,
				data: 'action=k_version_checker_delete_row&id=' + id,
				timeout: 3000,
				success: function( data, textStatus, jqXHR ) {
					alert(data);
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseText);
				}
			});
		}
	});

	$(document).on('click', '.posts_page_version_checker .delete-rows', function(e) {
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
					'action' : 'k_version_checker_delete_rows',
					'ids' : ids,
					'all_ids' : $('[name="all_ids"]').val()
				},
				timeout: 3000,
				success: function( data, textStatus, jqXHR ) {
					alert(data);
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseText);
				}
			});
		}
	});

	$(document).on('click', '[name="all_ids"]', function(e) {
		$('[name="ids"]').prop('checked', this.checked);
	});

	/**
	 *	Version Checker Json
	 */
	$('.posts_page_version_checker_json #publish').on('click', function(e) {
		e.preventDefault();	
		var $form = $(this).parents('#post');
		$form.find('.spinner').removeClass('is-active');
		var message = '<div class="acf-admin-notice notice notice-success">' +
					  	'<p>Version checker is running. Reload page to see latest updates.</p>' +
					  '</div>';
		$('.posts_page_version_checker_json').find('.acf-admin-notice').remove();
		$('.posts_page_version_checker_json h1').after(message);
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: $form.serialize() + '&action=k_version_checker_json',
			success: function( data, textStatus, jqXHR ) {
				$('.posts_page_version_checker_json').find('.acf-admin-notice p').html(data.status);
				$('#postbox-container-2').html(data.result);
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				$('.posts_page_version_checker_json').find('.acf-admin-notice p').html(jqXHR.responseText);
			}
		});
	});

	$(document).on('click', '.posts_page_version_checker_json .update', function(e) {
		e.preventDefault();	
		var $this = $(this);
		var postId = $this.data('post_id');
		$this.text('Updating...');
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: 'action=k_version_checker_json_update_post&post_id=' + postId,
			success: function( data, textStatus, jqXHR ) {
				if ( data.status == true ) {
					$this.addClass('button-primary').removeClass('update').text('Updated');
					$this.parent().prev().prev().prev().text(data.version);
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

	$(document).on('click', '.posts_page_version_checker_json .delete', function(e) {
		e.preventDefault();	
		if ( confirm('Do you really want to delete?') ) {
			var id = $(this).data('id');
			$(this).parents('tr').remove();
			$.ajax({
				type: 'POST',
				url: ajax.ajax_url,
				data: 'action=k_version_checker_json_delete_row&id=' + id,
				timeout: 3000,
				success: function( data, textStatus, jqXHR ) {
					alert(data);
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseText);
				}
			});
		}
	});

	$(document).on('click', '.posts_page_version_checker_json .delete-rows', function(e) {
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
					'action' : 'k_version_checker_json_delete_rows',
					'ids' : ids,
					'all_ids' : $('[name="all_ids"]').val()
				},
				timeout: 3000,
				success: function( data, textStatus, jqXHR ) {
					alert(data);
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseText);
				}
			});
		}
	});

	/**
	 * Show versions
	 */
	$('.k-show-versions').on('click', function(e) {
		e.preventDefault();	
		var $this = $(this);
		$('.k-popover-versions').remove();
		$('.k-popover-download-links').remove();
		$('.k-popover-text-field').remove();
		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'k_show_versions',
				'post_id' : $this.data('post_id')
			},
			success: function( data, textStatus, jqXHR ) {
				$this.after(data);
			}
		});
	});

	/**
	 * Edit download links
	 */
	$('.k-edit-download-links').on('click', function(e) {
		e.preventDefault();	
		var $this = $(this);
		$('.k-popover-download-links').remove();
		$('.k-popover-text-field').remove();
		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'k_edit_download_links',
				'post_id' : $this.data('post_id')
			},
			success: function( data, textStatus, jqXHR ) {
				$this.after(data);
			}
		});
	});

	$('body').on('click', '.k-close-download-links', function(e) {
		e.preventDefault();	
		$('.k-popover-download-links').remove();
		$('.k-popover-versions').remove();
	});

	$('body').on('click', '.k-add-download-link', function(e) {
		e.preventDefault();	
		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'k_add_download_link'
			},
			success: function( data, textStatus, jqXHR ) {
				$('.k-popover-download-links table').append(data);
			}
		});
	});

	$('body').on('click', '.k-remove-download-link', function(e) {
		e.preventDefault();	
		$(this).parent('td').parent('tr').remove();
	});

	$('body').on('click', '.k-save-download-links', function(e) {
		e.preventDefault();	
		var $form = $(this).parents('.k-popover-download-links');
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: $form.serialize(),
			success: function( data, textStatus, jqXHR ) {
				$form.prevAll('.k-download-links').html(data);
				$('.k-popover-download-links').remove();
			}
		});
	});

	/**
	 * Edit text field
	 */
	$('body').on('click', '.k-edit-text-field', function(e) {
		e.preventDefault();	
		var $this = $(this);
		$('.k-popover-download-links').remove();
		$('.k-popover-text-field').remove();
		$.ajax({
			type: 'GET',
			url: ajax.ajax_url,
			data: {
				'action' : 'k_edit_text_field',
				'post_id' : $this.data('post_id'),
				'field' : $this.data('field')
			},
			success: function( data, textStatus, jqXHR ) {
				$this.after(data);
			}
		});
	});

	$('body').on('click', '.k-close-text-field', function(e) {
		e.preventDefault();	
		$('.k-popover-text-field').remove();
	});

	$('body').on('click', '.k-save-text-field', function(e) {
		e.preventDefault();	
		var $form = $(this).parents('.k-popover-text-field');
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