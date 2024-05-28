<?php
/**
 * app functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mod
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if ( ! function_exists( 'app_setup' ) ) :
function app_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'mod', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'site-nav'     => 'Site navigation',
		'footer-nav-1' => 'Footer navigation 1',
		'footer-nav-2' =>  'Footer navigation 2',
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/**
	 * Add image sizes
	 */
	add_image_size( 'thumbnail', 150, 150, true );
	add_image_size( 'medium', 630, 310, true );
}
endif;
add_action( 'after_setup_theme', 'app_setup' );

/**
 * Remove medium_large size
 */
function app_remove_image_sizes( $sizes) {
	unset( $sizes['medium_large'] );
	unset( $sizes['large'] );
	unset( $sizes['1536x1536'] );
    unset( $sizes['2048x2048'] );
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'app_remove_image_sizes');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function app_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'app_content_width', 640 );
}
add_action( 'after_setup_theme', 'app_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function app_site_scripts() {
	wp_enqueue_style( 'app-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', false, '2' );
	wp_enqueue_style( 'app-style', get_stylesheet_uri(), false, '2' );

	wp_enqueue_script( 'app-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '2', true );
	wp_enqueue_script( 'app-js-site', get_template_directory_uri() . '/js/site.js', array('jquery'), '2', true );

	wp_localize_script( 'app-js-site', 'ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'app_site_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function app_admin_scripts() {
	wp_enqueue_script( 'app-admin-script', get_template_directory_uri() . '/js/admin.js', array('jquery'), '2', true );
	wp_localize_script( 'app-admin-script'
        , 'ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'admin_enqueue_scripts', 'app_admin_scripts' );

// 1. customize ACF path
add_filter('acf/settings/path', 'k_acf_settings_path');
function k_acf_settings_path( $path ) {
	$path = get_stylesheet_directory() . '/inc/acf/';
	return $path;
}

// 2. customize ACF dir
add_filter('acf/settings/dir', 'app_acf_settings_dir');
function app_acf_settings_dir( $dir ) {
	$dir = get_stylesheet_directory_uri() . '/inc/acf/';
	return $dir;
}

// 3. Hide ACF field group menu item
add_filter('acf/settings/show_admin', '__return_false');

// 4. Include ACF
include_once( get_stylesheet_directory() . '/inc/acf/acf.php' );
include_once( get_stylesheet_directory() . '/inc/acf-quick-edit-fields/index.php' );

/**
 * Custom fields
 */
require get_template_directory() . '/inc/setting-post.php';
require get_template_directory() . '/inc/setting-tip.php';
require get_template_directory() . '/inc/setting-page-home.php';

/**
 * Ajax functions
 */
require get_template_directory() . '/inc/ajax.php';

/**
 * Utility functions.
 */
require get_template_directory() . '/inc/functions.php';

/**
 * Theme Options.
 */
require get_template_directory() . '/inc/options.php';

/**
 * Load Breadcrumb
 */
require get_template_directory() . '/inc/breadcrumb.php';
add_filter( 'wpseo_schema_needs_breadcrumb', '__return_false' ); // disable Yoast breadcrumb

/**
 * Load Pagination
 */
require get_template_directory() . '/inc/pagination.php';

/**
 * Load Grabber
 */
require get_template_directory() . '/inc/grabber.php';

/**
 * Load Version Checking
 */
require get_template_directory() . '/inc/version-checking.php';
require get_template_directory() . '/inc/version-checking-playstore.php';

/**
 * Load Schedule
 */
require get_template_directory() . '/inc/schedule.php';

/**
 * Admin Ajax
 */
require get_template_directory() . '/inc/admin-ajax.php';

/**
 * Sample page
 */
require get_template_directory() . '/inc/sample-page.php';

function load_media_files() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_media_files' );

function the_ads(){
    if($banner = get_field('banner_ads', 'options')) {
        echo '<div class="my-3">'.$banner.'</div>';
    }
}

function dump($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}