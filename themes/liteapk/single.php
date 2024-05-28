<?php
/**
 * The template for displaying all single posts.
 *
 * @package mod
 */

if ( ! wp_is_mobile() && get_field('hide_on_pc') ) {
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 ); exit();
}

get_header(); 

while ( have_posts() ) : the_post();
	global $post;
	$versions = get_field('versions');

    $mod_info = get_field('mod_info');
    $_softwareVersion = get_field('_softwareVersion');

	$cats = wp_get_post_terms($post->ID, 'category', array('fields' => 'all'));
	if ( ! is_wp_error($cats) && ! empty($cats) ) $cat = $cats[0];
?>
<div class="container py-4">
	<div class="row">
		<main id="primary" class="content-area col-12 col-lg-10 mx-auto">
			<article class="mb-4">
				<div class="heading-container rounded-lg mb-3 mb-md-4 position-relative">
					<?php if ( $image = get_field('post_banner') ) : ?>
						<img class="rounded-lg d-block heading-cover" loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php the_title(); ?>">
					<?php 
					else :
						the_post_thumbnail( 'full', array( 'class' => 'rounded-lg d-block mx-auto heading-cover' ) );
					endif; 
					?>
					<div class="heading-info text-white rounded-lg p-3">

                        <div class="heading-featured">
                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'heading-icon rounded-lg mr-2' ) ); ?>
                        </div>

                        <div class="heading-title">
							<h1 class="h5 font-weight-semibold">
								<?php
								if ( $title = get_post_meta(get_the_ID(), '_yoast_wpseo_title', true) )
									echo $title;
                                else {
                                    echo get_the_title().' v'. $_softwareVersion;
                                    if ($mod_info) echo ' MOD APK ('.$mod_info.')';

                                    echo ' '.__('for Android','mod');
								}
								?>
							</h1>

                            <div class="publisher">
                                <?php
                                $terms = get_the_terms(get_the_ID(), 'publisher');

                                if ($terms && !is_wp_error($terms)) {
	                                $term_links = array();
	                                foreach ($terms as $term) {
		                                $term_links[] = '<a class="text-primary" href="' . get_term_link($term) . '">' . $term->name . '</a>';
	                                }
	                                echo implode(', ', $term_links);
                                }
                                ?>
                            </div>

						</div>
					</div>

				</div>

                <?php if ( ! wp_is_mobile() && get_field('hide_download_on_pc') ) : ?>
                    <div class="text-center alert alert-warning mb-4"><?php the_field('page_download_hidden_message', 'options'); ?></div>
                <?php
                elseif ( $versions ) :
                    if ( get_field('app_name') )
                        $download_slug = sanitize_title(get_field('app_name'));
                    else
                        $download_slug = $post->post_name;
                    $download_slug .= '-' . $post->ID;
                    ?>
                    <a class="btn btn-primary d-block mb-4" href="<?php echo home_url('download/' . $download_slug); ?>" rel="nofollow">
                        <svg class="svg-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/></svg>
                        <span class="align-middle"><?php _e('Download', 'mod'); ?></span>
                    </a>
                <?php endif; ?>

                <?php the_ads(); ?>

				<div class="bg-white border rounded shadow-sm">

                    <table class="table table-striped table-borderless">

                        <tr>
                            <th style="width: 12rem;">
                                <?php _e('Updated', 'mod'); ?>
                            </th>
                            <td>
                                <time class="d-block" datetime="<?php echo esc_attr(get_the_modified_time('Y-m-d H:i:s')); ?>">
                                    <?php echo get_the_modified_date(); ?>

                                    (<?php echo esc_html(human_time_diff(get_the_modified_time('U'), current_time('timestamp'))) . ' ' . __('ago', 'mod'); ?>)
                                </time>
                            </td>
                        </tr>

                        <?php if ( $value = get_field('app_name') ) : ?>
                            <tr>
                                <th style="width: 10rem;">
                                    <?php _e('App Name', 'mod'); ?>
                                </th>
                                <td><?php echo $value; ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( has_term('', 'category') ) : ?>
                            <tr>
                                <th style="width: 12rem;">
                                    <?php _e('Genre', 'mod'); ?>
                                </th>
                                <td><?php echo get_the_term_list(get_the_ID(), 'category', '', ', '); ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( $value = get_field('_apkfilesize') ) : ?>
                            <tr>
                                <th style="width: 10rem;">
                                    <?php _e('Size', 'mod'); ?>
                                </th>
                                <td><?php echo $value; ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( $_softwareVersion ) : ?>
                            <tr>
                                <th style="width: 10rem;">
                                    <?php _e('Latest version', 'mod'); ?>
                                </th>
                                <td><?php echo $_softwareVersion; ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( $mod_info ) : ?>
                            <tr>
                                <th style="width: 10rem;">
                                    <?php _e('MOD', 'mod'); ?>
                                </th>
                                <td style="color: #f25132"><?php echo $mod_info; ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( $_require = get_field('_require') ) : ?>
                            <tr>
                                <th style="width: 10rem;">
                                    <?php _e('Require', 'mod'); ?>
                                </th>
                                <td><?php echo $_require; ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( $playurl = get_field('_playstore_url') ) : ?>
                            <tr>
                                <th style="width: 10rem;">
                                    <?php _e('Download on', 'mod'); ?>
                                </th>
                                <td>
                                    <a href="<?php echo $playurl; ?>" target="_blank" rel="nofollow">
                                        <img style="max-height: 1.5rem;" src="<?php echo get_template_directory_uri(); ?>/images/google-play.png" alt="Google Play">
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>

                    </table>

                    <?php
                    $rating = false;
                    if ( isset($_COOKIE['ratings']) ) {
                        $ratings = json_decode(stripslashes($_COOKIE['ratings']), true);
                        if ( in_array($post->ID, $ratings) )
                            $rating = true;
                    }
                    $count = (int)get_post_meta($post->ID, 'rating_count', true);
                    $count = max(0, $count);
                    $score = (float)get_post_meta($post->ID, 'rating_avg', true);
                    $score = round($score, 1, PHP_ROUND_HALF_DOWN);
                    ?>
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rating" data-post_id="<?php echo $post->ID; ?>" data-rateyo-rating="<?php echo $score; ?>" <?php if ( $rating ) : ?>data-rateyo-read-only="true"<?php endif; ?>></div>
                        <span class="ml-2">
                            <?php echo $score; ?>/5 (<?php echo $count . ' ' . _n( 'vote', 'votes', $count, 'mod' ); ; ?>)
                        </span>
                    </div>
				</div>

			</article>

            <?php the_ads(); ?>

            <?php if ( $short_description = get_field('short_description') ) : ?>
                <div class="small mb-4"><?php echo $short_description; ?></div>
            <?php endif; ?>

			<div class="mb-4">
				<?php if ( $gallery = get_field('post_gallary') ) : ?>

                    <div class="mb-4">
                        <h3 class="h5 mb-3 text-center"><?php _e('Screenshot','mod') ?></h3>

                        <div class="overflow-auto mb-3 entry-screenshots">
                            <div class="row row-small flex-nowrap align-items-end screenshots">
                                <?php foreach ( $gallery as $value ) : ?>
                                    <div class="col-auto col-small">
                                        <a class="screenshot" data-fancybox="gallery" href="<?php echo $value['url']; ?>">
                                            <img style="max-height: 300px;" loading="lazy" src="<?php echo $value['url']; ?>" alt="<?php echo $value['alt']; ?>">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>

				<div class="mb-4 entry-content">

                    <?php $extra_info = get_field('extra_info', get_the_ID()); ?>

                    <div id="body1" class="content-block ui-tabs">
                        <div class="tabs-nav-container">
                            <ul class="tabs-nav ui-tabs-nav p-0 <?php if (!empty($extra_info)) echo 'scroll-x' ?>" role="tablist">

                                <li role="tab" tabindex="0" class="ui-tabs-tab ui-tabs-active py-3"
                                    aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
                                    <a href="#tabs-1" tabindex="-1" class="ui-tabs-anchor text-dark" id="ui-id-1">
                                        <h2 class="m-0 font-size-body font-weight-bold"><?php _e('About this game', 'mod') ?></h2>
                                    </a>
                                </li>

                                <?php if(!empty($extra_info)) : ?>
                                    <?php foreach ($extra_info as $key => $value) : ?>

                                        <li role="tab" tabindex="-1" class="ui-tabs-tab py-3"
                                            aria-controls="tabs-<?php echo $key + 2 ?>" aria-labelledby="ui-id-<?php echo $key + 2 ?>" aria-selected="false" aria-expanded="false">
                                            <a href="#tabs-<?php echo $key + 2 ?>" tabindex="-1" class="ui-tabs-anchor text-dark" id="ui-id-<?php echo $key + 2 ?>">
                                                <h3 class="m-0 font-size-body font-weight-bold"><?php echo $value['extra_info_title'] ?></h3>
                                            </a>
                                        </li>

                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </ul>
                        </div>

                        <div id="tabs-1" class="tabs-container" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false">
                            <div class="body-2 blockreadmore body-content readmore-section post-content">
                                <a class="btn btn-light collapsed" data-toggle="collapse" href="#table-of-contents"><?php _e('Explore this article', 'mod'); ?></a>

                                <div class="mb-3">
                                    <div id="table-of-contents" class="collapse mt-2">
                                        <div class="bg-light rounded d-inline-block p-3 table-of-contents" style="margin-top: -1px;">
                                            <div class="links"></div>
                                        </div>
                                    </div>
                                </div>

                                <?php the_content(); ?>

                                <?php if ( $versions ) : ?>
                                    <h2 id="download" class="h4 font-weight-semibold mb-3"><?php echo get_post_meta($post->ID, '_yoast_wpseo_title', true);?></h2>

                                    <?php the_ads(); ?>

                                    <?php if ( ! wp_is_mobile() && get_field('hide_download_on_pc') ) : ?>
                                        <div class="text-center alert alert-warning"><?php the_field('page_download_hidden_message', 'options'); ?></div>
                                    <?php else : ?>
                                        <a class="btn btn-primary d-block" href="<?php echo home_url('download/' . $download_slug); ?>" rel="nofollow">
                                            <svg class="svg-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/></svg>
                                            <span class="align-middle"><?php _e('Download', 'mod'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if(!empty($extra_info)) : ?>
                            <?php foreach ($extra_info as $key => $value) : ?>

                                <div id="tabs-<?php echo $key + 2 ?>" class="tabs-container" aria-labelledby="ui-id-<?php echo $key + 2 ?>" role="tabpanel" aria-hidden="true">
                                    <div class="body-2 blockreadmore body-content readmore-section">
                                        <?php echo $value['extra_info_desc'] ?>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                    
				</div>

                <?php
                if ( $note = get_field('page_download_note', 'options') ) :
                    $note = str_replace('[title]', get_field('app_name'), $note);
                ?>
                    <div class="small mb-3"><?php echo $note; ?></div>
                <?php endif; ?>

                <?php if ( $telegram = get_field('telegram', 'options') ) : ?>
                    <div class="text-center mb-3">
                        <a class="btn btn-info rounded-pill" href="<?php echo $telegram['url']; ?>" target="<?php echo $telegram['target']; ?>" rel="nofollow">
                            <svg class="svg-5 mr-1" fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"/></svg>
                            <?php echo $telegram['title']; ?>
                        </a>
                    </div>
                <?php endif; ?>


				<?php if ( $tags = get_the_tags(get_the_ID()) ) : ?>
				    <div class="d-flex flex-wrap mb-2">
				    	<?php foreach ($tags as $tag) : ?>
				            <a class="btn btn-primary btn-sm mr-2 mb-2" href="<?php echo get_term_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
				       	<?php endforeach; ?>
				    </div>
			    <?php endif; ?>
			</div>

			
			<?php
			$term_ids = wp_get_post_terms($post->ID, 'publisher', array('fields' => 'ids'));
			$args = array(
				'posts_per_page'   => 8,
				'post__not_in'     => array($post->ID),
				'no_found_rows'    => true,
				'tax_query'		   => array(
					array(
						'taxonomy' => 'publisher',
						'field'	   => 'term_id',
						'terms'	   => $term_ids
					)
				)
			);
			$query = new WP_Query($args);
			if ( $query->have_posts() ) : 
			?>
				<section class="related-posts">
					<h3 class="h4 font-weight-bold mb-4">
						<?php _e('More from Publisher', 'mod'); ?>
					</h3>
					<div class="row">
						<?php 
						while($query->have_posts()) : $query->the_post();
							get_template_part('template-parts/content', 'archive-post');
						endwhile; wp_reset_postdata(); 
						?>
					</div>
				</section>
			<?php endif; ?>
            
			<?php
			$term_ids = wp_get_post_terms($post->ID, 'category', array('fields' => 'ids'));
			$args = array(
				'posts_per_page'   => 6,
				'post__not_in'     => array($post->ID),
				'no_found_rows'    => true,
				'tax_query'		   => array(
					array(
						'taxonomy' => 'category',
						'field'	   => 'term_id',
						'terms'	   => $term_ids
					)
				)
			);
			$query = new WP_Query($args);
			if ( $query->have_posts() ) : 
			?>
				<section class="related-posts">
					<h3 class="h4 font-weight-bold mb-3">
						<?php _e('Recommended for You', 'mod'); ?>
					</h3>
					<div class="row">
						<?php 
						while($query->have_posts()) : $query->the_post();
							get_template_part('template-parts/content', 'archive-post');
						endwhile; wp_reset_postdata(); 
						?>
					</div>
				</section>
			<?php endif; ?>

			<?php comments_template(); ?>
		</main>

	</div>
</div>
<?php
endwhile;

get_footer();