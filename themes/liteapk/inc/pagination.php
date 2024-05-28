<?php
/*
 * Pagination
 */
if( !function_exists('app_pagination') ){
function app_pagination($pages = '', $range = 1) {
    global $paged;
    $showitems = ( $range * 2 )+1;
    if( empty( $paged) ) $paged = 1;
    if($pages == ''){
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages){
            $pages = 1;
        }
    }
    if(1 != $pages)  {
        echo "<nav class='nav-pagination'><ul class='pagination'>";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li class='page-item'><a class='page-link' href='".get_pagenum_link(1)."'><svg class='svg-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M20.2 247.5L167 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L85.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L184 412.5c-4.7 4.7-12.3 4.7-17 0l-146.8-148c-4.7-4.7-4.7-12.3 0-17zm160 17l146.8 148c4.7 4.7 12.3 4.7 17 0l19.8-19.8c4.7-4.7 4.7-12.3 0-17L245.3 256l118.5-119.7c4.7-4.7 4.7-12.3 0-17L344 99.5c-4.7-4.7-12.3-4.7-17 0l-146.8 148c-4.7 4.7-4.7 12.3 0 17z'/></svg></a></li>";
        if($paged > 1 && $showitems < $pages) echo "<li class='page-item'><a class='page-link' aria-label=\"Previous\" href='".get_pagenum_link($paged - 1)."'><svg class='svg-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 512'><path d='M4.2 247.5L151 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L69.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L168 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 264.5c-4.7-4.7-4.7-12.3 0-17z'/></svg></a></li>";
        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                echo ($paged == $i)? "<li class='page-item active'><a class='page-link' href='#'>" . $i . "</a></li>" : "<li class='page-item'><a class='page-link' href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a></li>";
            }
        }
        if ($paged < $pages && $showitems < $pages) echo "<li class='page-item'><a class='page-link' aria-label=\"Next\" href='".get_pagenum_link($paged + 1)."'><svg class='svg-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 512'><path d='M187.8 264.5L41 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 392.7c-4.7-4.7-4.7-12.3 0-17L122.7 256 4.2 136.3c-4.7-4.7-4.7-12.3 0-17L24 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17z'/></svg></a></li>";
        
		if ( $paged < $pages - 1 ) {
			if ( $paged + $range - 1 < $pages ) {
				if ( $showitems < $pages ) {
					echo "<li class='page-item'><a class='page-link' href='" . get_pagenum_link($pages) . "'><svg class='svg-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M363.8 264.5L217 412.5c-4.7 4.7-12.3 4.7-17 0l-19.8-19.8c-4.7-4.7-4.7-12.3 0-17L298.7 256 180.2 136.3c-4.7-4.7-4.7-12.3 0-17L200 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17zm-160-17L57 99.5c-4.7-4.7-12.3-4.7-17 0l-19.8 19.8c-4.7 4.7-4.7 12.3 0 17L138.7 256 20.2 375.7c-4.7 4.7-4.7 12.3 0 17L40 412.5c4.7 4.7 12.3 4.7 17 0l146.8-148c4.7-4.7 4.7-12.3 0-17z'/></svg></a></li>";
				}
			}
		}
       
	   echo "</ul></nav>";
    }
}
}