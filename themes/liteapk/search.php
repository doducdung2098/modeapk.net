<?php
/**
 * The template for displaying search results.
 * @package mod
 */

get_header(); 
?>
<div class="container py-4">
	<main id="primary" class="content-area">
		<h2 class="font-weight-bold mb-4">
			<?php echo __('Search Results for', 'mod') . ': ' . get_search_query(); ?>
		</h2>

		<?php 
		if ( have_posts() ) : ?>
			<div class="row">
				<?php
				global $wp_query;
				while ( have_posts() ) : the_post(); 
					get_template_part('template-parts/content', 'archive-post');
				endwhile; 
				?>
			</div>
		<?php
			app_pagination();
			
		else :
			get_template_part('template-parts/content', 'none');
		endif; 
		?>
	</main>
</div>
<?php
get_footer();