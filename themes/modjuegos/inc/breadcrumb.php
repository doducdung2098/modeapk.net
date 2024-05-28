<?php
// Breadcrumbs
function site_breadcrumb() {
       
    // Settings
    $breadcrums_id      = 'breadcrumb';
    $breadcrums_class   = 'breadcrumb';
    $home_title         = __( 'Home', 'mod' );
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    if ( is_singular('tip') ) {
        $custom_taxonomy    = 'tip_cat';
    }
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) { 
    	$id = 'itemid="';
    	$id .= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$id .= '#breadcrumb"';
    ?>
	   
	<ul id="<?php echo $breadcrums_id; ?>" class="<?php echo $breadcrums_class; ?>" itemscope itemtype="http://schema.org/BreadcrumbList" <?php echo $id; ?>>           
        <li class="breadcrumb-item home" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="<?php echo get_home_url(); ?>" title="<?php echo $home_title; ?>" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="<?php echo get_home_url(); ?>">
                <span itemprop="name"><?php echo $home_title; ?></span>        
            </a>
            <meta itemprop="position" content="1" />
        </li>
          
		<?php	
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
        ?>
        <li class="breadcrumb-item active"><?php echo post_type_archive_title('', false); ?></li>
        <?php    
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
            
            $parent = get_queried_object()->parent;
            $terms = array();
            while ($parent) { 
                $term = get_term($parent);
                $parent = $term->parent;
                array_unshift($terms, $term);
            }
            foreach ($terms as $key => $term) { ?>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a href="<?php echo get_term_link($term); ?>" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="<?php echo get_term_link($term); ?>">
                        <span itemprop="name">
                            <?php echo $term->name; ?>       
                        </span>     
                    </a>
                    <meta itemprop="position" content="<?php echo $key + 2; ?>" /> 
                </li>
            <?php
            }
			$custom_tax_name = get_queried_object()->name;
			?>
            <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <span itemprop="name">
                    <?php echo $custom_tax_name; ?>   
                </span>
                <meta itemprop="position" content="<?php echo count($terms) + 2; ?>" />         
            </li>
        <?php
        } else if ( is_single() ) {
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                $last_category = end($category);
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);

                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $key => $parents) {
                    preg_match('/href="(.*)">/', $parents, $matches);
                    $parents = str_replace('<a ', '<a itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="' . $matches[1] . '" ', $parents);
                    $parents = str_replace('">', '"><span itemprop="name">', $parents);
                    $parents = str_replace('</a>', '</span></a>', $parents);

                    $cat_display .= '<li class="breadcrumb-item item-cat" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">'.$parents.'<meta itemprop="position" content="' . ($key + 2) . '"/></li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            if ( ! empty($custom_taxonomy) ) {
	            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
	            if ( empty($last_category) && ! empty($custom_taxonomy) && $taxonomy_exists ) {
	                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
	                $cat_id         = $taxonomy_terms[0]->term_id;
	                $cat_nicename   = $taxonomy_terms[0]->slug;
	                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
	                $cat_name       = $taxonomy_terms[0]->name;      
	            }
	        }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
            ?>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        <?php echo get_field('app_name') ? get_field('app_name') : get_the_title(); ?>
                    </span>
                    <meta itemprop="position" content="<?php echo count($cat_parents) + 2; ?>" />        
                </li>
            <?php

            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
			?>  
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a href="<?php echo $cat_link; ?>" title="<?php echo $cat_name; ?>" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="<?php echo $cat_link; ?>">
                        <span itemprop="name">
                            <?php echo $cat_name; ?>       
                        </span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        <?php the_title(); ?>    
                    </span>    
                    <meta itemprop="position" content="3" />
                </li>
			<?php
            
            } else {
		    ?>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        <?php echo get_field('app_name') ? get_field('app_name') : get_the_title(); ?>        
                    </span>    
                    <meta itemprop="position" content="2" />
                </li>
            <?php
            }

        } else if ( ! empty( $wp_query->query_vars['download'] ) ) {
            $download_slug = $wp_query->query_vars['download'];
            $download_slugs = explode('/', $download_slug);
            preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
            $post_id = $matches[1];

            // Get post category info
            $category = get_the_category($post_id);
             
            if ( ! empty($category) ) {
              
                $last_category = end($category);
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach ( $cat_parents as $key => $parents ) { 
                    preg_match('/href="(.*)">/', $parents, $matches);
                    $parents = str_replace('<a ', '<a itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="' . $matches[1] . '" ', $parents);
                    $parents = str_replace('">', '"><span itemprop="name">', $parents);
                    $parents = str_replace('</a>', '</span></a>', $parents);
                ?>
                    <li class="breadcrumb-item item-cat" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <?php echo $parents; ?>       
                        <meta itemprop="position" content="<?php echo $key + 2; ?>" /> 
                    </li>
                <?php
                }    
            }
            ?>
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="<?php echo get_the_permalink($post_id); ?>" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="<?php echo get_the_permalink($post_id); ?>">
                    <span itemprop="name">
                        <?php echo get_field('app_name', $post_id) ? get_field('app_name', $post_id) : get_the_title($post_id); ?>
                    </span>     
                </a>      
                <meta itemprop="position" content="<?php echo count($cat_parents) + 2; ?>" />
            </li>
            <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <span itemprop="name"><?php _e('Download', 'mod'); ?></span>
                <meta itemprop="position" content="<?php echo count($cat_parents) + 3; ?>" />   
            </li>
            <?php
        } else if ( is_category() ) { 
            // Category page
        	$parent = get_queried_object()->parent;
            $terms = array();
            while ($parent) { 
                $term = get_term($parent);
                $parent = $term->parent;
                array_unshift($terms, $term);
            }
            foreach ($terms as $key => $term) { ?>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a href="<?php echo get_term_link($term); ?>" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="<?php echo get_term_link($term); ?>">
                        <span itemprop="name">
                            <?php echo $term->name; ?>   
                        </span>    
                    </a>
                    <meta itemprop="position" content="<?php echo $key + 2; ?>" />       
                </li>
            <?php
            }

            if ( ! isset($_GET['orderby']) ) {
		    ?>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        <?php echo get_queried_object()->name; ?>   
                    </span>
                    <meta itemprop="position" content="<?php echo count($terms) + 2; ?>" />     
                </li>
            <?php
            } else {
            ?>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a href="<?php echo get_term_link(get_queried_object()->term_id); ?>" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="<?php echo get_term_link(get_queried_object()->term_id); ?>">
                        <span itemprop="name">  
                            <?php echo get_queried_object()->name; ?>        
                        </span>
                    </a>
                    <meta itemprop="position" content="<?php echo count($terms) + 2; ?>" />     
                </li>
            <?php
                if ( $_GET['orderby'] == 'latest-updates' ) $title = __('Latest Updates', 'mod');
                if ( $_GET['orderby'] == 'new-releases' ) $title = __('New Releases', 'mod');
                if ( $_GET['orderby'] == 'popular' ) $title = __('Popular', 'mod');
                if ( $_GET['orderby'] == 'trending' ) $title = __('Trending', 'mod');
                if ( isset($title) ) {
                ?>
                    <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <span itemprop="name">
                            <?php echo $title; ?>
                        </span>
                        <meta itemprop="position" content="<?php echo count($terms) + 3; ?>" />     
                    </li>
                <?php
                } 
            }
		} else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                foreach ( $anc as $key => $ancestor ) {
				?>
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="<?php echo get_permalink($ancestor); ?>" title="<?php echo get_the_title($ancestor); ?>" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="<?php echo get_permalink($ancestor); ?>">
                            <span itemprop="name">
                                <?php echo get_the_title($ancestor); ?>
                            </span>
                        </a>
                        <meta itemprop="position" content="<?php echo $key + 2; ?>" />     
                    </li>
                <?php
				}
                   
                // Display parent pages
                echo $parents;
                   
                // Current page
				?>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        <?php echo get_the_title(); ?>  
                    </span>
                    <meta itemprop="position" content="<?php echo count($anc) + 2; ?>" />      
                </li>
                <?php   
            } else {
                   
                // Just display current page if not parents
			?>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span itemprop="name">
                        <?php echo get_the_title(); ?> 
                    </span>
                    <meta itemprop="position" content="2" />       
                </li>
            <?php 
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
			?>
            <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <span itemprop="name">
                    <?php echo __( 'Tag', 'mod' ) . ': ' . $get_term_name; ?>
                </span>
                <meta itemprop="position" content="2" />  
            </li>
        <?php   
        } elseif ( is_day() ) {
               
            // Day archive
		?>
            <li class="breadcrumb-item"><a href="<?php echo get_year_link( get_the_time('Y') ); ?>" title="<?php echo get_the_time('Y'); ?>"><?php echo get_the_time('Y'); ?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo get_month_link( get_the_time('Y'), get_the_time('m') ); ?>" title="<?php echo get_the_time('M'); ?>"><?php echo get_the_time('M'); ?></a></li>
            <li class="breadcrumb-item active"><?php echo get_the_time('jS') . ' ' . get_the_time('M'); ?></li>
        <?php 
        } else if ( is_month() ) {
               
            // Month Archive
        ?>
            <li class="breadcrumb-item"><a href="<?php echo get_year_link( get_the_time('Y') ); ?>" title="<?php echo get_the_time('Y'); ?>"><?php echo get_the_time('Y'); ?></a></li>
            <li class="breadcrumb-item active"><?php echo get_the_time('M'); ?></li>
        <?php     
        } else if ( is_year() ) {
            // Display year archive
		?>
            <li class="breadcrumb-item active"><?php echo get_the_time('Y'); ?></li>
        <?php     
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
		?>
            <li class="breadcrumb-item active"><?php echo __( 'Author', 'mod' ) . ': ' . $userdata->display_name; ?></li>
        <?php
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
		?>
            <li class="breadcrumb-item active"><?php echo __( 'Page', 'mod' ) . ' ' . get_query_var('paged'); ?></li>
        <?php     
        } else if ( is_search() ) {
           
            // Search results page
		?>
            <li class="breadcrumb-item active"><?php echo __( 'Search results for', 'mod' ) . ': ' . get_search_query(); ?></li>
        <?php 
        } elseif ( is_404() ) {
               
            // 404 page
		?>
            <li class="breadcrumb-item"><?php _e( 'Error 404', 'mod' ); ?></li>
		<?php
        }
		?>
	</ul>
	<?php
    }     
}
?>