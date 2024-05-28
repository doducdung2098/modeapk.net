<?php

function sample_page_init() {
    /**
     * Create version checking page
     */
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title' 	  => 'Sample',
            'menu_title'	  => 'Sample',
            'menu_slug' 	  => 'sample',
            'capability'	  => 'manage_options',
            'id'			  => 'sample',
            'post_id' 		  => 'sample',
            'parent'		  => 'edit.php'
        ));
    }

    /**
     * Add sample field group
     */
    if ( function_exists('acf_add_local_field_group') ) {
        acf_add_local_field_group(array(
            'location' => array (
                array (
                    array (
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'sample',
                    ),
                ),
            ),
        ));
    }
}
add_action( 'init', 'sample_page_init' );

add_action('posts_page_sample', 'app_after_sample_page', 20);
function app_after_sample_page() {
    echo '<h3>Info</h3>';

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'extra_info',
                'compare' => 'EXISTS',
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a><br>';
//            $extra = get_field('extra_info');
//            if($extra && sizeof($extra ) > 1 ) {
//                echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a> ('.sizeof($extra).')<br>';
//                dump($extra[0]);
//                delete_field('extra_info', get_the_ID());
//                add_row('extra_info', $extra[0], get_the_ID());
//            }
        }
        wp_reset_postdata();
    } else {
        echo 'Không có bài viết nào có trường dữ liệu "extra_info"';
    }


}
