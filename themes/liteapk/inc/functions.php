<?php

if (!function_exists('getenvd')) {
    function getenvd($key, $default) {
        $value = getenv($key);
        if (!$value) {
            $value = $default;
        }
        return $value;
    }
}

/**
 * Truncate text.
 */
function truncate( $text, $chars = 250 ) {
	$text = strip_tags( $text );
	if ( strlen( $text ) > $chars ) {
		$text = $text." ";
		$text = substr($text,0,$chars);
		$text = substr($text,0,strrpos($text,' '));
		$text = $text."...";
	}
    return $text;
}

/**
 * Remove body tag class
 */
add_action( 'body_class', 'app_body_class');
function app_body_class( $classes ) {
    if( in_array('tag', $classes) ) {
        unset( $classes[array_search('tag', $classes)] );
    }
    return $classes;
}

/**
 * Change excerpt more string
 */
function app_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'app_excerpt_more' );

/**
 * Change excerpt more string
 */
function app_excerpt_length( $length ) {
    return 44;
}
add_filter( 'excerpt_length', 'app_excerpt_length' );

/**
 * Make links
 */
function app_make_links($text) {
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" rel="nofollow">$1</a>', $text);
}

/**
 * Get social icons
 */
function app_social_icons() {
    return array(
        ''            => 'Select Icon',
        'facebook'    => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>',
        'gmail'       => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
        'twitter'     => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>',
        'instagram'   => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>',
        'youtube'     => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>',
        'linkedin'    => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>',
        'pinterest'   => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>',
        'weibo'       => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M407 177.6c7.6-24-13.4-46.8-37.4-41.7-22 4.8-28.8-28.1-7.1-32.8 50.1-10.9 92.3 37.1 76.5 84.8-6.8 21.2-38.8 10.8-32-10.3zM214.8 446.7C108.5 446.7 0 395.3 0 310.4c0-44.3 28-95.4 76.3-143.7C176 67 279.5 65.8 249.9 161c-4 13.1 12.3 5.7 12.3 6 79.5-33.6 140.5-16.8 114 51.4-3.7 9.4 1.1 10.9 8.3 13.1 135.7 42.3 34.8 215.2-169.7 215.2zm143.7-146.3c-5.4-55.7-78.5-94-163.4-85.7-84.8 8.6-148.8 60.3-143.4 116s78.5 94 163.4 85.7c84.8-8.6 148.8-60.3 143.4-116zM347.9 35.1c-25.9 5.6-16.8 43.7 8.3 38.3 72.3-15.2 134.8 52.8 111.7 124-7.4 24.2 29.1 37 37.4 12 31.9-99.8-55.1-195.9-157.4-174.3zm-78.5 311c-17.1 38.8-66.8 60-109.1 46.3-40.8-13.1-58-53.4-40.3-89.7 17.7-35.4 63.1-55.4 103.4-45.1 42 10.8 63.1 50.2 46 88.5zm-86.3-30c-12.9-5.4-30 .3-38 12.9-8.3 12.9-4.3 28 8.6 34 13.1 6 30.8.3 39.1-12.9 8-13.1 3.7-28.3-9.7-34zm32.6-13.4c-5.1-1.7-11.4.6-14.3 5.4-2.9 5.1-1.4 10.6 3.7 12.9 5.1 2 11.7-.3 14.6-5.4 2.8-5.2 1.1-10.9-4-12.9z"/></svg>',
        'tumblr'      => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M309.8 480.3c-13.6 14.5-50 31.7-97.4 31.7-120.8 0-147-88.8-147-140.6v-144H17.9c-5.5 0-10-4.5-10-10v-68c0-7.2 4.5-13.6 11.3-16 62-21.8 81.5-76 84.3-117.1.8-11 6.5-16.3 16.1-16.3h70.9c5.5 0 10 4.5 10 10v115.2h83c5.5 0 10 4.4 10 9.9v81.7c0 5.5-4.5 10-10 10h-83.4V360c0 34.2 23.7 53.6 68 35.8 4.8-1.9 9-3.2 12.7-2.2 3.5.9 5.8 3.4 7.4 7.9l22 64.3c1.8 5 3.3 10.6-.4 14.5z"/></svg>',
        'telegram'    => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"/></svg>',
        'reddit'      => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M201.5 305.5c-13.8 0-24.9-11.1-24.9-24.6 0-13.8 11.1-24.9 24.9-24.9 13.6 0 24.6 11.1 24.6 24.9 0 13.6-11.1 24.6-24.6 24.6zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-132.3-41.2c-9.4 0-17.7 3.9-23.8 10-22.4-15.5-52.6-25.5-86.1-26.6l17.4-78.3 55.4 12.5c0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.3 24.9-24.9s-11.1-24.9-24.9-24.9c-9.7 0-18 5.8-22.1 13.8l-61.2-13.6c-3-.8-6.1 1.4-6.9 4.4l-19.1 86.4c-33.2 1.4-63.1 11.3-85.5 26.8-6.1-6.4-14.7-10.2-24.1-10.2-34.9 0-46.3 46.9-14.4 62.8-1.1 5-1.7 10.2-1.7 15.5 0 52.6 59.2 95.2 132 95.2 73.1 0 132.3-42.6 132.3-95.2 0-5.3-.6-10.8-1.9-15.8 31.3-16 19.8-62.5-14.9-62.5zM302.8 331c-18.2 18.2-76.1 17.9-93.6 0-2.2-2.2-6.1-2.2-8.3 0-2.5 2.5-2.5 6.4 0 8.6 22.8 22.8 87.3 22.8 110.2 0 2.5-2.2 2.5-6.1 0-8.6-2.2-2.2-6.1-2.2-8.3 0zm7.7-75c-13.6 0-24.6 11.1-24.6 24.9 0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.1 24.9-24.6 0-13.8-11-24.9-24.9-24.9z"/></svg>',
        'flickr'      => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM144.5 319c-35.1 0-63.5-28.4-63.5-63.5s28.4-63.5 63.5-63.5 63.5 28.4 63.5 63.5-28.4 63.5-63.5 63.5zm159 0c-35.1 0-63.5-28.4-63.5-63.5s28.4-63.5 63.5-63.5 63.5 28.4 63.5 63.5-28.4 63.5-63.5 63.5z"/></svg>',
        'tripadvisor' => '<svg height="16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M166.4 280.521c0 13.236-10.73 23.966-23.966 23.966s-23.966-10.73-23.966-23.966 10.73-23.966 23.966-23.966 23.966 10.729 23.966 23.966zm264.962-23.956c-13.23 0-23.956 10.725-23.956 23.956 0 13.23 10.725 23.956 23.956 23.956 13.23 0 23.956-10.725 23.956-23.956-.001-13.231-10.726-23.956-23.956-23.956zm89.388 139.49c-62.667 49.104-153.276 38.109-202.379-24.559l-30.979 46.325-30.683-45.939c-48.277 60.39-135.622 71.891-197.885 26.055-64.058-47.158-77.759-137.316-30.601-201.374A186.762 186.762 0 0 0 0 139.416l90.286-.05a358.48 358.48 0 0 1 197.065-54.03 350.382 350.382 0 0 1 192.181 53.349l96.218.074a185.713 185.713 0 0 0-28.352 57.649c46.793 62.747 34.964 151.37-26.648 199.647zM259.366 281.761c-.007-63.557-51.535-115.075-115.092-115.068C80.717 166.7 29.2 218.228 29.206 281.785c.007 63.557 51.535 115.075 115.092 115.068 63.513-.075 114.984-51.539 115.068-115.052v-.04zm28.591-10.455c5.433-73.44 65.51-130.884 139.12-133.022a339.146 339.146 0 0 0-139.727-27.812 356.31 356.31 0 0 0-140.164 27.253c74.344 1.582 135.299 59.424 140.771 133.581zm251.706-28.767c-21.992-59.634-88.162-90.148-147.795-68.157-59.634 21.992-90.148 88.162-68.157 147.795v.032c22.038 59.607 88.198 90.091 147.827 68.113 59.615-22.004 90.113-88.162 68.125-147.783zm-326.039 37.975v.115c-.057 39.328-31.986 71.163-71.314 71.106-39.328-.057-71.163-31.986-71.106-71.314.057-39.328 31.986-71.163 71.314-71.106 39.259.116 71.042 31.94 71.106 71.199zm-24.512 0v-.084c-.051-25.784-20.994-46.645-46.778-46.594-25.784.051-46.645 20.994-46.594 46.777.051 25.784 20.994 46.645 46.777 46.594 25.726-.113 46.537-20.968 46.595-46.693zm313.423 0v.048c-.02 39.328-31.918 71.194-71.247 71.173s-71.194-31.918-71.173-71.247c.02-39.328 31.918-71.194 71.247-71.173 39.29.066 71.121 31.909 71.173 71.199zm-24.504-.008c-.009-25.784-20.918-46.679-46.702-46.67-25.784.009-46.679 20.918-46.67 46.702.009 25.784 20.918 46.678 46.702 46.67 25.765-.046 46.636-20.928 46.67-46.693v-.009z"/></svg>'
    );
}

