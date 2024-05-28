<?php
/**
 * The template for displaying archive posts.
 *
 * @package mod
 */
get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<div class="container">
	<div class="row">
		<main id="primary" class="col-12 col-lg-9 content-area">
			<section class="bg-white border rounded shadow-sm pt-3 px-2 px-md-3 mb-3">
				<?php if ( $image = get_field('pub_icon', 'term_' . get_queried_object()->term_id) ) : ?>
					<div class="text-center mb-3">
						<img class="lazyload" src="<?php echo $image['url']; ?>" alt="<?php echo get_queried_object()->name; ?>">
					</div>
				<?php endif; ?>

				<h1 class="h5 font-weight-semibold mb-3">
					<?php echo get_queried_object()->name; ?>		
				</h1>

				<?php if ( $paged == 1 && term_description() ) : ?>
					<div class="mb-3 entry-content"><?php echo term_description(); ?></div>
				<?php endif; ?>

				<?php if ( have_posts() ) : ?>
					<div class="row">	
						<?php 
						while ( have_posts() ) : the_post(); 
							if ( get_post_type() == 'post' )
								get_template_part('template-parts/content', 'archive-post');
							else
								get_template_part('template-parts/content', 'related-tip');
						endwhile; 
						?>
					</div>
				<?php 
					k_pagination();
				else :
					get_template_part('template-parts/content', 'none');	
				endif; 
				?>

				<?php 
				if ( is_tax('publisher') ) :
					$terms = get_terms(array('taxonomy' => 'publisher'));
					if ( ! is_wp_error($terms) && ! empty($terms) ) :
						shuffle($terms);
						$random_terms = array_slice($terms, 0, 10);
					?>
						<section class="mb-3">
							<h2 class="h5 font-weight-semibold mb-3">
								<span class="border-bottom-2 border-secondary d-inline-block pb-1">
									<?php _e('Another Publishers', 'mod'); ?>
								</span>
							</h2>
							<div class="d-flex flex-wrap">
								<?php foreach ( $random_terms as $value ) : ?>
									<a class="btn btn-light mr-2 mb-2" href="<?php echo get_term_link($value); ?>"><?php echo $value->name; ?></a>
								<?php endforeach; ?>
							</div>
						</section>
					<?php
					endif;

				elseif ( is_tag() ) :
					$terms = get_tags();
					if ( ! is_wp_error($terms) && ! empty($terms) ) :
						shuffle($terms);
						$random_terms = array_slice($terms, 0, 10);
					?>
						<section class="mb-3">
							<h2 class="h5 font-weight-semibold mb-3">
								<span class="border-bottom-2 border-secondary d-inline-block pb-1">
									<?php _e('Another Tags', 'mod'); ?>
								</span>
							</h2>
							<div class="d-flex flex-wrap">
								<?php foreach ( $random_terms as $value ) : ?>
									<a class="btn btn-light mr-2 mb-2" href="<?php echo get_term_link($value); ?>"><?php echo $value->name; ?></a>
								<?php endforeach; ?>
							</div>
						</section>
					<?php
					endif;
				endif;
				?>

				<?php if ( $banner = get_field('ads_banner', 'options') ) : ?>
					<div class="mb-3">
						<?php echo $banner; ?>
					</div>
				<?php endif; ?>
			</section>
		</main>

		<?php get_sidebar(); ?>
	</div>
</div>
<?php
get_footer();