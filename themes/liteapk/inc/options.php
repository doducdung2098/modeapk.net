<?php
/**
 * k Theme Options.
 *
 * @package mod
 */

/**
 * Create options page
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Site Options',
		'menu_title'	=> 'Site Options',
		'menu_slug' 	=> 'options',
		'capability'	=> 'edit_posts',
		'icon_url' 		=> 'dashicons-hammer',
		'id'			=> 'options',
		'post_id' 		=> 'options',
	));
}

/**
 * Add options field group
 */
if( function_exists('acf_add_local_field_group') ) {
	
	acf_add_local_field_group(	array(
		'key'		=> 'options_settings',
		'title' 	=> 'Settings',
		'fields' 	=> array (
			array (
				'key'   		=> 'tab_general',
				'label' 		=> 'General',
				'name'  		=> 'tab_general',
				'type'  		=> 'tab',
				'placement' 	=> 'left',
			),
			array (
				'key'   		=> 'favicon',
				'label' 		=> 'Favicon',
				'name'  		=> 'favicon',
				'type'  		=> 'image',
				'preview_size'  => 'thumbnail',
			),
			array (
				'key'   		=> 'logo_text',
				'label' 		=> 'Logo Text',
				'name'  		=> 'logo_text',
				'type'  		=> 'text',
			),
			array (
				'key'   		=> 'telegram',
				'label' 		=> 'Telegram',
				'name'  		=> 'telegram',
				'type'  		=> 'link',
			),	
			array (
				'key' 		    => 'social_networks',
				'label'		    => 'Social networks',
				'name' 		    => 'social_networks',
				'type' 		    => 'repeater',
				'layout'	    => 'table',
				'button_label'  => 'Add',
				'sub_fields'    => array (
					array (
						'key' 	  	   => 'social_network_icon',
						'label'   	   => 'Icon',
						'name' 	  	   => 'social_network_icon',
						'type' 	  	   => 'select',
						'ui' 		   => 1,
						'allow_null'   => 1,
						'choices'	   => app_social_icons(),
						'wrapper'      => array(
							'width'    => '25',
						),
					),
                    array (
						'key' 		   => 'social_network_url',
						'label' 	   => 'Url',
						'name' 		   => 'social_network_url',
						'type' 		   => 'text',
						'wrapper'      => array(
							'width'    => '75',
						),
					),
				),
			),
			array (
				'key'   		=> 'copyright',
				'label' 		=> 'Copyright',
				'name'  		=> 'copyright',
				'type'  		=> 'wysiwyg',
			),	
			array (
				'key'   		=> 'offset',
				'label' 		=> 'Version Checker Offset',
				'name'  		=> 'offset',
				'type'  		=> 'number',
				'default_value' => 10
			),	
			
			array (
				'key'   		=> 'tab_page_download',
				'label' 		=> 'Page Download',
				'name'  		=> 'tab_page_download',
				'type'  		=> 'tab',
				'placement' 	=> 'left',
			),
			array (
				'key'   		=> 'page_download_hidden_message',
				'label' 		=> 'Hidden message',
				'name'  		=> 'page_download_hidden_message',
				'type'  		=> 'text',
			),
			array (
				'key'   		=> 'page_download_intro',
				'label' 		=> 'Introduction',
				'name'  		=> 'page_download_intro',
				'type'  		=> 'wysiwyg',
				'instructions'  => '[title]',
			),
			array (
				'key'   		=> 'page_download_note',
				'label' 		=> 'Note',
				'name'  		=> 'page_download_note',
				'type'  		=> 'wysiwyg',
				'instructions'  => '[title]',
			),	
			array (
				'key'   		=> 'page_download_subtitle',
				'label' 		=> 'Subtitle',
				'name'  		=> 'page_download_subtitle',
				'type'  		=> 'text',
				'instructions'  => '[name], [note], [version]',
			),	
			array (
				'key'   		=> 'page_download_tutorial',
				'label' 		=> 'Tutorial',
				'name'  		=> 'page_download_tutorial',
				'type'  		=> 'text',
				'instructions'  => '[tutorial]',
			),
			array (
				'key' 		    => 'page_download_faqs',
				'label'		    =>  'FAQs',
				'name' 		    => 'page_download_faqs',
				'type' 		    => 'repeater',
				'layout'	    => 'block',
				'button_label'  =>  'Add',
				'sub_fields'    => array (
					array (
						'key' 	=> 'page_download_faq_question',
						'label' =>  'Question',
						'name' 	=> 'page_download_faq_question',
						'type' 	=> 'text',
					),
                    array (
						'key' 	=> 'page_download_faq_answer',
						'label' =>  'Answer',
						'name' 	=> 'page_download_faq_answer',
						'type' 	=> 'wysiwyg',
					),
				),
			),
			array (
				'key'   		=> 'tab_ftp',
				'label' 		=>  'FTP',
				'name'  		=> 'tab_ftp',
				'type'  		=> 'tab',
				'placement' 	=> 'left',
			),
			array (
				'key'   		=> 'ftp_protocol',
				'label' 		=>  'Protocol',
				'name'  		=> 'ftp_protocol',
				'type'  		=> 'select',
				'choices'		=> array(
					'http'      => 'http',
					'https'     => 'https',
				),
			),
			array (
				'key'   		=> 'ftp_hostname',
				'label' 		=>  'Host name',
				'name'  		=> 'ftp_hostname',
				'type'  		=> 'text',
			),
			array (
				'key'   		=> 'ftp_ip',
				'label' 		=>  'IP',
				'name'  		=> 'ftp_ip',
				'type'  		=> 'text',
			),
			array (
				'key'   		=> 'ftp_username',
				'label' 		=>  'User name',
				'name'  		=> 'ftp_username',
				'type'  		=> 'text',
			),
			array (
				'key'   		=> 'ftp_password',
				'label' 		=>  'Password',
				'name'  		=> 'ftp_password',
				'type'  		=> 'text',
			),

			array (
				'key'   		=> 'tab_ads',
				'label' 		=>  'Ads',
				'name'  		=> 'tab_ads',
				'type'  		=> 'tab',
				'placement' 	=> 'left',
			),
			array (
				'key' 		    => 'banner_ads',
				'label'		    =>  'Banner ads',
				'name' 		    => 'banner_ads',
				'type' 		    => 'textarea',
				'rows'			=> 10,
			),

			array (
				'key'   		=> 'tab_js',
				'label' 		=>  'Javascript',
				'name'  		=> 'tab_js',
				'type'  		=> 'tab',
				'placement' 	=> 'left',
			),
			array (
				'key' 		    => 'header_js',
				'label'		    =>  'Header Javascript',
				'name' 		    => 'header_js',
				'type' 		    => 'textarea',
				'rows'			=> 20,
			),
			array (
				'key' 		    => 'footer_js',
				'label'		    =>  'Footer Javascript',
				'name' 		    => 'footer_js',
				'type' 		    => 'textarea',
				'rows'			=> 20,
			),
		),
		'location' => array (
			array (
				array (
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'options',
				),
			),
		),
	));
}