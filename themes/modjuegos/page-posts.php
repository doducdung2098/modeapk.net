<?php
/**
 * Template Name: Posts
 *
 * @package mod
 */

get_header(); 

while ( have_posts() ) : the_post();
	global $post;
?>
<div class="container">
	<div class="row">
		<main id="primary" class="col-12 col-lg-9 content-area">
			<?php if ( $post->post_content !== '' ) : ?>
				<h1 class="h5 font-weight-semibold mb-3"><?php the_title(); ?></h1>
				<div class="mb-4 entry-content"><?php the_content(); ?></div>
			<?php else : ?>
				<h1 class="text-center mb-3"><?php the_title(); ?></h1>
			<?php endif; ?>

			<?php
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			$args = array(
				'post_type' 	 => 'post',
				'posts_per_page' => 50,
				'paged'	     	 => $paged,
			); 
			$query = new WP_Query($args);
			if ( $query->have_posts() ) : 
			?>
				<div class="mb-4">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="border-bottom pb-3 mb-3">
							<h3 class="h6 font-weight-semibold mb-1"><a class="text-body" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="row small text-muted">
								<?php if ( has_term('', 'publisher') ) : ?>
									<div class="col col-md-auto text-center">
										<span class="d-block d-md-inline"><?php _e('Publisher', 'mod'); ?>:</span>
										<?php echo get_the_term_list(get_the_ID(), 'publisher', '', ', '); ?>
									</div>
								<?php endif; ?>

								<?php if ( has_term('', 'publisher') ) : ?>
									<div class="col col-md-auto text-center d-none d-md-block px-0">-</div>
								<?php endif; ?>

								<?php if ( has_term('', 'category') ) : ?>
									<div class="col col-md-auto text-center">
										<span class="d-block d-md-inline"><?php _e('Genre', 'mod'); ?>:</span>
										<?php echo get_the_term_list(get_the_ID(), 'category', '', ', '); ?>		
									</div>
								<?php endif; ?>

								<?php if ( has_term('', 'publisher') || has_term('', 'category') ) : ?>
									<div class="col col-md-auto text-center d-none d-md-block px-0">-</div>
								<?php endif; ?>

								<?php if ( $value = get_field('_apkfilesize') ) : ?>
									<div class="col col-md-auto text-center">
										<span class="d-block d-md-inline"><?php _e('Size', 'mod'); ?>:</span>
										<?php echo $value; ?>		
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php 
				k_pagination($query->max_num_pages); 
			else :
				get_template_part('template-parts/content', 'none');
			endif; 
			?>
		</main>

		<?php get_sidebar(); ?>
	</div>
</div>
<?php
endwhile; 

get_footer();