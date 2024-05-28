<?php
///**
// * Template name: Package
// *
// */
//
//if( isset($_GET['get']) && isset($_GET['token']) && $_GET['token'] == get_token_version_checking() ) :
//
//	$current_page = get_current_page_package();
//	$posts_per_page = get_const_posts_per_page();
//
//	$args = array(
//		'post_type' => 'post',
//		'posts_per_page' => $posts_per_page,
//		'post_status' => array( 'publish' ),
//		'paged' => $current_page,
//		'meta_key' => '_playstore_url',
//		'meta_query' => array(
//			'relation' => 'EXISTS',
//		),
//	);
//
//	$query = new WP_Query($args);
//
//	$max_num_pages = $query->max_num_pages;
//	$appIds = array();
//
//	if ($query->have_posts()) :
//
//		while ($query->have_posts()): $query->the_post();
//
//			$url = get_field('_playstore_url', get_the_ID());
//
//			if (stripos($url, 'https://play.google.com/store/apps/details?id=') !== false) :
//
//				$url_components = parse_url($url);
//				parse_str($url_components['query'], $params);
//
//				if ($appId = $params['id']) :
//					$appIds[] = $appId;
//				endif;
//
//			endif;
//
//		endwhile;
//
//	endif; wp_reset_postdata();
//
//	update_current_page_package($current_page, $max_num_pages);
//
//	header('Content-Type: application/json');
//	$data = array();
//	$data['maxNumPages'] = $max_num_pages;
//	$data['postsPerPage'] = $posts_per_page;
//	$data['currentPage'] = $current_page;
//	$data['size'] = sizeof($appIds);
//	$data['appIds'] = $appIds;
//
//	echo json_encode($data);
//
//else : // not get -> redirect to home
//
//	$home_url = get_home_url();
//	header("Location: $home_url");
//
//endif;
//
//exit;