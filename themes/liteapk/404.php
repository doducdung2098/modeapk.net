<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package willgroup
 */

get_header(); 
?>
<main id="primary" class="content-area">
	<div class="container">
		<h1 class="h2 font-weight-semibold text-center mb-5">
			<?php esc_html_e( 'Sorry! Page not found.', 'willgroup' ); ?>
		</h1>
	</div>
</main>
<?php
get_footer();
