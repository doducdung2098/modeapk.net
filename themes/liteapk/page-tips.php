<?php
/**
 * Template Name: Tips
 *
 * @package mod
 */

get_header(); 

while ( have_posts() ) : the_post();
	global $post;
?>
<div class="container">
	<main id="primary" class="content-area">
		<h1 class="h5 font-weight-semibold mb-3">
			<span class="border-bottom-2 border-secondary d-inline-block pb-1">
				<?php the_title(); ?>		
			</span>
		</h1>

		<?php if ( $post->post_content !== '' ) : ?>
			<div class="mb-4 entry-content"><?php the_content(); ?></div>
		<?php endif; ?>

		<?php
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$args = array(
			'post_type' 	 => 'tip',
			'paged'	     	 => $paged,
		); 
		$query = new WP_Query($args);
		if ( $query->have_posts() ) : 
		?>
			<div class="row">	
				<?php 
				while ( $query->have_posts() ) : $query->the_post(); 
					get_template_part('template-parts/content', 'archive-tip');
				endwhile; wp_reset_postdata();
				?>
			</div>
		<?php 
			app_pagination($query->max_num_pages);
		else :
			get_template_part('template-parts/content', 'none');	
		endif; 
		?>

		<?php the_ads(); ?>

		<?php 
		$terms = get_tags();
		if ( ! is_wp_error($terms) && ! empty($terms) ) :
			shuffle($terms);
			$random_terms = array_slice($terms, 0, 10);
		?>
			<section class="mb-4">
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
	</main>
</div>
<?php
endwhile; 

get_footer();