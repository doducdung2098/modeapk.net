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
	$content = get_the_content();
	$content = apply_filters( 'the_content', $content );
	$hide_ads = get_field('hide_ads');
    $banner = get_field('ads_banner', 'options');
	$versions = get_field('versions');

    $mod_info = get_field('mod_info');
    $_softwareVersion = get_field('_softwareVersion');

	$cats = wp_get_post_terms($post->ID, 'category', array('fields' => 'all'));
	if ( ! is_wp_error($cats) && ! empty($cats) ) $cat = $cats[0];
?>
<div class="container py-4">
	<div class="row">
		<main id="primary" class="col-12 col-lg-8 col-xl-9 content-area">
			<article class="bg-white border rounded shadow-sm p-3 mb-4">
				<div class="rounded-lg mb-3 mb-md-4 position-relative">
					<?php if ( $image = get_field('post_banner') ) : ?>
						<img class="rounded-lg d-block object-cover" style="min-height: 200px;" loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php the_title(); ?>">
					<?php 
					else :
						the_post_thumbnail( 'full', array( 'class' => 'rounded-lg d-block mx-auto' ) );
					endif; 
					?>
					<div class="text-center text-white rounded-lg p-4 p-md-5 d-flex align-items-center justify-content-center position-absolute" style="background-color: rgba(0, 0, 0, 0.6); z-index: 10; top: 0; bottom: 0; left: 0; right: 0;">
						<div>
							<h1 class="h3 font-weight-bold">
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
							<time class="d-block">
								<svg class="svg-5 svg-white mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C201.7 512 151.2 495 109.7 466.1C95.2 455.1 91.64 436 101.8 421.5C111.9 407 131.8 403.5 146.3 413.6C177.4 435.3 215.2 448 256 448C362 448 448 362 448 256C448 149.1 362 64 256 64C202.1 64 155 85.46 120.2 120.2L151 151C166.1 166.1 155.4 192 134.1 192H24C10.75 192 0 181.3 0 168V57.94C0 36.56 25.85 25.85 40.97 40.97L74.98 74.98C121.3 28.69 185.3 0 255.1 0L256 0zM256 128C269.3 128 280 138.7 280 152V246.1L344.1 311C354.3 320.4 354.3 335.6 344.1 344.1C335.6 354.3 320.4 354.3 311 344.1L239 272.1C234.5 268.5 232 262.4 232 256V152C232 138.7 242.7 128 256 128V128z"/></svg>
								<em class="align-middle">
									<?php echo get_the_modified_date(); ?>
									(<?php echo esc_html(human_time_diff(get_the_modified_time('U'), current_time('timestamp'))) . ' ' . __('ago', 'mod'); ?>)
								</em>
							</time>
						</div>
					</div>

				</div>

                <?php if ( $short_description = get_field('short_description') ) : ?>
                    <div class="">
                        <div class="small bg-light rounded d-none d-md-block pt-2 px-3 mb-4 entry-content" style="padding-bottom: 1px;"><?php echo $short_description; ?></div>
                    </div>
                <?php endif; ?>

                <?php if ( ! $hide_ads && $banner ) : ?>
                    <div class="mb-3">
                        <?php echo $banner; ?>
                    </div>
                <?php endif; ?>

				<div class="row align-items-center">
					<div class="col-12 col-md-4 text-center d-none">
						<img class="rounded-lg mb-4" style="width: 200px;" loading="lazy" src="<?php the_post_thumbnail_url( 'full' ); ?>" alt="<?php the_title(); ?>">
					</div>

					<div class="col-12">
						<?php if ( $short_description = get_field('short_description') ) : ?>
							<div class="small bg-light rounded d-md-none pt-2 px-3 mb-3 entry-content" style="padding-bottom: 1px;"><?php echo $short_description; ?></div>
						<?php endif; ?>

						<table class="table table-striped table-borderless">
							<?php if ( $value = get_field('app_name') ) : ?>
								<tr>
									<th style="width: 10rem;">
										<svg class="svg-5 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M420.55,301.93a24,24,0,1,1,24-24,24,24,0,0,1-24,24m-265.1,0a24,24,0,1,1,24-24,24,24,0,0,1-24,24m273.7-144.48,47.94-83a10,10,0,1,0-17.27-10h0l-48.54,84.07a301.25,301.25,0,0,0-246.56,0L116.18,64.45a10,10,0,1,0-17.27,10h0l47.94,83C64.53,202.22,8.24,285.55,0,384H576c-8.24-98.45-64.54-181.78-146.85-226.55"/></svg>
										<?php _e('App Name', 'mod'); ?>
									</th>
									<td><?php echo $value; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( has_term('', 'publisher') ) : ?>
								<tr>
									<th style="width: 10rem;">
										<svg class="svg-5 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M501.1 395.7L384 278.6c-23.1-23.1-57.6-27.6-85.4-13.9L192 158.1V96L64 0 0 64l96 128h62.1l106.6 106.6c-13.6 27.8-9.2 62.3 13.9 85.4l117.1 117.1c14.6 14.6 38.2 14.6 52.7 0l52.7-52.7c14.5-14.6 14.5-38.2 0-52.7zM331.7 225c28.3 0 54.9 11 74.9 31l19.4 19.4c15.8-6.9 30.8-16.5 43.8-29.5 37.1-37.1 49.7-89.3 37.9-136.7-2.2-9-13.5-12.1-20.1-5.5l-74.4 74.4-67.9-11.3L334 98.9l74.4-74.4c6.6-6.6 3.4-17.9-5.7-20.2-47.4-11.7-99.6.9-136.6 37.9-28.5 28.5-41.9 66.1-41.2 103.6l82.1 82.1c8.1-1.9 16.5-2.9 24.7-2.9zm-103.9 82l-56.7-56.7L18.7 402.8c-25 25-25 65.5 0 90.5s65.5 25 90.5 0l123.6-123.6c-7.6-19.9-9.9-41.6-5-62.7zM64 472c-13.2 0-24-10.8-24-24 0-13.3 10.7-24 24-24s24 10.7 24 24c0 13.2-10.7 24-24 24z"/></svg>
										<?php _e('Publisher', 'mod'); ?>
									</th>
									<td><?php echo get_the_term_list($post->ID, 'publisher', '', ', '); ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( has_term('', 'category') ) : ?>
								<tr>
									<th style="width: 10rem;">
										<svg class="svg-5 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M48 48a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm448 16H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"/></svg>
										<?php _e('Genre', 'mod'); ?>
									</th>
									<td><?php echo get_the_term_list(get_the_ID(), 'category', '', ', '); ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( $value = get_field('_apkfilesize') ) : ?>
								<tr>
									<th style="width: 10rem;">
										<svg class="svg-5 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M567.938 243.908L462.25 85.374A48.003 48.003 0 0 0 422.311 64H153.689a48 48 0 0 0-39.938 21.374L8.062 243.908A47.994 47.994 0 0 0 0 270.533V400c0 26.51 21.49 48 48 48h480c26.51 0 48-21.49 48-48V270.533a47.994 47.994 0 0 0-8.062-26.625zM162.252 128h251.497l85.333 128H376l-32 64H232l-32-64H76.918l85.334-128z"/></svg>
										<?php _e('Size', 'mod'); ?>
									</th>
									<td><?php echo $value; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( $_softwareVersion ) : ?>
								<tr>
									<th style="width: 10rem;">
										<svg class="svg-5 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M296 160H180.6l42.6-129.8C227.2 15 215.7 0 200 0H56C44 0 33.8 8.9 32.2 20.8l-32 240C-1.7 275.2 9.5 288 24 288h118.7L96.6 482.5c-3.6 15.2 8 29.5 23.3 29.5 8.4 0 16.4-4.4 20.8-12l176-304c9.3-15.9-2.2-36-20.7-36z"/></svg>
										<?php _e('Latest version', 'mod'); ?>
									</th>
									<td><?php echo $_softwareVersion; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ( $mod_info ) : ?>
								<tr>
									<th style="width: 10rem;">
										<svg class="svg-5 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M480.07 96H160a160 160 0 1 0 114.24 272h91.52A160 160 0 1 0 480.07 96zM248 268a12 12 0 0 1-12 12h-52v52a12 12 0 0 1-12 12h-24a12 12 0 0 1-12-12v-52H84a12 12 0 0 1-12-12v-24a12 12 0 0 1 12-12h52v-52a12 12 0 0 1 12-12h24a12 12 0 0 1 12 12v52h52a12 12 0 0 1 12 12zm216 76a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm64-96a40 40 0 1 1 40-40 40 40 0 0 1-40 40z"/></svg>
										<?php _e('MOD', 'mod'); ?>
									</th>
									<td style="color: #f25132"><?php echo $mod_info; ?></td>
								</tr>
							<?php endif; ?>

                            <?php if ( $_require = get_field('_require') ) : ?>
                                <tr>
                                    <th style="width: 10rem;">
                                        <svg class="svg-5 svg-secondary mr-1" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <title>android</title>
                                            <path d="M23.35 12.653l2.496-4.323c0.044-0.074 0.070-0.164 0.070-0.26 0-0.287-0.232-0.519-0.519-0.519-0.191 0-0.358 0.103-0.448 0.257l-0.001 0.002-2.527 4.377c-1.887-0.867-4.094-1.373-6.419-1.373s-4.532 0.506-6.517 1.413l0.098-0.040-2.527-4.378c-0.091-0.156-0.259-0.26-0.45-0.26-0.287 0-0.519 0.232-0.519 0.519 0 0.096 0.026 0.185 0.071 0.262l-0.001-0.002 2.496 4.323c-4.286 2.367-7.236 6.697-7.643 11.744l-0.003 0.052h29.991c-0.41-5.099-3.36-9.429-7.57-11.758l-0.076-0.038zM9.098 20.176c-0 0-0 0-0 0-0.69 0-1.249-0.559-1.249-1.249s0.559-1.249 1.249-1.249c0.69 0 1.249 0.559 1.249 1.249v0c-0.001 0.689-0.559 1.248-1.249 1.249h-0zM22.902 20.176c-0 0-0 0-0 0-0.69 0-1.249-0.559-1.249-1.249s0.559-1.249 1.249-1.249c0.69 0 1.249 0.559 1.249 1.249v0c-0.001 0.689-0.559 1.248-1.249 1.249h-0z"></path>
                                        </svg>
                                        <?php _e('Require', 'mod'); ?>
                                    </th>
                                    <td><?php echo $_require; ?></td>
                                </tr>
                            <?php endif; ?>

							<?php if ( $value = get_field('_playstore_url') ) : ?>
								<tr>
									<th style="width: 10rem;">
										<svg class="svg-5 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg>
										<?php _e('Download on', 'mod'); ?>
									</th>
									<td>
										<a href="<?php echo $value; ?>" target="_blank" rel="nofollow">
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
				    	<div class="d-flex align-items-center mb-3">
				    		<div class="rating" data-post_id="<?php echo $post->ID; ?>" data-rateyo-rating="<?php echo $score; ?>" <?php if ( $rating ) : ?>data-rateyo-read-only="true"<?php endif; ?>></div>
				    		<span class="ml-2">
				    			<?php echo $score; ?>/5 (<?php echo $count . ' ' . _n( 'vote', 'votes', $count, 'mod' ); ; ?>)
				    		</span>
				    	</div>



					</div>
				</div>

			</article>

            <?php if ( ! $hide_ads && $banner ) : ?>
                <div class="mb-3">
                    <?php echo $banner; ?>
                </div>
            <?php endif; ?>

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
				<a class="btn btn-primary btn-block mb-4" href="<?php echo home_url('download/' . $download_slug); ?>" rel="nofollow">
					<svg class="svg-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/></svg>
					<span class="align-middle"><?php _e('Download', 'mod'); ?></span>
				</a>
			<?php endif; ?>

			<div class="bg-white border rounded shadow-sm p-3 mb-4">
				<?php if ( $gallery = get_field('post_gallary') ) : ?>
                    <h3 class="mb-3 h4 text-center"><?php _e('Screenshot','mod') ?></h3>

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
				<?php endif; ?>

				<?php if ( $mod_detail = get_field('post_mod_info') ) : ?>
                    <section class="mb-4 alert alert-danger">
                        <h3 class="h5 font-weight-semibold mb-3"><?php _e('MOD information?','mod'); ?></h3>
                        <div><?php echo $mod_detail; ?></div>
                    </section>
				<?php endif; ?>

                <?php if ( $how_to = get_field('post_how_to') ) : ?>
                    <section class="mb-4 alert alert-warning">
                        <h3 class="h5 font-weight-semibold mb-3"><?php _e('How to install?','mod'); ?></h3>
                        <div><?php echo $how_to; ?></div>
                    </section>
                <?php endif; ?>

                <?php
                if ( $more_info = get_field('more_info') ) :
                    $show = get_field('show_more_info');
                    ?>
                    <div id="accordion-more-info" class="mb-3 accordion accordion-more-info">
                        <?php foreach ( $more_info as $key => $value ) : ?>
                            <div class="rounded border bg-light" style="margin-top: -1px;">
                                <a class="bg-success text-white rounded d-flex align-items-center py-2 px-3 <?php if ( ! $show ) : ?>collapsed<?php endif; ?> toggler" data-toggle="collapse" href="#more-info-<?php echo $key + 1; ?>">
                                    <?php echo $value['more_info_title']; ?>
                                </a>
                                <div id="more-info-<?php echo $key + 1; ?>" class="collapse <?php if ( $show ) echo 'show'; ?>" data-parent="#accordion-more-info">
                                    <div class="pt-3 px-3">
                                        <?php echo $value['more_info_desc']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

				<?php if ( $what_new = get_field('post_what_new') ) : ?>
					<section class="mb-4 alert alert-success">
						<h3 class="h5 font-weight-semibold mb-3"><?php _e('What\'s new', 'mod'); ?></h3>
						<div><?php echo $what_new; ?></div>
					</section>
				<?php endif; ?>
				
				<?php if ( has_excerpt() ) : ?>
					<div class="mb-3 entry-content"><?php the_excerpt(); ?></div>
				<?php endif; ?>

				<div class="mb-3">
					<a class="btn btn-light collapsed" data-toggle="collapse" href="#table-of-contents"><?php _e('Explore this article', 'mod'); ?></a>
					<div id="table-of-contents" class="collapse mt-2">
						<div class="bg-light rounded d-inline-block p-3 table-of-contents" style="margin-top: -1px;">
							<div class="links"></div>

							<?php if ( $versions ) : ?>
								<a class="d-block" href="#download"><?php _e('Download', 'mod'); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="mb-3 entry-content">
                    <h2 class="h3"> <?php _e('About this game', 'mod') ?> </h2>
					<?php
					$content = preg_replace('@\[su_spoiler.*?\[\/su_spoiler\]@si', '', $content);
					$content = preg_replace('@\[tabs\].*?\[\/tabs\]@si', '', $content);
				    echo $content;
					?>
				</div>

				<?php if ( $versions ) : ?>
					<h2 id="download" class="h5 font-weight-semibold mb-3"><?php echo get_post_meta($post->ID, '_yoast_wpseo_title', true);?></h2>

                    <?php if ( ! $hide_ads && $banner ) : ?>
                        <div class="mb-3">
                            <?php echo $banner; ?>
                        </div>
                    <?php endif; ?>

					<?php if ( ! wp_is_mobile() && get_field('hide_download_on_pc') ) : ?>
						<div class="text-center alert alert-warning"><?php the_field('page_download_hidden_message', 'options'); ?></div>
					<?php else : ?>
						<a class="btn btn-primary btn-block mb-3" href="<?php echo home_url('download/' . $download_slug); ?>" rel="nofollow">
							<svg class="svg-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/></svg>
							<span class="align-middle"><?php _e('Download', 'mod'); ?></span>
						</a>
					<?php endif; ?>

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
				<?php endif; ?>

				<?php
				$tags = get_the_tags();
			    if ( ! empty($tags) ) :
			    ?>
				    <div class="d-flex flex-wrap mb-2">
				    	<?php foreach ($tags as $tag) : ?>
				            <a class="btn btn-light btn-sm mr-2 mb-2" href="<?php echo get_term_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
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
					<h2 class="font-weight-bold mb-4">
						<?php _e('More from Publisher', 'mod'); ?>
					</h2>
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
		<aside id="secondary" class="col-12 col-lg-4 col-xl-3 widget-area">
