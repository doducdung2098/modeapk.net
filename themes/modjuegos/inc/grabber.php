<?php
function grabber_init() {
	/**
	 * Create grabber page
	 */
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	  => 'Grabber',
			'menu_title'	  => 'Grabber',
			'menu_slug' 	  => 'grabber',
			'icon_url'		  => 'dashicons-download',
			'capability'	  => 'manage_options',
			'id'			  => 'grabber',
			'post_id' 		  => 'grabber',
			'parent'		  => 'edit.php'
		));
	}

	/**
	 * Add grabber field group
	 */
	if ( function_exists('acf_add_local_field_group') ) {
		acf_add_local_field_group(array(
			'key'		=> 'grabber_settings',
			'title' 	=> 'Settings',
			'fields' 	=> array (
				array(
					'label' => 'Package',
					'name'  => 'grabber_package',
					'key'   => 'grabber_package',
					'type'  => 'text',
                    'required' => 1,
				),
                array(
                    'label' => 'Language',
                    'key'  => 'lang',
                    'name' => 'lang',
                    'type' => 'select',
                    'choices' => get_locale_codes(),
                    'layout' => 0,
                    'required' => 1,
                ),
//                array(
//                    'key' => 'category',
//                    'label' => 'Category',
//                    'name' => 'category',
//                    'aria-label' => '',
//                    'type' => 'taxonomy',
//                    'instructions' => '',
//                    'required' => 0,
//                    'taxonomy' => 'category',
//                    'add_term' => 1,
//                    'save_terms' => 0,
//                    'load_terms' => 0,
//                    'return_format' => 'id',
//                    'field_type' => 'checkbox',
//                    'bidirectional' => 0,
//                    'multiple' => 0,
//                    'allow_null' => 0,
//                ),
//				array(
//					'label' => 'Content',
//					'name'  => 'grabber_content',
//					'key'   => 'grabber_content',
//					'type'  => 'wysiwyg',
//				),
			),
			'location' => array (
				array (
					array (
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'grabber',
					),
				),
			),
		));	
	}
}
add_action( 'init', 'grabber_init' );

/**
 * Grabber
 */
function k_grabber() {
	$params = $_REQUEST['acf'];
	if ( empty($params['grabber_package']) ) {
		echo('Please input Package.');
		exit;
	}

	$package = $params['grabber_package'];	
//	$content = $params['grabber_content'];


//    $locale    = 'ru_RU';
    $country = 'RU';
    $locale    = $params['lang'];
    if($locale == 'es_ES') {
        $country = 'ES';
    } else if($locale == 'de_DE') {
        $country = 'DE';
    } else if($locale == 'en_US') {
        $country = 'US';
    } else if($locale == 'pt_PT') {
        $country = 'PT';
    } else if($locale == 'ja_JP') {
        $country = 'JP';
    }  else if($locale == 'fr_FR') {
        $country = 'FR';
    }

	update_field('grabber_package', $package, 'grabber');

	require_once(get_template_directory() . '/vendor/autoload.php');
    $gplay = new \Nelexa\GPlay\GPlayApps($locale, $country);

    $appInfo = $gplay->getAppInfo(new \Nelexa\GPlay\Model\AppId(
        $package, // id
        $locale, // locale
        $country // country
    ));
	$title = $appInfo->getName();

    if ( $post_id = post_exists($title, '', '', 'post') ) {
        echo '<b>' . $title . '</b> existed: ' . '<a href="' . get_edit_post_link($post_id) . '" target="_blank">' . get_edit_post_link($post_id) . '</a>';
        exit;
    } else {
        $data = array();
        $data['playstore_url'] = $appInfo->getUrl();
        $data['title'] = $appInfo->getName();
        $data['feature'] = $appInfo->getIcon()->getUrl(). '=w300-rw';
        $data['banner'] = $appInfo->getCover()->getUrl() . '=w1000-rw';
        $data['screenshots'] = $appInfo->getScreenshots();
        $data['summary'] = $appInfo->getSummary();
        $data['version'] = $appInfo->getAppVersion();
        $data['publisher'] = str_replace(',','',$appInfo->getDeveloperName());
        $data['offsite'] = str_replace(',','',$appInfo->getDeveloper()->getWebsite());
        $data['category'] = $appInfo->getCategory()->getName();
        $data['whats_new'] = $appInfo->getRecentChanges();
        $data['require'] = trim('Android '.$appInfo->getAndroidVersion());
        $data['content'] = $appInfo->getDescription();
//        $data['category_ids'] = $params['category'];

		$post_id = wp_insert_post(array(
            'post_type'    => 'post',
            'post_title'   => $data['title'],
            'post_content' => $data['content'],
            'meta_input'   => array(
                'app_name'         => $data['title'],
                '_softwareVersion'  => $data['version'],
                '_playstore_url'    => $data['playstore_url'],
                'short_description' => $data['summary'],
                '_require' => $data['require'],
                'post_what_new' => $data['whats_new'],
                'internal_note' => 'Category: '.$data['category'].'; Publisher: ' . $data['publisher'],
            ),
		));

//        if ( !empty($data['category_ids']) ) {
//            wp_set_post_terms($post_id, $data['category_ids'], 'category');
//        }

        if(!empty($data['feature'])){
            Generate_Featured_Image($data['feature'], $title, $post_id);
        }

        if(!empty($data['banner'])) {
            $attach_id = Generate_Featured_Image($data['banner'], $title);
            update_field('post_banner', $attach_id, $post_id);
        }

        if ( !empty($data['screenshots']) ) {
            $gallery = array();
            foreach ( $data['screenshots'] as $key => $temp ) {
                if ( $key == 6 ) break;
                $attach_id = Generate_Featured_Image($temp->getUrl() . '=w800-rw', $title);
                $gallery[] = $attach_id;
            }
            update_field('post_gallary', $gallery, $post_id);
        }
	}

    echo '<b>' . $title . '</b> created: ' . '<a href="' . get_edit_post_link($post_id) . '" target="_blank">'.  get_edit_post_link($post_id) . '</a>' . '<br>';

	exit;
}
add_action( 'wp_ajax_k_grabber', 'k_grabber' );

