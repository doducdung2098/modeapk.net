<?php
/**
 * The template for displaying all single tips.
 *
 * @package mod
 */
get_header(); 

while ( have_posts() ) : the_post();
	global $post;
	$content = get_the_content();
	$content = apply_filters( 'the_content', $content );
?>
<div class="container pt-4">
	<div class="row">
		<main id="primary" class="col-12 col-lg-9 content-area">
			<article class="bg-white border rounded shadow-sm p-3 mb-4">
				<h1 class="h2 font-weight-bold"><?php the_title(); ?></h1>
				<div class="small text-muted mb-4">
					<?php the_modified_date(); ?>
					(<?php echo esc_html(human_time_diff(get_the_modified_time('U'), current_time('timestamp'))) . ' ' . __('ago', 'mod'); ?>)
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="text-center mb-3">
						<?php the_post_thumbnail(); ?>
					</div>
				<?php endif; ?>

				<?php if ( has_excerpt() ) : ?>
					<div class="mb-3 entry-content"><?php the_excerpt(); ?></div>
				<?php endif; ?>

				<div class="mb-3 entry-content">
					<?php
					$content = preg_replace('@\[su_spoiler.*?\[\/su_spoiler\]@si', '', $content);
					$content = preg_replace('@\[tabs\].*?\[\/tabs\]@si', '', $content);
				    echo $content;
					?>
				</div>

				<?php
				$tags = get_the_tags();
			    if ( ! empty($tags) ) :
			    ?>
				    <div class="d-flex flex-wrap mb-2">
				    	<?php foreach ($tags as $tag) : ?>
				            <a class="btn btn-light mr-2 mb-2" href="<?php echo get_term_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
				       	<?php endforeach; ?>
				    </div>
			    <?php endif; ?> 

			    <ul class="nav mb-2 list-shares">
					<li class="mr-2 mb-2">
						<a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" rel="nofollow" target="_blank">
							<svg fill="#fff" height="14px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
							<span class="small d-none d-sm-inline ml-1"><?php _e('Share On Facebook', 'mod'); ?></span>
						</a>
					</li>
					<li class="mr-2 mb-2">
						<a class="twitter" href="https://twitter.com/home?status=<?php the_permalink(); ?>" rel="nofollow" target="_blank">
							<svg fill="#fff" height="14px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>
							<span class="small d-none d-sm-inline ml-1">Twitter</span>
						</a>
					</li>
					<li class="mr-2 mb-2">
						<a class="pinterest" href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>" rel="nofollow" target="_blank">
							<svg fill="#fff" height="14px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>
							<span class="small d-none d-sm-inline ml-1">Pinterest</span>
						</a>
					</li>
					<li class="mr-2 mb-2">
						<a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=&source=" rel="nofollow" target="_blank">
							<svg fill="#fff" height="14px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
							<span class="small d-none d-sm-inline ml-1">Linkedin</span>
						</a>
					</li>
					<li class="mr-2 mb-2">
						<a class="email" href="mailto:?subject=<?php the_title(); ?>&body=<?php the_permalink(); ?>" rel="nofollow" target="_blank">
							<svg fill="#fff" height="14px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49-16.841 13.247-50.201 45.072-73.413 44.701-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646 21.857 17.205 60.134 55.186 103.062 54.955 42.717.231 80.509-37.199 103.053-54.947 49.528-38.783 82.032-64.401 104.947-82.653V400H48z"/></svg>
							<span class="small d-none d-sm-inline ml-1"><?php _e('Email', 'mod'); ?></span>
						</a>
					</li>
				</ul>
			</article>

			<?php
			$term_ids = wp_get_post_terms($post->ID, 'relation', array('fields' => 'ids'));
			$args = array(
				'posts_per_page'   => 6,
				'post__not_in'     => array($post->ID),
				'no_found_rows'    => true,
				'tax_query'		   => array(
					array(
						'taxonomy' => 'relation',
						'field'	   => 'term_id',
						'terms'	   => $term_ids
					)
				)
			);
			$query = new WP_Query($args);
			if ( $query->have_posts() ) : 
			?>
				<section class="bg-white border rounded shadow-sm pt-3 px-2 px-md-3 mb-3">
					<h2 class="h5 font-weight-semibold mb-3">
						<span class="border-bottom-2 border-secondary d-inline-block pb-1">
							<?php _e('Related App', 'mod'); ?>
						</span>
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
			$term_ids = wp_get_post_terms($post->ID, 'tip_cat', array('fields' => 'ids'));
			$args = array(
				'post_type'        => 'tip',
				'posts_per_page'   => 6,
				'post__not_in'     => array($post->ID),
				'no_found_rows'    => true,
				'tax_query'		   => array(
					array(
						'taxonomy' => 'tip_cat',
						'field'	   => 'term_id',
						'terms'	   => $term_ids
					)
				)
			);
			$query = new WP_Query($args);
			if ( $query->have_posts() ) : 
			?>
				<section class="bg-white border rounded shadow-sm pt-3 px-2 px-md-3 mb-3">
					<h2 class="h5 font-weight-semibold mb-3">
						<span class="border-bottom-2 border-secondary d-inline-block pb-1">
							<?php _e('Recommended for You', 'mod'); ?>
						</span>
					</h2>
					<div class="row">
						<?php 
						while($query->have_posts()) : $query->the_post();
							get_template_part('template-parts/content', 'related-tip');
						endwhile; wp_reset_postdata(); 
						?>
					</div>
				</section>
			<?php endif; ?>

			<?php comments_template(); ?>
		</main>
		<aside id="secondary" class="col-12 col-lg-3 widget-area">
			<?php
			$args = array(
				'post_type' 	   => 'post',
				'posts_per_page'   => 5,
				'orderby'     	   => 'modified',
				'order'       	   => 'DESC',
				'no_found_rows'    => true,
			);
			$query = new WP_Query($args);
			if ( $query->have_posts() ) : 
			?>
				<section class="mb-4">
					<h4 class="h4 font-weight-bold mb-3"><?php _e('Latest Updates', 'mod'); ?></h4>
					
					<?php 
					while ( $query->have_posts() ) : $query->the_post();
						get_template_part('template-parts/content', 'aside-post');
					endwhile; wp_reset_postdata(); 
					?>
				</section>
			<?php endif; ?>

			<?php
			$args = array(
				'post_type' 	   => 'post',
				'posts_per_page'   => 5,
				'no_found_rows'    => true,
				'meta_key'   	   => 'post_views',
				'orderby' 	 	   => 'meta_value_num',
				'order' 	 	   => 'DESC',
			);
			$query = new WP_Query($args);
			if ( $query->have_posts() ) : 
			?>
				<section class="mb-4">
					<h4 class="h4 font-weight-bold mb-3"><?php _e('Popular', 'mod'); ?></h4>

					<?php 
					while ( $query->have_posts() ) : $query->the_post();
						get_template_part('template-parts/content', 'aside-post');
					endwhile; wp_reset_postdata(); 
					?>
				</section>
			<?php endif; ?>
		</aside>
	</div>
</div>
<?php
endwhile;

get_footer();