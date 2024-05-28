<?php
function k_init_page_home() {
	/**
	 * Add home field group
	 */
	if( function_exists('acf_add_local_field_group') ) {
		
		// Page home
		acf_add_local_field_group( array(
			'key'		=> 'home_settings',
			'title' 	=>  'Settings',
			'fields' 	=> array (
				array(
					'label' =>  'Content',
					'name'  => 'home_content',
					'key'   => 'home_content',
					'type'  => 'flexible_content',
					'layouts' => array(
						array(
							'label'   	 =>  'Categories',
							'name'    	 => 'home_cats',
							'key'    	 => 'home_cats',
							'display'    => 'block',
							'sub_fields' => array(
								array (
								 	'label'		 	=>  'Title',
								 	'name' 		 	=> 'home_cats_title',
								 	'key'   	 	=> 'home_cats_title',
									'type' 		 	=> 'text',
									'wrapper'		=> array(
										'width'		=> '50',
									),
								),
								array (
								 	'label'		 	=>  'Parent Category',
								 	'name' 		 	=> 'home_cats_parent_cat',
								 	'key'   	 	=> 'home_cats_parent_cat',
									'type' 		 	=> 'taxonomy',
									'taxonomy'		=> 'category',
									'allow_null'    => 1,
									'field_type'	=> 'select',
									'wrapper'		=> array(
										'width'		=> '50',
									),
								),
							),
						),

						array(
							'label'   	 =>  'Latest Updates',
							'name'    	 => 'home_latest_updates',
							'key'    	 => 'home_latest_updates',
							'display'    => 'block',
							'sub_fields' => array(
								array (
								 	'label'		 	=>  'Title',
								 	'name' 		 	=> 'home_latest_updates_title',
								 	'key'   	 	=> 'home_latest_updates_title',
									'type' 		 	=> 'text',
									'wrapper'		=> array(
										'width'		=> '50',
									),
								),
								array (
								 	'label'		 	=>  'Category',
								 	'name' 		 	=> 'home_latest_updates_cat',
								 	'key'   	 	=> 'home_latest_updates_cat',
									'type' 		 	=> 'taxonomy',
									'taxonomy'   	=> 'category',
									'field_type' 	=> 'select',
									'allow_null' 	=> 1,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
								array (
								 	'label'		 	=>  'Number',
								 	'name' 		 	=> 'home_latest_updates_number',
								 	'key'   	 	=> 'home_latest_updates_number',
									'type' 		 	=> 'number',
									'default_value' => 6,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
							),
						),
						array(
							'label'   	 =>  'New Releases',
							'name'    	 => 'home_new_releases',
							'key'    	 => 'home_new_releases',
							'display'    => 'block',
							'sub_fields' => array(
								array (
								 	'label'		 	=>  'Title',
								 	'name' 		 	=> 'home_new_releases_title',
								 	'key'   	 	=> 'home_new_releases_title',
									'type' 		 	=> 'text',
									'wrapper'		=> array(
										'width'		=> '50',
									),
								),
								array (
								 	'label'		 	=>  'Category',
								 	'name' 		 	=> 'home_new_releases_cat',
								 	'key'   	 	=> 'home_new_releases_cat',
									'type' 		 	=> 'taxonomy',
									'taxonomy'   	=> 'category',
									'field_type' 	=> 'select',
									'allow_null' 	=> 1,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
								array (
								 	'label'		 	=>  'Number',
								 	'name' 		 	=> 'home_new_releases_number',
								 	'key'   	 	=> 'home_new_releases_number',
									'type' 		 	=> 'number',
									'default_value' => 6,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
							),
						),
						array(
							'label'   	 =>  'Popular',
							'name'    	 => 'home_popular',
							'key'    	 => 'home_popular',
							'display'    => 'block',
							'sub_fields' => array(
								array (
								 	'label'		 	=>  'Title',
								 	'name' 		 	=> 'home_popular_title',
								 	'key'   	 	=> 'home_popular_title',
									'type' 		 	=> 'text',
									'wrapper'		=> array(
										'width'		=> '50',
									),
								),
								array (
								 	'label'		 	=>  'Category',
								 	'name' 		 	=> 'home_popular_cat',
								 	'key'   	 	=> 'home_popular_cat',
									'type' 		 	=> 'taxonomy',
									'taxonomy'   	=> 'category',
									'field_type' 	=> 'multi_select',
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
								array (
								 	'label'		 	=>  'Number',
								 	'name' 		 	=> 'home_popular_number',
								 	'key'   	 	=> 'home_popular_number',
									'type' 		 	=> 'number',
									'default_value' => 6,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
							),
						),
						array(
							'label'   	 =>  'Tag',
							'name'    	 => 'home_tag',
							'key'    	 => 'home_tag',
							'display'    => 'block',
							'sub_fields' => array(
								array (
								 	'label'		 	=>  'Title',
								 	'name' 		 	=> 'home_tag_title',
								 	'key'   	 	=> 'home_tag_title',
									'type' 		 	=> 'text',
									'wrapper'		=> array(
										'width'		=> '50',
									),
								),
								array (
								 	'label'		 	=>  'Tag',
								 	'name' 		 	=> 'home_tag',
								 	'key'   	 	=> 'home_tag',
									'type' 		 	=> 'taxonomy',
									'taxonomy'   	=> 'post_tag',
									'field_type' 	=> 'select',
									'allow_null' 	=> 1,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
								array (
								 	'label'		 	=>  'Number',
								 	'name' 		 	=> 'home_tag_number',
								 	'key'   	 	=> 'home_tag_number',
									'type' 		 	=> 'number',
									'default_value' => 6,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
							),
						),

						array(
							'label'   	 =>  'Tip',
							'name'    	 => 'home_tip',
							'key'    	 => 'home_tip',
							'display'    => 'block',
							'sub_fields' => array(
								array (
								 	'label'		 	=>  'Title',
								 	'name' 		 	=> 'home_tip_title',
								 	'key'   	 	=> 'home_tip_title',
									'type' 		 	=> 'text',
									'wrapper'		=> array(
										'width'		=> '50',
									),
								),
								array (
								 	'label'		 	=>  'Category',
								 	'name' 		 	=> 'home_tip_cat',
								 	'key'   	 	=> 'home_tip_cat',
									'type' 		 	=> 'taxonomy',
									'taxonomy'   	=> 'tip_cat',
									'field_type' 	=> 'select',
									'allow_null' 	=> 1,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
								array (
								 	'label'		 	=>  'Number',
								 	'name' 		 	=> 'home_tip_number',
								 	'key'   	 	=> 'home_tip_number',
									'type' 		 	=> 'number',
									'default_value' => 6,
									'wrapper'		=> array(
										'width'		=> '25',
									),
								),
							),
						),
					),
					'button_label' =>  'Add layout',
				),
			),
			'location' => array (
				array (
					array (
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'index.php',
					),
				),
			),
			'position' => 'acf_after_title',
			'label_placement' => 'top',
			'hide_on_screen' => array (
				0 => 'excerpt',
				1 => 'the_content',
				2 => 'featured_image',
			),
		));
	}
}
add_action( 'init', 'k_init_page_home' );