jQuery(function($) {

	/**
	 * General variables
	 */
	var adminbarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
	var siteheaderHeight = $('.site-header').length > 0 ? $('.site-header').height() : 0;
	$(window).resize(function() {
		adminbarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
		siteheaderHeight = $('.site-header').length > 0 ? $('.site-header').height() : 0;
	});
    
	/**
	 * Site navigation
	 */
	$('.site-nav-toggler').on('click', function() {
		if ($('body').hasClass('open-site-nav')) {
			$('body').removeClass('open-site-nav').css('overflow', '');
			$('.site-overlay').remove();
		} else {
			$('body').addClass('open-site-nav').css('overflow', 'hidden');
			$('.site-nav').css('top', adminbarHeight);
			$('.site-nav').after('<div class="site-overlay"></div>');
		}
	});
	$('body').on('click', '.site-nav-closer, .site-overlay', function(e) {
		e.stopPropagation();
		$('body').removeClass('open-site-nav').css('overflow', '');
		$('.site-overlay').remove();
	});
	$('.site-nav li.menu-item-has-children').on('click', function(e) {
		e.stopPropagation();
		$(this).siblings().children('.sub-menu').slideUp(300);
		$(this).toggleClass('active').children('.sub-menu').slideToggle(300);
	});
	$('.site-nav a').on('click', function(e) {
		e.stopPropagation();
	});

	/**
	 * Table of contents
	 */
	if ( $('.table-of-contents').length > 0 ) {
		var index= 1;
		$('.entry-content .post-content h2, .entry-content .post-content h3').each(function() {
			$(this).attr('id', convertToSlug($(this).text()));
			if ( $(this).is('h2') ) {
				$('.table-of-contents .links').append('<a class="d-block mb-2 toc-heading" href="#'+convertToSlug($(this).text())+'">' + $(this).text() + '</a>');
			} else if ( $(this).is('h3') ) {
				$('.table-of-contents .links').append('<a class="d-block ml-3 mb-2 toc-heading" href="#'+convertToSlug($(this).text())+'">' + $(this).text() + '</a>');
			}
			index++;
		});
	}

	$('.table-of-contents a').on('click', function(event) {
	    event.preventDefault();
	    var hash = this.hash;
	    location.hash = hash;
	    $('html, body').animate({
	    	scrollTop: $(hash).offset().top - adminbarHeight - siteheaderHeight
	    }, 0);
	});

	if ( location.hash ) {
		var hash = location.hash;
		if ( $('.table-of-contents a[href="' + hash + '"]').length > 0 ) {
			$('.tab-nav a').removeClass('active');
			$('.tab-pane').removeClass('active show');
			var id = $(hash).parents('.tab-pane').attr('id');
			$('a[href="#' + id + '"]').trigger('click');
			setTimeout(function() {
				$('html, body').animate({
			    	scrollTop: $(hash).offset().top - adminbarHeight - siteheaderHeight
			    }, 300);
			}, 2000);
		}
	}

	/**
	 * Schema faq
	 */
	$('.schema-faq-section').on('click', function() {
		$('.schema-faq-section.active').find('.schema-faq-answer').slideUp(100);
		if ( $(this).hasClass('active') ) {
			$('.schema-faq-section.active').removeClass('active');
			$(this).find('.schema-faq-answer').slideUp(100);
		} else {
			$('.schema-faq-section.active').removeClass('active');
			$(this).addClass('active');
			$(this).find('.schema-faq-answer').slideDown(100);
		}
	});

	/**
	 * Rating
	 */
	$('.rating').rateYo({
		starWidth: '20px',
		fullStar: true,
		onSet: function (rating, rateYoInstance) {
			var $this = $(this);
			var post_id = $this.data('post_id');
			$.ajax({
				type: 'POST',
				url: ajax.ajax_url,
				data: {
					'action'  : 'site_rating',
					'post_id' : post_id,
					'rating'  : rating,
				},
				success: function( data, textStatus, jqXHR ) {
					if ( data.status == true ) {
						$this.rateYo('option', 'readOnly', true);
					} else {
						alert(data.message);
					}
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					alert( jqXHR.responseText );
				}
			});
	    }
	});
	
	/**
	 * Login
	 */
	$('.form-comment').on('submit', function(e) {
		e.preventDefault();
		$form = $(this);
		$form.find('[type="submit"]').append('<svg class="ml-2 svg-6 spin icon" fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"/></svg>');
		$form.find('.alert').remove();
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			data: $form.serialize(),
			success: function( data, textStatus, jqXHR ) {
				$form.find('[type="submit"]').find('.icon').remove();
				if( data.status == true ) {
					$form.append('<div class="alert alert-success">' + data.message + '</div>');
					$form.find('.form-control').val('');
					if( data.redirect ) {
						window.location.href = data.redirect;
					}
				} else {
					$form.append('<div class="alert alert-danger">' + data.message + '</div>');
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) {
				alert( errorThrown );
			}
		});
	});

	/**
	 * Comment
	 */
	$('.comment .reply').on('click', function() {
		$('.form-comment').find('input[name="parent"]').remove();
		$('.form-comment').prepend('<input type="hidden" name="parent" value="' + $(this).data('parent') + '"/>');
		$('.form-comment').find('[name="comment"]').focus();
		$('html, body').animate({
			scrollTop: $('.form-comment').offset().top - adminbarHeight - siteheaderHeight
		}, 600);
	});

	/**
	 * Update post views
	 */
	if ( $('body[data-post-id]').length > 0 ) {
		$.ajax({
			type: 'POST',
			url: ajax.ajax_url,
			cache: false,
			data: {
				'action' : 'site_update_post_views',
				'post_id' : $('body[data-post-id]').data('post-id')
			}
		});
	}

	/**
	 * Countdown and show download
	 */
	// if ( $('#download-loading').length > 0 ) {
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: ajax.ajax_url,
	// 		data: {
	// 			'action' : 'site_get_download',
	// 		},
	// 		success: function( data, textStatus, jqXHR ) {
	// 			$('#download-loading').after(data);
	// 			$('#download-loading').remove();
	// 		},
	// 		error: function( jqXHR, textStatus, errorThrown ) {
	// 			alert( errorThrown );
	// 		}
	// 	});
	// }

	if ( $('#download-loading').length > 0 ) {
		var timeleft = 5;
		var downloadTimer = setInterval(function(){
			timeleft -= 1;
			$('#download-timeleft').html(timeleft);
			if ( timeleft <= 0 ) {
				clearInterval(downloadTimer);
				// $('#download-loading').css('display', 'none');
				$('#download-loading').addClass('d-none');
				// $('#download').css('display', 'block');
				$('#download').removeClass('d-none');
			}
		}, 1000);
	}

	/**
	 * Toggle show more / show less
	 */

	const readMoreContent = $('.readmore-section');

	readMoreContent.each(function (index){
		// hide with index > 0
		if (index > 0) {
			$(this).parent().css('display', 'none');
		}
	});

	$('ul.tabs-nav li').click(function (event) {
		event.preventDefault();

		var tabId = $(this).find('a').attr('href');

		$('.tabs-container').hide();
		$(tabId).show();

		$('ul.tabs-nav li').removeClass('ui-tabs-active ui-state-active');

		$(this).addClass('ui-tabs-active ui-state-active');
	});

});

function convertRuToLatin(string) {
	const cyrillicToLatinMap = {
		'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'E', 'Ж': 'ZH', 'З': 'Z', 'И': 'I',
		'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T',
		'У': 'U', 'Ф': 'F', 'Х': 'KH', 'Ц': 'TS', 'Ч': 'CH', 'Ш': 'SH', 'Щ': 'SCH', 'Ъ': '', 'Ы': 'Y', 'Ь': '',
		'Э': 'E', 'Ю': 'YU', 'Я': 'YA', 'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e',
		'ж': 'zh', 'з': 'z', 'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p',
		'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'kh', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'sch',
		'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
	};

	return string.split('')
		.map(char => cyrillicToLatinMap[char] || char) // Chuyển đổi ký tự Nga sang Latinh
		.join('');
}

function convertToSlug(Text) {
	Text = convertRuToLatin(Text);
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
		.trim()
        ;
}