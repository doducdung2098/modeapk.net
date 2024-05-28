<?php
/**
 * The template for displaying type posts.
 *
 * @package k
 */
get_header(); 
?>
<div class="container">
	<div class="row">
		<main id="primary" class="col-12 col-lg-9 content-area">
			<section class="bg-white border rounded shadow-sm pt-3 px-2 px-md-3 mb-3">
				<h1 class="h5 font-weight-semibold mb-3">
					<span class="border-bottom-2 border-secondary d-inline-block pb-1">
						<?php 
						echo __('Type', 'mod') . ' - ' . get_queried_object()->name;
						?>	
					</span>
				</h1>

				<?php if ( term_description() ) : ?>
					<div class="mb-4 entry-content"><?php echo term_description(); ?></div>
				<?php endif; ?>

				<?php 
				$terms = get_terms(array('taxonomy' => 'type', 'hide_empty' => false));
				if ( ! is_wp_error($terms) && ! empty($terms) ) :
				?>
					<nav class="overflow-auto d-flex mb-4">
						<?php foreach ( $terms as $value ) : ?>
							<a class="btn btn-light text-nowrap mr-2 <?php if ( get_queried_object()->term_id == $value->term_id ) echo 'active'; ?>" href="<?php echo get_term_link($value); ?>">
								<?php echo $value->name; ?>		
							</a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>

				<?php if ( have_posts() ) : ?>
					<div class="row">	
						<?php 
						while ( have_posts() ) : the_post(); 
							get_template_part('template-parts/content', 'archive-post');
						endwhile; 
						?>
					</div>
				<?php 
					k_pagination();
				else :
					get_template_part('template-parts/content', 'none');
				endif; 
				?>

				<?php if ( $banner = get_field('ads_banner', 'options') ) : ?>
					<div class="mb-4">
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