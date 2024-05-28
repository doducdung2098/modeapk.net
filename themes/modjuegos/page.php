<?php
/**
 * The template default page.
 *
 * @package mod
 */
get_header(); 
?>
<div class="container">
	<div class="row">
		<main id="primary" class="col-12 col-lg-9 content-area">
			<article class="bg-white border rounded shadow-sm pt-3 px-2 px-md-3 mb-3">
				<?php while ( have_posts() ) : the_post(); ?>
					<h1 class="h5 font-weight-semibold mb-3">
						<?php the_title(); ?>
					</h1>
					<div class="mb-4 entry-content"><?php the_content(); ?></div>
				<?php endwhile; ?>
			</article>
		</main>

		<?php get_sidebar(); ?>
	</div>
</div>
<?php
get_footer();