/**
 * Get Extra info title
 */
function app_extra_info_titles() {
    return array(
		'whats_new' => __('What\'s new', 'mod'),
        'mod_info' => __('MOD details', 'mod'),
        'installation_guides' => __('Installation guides', 'mod'),
        'note' => __('Note', 'mod'),
    );
}

/**
 * Get page login
 */
function app_get_page_login() {
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 1,
        'meta_query'     => array(
            array(
                'key'    => '_wp_page_template',
                'value'  => 'page-login.php',
            ),
        ),
    );
    $posts = get_posts($args);
    if ( ! empty($posts) ) {
        return $posts[0];
    } else {
        return false;
    }
}

/**
 * Add noindex to download
 */
function app_add_noindex_to_download($str) {
    global $wp_query;
    if ( ! empty( $wp_query->query_vars['download'] ) ) {
        $download_slug = $wp_query->query_vars['download'];
        $download_slugs = explode('/', $download_slug);
        preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
        $post_id = $matches[1];

        if ( ! get_field('index_download', $post_id) ) return 'noindex,follow';
    }
    return $str;
}
add_filter( 'wpseo_robots', 'app_add_noindex_to_download', 10, 1 );

/**
 * Remove canonical from download
 */
function app_remove_canonical_from_download( $canonical ) {
    global $wp_query;
    if ( ! empty( $wp_query->query_vars['download'] ) ) {
        $download_slug = $wp_query->query_vars['download'];
        $download_slugs = explode('/', $download_slug);
        preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
        $post_id = $matches[1];

        if ( get_field('index_download', $post_id) )
            $canonical = false;
        else
            $canonical = get_the_permalink($post_id);
    }
    return $canonical;
}
add_filter( 'wpseo_canonical', 'app_remove_canonical_from_download', 99, 1 );