/**
 * Insert image
 */
function Generate_Featured_Image( $image_url, $image_title = '', $post_id = 0 ) {
    $upload_dir = wp_upload_dir();
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $image_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    $image_data = curl_exec($curl);
    curl_close($curl);

    $filename = sanitize_file_name($image_title . '.webp');
    $upload = wp_upload_bits($filename, null, $image_data);

    // Load the WebP file
    $im = imagecreatefromwebp($upload['file']);
    // Convert to 82% quality
    imagewebp($im, $upload['file'], 82);
    imagedestroy($im);

    if ( ! $upload['error'] ) :
        $attachment = array(
            'post_mime_type' => $upload['type'],
            'post_title'     => $image_title,
            'post_content'   => $image_title,
            'post_status'    => 'inherit',
        );
        $attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        update_post_meta($attach_id, '_wp_attachment_image_alt', get_the_title($attach_id));

        if ( $post_id ) {
            set_post_thumbnail( $post_id, $attach_id );
        }

        return $attach_id;
    else :
        return false;
    endif;
}

function sanitize_filename_on_upload($filename) {
	$ext = end(explode('.',$filename));
	$sanitized = preg_replace('/[^a-zA-Z0-9-_.]/','', substr($filename, 0, -(strlen($ext)+1)));
	$sanitized = str_replace('.','-', $sanitized);
	return strtolower($sanitized.'.'.$ext);
}
add_filter('sanitize_file_name', 'sanitize_filename_on_upload', 10);

function Curl ($url, $ifpost = 0, $datafields = ', $cookiefile = ', $v = False) {
	$ip_long = Array (
	Array (' 607649792 ', ' 608174079 '),//36.56.0.0-36.63.255.255
	Array (' 1038614528 ', ' 1039007743 '),//61.232.0.0-61.237.255.255
	Array (' 1783627776 ', ' 1784676351 '),//106.80.0.0-106.95.255.255
	Array (' 2035023872 ', ' 2035154943 '),//121.76.0.0-121.77.255.255
	Array (' 2078801920 ', ' 2079064063 '),//123.232.0.0-123.235.255.255
	Array ('-1950089216 ', '-1948778497 '),//139.196.0.0-139.215.255.255
	Array ('-1425539072 ', '-1425014785 '),//171.8.0.0-171.15.255.255
	Array ('-1236271104 ', '-1235419137 '),//182.80.0.0-182.92.255.255
	Array ('-770113536 ', '-768606209 '),//210.25.0.0-210.47.255.255
	Array ('-569376768 ', '-564133889 '),//222.16.0.0-222.95.255.255
	);
	$rand_key = Mt_rand (0, 9);
	$ip = Long2ip (Mt_rand ($ip_long[$rand_key][0], $ip_long[$rand_key][1]));

	$agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36';
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt ($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
	curl_setopt ($ch, CURLOPT_HEADER, $v);
	curl_setopt ($ch, CURLOPT_FRESH_CONNECT, TRUE);
	$ifpost && curl_setopt ($ch, CURLOPT_POST, $ifpost);
	$ifpost && curl_setopt ($ch, CURLOPT_POSTFIELDS, $datafields);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_REFERER, 'http://' . getRandomString() . '.com');
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
	$cookiefile && curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookiefile);
	$cookiefile && curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookiefile);
	curl_setopt ($ch, CURLOPT_TIMEOUT,30);
	$ok = curl_exec ($ch);
	curl_close ($ch);

	Unset ($ch);
	return $ok;
}

function getRandomString($n = 3) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
}

/**
 * locale codes
 */
function get_locale_codes() {
    return array(
		'es_ES' => 'Spanish',
        'en_US' => 'English',
    );
}