<?php
function k_init_tip() {

	/**
	 * Add tip post type
	 */
	register_post_type( 'tip',
		array ( 
			'labels' => array(
				'name' 				=>  'Tips',
				'singular_name' 	=>  'Tip',
				'menu_name' 		=>  'Tips',
				'name_admin_bar'    =>  'Tips',
				'all_items'			=>  'All Tips',
				'add_new' 			=>  'Add Tip',
				'add_new_item' 		=>  'Add Tip',
				'edit_item' 		=>  'Edit Tip',
			),
			'taxonomies' 		=> array('post_tag'),
			'description' 		=>  'News',
			'menu_position' 	=> 5,
			'menu_icon' 		=> 'dashicons-lightbulb',
			'capability_type' 	=> 'post',
			'public' 			=> true,
			'has_archive' 		=> false,
			'show_in_rest'		=> true,
			'supports' 			=> array('title', 'editor', 'thumbnail'),
		)
	);

	/**
	 * Add tip_cat taxonomy
	 */
	register_taxonomy('tip_cat', array('tip'),
		array(
			'labels'            => array(
				'name'              =>  'Tip Categories',
				'singular_name'     =>  'Tip Categories',
				'search_items'      =>  'Search Tip Categories',
				'all_items'         =>  'All Tip Categories',
				'parent_item'       =>  'Parent Tip Categories',
				'parent_item_colon' =>  'Parent Tip Categories:',
				'edit_item'         =>  'Edit Tip Categories',
				'update_item'       =>  'Update Tip Categories',
				'add_new_item'      =>  'Add Tip Categories',
				'new_item_name'     =>  'Tip Categories Name',
				'menu_name'         =>  'Tip Categories',
			),
			'hierarchical'      	=> true,
			'public'            	=> true,
			'show_in_rest'			=> true,
			'show_admin_column' 	=> true,
		) 
	);



	/**
	 * Add tip field group
	 */
	acf_add_local_field_group( array(
		'key'		=> 'tip_settings',
		'title'     =>  'Settings',
		'fields' 	=> array (
			array (
				'label' 		=>  'Views',
				'key'   		=> 'tip_views',
				'name'  		=> 'tip_views',
				'type' 		    => 'number',
				'default_value' => 0,
				'wrapper'    	=> array(
					'width'  	=> '50',
				),
				'show_column'   	   => 1,
				'show_column_weight'   => 210,
				'show_column_sortable' => 1,
			),
			array (
				'label' 		=>  'Views - Week',
				'key'   		=> 'tip_views_week',
				'name'  		=> 'tip_views_week',
				'type' 		    => 'number',
				'default_value' => 0,
				'wrapper'    	=> array(
					'width'  	=> '50',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'tip',
				),
			),
		),
		'position' => 'acf_after_title',
		'label_placement' => 'top',
	) );
}
add_action( 'init', 'k_init_tip' );

// Add tip columns
function k_tip_columns_head($cols) {		
	$cols = array_merge(
		array_slice( $cols, 0, 1, true ),
		array( 'thumbnail' => 'Image'  ),
		array_slice( $cols, 1, null, false )
	);
	return $cols;
}
function k_tip_columns_content($col_name, $post_ID) {
	if ($col_name == 'thumbnail') {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );
		if ( $image ) {
			echo '<a href="' . get_edit_post_link( $post_ID ) . '"><img width="70px" src="' . $image['0'] . '"/></a>';
		} else {
			echo '—';
		}
		echo '<style type="text/css">';
		echo '.column-thumbnail, .column-tip_views--qef-type-number-- { width:70px; }';
		echo '.column-taxonomy-tip_cat, .column-tags { width:10% !important; }';
		echo '.column-title { width:15%; }';
		echo '</style>';
	}
}
add_filter('manage_tip_posts_columns', 'k_tip_columns_head');
add_action('manage_tip_posts_custom_column', 'k_tip_columns_content', 20, 2);

/**
 * Remove the slug from custom post type and add .html to custom post type
 */
add_action( 'rewrite_rules_array', 'rewrite_rules' );
function rewrite_rules( $rules ) {
    $new_rules = array();
    foreach ( get_post_types() as $t )
        $new_rules[ '/([^/]+)\.html$' ] = 'index.php?post_type=' . $t . '&name=$matches[1]';
    return $new_rules + $rules;
}

add_filter( 'post_type_link', 'custom_post_permalink' );
function custom_post_permalink ( $post_link ) {
    global $post;
    $type = get_post_type( $post->ID );
    return home_url( $post->post_name . '.html' );
}

/**
 * Have WordPress match postname to any of our public post types (post, page, re).
 * All of our public post types can have /post-name/ as the slug, so they need to be unique across all posts.
 * By default, WordPress only accounts for posts and pages where the slug is /post-name/.
 *
 * @param $query The current query.
 */
function k_add_cpt_post_names_to_main_query( $query ) {

	// Bail if this is not the main query.
	if ( ! $query->is_main_query() ) {
		return;
	}

	// Bail if this query doesn't match our very specific rewrite rule.
	if ( ! isset( $query->query['page'] ) || 2 !== count( $query->query ) ) {
		return;
	}

	// Bail if we're not querying based on the post name.
	if ( empty( $query->query['name'] ) ) {
		return;
	}

	// Add CPT to the list of post types WP will include when it queries based on the post name.
	$query->set( 'post_type', array( 'post', 'page', 'tip' ) );
}
add_action( 'pre_get_posts', 'k_add_cpt_post_names_to_main_query' );

/**
 * Rewrite url tip_cat taxonomy
 */
function k_rewrite_url_tip_cat_taxonomy($wp_rewrite) {
    $terms = get_terms( array(
        'taxonomy' 	 => 'tip_cat',
        'hide_empty' => false,
    ) );
    if ( ! is_wp_error($terms) && ! empty($terms) ) {
	    foreach ( $terms as $term ) {
			$rules[$term->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
	        $rules[$term->slug . '/page/?([0-9]{1,})/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug . '&paged=' . $wp_rewrite->preg_index( 1 );
		}

	    // merge with global rules
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;	
	}
}
add_filter('generate_rewrite_rules', 'k_rewrite_url_tip_cat_taxonomy');

function k_tip_cat_link( $link, $term, $taxonomy ) {
    if ( $taxonomy !== 'tip_cat' )
        return $link;

    return str_replace( '/tip_cat/', '/', $link );
}
add_filter( 'term_link', 'k_tip_cat_link', 10, 3 );