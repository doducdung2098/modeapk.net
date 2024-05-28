<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package mod
 */
?>
<aside id="secondary" class="col-12 col-lg-3 widget-area">
	<?php 
	$parents = get_terms(array('taxonomy' => 'category', 'parent' => 0, 'orderby' => 'id', 'order' => 'ASC', 'hide_empty' => false));
	if ( ! is_wp_error($parents) && ! empty($parents) ) :
		foreach ( $parents as $parent ) :
			$children = get_terms(array('taxonomy' => 'category', 'child_of' => $parent->term_id, 'hide_empty' => false));
			if ( ! is_wp_error($children) && ! empty($children) ) :
			?>
				<section class="bg-white border rounded shadow-sm pt-3 px-2 px-md-3 mb-3">
					<header class="d-flex align-items-end mb-3">
						<h2 class="h5 font-weight-semibold mb-0">
							<a class="text-body" href="<?php echo get_term_link($parent); ?>">
								<?php echo $parent->name; ?>			
							</a>
						</h2>
						<a class="btn btn-primary btn-sm ml-auto" href="<?php echo get_term_link($parent); ?>"><?php _e('View More', 'mod'); ?></a>
					</header>
					<div class="row">
						<?php foreach ( $children as $child ) : $icon = get_field('cat_icon', 'term_' . $child->term_id); ?>
							<div class="col-6 mb-3">
								<a class="small text-truncate d-block" href="<?php echo get_term_link($child); ?>" title="<?php echo $child->name; ?>">
									<?php echo $child->name; ?>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php
			endif; 
		endforeach;
	endif; 
	?>

	<?php dynamic_sidebar('sidebar'); ?>
</aside>