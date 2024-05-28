<?php
/**
 * The template for displaying category tips.
 *
 * @package mod
 */
get_header(); 
?>
<div class="container pt-4">
	<main id="primary" class="content-area">
		<section class="mb-4">
			<h1 class="h2 font-weight-bold mb-4">
				<?php echo get_queried_object()->name; ?>		
			</h1>

			<?php if ( term_description() ) : ?>
				<div class="mb-3 entry-content"><?php echo term_description(); ?></div>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<div class="row">	
					<?php 
					while ( have_posts() ) : the_post(); 
						get_template_part('template-parts/content', 'archive-tip');
					endwhile; 
					?>
				</div>
			<?php 
				app_pagination();
			else :
				get_template_part('template-parts/content', 'none');	
			endif; 
			?>

			<?php the_ads();?>

			<?php 
			$terms = get_tags();
			if ( ! is_wp_error($terms) && ! empty($terms) ) :
				shuffle($terms);
				$random_terms = array_slice($terms, 0, 10);
			?>
				<section class="mb-3">
					<h2 class="h5 font-weight-semibold mb-3">
						<span class="border-bottom-2 border-secondary d-inline-block pb-1">
							<?php _e('Tags', 'mod'); ?>
						</span>
					</h2>
					<div class="d-flex flex-wrap">
						<?php foreach ( $random_terms as $value ) : ?>
							<a class="btn btn-light mr-2 mb-2" href="<?php echo get_term_link($value); ?>"><?php echo $value->name; ?></a>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>
		</section>
	</main>
</div>
<?php
get_footer();