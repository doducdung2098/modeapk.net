<?php
function k_init_contact() {
	// Add contact post type
	register_post_type( 'contact',
		array ( 
			'labels' => array(
				'name' 				=> 'Contact',
				'singular_name' 	=> 'Contact',
				'menu_name' 		=> 'Contact',
				'name_admin_bar'    => 'Contact',
				'all_items'			=> 'All Contact',
				'add_new' 			=> 'Add Contact',
				'add_new_item' 		=> 'Add Contact',
				'edit_item' 		=> 'Edit Contact',
			),
			'description' 		=> 'Contact',
			'menu_position' 	=> 5,
			'menu_icon' 		=> 'dashicons-email',
			'capability_type' 	=> 'post',
			'public' 			=> false,
			'show_ui'			=> true,
			'has_archive' 		=> false,
			'supports' 			=> array(''),
		)
	);

	/**
	 * Add contact field group
	 */
	if( function_exists('acf_add_local_field_group') ) {
		
		acf_add_local_field_group(	array(
			'key'		=> 'contact_settings',
			'title' 	=>  'Information',
			'fields' 	=> array (
				array (
					'key'   		=> 'contact_name',
					'label' 		=> 'Name',
					'name'  		=> 'contact_name',
					'type'  		=> 'text',
					'show_column'   => true
				),
				array (
					'key'   		=> 'contact_email',
					'label' 		=> 'Email',
					'name'  		=> 'contact_email',
					'type'  		=> 'email',
					'show_column'   => true
				),
				array (
					'key'   		=> 'contact_phone',
					'label' 		=> 'Phone',
					'name'  		=> 'contact_phone',
					'type'  		=> 'text',
					'show_column'   => true
				),
				array (
					'key'   		=> 'contact_message',
					'label' 		=> 'Message',
					'name'  		=> 'contact_message',
					'type'  		=> 'textarea',
					'show_column'   => true
				),
			),
			'location' => array (
				array (
					array (
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'contact',
					),
				),
			),
			'position' => 'normal',
			'label_placement' => 'left',
		));
	}
}
add_action( 'init', 'k_init_contact' );

// Add contact columns
function k_contact_columns_head($cols) {		
	$cols = array_merge(
		array_slice( $cols, 0, 1, true ),
		array_slice( $cols, 2, null, true )
	);
	return $cols;
}
add_filter('manage_contact_posts_columns', 'k_contact_columns_head');