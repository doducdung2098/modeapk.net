<?php
/**
 * Template name: Home
 *
 * @package mod
 */
get_header(); 

while (have_posts()) : the_post(); 
?>
<div class="container pt-5">
	<div class="row">
        <main id="primary" class="content-area col-12 col-lg-10 mx-auto">
            <?php
            if ( have_rows('home_content') ) :
                while ( have_rows('home_content') ) : the_row();

                    if ( get_row_layout() == 'home_cats' ) :
                        $title = get_sub_field('home_cats_title');
                        $parent_cat = get_sub_field('home_cats_parent_cat');
                        $terms = get_terms(array('taxonomy' => 'category', 'parent' => $parent_cat));
                        if ( ! is_wp_error($terms) && ! empty($terms) ) :
                            ?>
                            <section class="mb-4">
                                <header class="d-flex align-items-center mb-4">
                                    <h2 class="h4 mb-0">
                                        <a class="text-body" href="<?php echo get_term_link($parent_cat); ?>">
                                            <?php echo $title; ?>
                                        </a>
                                    </h2>
                                    <a class="btn btn-sm btn-primary ml-auto" href="<?php echo get_term_link($parent_cat); ?>"><?php _e('View all', 'mod'); ?></a>
                                </header>
                                <div class="row">
                                    <?php foreach ( $terms as $term ) : ?>
                                        <div class="col-4 col-md-3 col-lg-2 col-xl-2 mb-3">
                                            <a class="h6 font-weight-semibold text-truncate text-center text-body bg-white border rounded shadow-sm d-block p-2 mb-0" href="<?php echo get_term_link($term); ?>">
                                                <?php echo $term->name; ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php
                        endif;

                    elseif ( get_row_layout() == 'home_latest_updates' ) :
                        $title = get_sub_field('home_latest_updates_title');
                        $cat = get_sub_field('home_latest_updates_cat');
                        $number = get_sub_field('home_latest_updates_number');
                        if ( $cat ) :
                            $args = array(
                                'posts_per_page' 	  => $number,
                                'orderby'     		  => 'modified',
                                'order'       	      => 'DESC',
                                'no_found_rows'  	  => true,
                                'tax_query' 		  => array(
                                    array(
                                        'taxonomy' 	  => 'category',
                                        'field'       => 'term_id',
                                        'terms'       => $cat,
                                    ),
                                ),
                            );
                            $query = new WP_Query($args);
                            if ( $query->have_posts() ) :
                                ?>
                                <section class="mb-4">
                                    <header class="d-flex align-items-center mb-4">
                                        <h2 class="h4 mb-0">
                                            <a class="text-body" href="<?php echo get_term_link($cat); ?>">
                                                <?php echo $title; ?>
                                            </a>
                                        </h2>
                                        <a class="btn btn-sm btn-primary ml-auto" href="<?php echo get_term_link($cat); ?>"><?php _e('View all', 'mod'); ?></a>
                                    </header>
                                    <div class="row">
                                        <?php
                                        while ( $query->have_posts() ) : $query->the_post();
                                            get_template_part('template-parts/content', 'archive-post');
                                        endwhile; wp_reset_postdata();
                                        ?>
                                    </div>
                                </section>
                            <?php
                            endif;
                        endif;

                    elseif ( get_row_layout() == 'home_new_releases' ) :
                        $title = get_sub_field('home_new_releases_title');
                        $cat = get_sub_field('home_new_releases_cat');
                        $number = get_sub_field('home_new_releases_number');
                        if ( $cat ) :
                            $args = array(
                                'posts_per_page' 	  => $number,
                                'no_found_rows'  	  => true,
                                'tax_query' 		  => array(
                                    array(
                                        'taxonomy' 	  => 'category',
                                        'field'       => 'term_id',
                                        'terms'       => $cat,
                                    ),
                                ),
                            );
                            $query = new WP_Query($args);
                            if ( $query->have_posts() ) :
                                ?>
                                <section class="mb-4">
                                    <header class="d-flex align-items-center mb-4">
                                        <h2 class="h4 mb-0">
                                            <a class="text-body" href="<?php echo get_term_link($cat); ?>">
                                                <?php echo $title; ?>
                                            </a>
                                        </h2>
                                        <a class="btn btn-sm btn-primary ml-auto" href="<?php echo get_term_link($cat); ?>"><?php _e('View all', 'mod'); ?></a>
                                    </header>
                                    <div class="row">
                                        <?php
                                        while ( $query->have_posts() ) : $query->the_post();
                                            get_template_part('template-parts/content', 'archive-post');
                                        endwhile; wp_reset_postdata();
                                        ?>
                                    </div>
                                </section>
                            <?php
                            endif;
                        endif;

                    elseif ( get_row_layout() == 'home_popular' ) :
                        $title = get_sub_field('home_popular_title');
                        $cat = get_sub_field('home_popular_cat');
                        $number = get_sub_field('home_popular_number');
                        if ( $cat ) :
                            $args = array(
                                'posts_per_page' 	  => $number,
                                'meta_key' 			  => 'post_views',
                                'orderby' 		 	  => 'meta_value_num',
                                'order' 		 	  => 'DESC',
                                'no_found_rows'  	  => true,
                                'tax_query' 		  => array(
                                    array(
                                        'taxonomy' 	  => 'category',
                                        'field'       => 'term_id',
                                        'terms'       => $cat,
                                    ),
                                ),
                            );
                            $query = new WP_Query($args);
                            if ( $query->have_posts() ) :
                                ?>
                                <section class="mb-4">
                                    <header class="d-flex align-items-center mb-4">
                                        <h2 class="h4 mb-0">
                                            <a class="text-body" href="<?php echo get_term_link($cat); ?>">
                                                <?php echo $title; ?>
                                            </a>
                                        </h2>
                                        <a class="btn btn-sm btn-primary ml-auto" href="<?php echo get_term_link($cat); ?>"><?php _e('View all', 'mod'); ?></a>
                                    </header>
                                    <div class="row">
                                        <?php
                                        while ( $query->have_posts() ) : $query->the_post();
                                            get_template_part('template-parts/content', 'archive-post');
                                        endwhile; wp_reset_postdata();
                                        ?>
                                    </div>
                                </section>
                            <?php
                            endif;
                        endif;

                    elseif ( get_row_layout() == 'home_type' ) :
                        $title = get_sub_field('home_type_title');
                        $type = get_sub_field('home_type');
                        $number = get_sub_field('home_type_number');
                        $sort = get_sub_field('home_type_sort');
                        if ( $type ) :
                            $args = array(
                                'posts_per_page' 	  => $number,
                                'no_found_rows'  	  => true,
                                'tax_query' 		  => array(
                                    array(
                                        'taxonomy' 	  => 'app_type',
                                        'field'       => 'term_id',
                                        'terms'       => $type,
                                    ),
                                ),
                            );
                            if ( $sort == 'latest' ) {
                                $args['orderby'] = 'modified';
                                $args['order'] = 'DESC';
                            }
                            if ( $sort == 'random' ) {
                                $args['orderby'] = 'rand';
                            }
                            $query = new WP_Query($args);
                            if ( $query->have_posts() ) :
                                ?>
                                <section class="mb-4">
                                    <header class="d-flex align-items-center mb-4">
                                        <h2 class="h4 mb-0">
                                            <a class="text-body" href="<?php echo get_term_link($type); ?>">
                                                <?php echo $title; ?>
                                            </a>
                                        </h2>
                                        <a class="btn btn-sm btn-primary ml-auto" href="<?php echo get_term_link($type); ?>"><?php _e('View all', 'mod'); ?></a>
                                    </header>
                                    <div class="row">
                                        <?php
                                        while ( $query->have_posts() ) : $query->the_post();
                                            get_template_part('template-parts/content', 'archive-post');
                                        endwhile; wp_reset_postdata();
                                        ?>
                                    </div>
                                </section>
                            <?php
                            endif;
                        endif;

                    elseif ( get_row_layout() == 'home_tag' ) :
                        $title = get_sub_field('home_tag_title');
                        $tag = get_sub_field('home_tag');
                        $number = get_sub_field('home_tag_number');
                        if ( $tag ) :
                            $args = array(
                                'posts_per_page' 	  => $number,
                                'no_found_rows'  	  => true,
                                'tax_query' 		  => array(
                                    array(
                                        'taxonomy' 	  => 'post_tag',
                                        'field'       => 'term_id',
                                        'terms'       => $tag,
                                    ),
                                ),
                            );
                            $query = new WP_Query($args);
                            if ( $query->have_posts() ) :
                                ?>
                                <section class="mb-4">
                                    <header class="d-flex align-items-end mb-4">
                                        <h2 class="h4 mb-0">
                                            <a class="text-body" href="<?php echo get_term_link($tag); ?>">
                                                <?php echo $title; ?>
                                            </a>
                                        </h2>
                                        <a class="btn btn-sm btn-primary ml-auto" href="<?php echo get_term_link($tag); ?>"><?php _e('View all', 'mod'); ?></a>
                                    </header>
                                    <div class="row">
                                        <?php
                                        while ( $query->have_posts() ) : $query->the_post();
                                            get_template_part('template-parts/content', 'archive-post');
                                        endwhile; wp_reset_postdata();
                                        ?>
                                    </div>
                                </section>
                            <?php
                            endif;
                        endif;

                    elseif ( get_row_layout() == 'home_tip' ) :
                        $title = get_sub_field('home_tip_title');
                        $cat = get_sub_field('home_tip_cat');
                        $number = get_sub_field('home_tip_number');
                        if ( $cat ) :
                            $args = array(
                                'posts_per_page' 	  => $number,
                                'no_found_rows'  	  => true,
                                'tax_query' 		  => array(
                                    array(
                                        'taxonomy' 	  => 'tip_cat',
                                        'field'       => 'term_id',
                                        'terms'       => $cat,
                                    ),
                                ),
                            );
                            $query = new WP_Query($args);
                            if ( $query->have_posts() ) :
                                ?>
                                <section class="mb-4">
                                    <header class="d-flex align-items-end mb-4">
                                        <h2 class="h4 mb-0">
                                            <a class="text-body" href="<?php echo get_term_link($cat); ?>">
                                                <?php echo $title; ?>
                                            </a>
                                        </h2>
                                        <a class="btn btn-sm btn-primary ml-auto" href="<?php echo get_term_link($cat); ?>"><?php _e('View all', 'mod'); ?></a>
                                    </header>
                                    <div class="row">
                                        <?php
                                        while ( $query->have_posts() ) : $query->the_post();
                                            get_template_part('template-parts/content', 'archive-tip');
                                        endwhile; wp_reset_postdata();
                                        ?>
                                    </div>
                                </section>
                            <?php
                            endif;
                        endif;
                    endif;

                endwhile;
            endif;
            ?>
        </main>
    </div>
</div>
<?php
endwhile; 

get_footer();