/**
 * Add download title
 */
function app_change_download_title($title) {
    global $wp_query;
    if ( ! empty( $wp_query->query_vars['download'] ) ) {
        $download_slug = $wp_query->query_vars['download'];
        $download_slugs = explode('/', $download_slug);
        preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
        $post_id = $matches[1];

        $title = get_field('_yoast_wpseo_title', $post_id);
    }
    return $title;
}
add_filter('wpseo_title', 'app_change_download_title');

/**
 * Add download description
 */
function app_change_download_desc($desc) {
    global $wp_query;
    if ( ! empty( $wp_query->query_vars['download'] ) ) {
        $download_slug = $wp_query->query_vars['download'];
        $download_slugs = explode('/', $download_slug);
        preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
        $post_id = $matches[1];

        $desc = get_field('_yoast_wpseo_metadesc', $post_id);
    }
    return $desc;
}
add_filter('wpseo_metadesc', 'app_change_download_desc');

/**
 * Add download endpoint
 */
function app_add_download_endpoint() {
    remove_action('template_redirect', 'redirect_canonical');
    add_rewrite_endpoint( 'download', EP_ROOT );
}
add_action( 'init', 'app_add_download_endpoint' );

function app_download_template_redirect() {

    global $wp_query;
    if ( empty( $wp_query->query_vars['download'] ) ) {
        return;
    }
    $wp_query->is_404 = false;
    require get_template_directory() . '/download.php';
    exit;
}
add_action( 'template_redirect', 'app_download_template_redirect' );

/**
 * Block non-administrators from accessing the WordPress back-end.
 */
function app_block_users_backend() {
    if ( is_user_logged_in() && is_admin() && current_user_can( 'subscriber' ) && ! wp_doing_ajax() ) {
        wp_redirect( home_url() );
        exit;
    }

    if ( is_user_logged_in() && current_user_can( 'subscriber' ) ) {
        show_admin_bar(false);
    }
}
add_action( 'init', 'app_block_users_backend' );

