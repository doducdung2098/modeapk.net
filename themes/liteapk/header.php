<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package mod
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="referrer" content="no-referrer-when-downgrade">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php if ($image = get_field( 'favicon', 'options' )) : ?>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $image['url']; ?>">
<?php endif; ?>

<?php wp_head(); ?>

<?php
if ( is_home() ) {
	$en_url = home_url();
} elseif ( is_single() || is_page() ) {
	global $post;
	$en_url = get_the_permalink($post->ID);
} elseif ( is_category() || is_tax('tip_cat') ) {
	$en_url = get_term_link(get_queried_object()->term_id);
}
if ( ! empty($en_url) ) :
?>
<link rel="alternate" href="<?php echo $en_url; ?>" hreflang="x-default">
<?php endif; ?>

<?php
if ( is_single() ) :
	global $post;
	$terms = get_the_terms($post->ID, 'category');
    $category = 'UtilitiesApplication';
	if ( !is_wp_error($terms) && !empty($terms) ) {
		$parents = array();
		foreach ( $terms as $term ){
		    if ( $term->parent == 0 ) {
		        $parents[] = $term->slug;
		    } else {
		    	$parents[] = get_term($term->parent, 'category')->slug;
		    }
		}

		foreach ( $parents as $parent ) {
			if ( $parent == 'game' || $parent == 'games' || $parent == 'juegos')
				$category = 'GameApplication';
			elseif ( $parent == 'applicaciones' || $parent == 'application' || $parent == 'applications' || $parent == 'app' || $parent == 'apps' )
				$category = 'UtilitiesApplication';
		}
	}

	if ( get_field('app_name') )
		$name = get_field('app_name');
	else
		$name = get_the_title();

	$count = (int)get_post_meta($post->ID, 'rating_count', true);
	$count = max(0, $count);
	$score = (float)get_post_meta($post->ID, 'rating_avg', true);
	$score = round($score, 1, PHP_ROUND_HALF_DOWN);
?>

	<script type="application/ld+json">
	{
	    "@context": "http://schema.org/",
	    "@type": "SoftwareApplication",
	    "name": "<?php echo $name; ?>",
	    "applicationCategory": "<?php echo $category; ?>",
	    "fileSize" : "<?php the_field('_apkfilesize') ?>",
	    "operatingSystem": "<?php the_field('_require'); ?>",
	    "softwareVersion": "<?php the_field('_softwareVersion'); ?>",
	    "offers": {
	        "@type": "Offer",
	        "price": "0",
	        "priceCurrency": "USD"
	    },
	    "aggregateRating": {
	        "@type": "AggregateRating",
	        "bestRating": 5,
	        "worstRating": 1,
	        "ratingCount": <?php echo $count; ?>,
	        "ratingValue": <?php echo $score; ?>
	    }
	}
	</script>
<?php endif; ?>

<?php the_field('header_js', 'options'); ?>
</head>
<body <?php body_class(); ?> <?php if ( is_single() && !is_user_logged_in() && ! isset($_GET['download']) ) : ?>data-post-id="<?php the_ID(); ?>"<?php endif; ?>>
<div id="page" class="bg-light site">
	<a class="skip-link sr-only" href="#content"><?php esc_html_e( 'Skip to content', 'mod' ); ?></a>

    <header id="masthead" class="bg-primary fixed-top site-header">
		<div class="container d-flex align-items-center">
			<button class="bg-transparent border-0 d-lg-none px-0 mr-2 site-nav-toggler" style="padding-top: 0.625rem; padding-bottom: 0.625rem;" type="button">
				<svg class="svg-4 svg-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M436 124H12c-6.627 0-12-5.373-12-12V80c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12z"/></svg>
			</button>

            <?php if ( is_front_page() || is_home() ) : ?>
				<h1 class="h2 font-weight-bold mb-0 mr-2 mr-lg-0 site-logo">
					<a class="text-white" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php the_field('logo_text', 'options'); ?>
					</a>
				</h1>
			<?php else : ?>
				<h2 class="h2 font-weight-bold mb-0 mr-2 mr-lg-0 site-logo">
					<a class="text-white" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php the_field('logo_text', 'options'); ?>
					</a>
				</h2>
			<?php endif; ?>

			<nav class="mx-auto site-nav">
				<div class="d-flex d-lg-none justify-content-end">
					<button class="bg-transparent border-0 py-1 px-2 site-nav-closer" type="button">
						<svg class="svg-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/></svg>
					</button>
				</div>

				<?php
				if ( has_nav_menu( 'site-nav' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'site-nav',
							'container'		 => false,
							'menu_class' 	 => 'menu',
						)
					);
				} ?>

			</nav>

			<div class="ml-auto ml-lg-0 mw-100" style="width: 300px;"><?php get_search_form(); ?></div>
		</div>
	</header>

	<div id="content" class="site-content">
		<?php if ( ! is_front_page() && ! is_home() || ( ! empty( $wp_query->query_vars['download'] ) ) ) : ?>
			<div class="py-2 container">
				<div class=" row">
                    <div class="col-12 col-lg-10 mx-auto">
					    <?php site_breadcrumb(); ?>
                    </div>
				</div>
			</div>
		<?php endif; ?>
