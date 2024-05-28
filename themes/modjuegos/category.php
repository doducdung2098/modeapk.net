<?php
/**
 * The template for displaying category posts.
 *
 * @package mod
 */
get_header(); 

$current_link = get_term_link(get_queried_object()->term_id);
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

?>
<div class="container">
	<main id="primary" class="content-area">
		<section class="py-4">
			<?php 
			$terms = get_terms(array('taxonomy' => 'category', 'parent' => get_queried_object()->term_id));
			if ( ! is_wp_error($terms) && ! empty($terms) ) :
			?>
				<div class="row mb-4">	
					<?php foreach ( $terms as $term ) : ?>
						<div class="col-4 col-md-3 col-lg-2 col-xl-2 mb-3">
							<a class="h6 font-weight-semibold text-truncate text-center text-body bg-white border rounded shadow-sm d-block p-2 mb-0" href="<?php echo get_term_link($term); ?>">
								<?php echo $term->name; ?>		
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h1 class="h4 font-weight-bold mb-3">
				<?php 
				echo get_queried_object()->name; 

				if ( ! isset($_GET['orderby']) ) echo ' - ' . __('Latest Updates', 'mod');
				if ( isset($_GET['orderby']) && $_GET['orderby'] == 'new-releases' ) echo ' - ' . __('New Releases', 'mod');
				if ( isset($_GET['orderby']) && $_GET['orderby'] == 'popular' ) echo ' - ' . __('Popular', 'mod');
				if ( isset($_GET['orderby']) && $_GET['orderby'] == 'trending' ) echo ' - ' . __('Trending', 'mod');
				?>	
			</h1>

			<?php if ( $paged == 1 && get_queried_object()->description ) : ?>
				<div class="mb-4 entry-content"><?php echo term_description(); ?></div>
			<?php endif; ?>

			<?php 
			$args = array(
				'paged'	     	 	  => $paged,
				'tax_query' 		  => array(
		            array(
		                'taxonomy' 	  => 'category',
		                'field'       => 'term_id',
		                'terms'       => get_queried_object()->term_id,
		            ),
		        ),
			);
			if ( ! isset($_GET['orderby']) ) {
				$args['orderby'] = 'modified';
				$args['order'] = 'DESC';
			} elseif ( isset($_GET['orderby']) && $_GET['orderby'] == 'popular' ) {
				$args['meta_key'] = 'post_views';
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'DESC';
			} elseif ( isset($_GET['orderby']) && $_GET['orderby'] == 'trending' ) {
				$args['meta_key'] = 'post_views_week';
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'DESC';
			}
			$query = new WP_Query($args);
			if ( $query->have_posts() ) :
			?>
				<div class="row">	
					<?php 
					while ( $query->have_posts() ) : $query->the_post(); 
						get_template_part('template-parts/content', 'archive-post');
					endwhile; wp_reset_postdata(); 
					?>
				</div>
			<?php 
				k_pagination($query->max_num_pages);
			endif; 
			?>

			<?php if ( $banner = get_field('ads_banner', 'options') ) : ?>
				<div class="mb-4">
					<?php echo $banner; ?>
				</div>
			<?php endif; ?>
		</section>
	</main>
</div>
<?php
get_footer();