<!--			--><?php
//			if ( isset($cat) ) :
//				$args = array(
//					'post_type' 	   => 'post',
//					'posts_per_page'   => 5,
//					'post__not_in'     => array($post->ID),
//					'orderby'     	   => 'modified',
//					'order'       	   => 'DESC',
//					'no_found_rows'    => true,
//					'tax_query'		   => array(
//						array(
//							'taxonomy' => 'category',
//							'field'	   => 'term_id',
//							'terms'	   => $cat->term_id
//						)
//					),
//				);
//				$query = new WP_Query($args);
//				if ( $query->have_posts() ) :
//			?>
<!--				<section class="mb-4">-->
<!--					<h4 class="h4 font-weight-bold mb-3">--><?php //echo $cat->name . ' - ' . __('Latest', 'mod'); ?><!--</h4>-->
<!--					-->
<!--					--><?php //
//					while ( $query->have_posts() ) : $query->the_post();
//						get_template_part('template-parts/content', 'aside-post');
//					endwhile; wp_reset_postdata();
//					?>
<!--				</section>-->
<!--			--><?php //
//				endif;
//			endif;
//			?>

			<?php
			if ( isset($cat) ) :
				$args = array(
					'post_type' 	   => 'post',
					'posts_per_page'   => 6,
					'post__not_in'     => array($post->ID),
					'no_found_rows'    => true,
					'tax_query'		   => array(
						array(
							'taxonomy' => 'category',
							'field'	   => 'term_id',
							'terms'	   => $cat->term_id
						)
					),
					'meta_key'   	   => 'post_views',
					'orderby' 	 	   => 'meta_value_num',
					'order' 	 	   => 'DESC',
				);
				$query = new WP_Query($args);
				if ( $query->have_posts() ) : 
			?>
				<section class="mb-4">
					<h4 class="h4 font-weight-bold mb-3"><?php echo $cat->name . ' - ' . __('Popular', 'mod'); ?></h4>

					<?php 
					while ( $query->have_posts() ) : $query->the_post();
						get_template_part('template-parts/content', 'aside-post');
					endwhile; wp_reset_postdata(); 
					?>
				</section>
			<?php 
				endif; 
			endif;
			?>
		</aside>
	</div>
</div>
<?php
endwhile;

get_footer();