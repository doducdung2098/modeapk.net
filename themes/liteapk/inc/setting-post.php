<?php
function app_init_post()
{

    /**
     * Add publisher taxonomy
     */
    register_taxonomy('publisher', array('post'),
        array(
            'labels' => array(
                'name' => 'Publisher',
                'singular_name' => 'Publisher',
                'search_items' => 'Search Publisher',
                'all_items' => 'All Publisher',
                'parent_item' => 'Parent Publisher',
                'parent_item_colon' => 'Parent Publisher:',
                'edit_item' => 'Edit Publisher',
                'update_item' => 'Update Publisher',
                'add_new_item' => 'Add Publisher',
                'new_item_name' => 'Publisher Name',
                'menu_name' => 'Publisher',
            ),
            'hierarchical' => false,
            'public' => true,
            'show_in_rest' => true,
        )
    );

    /**
     * Add publisher taxonomy
     */
    register_taxonomy('app_type', array('post'),
        array(
            'labels' => array(
                'name' => 'Type',
                'singular_name' => 'Type',
                'search_items' => 'Search Type',
                'all_items' => 'All Type',
                'parent_item' => 'Parent Type',
                'parent_item_colon' => 'Parent Type:',
                'edit_item' => 'Edit Type',
                'update_item' => 'Update App Type',
                'add_new_item' => 'Add Type',
                'new_item_name' => 'Type Name',
                'menu_name' => 'App Type',
            ),
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
        )
    );

    /**
     * Add post field group
     */
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'post_setting',
            'title' => 'Setting',
            'fields' => array(
                array(
                    'label' => 'Views',
                    'key' => 'post_views',
                    'name' => 'post_views',
                    'type' => 'number',
                    'default_value' => 0,
                    'wrapper' => array(
                        'width' => '20',
                    ),
                    'show_column' => 1,
                    'show_column_weight' => 130,
                    'show_column_sortable' => 1,
                ),
                array(
                    'label' => 'Hide on PC',
                    'key' => 'hide_on_pc',
                    'name' => 'hide_on_pc',
                    'type' => 'true_false',
                    'wrapper' => array(
                        'width' => '20',
                    ),
                ),
                array(
                    'label' => 'Hide download on PC',
                    'key' => 'hide_download_on_pc',
                    'name' => 'hide_download_on_pc',
                    'type' => 'true_false',
                    'wrapper' => array(
                        'width' => '20',
                    ),
                ),
                array(
                    'label' => 'Rating Count',
                    'key' => 'rating_count',
                    'name' => 'rating_count',
                    'type' => 'number',
                    'default_value' => 1,
                    'wrapper' => array(
                        'width' => '20',
                    ),
                ),
                array(
                    'label' => 'Rating Avg',
                    'key' => 'rating_avg',
                    'name' => 'rating_avg',
                    'type' => 'number',
                    'default_value' => 5,
                    'wrapper' => array(
                        'width' => '20',
                    ),
                ),
                array(
                    'label' => 'Playstore URL',
                    'key' => '_playstore_url',
                    'name' => '_playstore_url',
                    'type' => 'url',
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                array(
                    'label' => 'App Name',
                    'key' => 'app_name',
                    'name' => 'app_name',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '50',
                    ),
                ),
                array(
                    'label' => 'Mod Info',
                    'key' => 'mod_info',
                    'name' => 'mod_info',
                    'type' => 'text',
                    'wrapper' => array(
                        'width' => '25',
                    ),
                ),
                array(
                    'label' => 'Version',
                    'key' => '_softwareVersion',
                    'name' => '_softwareVersion',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '25',
                    ),
                    'show_column' => 1,
                    'show_column_weight' => 110,
                ),
                array(
                    'label' => 'Size',
                    'key' => '_apkfilesize',
                    'name' => '_apkfilesize',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '25',
                    ),
                ),
                array(
                    'label' => 'Require',
                    'key' => '_require',
                    'name' => '_require',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '25',
                    ),
                ),
                array(
                    'label' => 'Short description',
                    'key' => 'short_description',
                    'name' => 'short_description',
                    'type' => 'textarea',
                    'rows' => 5,
                ),
                array(
                    'label' => 'Internal note',
                    'key' => 'internal_note',
                    'name' => 'internal_note',
                    'type' => 'text',
                ),
                array(
                    'label' => 'https://dl.liteapk.ru/files/ | оригинал | Мод Меню| мега мод | много денег ',
                    'key' => 'versions',
                    'name' => 'versions',
                    'type' => 'repeater',
                    'layout' => 'row',
                    'button_label' => 'Add version',
                    'allow_quickedit' => 1,
                    'sub_fields' => array(
                        array(
                            'label' => 'Version',
                            'key' => 'version',
                            'name' => 'version',
                            'required' => 1,
                            'type' => 'text',
                            'default_value' => 'v - много денег/оригинал',
                        ),
                        array(
                            'label' => 'Version note',
                            'key' => 'version_note',
                            'name' => 'version_note',
                            'type' => 'textarea',
                            'rows' => 3,
                        ),
                        array(
                            'label' => 'Downloads',
                            'key' => 'version_downloads',
                            'name' => 'version_downloads',
                            'type' => 'repeater',
                            'layout' => 'table',
                            'button_label' => 'Add download',
                            'sub_fields' => array(
                                array(
                                    'label' => 'Мод APK',
                                    'key' => 'version_download_type',
                                    'name' => 'version_download_type',
                                    'type' => 'text',
                                    'default_value' => 'Мод APK',
                                    'required' => 1,
                                    'wrapper' => array(
                                        'width' => 10,
                                    ),

                                ),
                                array(
                                    'label' => 'Size',
                                    'key' => 'version_download_size',
                                    'name' => 'version_download_size',
                                    'type' => 'text',
                                    'required' => 1,
                                    'wrapper' => array(
                                        'width' => 15,
                                    ),
                                ),
                                array(
                                    'label' => 'Link',
                                    'key' => 'version_download_link',
                                    'name' => 'version_download_link',
                                    'type' => 'url',
                                    'required' => 1,
                                    'default_value' => 'https://dl.liteapk.ru/files/',
                                    'wrapper' => array(
                                        'width' => 50,
                                    ),
                                ),
                                array(
                                    'label' => 'Note',
                                    'key' => 'version_download_note',
                                    'name' => 'version_download_note',
                                    'type' => 'text',
                                    'wrapper' => array(
                                        'width' => 25,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'label' => 'Extra Info',
                    'key' => 'extra_info',
                    'name' => 'extra_info',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add',
                    'sub_fields' => array(
                        array(
                            'label' => 'Title',
                            'key' => 'extra_info_title',
                            'name' => 'extra_info_title',
                            'type' => 'select',
                            'ui' => 1,
                            'choices' => app_extra_info_titles(),
                            'required' => 1,
                            'return_format' => 'label',
                            'wrapper' => array(
                                'width' => '25',
                            ),
                        ),
                        array(
                            'label' => 'Description',
                            'key' => 'extra_info_desc',
                            'name' => 'extra_info_desc',
                            'type' => 'wysiwyg',
                            'required' => 1,
                            'wrapper' => array(
                                'width' => '75',
                            ),
                        ),
                    ),
                ),

                array(
                    'label' => 'Gallery',
                    'key' => 'post_gallary',
                    'name' => 'post_gallary',
                    'type' => 'gallery',
                    'wrapper' => array(
                        'width' => 50,
                    ),
                ),
                array(
                    'label' => 'Banner',
                    'key' => 'post_banner',
                    'name' => 'post_banner',
                    'type' => 'image',
                    'wrapper' => array(
                        'width' => 50,
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post',
                    ),
                ),
            ),
            'position' => 'acf_after_title',
            'label_placement' => 'top',
        ));
    }

    /**
     * Add category field group
     */
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'cat_settings',
            'fields' => array(
                array(
                    'label' => 'Icon',
                    'name' => 'cat_icon',
                    'key' => 'cat_icon',
                    'type' => 'font-awesome',
                ),
                array(
                    'label' => 'Icon color',
                    'name' => 'cat_icon_color',
                    'key' => 'cat_icon_color',
                    'type' => 'color_picker',
                ),
                array(
                    'label' => 'Banner',
                    'name' => 'term_banner',
                    'key' => 'term_banner',
                    'type' => 'image',
                    'preview_size' => 'full',
                    'library' => 'all',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'category',
                    ),
                ),
            ),
            'position' => 'normal',
            'label_placement' => 'left',
        ));
    }

    /**
     * Add tip field group
     */
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'tip_cat_settings',
            'fields' => array(
                array(
                    'label' => 'Banner',
                    'name' => 'term_banner',
                    'key' => 'term_banner',
                    'type' => 'image',
                    'preview_size' => 'full',
                    'library' => 'all',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'tip_cat',
                    ),
                ),
            ),
            'position' => 'normal',
            'label_placement' => 'left',
        ));
    }

    /**
     * Add app type field group
     */
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'app_type_settings',
            'fields' => array(
                array(
                    'label' => 'Banner',
                    'name' => 'term_banner',
                    'key' => 'term_banner',
                    'type' => 'image',
                    'preview_size' => 'full',
                    'library' => 'all',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'app_type',
                    ),
                ),
            ),
            'position' => 'normal',
            'label_placement' => 'left',
        ));
    }

    /**
     * Add publisher field group
     */
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'pub_settings',
            'fields' => array(
                array(
                    'label' => 'Icon',
                    'key' => 'pub_icon',
                    'name' => 'pub_icon',
                    'type' => 'image',
                    'preview_size' => 'thumbnail',
                    'show_column' => 1,
                    'show_column_weight' => 10,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'publisher',
                    ),
                ),
            ),
            'position' => 'normal',
            'label_placement' => 'left',
        ));
    }
}

