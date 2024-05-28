<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package mod
 */
?>
		
	</div><!-- .site-content -->
	
	<footer id="colophon" class="text-white bg-primary border-top pt-4 site-footer">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-6">
					<?php if ( has_nav_menu( 'footer-nav-1' ) ) : ?>
						<section class="mb-4">
							<h4 class="h5 font-weight-semibold mb-3">
								<svg class="mr-1 svg-5 svg-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M67.508 468.467c-58.005-58.013-58.016-151.92 0-209.943l225.011-225.04c44.643-44.645 117.279-44.645 161.92 0 44.743 44.749 44.753 117.186 0 161.944l-189.465 189.49c-31.41 31.413-82.518 31.412-113.926.001-31.479-31.482-31.49-82.453 0-113.944L311.51 110.491c4.687-4.687 12.286-4.687 16.972 0l16.967 16.971c4.685 4.686 4.685 12.283 0 16.969L184.983 304.917c-12.724 12.724-12.73 33.328 0 46.058 12.696 12.697 33.356 12.699 46.054-.001l189.465-189.489c25.987-25.989 25.994-68.06.001-94.056-25.931-25.934-68.119-25.932-94.049 0l-225.01 225.039c-39.249 39.252-39.258 102.795-.001 142.057 39.285 39.29 102.885 39.287 142.162-.028A739446.174 739446.174 0 0 1 439.497 238.49c4.686-4.687 12.282-4.684 16.969.004l16.967 16.971c4.685 4.686 4.689 12.279.004 16.965a755654.128 755654.128 0 0 0-195.881 195.996c-58.034 58.092-152.004 58.093-210.048.041z"/></svg>
								<span class="align-middle">
									<?php
									$locations = get_nav_menu_locations();
									$menu_id = $locations['footer-nav-1'] ;
									echo wp_get_nav_menu_object($menu_id)->name;
									?>
								</span>
							</h4>

							<?php 
							wp_nav_menu( 
								array( 
									'theme_location' => 'footer-nav-1', 
									'container'		 => false,
									'menu_class' 	 => 'nav menu',
								) 
							); ?>
						</section>
					<?php endif; ?>
				</div>
				<div class="col-12 col-md-6">
					<?php if ( has_nav_menu( 'footer-nav-2' ) ) : ?>
						<section class="mb-4">
							<h4 class="h5 font-weight-semibold mb-3">
								<svg class="mr-1 svg-5 svg-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm0-338c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"/></svg>
								<span class="align-middle">
									<?php
									$locations = get_nav_menu_locations();
									$menu_id = $locations['footer-nav-2'] ;
									echo wp_get_nav_menu_object($menu_id)->name;
									?>
								</span>
							</h4>

							<?php 
							wp_nav_menu( 
								array( 
									'theme_location' => 'footer-nav-2', 
									'container'		 => false,
									'menu_class' 	 => 'nav menu',
								) 
							); ?>
						</section>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( have_rows('social_networks', 'option') ) : ?>
				<div class="d-flex align-items-center justify-content-center mb-4 socials">
					<span class="border-top d-block flex-grow-1 mr-3"></span>
					
					<?php 
					while ( have_rows('social_networks', 'option') ) : the_row();
						$field = get_sub_field_object('social_network_icon');
						$icon = $field['choices'][ $field['value'] ];
					?>
						<a class="mx-2" href="<?php the_sub_field('social_network_url'); ?>" target="_blank" rel="nofollow">
							<?php echo str_replace('height="16px"', 'class="svg-5 svg-white"', $icon); ?>
						</a>
					<?php endwhile; ?>				

					<span class="border-top d-block flex-grow-1 ml-3"></span>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $copyright = get_field('copyright', 'options') ) : ?>
			<div class="small text-center d-flex pt-3" style="background-color: #369a52;">
				<div class="container">
					<?php echo $copyright; ?>
				</div>
			</div>
		<?php endif; ?>
	</footer><!-- .site-footer -->
</div><!-- #page -->

<?php wp_footer(); ?>

<?php the_field('footer_js', 'options'); ?>
</body>
</html>
