<?php
/**
 * Comment
 */
function site_comment() {
	$comment = $_POST['comment'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$post_id = $_POST['post_id'];
	$parent = $_POST['parent'];
	$parent = $parent ? $parent : 0;
	
	header('Content-Type: application/json');
	if ( $comment == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Please input your comment.', 'mod' ) ) );
		die();
	}
	if ( $name == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Please input your name.', 'mod' ) ) );
		die();
	}
	if ( $email == '' ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Please input your email.', 'mod' ) ) );
		die();
	}
	if ( !is_email( $_POST['email'] ) ) {
		echo json_encode( array( 'status' => false, 'message'=> __( 'Your email is incorrect.', 'mod' ) ) );
		die();
	}
		
	$comment_id = wp_new_comment( array(
					  'comment_post_ID' 	 => $post_id, 
					  'comment_author' 		 => $name, 
					  'comment_author_email' => $email, 
					  'comment_content' 	 => $comment, 
					  'comment_parent' 		 => $parent,
				  ) );
	wp_set_comment_status( $comment_id, 'hold' );
	
	echo json_encode( array( 'status' => true, 'message'=> __( 'Thank you for your comment.', 'mod' ) ) );
	die();
}
add_action( 'wp_ajax_nopriv_site_comment', 'site_comment' );
add_action( 'wp_ajax_site_comment', 'site_comment' );

/**
 * Rating
 */
function site_rating() {
	$post_id = $_POST['post_id'];
	$rating = $_POST['rating'];

	$ratings = array();
	if ( isset($_COOKIE['ratings']) ) {
	    $ratings = json_decode(stripslashes($_COOKIE['ratings']), true);
	    if ( ! in_array($post_id, $ratings) )
	    	array_unshift($ratings ,  $post_id);
	} else {
		array_unshift($ratings ,  $post_id);
	}
	$json_ratings = json_encode($ratings);
	$result = setcookie('ratings', $json_ratings, time() + (86400 * 365), '/');

	$count = get_post_meta($post_id, 'rating_count', true);
	$count = $count + 1;
	update_post_meta($post_id, 'rating_count', $count);

	$score = get_post_meta($post_id, 'rating_avg', true);
	$score = ($score * ($count - 1) + $rating) / $count;
	update_post_meta($post_id, 'rating_avg', $score);

	header('Content-Type: application/json');
	if ( $result ) {
		echo json_encode(array('status' => true));
	} else {
		echo json_encode(array('status' => false, 'message' => 'An error occurred. Please try again later.'));
	}
	die();
}
add_action( 'wp_ajax_nopriv_site_rating', 'site_rating' );
add_action( 'wp_ajax_site_rating', 'site_rating' );

/**
 * Update post views
 */
function site_update_post_views() {
	$post_id = $_POST['post_id'];

	$count = (int) get_field('post_views', $post_id);
    $count++;
    update_field('post_views', $count, $post_id); 

    die();
}
add_action( 'wp_ajax_nopriv_site_update_post_views', 'site_update_post_views' );
add_action( 'wp_ajax_site_update_post_views', 'site_update_post_views' );

/**
 * Get download
 */
function site_get_download() {
	$ref = $_SERVER["HTTP_REFERER"];
	$prefix = '/download/';
	$index = strpos($ref, $prefix) + strlen($prefix);
	$download_slug = substr($ref, $index);
	$download_slugs = explode('/', $download_slug);
	preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
	$post_id = $matches[1];

	$downloads = get_field('downloads', $post_id);
	$affs = get_field('affs', 'options');
	if ( $downloads && $affs ) {
		foreach ( $downloads as $key => $value ) {
			foreach ( $affs as $aff ) {
				if ( strpos($value['download_link'], $aff['aff_host']) !== false ) {
					if ( parse_url($value['download_link'], PHP_URL_QUERY) )
						$downloads[$key]['download_link'] = $value['download_link'] . '&' . $aff['aff_code'];
					else
						$downloads[$key]['download_link'] = $value['download_link'] . '?' . $aff['aff_code'];
				}
			}
		}
	}

	$key = $download_slugs[1] - 1; 
	if ( $downloads[$key] ) :
	?>	
		<div id="download" class="text-center mb-3">
			<a class="btn btn-secondary px-5" href="<?php echo $downloads[$key]['download_link']; ?>" download>
				<svg class="svg-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/></svg>
				<span class="align-middle">
					<?php echo __('Download', 'mod'); ?>
					(<?php echo $downloads[$key]['download_size']; ?>)	
				</span>
			</a>
		</div>
		<p class="text-center mb-3">
			<?php _e('If the download doesn\'t start in a few seconds,', 'mod'); ?>
			<a id="click-here" href="<?php echo $downloads[$key]['download_link']; ?>" download>
				<span><?php _e('click here', 'mod'); ?></span>
			</a>
		</p>
    <?php
	endif;

    exit;
}
add_action( 'wp_ajax_nopriv_site_get_download', 'site_get_download' );
add_action( 'wp_ajax_site_get_download', 'site_get_download' );