add_action('init', 'app_init_post');

// Add post columns
function app_columns_head($cols)
{
    $cols = array_merge(
        array_slice($cols, 0, 1, true),
        array('thumbnail' => 'Image'),
        array_slice($cols, 1, 2, false),
        array('versions' => 'Versions'),
        array_slice($cols, 3, null, false)
    );
    return $cols;
}

function app_columns_content($col_name, $post_ID)
{
    if ($col_name == 'thumbnail') {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_ID), 'thumbnail');
        if ($image) {
            echo '<a href="' . get_edit_post_link($post_ID) . '"><img width="70px" src="' . $image['0'] . '"/></a>';
        } else {
            echo '—';
        }
        echo '<style type="text/css">';
        echo '.column-thumbnail, .column-categories, .column-post_views--qef-type-number-- { width:70px !important; }';
        echo '.column-versions, .column-wpseo-title, .column-wpseo-metadesc { position:relative; }';
        echo '</style>';
    }

    if ($col_name == 'versions') {
        if ($versions = get_field('versions', $post_ID)) {
            foreach ($versions as $value) {
                echo $value['version'] . '<br>';
            }
            echo '<a class="app-show-versions" href="javascript:void(0);" data-post_id=' . $post_ID . '><i class="dashicons dashicons-visibility"></i></a>';
        }
    }

    if ($col_name == 'wpseo-title') {
        echo '<a class="app-edit-text-field" href="javascript:void(0);" data-post_id=' . $post_ID . ' data-field="_yoast_wpseo_title"><i class="dashicons dashicons-edit"></i></a>';
    }

    if ($col_name == 'wpseo-metadesc') {
        echo '<a class="app-edit-text-field" href="javascript:void(0);" data-post_id=' . $post_ID . ' data-field="_yoast_wpseo_metadesc"><i class="dashicons dashicons-edit"></i></a>';
    }
}

add_filter('manage_post_posts_columns', 'app_columns_head');
add_action('manage_post_posts_custom_column', 'app_columns_content', 10, 2);