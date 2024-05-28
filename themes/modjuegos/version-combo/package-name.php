<?php
/**
 * Template name: Package
 *
 */


if ( ! defined( 'ABSPATH' ) )
    exit;

if( isset($_GET['get']) ) :

    define('POSTS_PER_PAGE', 10);

    $current_page = get_option('update_current_page');
    if (!$current_page)  $current_page = 1;

    $update_args = array(
        'post_type' => 'post',
        'posts_per_page' => POSTS_PER_PAGE,
        'post_status' => array( 'publish' ),
        'paged' => $current_page
    );

    $the_query = new WP_Query($update_args);
    $max_num_pages = $the_query->max_num_pages;

  update_option('max_num_pages', $max_num_pages);

    $data = array();

    if ($the_query->have_posts()) :

        $needle = 'https://play.google.com/store/apps/details?id=';

        while ($the_query->have_posts()):

            $the_query->the_post();
            $post_id = get_the_ID();

            $url = get_field('_playstore_url', $post_id);

            if (stripos($url, $needle) !== false) :

                $packname = str_replace($needle, '', $url);

                $start = stripos($packname, '&');
                if ( $start !== false){
                    $packname = substr($packname, 0, $start);
                }
                array_push($data, [ "$post_id" => $packname]);

            endif;

        endwhile;

    endif; wp_reset_postdata();

    if ($current_page >= $max_num_pages || $current_page < 1) {
        update_option('update_current_page', 1);
    } else {
        update_option('update_current_page', $current_page + 1);
    }

    header('Content-Type: application/json');
    echo json_encode( array('max_num_pages' => $max_num_pages, 'posts_per_page' => POSTS_PER_PAGE, 'current_page' => $current_page, 'size' => sizeof($data), 'package' => $data) );

elseif( isset($_POST['post_id']) && isset($_POST['package']) && isset($_POST['new_version']) ) : // handle POST REQUEST to check new version

    $post_id = $_POST['post_id'];
    $package = $_POST['package'];
    $new_version =  $_POST['new_version'];

    $version = trim(get_field('_softwareVersion', $post_id)); //old version

    if( $version && $new_version && version_compare($version, $new_version, '<') ):

        $record_exist = false;

        global $wpdb;
        $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checking WHERE post_id = '.$post_id.' AND new_version = "'.$new_version.'";');
        if($rows) $record_exist =  true;

        if(!$record_exist):
            $wpdb->insert(
                $wpdb->prefix . 'version_checking',
                array(
                    'post_id' 	  => $post_id,
                    'new_version' => $new_version,
                    'views'		  => get_field('post_views', $post_id),
                    'times' => current_time('timestamp'),
                    'packname' => $package
                )
            );
            echo 'Insert new version: '.$new_version;
        endif;
    else:
        echo 'No new version';
    endif;

else : // not get -> redirect to home

    $home_url = get_home_url();
    header("Location: $home_url");

endif;

exit;