/**
 * Modify main query
 */
function app_modify_main_query( $query ) {
    if ( $query->is_tag() && $query->is_main_query() && ! is_admin() ) {
        $query->set( 'post_type', array('post', 'tip') );
    }
}
add_action( 'pre_get_posts', 'app_modify_main_query' );

/**
 * Change og meta
 */
add_filter( 'wpseo_opengraph_title', 'app_change_opengraph_title', 10, 1 );
function app_change_opengraph_title( $title ) {
	global $wp_query;
    if ( is_singular('post') ) {
        if ( get_post_meta(get_the_ID(), '_yoast_wpseo_title', true) )
            $title = get_post_meta(get_the_ID(), '_yoast_wpseo_title', true); 
    } elseif ( ! empty( $wp_query->query_vars['download'] ) ) {
        $download_slug = $wp_query->query_vars['download'];
        $download_slugs = explode('/', $download_slug);
        preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
        $post_id = $matches[1];
        if ( get_post_meta($post_id, '_yoast_wpseo_title', true) )
            $title = get_post_meta($post_id, '_yoast_wpseo_title', true); 
    }

    return $title;
}

add_filter( 'wpseo_opengraph_desc', 'app_change_opengraph_desc', 10, 1 );
function app_change_opengraph_desc( $desc ) {
	global $wp_query;
    if ( is_singular('post') ) {
        if ( $short_description = get_field('short_description') ) {
            $desc = strip_tags($short_description);
        } 
    } elseif ( ! empty( $wp_query->query_vars['download'] ) ) {
        $download_slug = $wp_query->query_vars['download'];
        $download_slugs = explode('/', $download_slug);
        preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
        $post_id = $matches[1];
        if ( $short_description = get_field('short_description', $post_id) )
            $desc = strip_tags($short_description);
        elseif ( get_post_meta($post_id, '_yoast_wpseo_metadesc', true) )
            $desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true); 
    }

    return $desc;
}

add_filter('wpseo_frontend_presentation', 'app_yoast_presentation', 30);
function app_yoast_presentation($presentation) {
	global $wp_query;
    if ( is_singular('post') ) {
        if ( $temp = get_field('post_banner') ) {
            $image = $temp['url'];
            $presentation->open_graph_images = [
	            [
	                'url' => $image,
	                'width' => false,
	                'height' => false,
	            ]
	        ];
        }
    } elseif ( ! empty( $wp_query->query_vars['download'] ) ) {
    	$download_slug = $wp_query->query_vars['download'];
        $download_slugs = explode('/', $download_slug);
        preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
        $post_id = $matches[1];
        if ( $temp = get_field('post_banner', $post_id) ) {
            $image = $temp['url'];
            $presentation->open_graph_images = [
	            [
	                'url' => $image,
	                'width' => false,
	                'height' => false,
	            ]
	        ];
        }
    }

    return $presentation;
}

/**
 * Filters the canonical URL.
 *
 * @param string $canonical The current page's generated canonical URL.
 *
 * @return string The filtered canonical URL.
 */
function app_change_category_canonical( $canonical ) {
	if ( is_category() ) {
		$canonical = get_category_link(get_queried_object());
	}

	return $canonical;
}
add_filter( 'wpseo_canonical', 'app_change_category_canonical' );


/**
 * Clean download cache when update or delete post
 */
function app_clean_download_cache( $post_id, $post, $update ) {

    if( ! is_plugin_active('wp-rocket/wp-rocket.php') ) {
        return;
    }

    if ( ! $update ){
        return;
    }

    if ( 'post' !== $post->post_type ) {
        return;
    }

    if ( $app_name = get_field('app_name', $post_id) )
        $download_slug = sanitize_title($app_name);
    else
        $download_slug = $post->post_name;
    $download_slug .= '-' . $post_id;

    app_delete_directory(WP_ROCKET_CACHE_ROOT_PATH . 'wp-rocket/liteapk.ru/download/' . $download_slug);
}
add_action( 'save_post', 'app_clean_download_cache', 10, 3 );

/*
 * php delete function that deals with directories recursively
 */
function app_delete_directory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
     if (!$dir_handle)
          return false;
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                    app_delete_directory($dirname.'/'.$file);
           }
     }
     closedir($dir_handle);
     rmdir($dirname);
     return true;
}
