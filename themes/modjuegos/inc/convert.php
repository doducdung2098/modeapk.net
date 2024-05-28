<?php
function k_convert_init() {
    /**
     * Create convert page
     */
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title'      => 'Convert',
            'menu_title'      => 'Convert',
            'menu_slug'       => 'convert',
            'icon_url'        => 'dashicons-download',
            'capability'      => 'manage_options',
            'id'              => 'convert',
            'post_id'         => 'convert',
            'parent'          => 'edit.php'
        ));
    }
}
add_action( 'init', 'k_convert_init' );

/**
 * Convert
 */
function k_convert() {
	$args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query' 	 => array(
	  		'relation'   => 'AND',
			array(
				'key' 	  => 'downloads_0_download_link',
				'compare' => 'NOT EXISTS'
			),
		)
    );
    $query = new WP_Query($args);
    if ( $query->have_posts() ) {
        global $post;
        //echo $query->found_posts;
        while ( $query->have_posts() ) { $query->the_post();
        	//echo '<a href="' . get_edit_post_link() . '">' . get_the_title() . '</a><br>';
        	//continue;
            
            $title = get_the_title();
            $content = apply_filters( 'the_content', get_the_content() );

            preg_match('/\((MOD|Mod),?(.*?)\)/', $title, $match);
            $mod_info = $match[2];
            
            $app_name = trim(preg_replace('/\((MOD|Mod).*?\)/', '', $title));

            preg_match_all('/\[su_spoiler title=(&#8221;|\")(.*?)(&#8221;|\")/', $content, $titles);
            preg_match_all('@\[su_spoiler(.*?)\](.+?)\[\/su_spoiler\]@si', $content, $descs);
            $more_info = array();
            if ( $descs ) {
                foreach ( $descs[2] as $key => $value ) {
                    $desc = apply_filters('the_content', $descs[2][$key]);
                    $desc = force_balance_tags( $desc );
                    $desc = preg_replace( '/<([^<\/>]*)([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU', '', $desc );
                    $more_info[] = array(
                        'more_info_title' => $titles[2][$key],
                        'more_info_desc'  => $desc,
                    );
                }
            }

            preg_match_all('/\[tab\](.*?)\[\/tab\]/', $content, $matches);
            $downloads = array();
            if ( $matches ) {
                foreach ( $matches[1] as $value ) {
                    preg_match('/href="(.*?)"/', $value, $match);
                    $link = trim($match[1]);
                    $name = wp_strip_all_tags($value);
                    $name = trim(preg_replace('/(\(APK.*?\))|(\[APK.*?\])/', '', $name));
                    $headers = get_headers($link, 1);
                    $filesize = '';
                    if ( empty($filesize) ) {
                        $filesize = intval($headers['Content-Length']);
                        $filesize = $filesize / 1024 / 1024;
                        $filesize = round($filesize, 1);
                        if ( $filesize >= 1024 ) {
                            $filesize = $filesize / 1024;
                            $filesize = round($filesize, 1);
                            $filesize .= 'G';
                        } else {
                            $filesize .= 'M';
                        }
                    }
                    $type = pathinfo($link, PATHINFO_EXTENSION);
                    $downloads[] = array(
                        'download_name'    => $name,
                        'download_type'    => $type,
                        'download_size'    => $filesize,
                        'download_link'    => $link
                    );
                }
            }

            if ( ! empty($app_name) ) update_field('app_name', $app_name);
            if ( ! empty($mod_info) ) update_field('mod_info', $mod_info);
            if ( ! empty($downloads) ) update_field('downloads', $downloads);
            if ( ! empty($more_info) ) update_field('more_info', $more_info);
        } wp_reset_postdata();
    }

    _e('Success!');
    exit;
}
add_action( 'wp_ajax_k_convert', 'k_convert' );