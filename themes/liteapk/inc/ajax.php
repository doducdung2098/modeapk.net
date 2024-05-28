<?php
/**
 * Comment
 */
function app_comment() {

    // nonce check
    if ( !isset($_POST['app_comment_nonce']) || !wp_verify_nonce($_POST['app_comment_nonce'], 'app_comment_nonce') ) {
        echo json_encode(array('status' => false, 'message' => 'Invalid request.'));
        die();
    }

    // valid http request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(array('status' => false, 'message' => 'Method not allowed.'));
        die();
    }

    // Thiết lập Headers cho CORS
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Content-Type: application/json');

    $comment = sanitize_text_field($_POST['comment']);
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $post_id = intval($_POST['post_id']);
    $parent = intval($_POST['parent']);
    $parent = $parent ? $parent : 0;

    if ( strlen( $comment ) > 250 ) {
        wp_die('Yorum çok uzun. Lütfen yorumunuzu 250 karakterin altında tutun.');
    }
    if ( strlen( $comment ) < 3 ) {
        wp_die('Yorum çok kısa. Lütfen en az 8 karakter kullanın.');
    }

    if ( preg_match( '/<[^>]*>/', $comment ) ) {
        echo json_encode( array( 'status' => false, 'message'=> __( 'Yorumun içerdiği HTML etiketlerine izin verilmiyor.', 'apkoyun' ) ) );
        die();
    }

    if ( preg_match( '#(http|https)://#i', $comment ) ) {
        echo json_encode( array( 'status' => false, 'message'=> __( 'Yorumun içerdiği bağlantıya izin verilmiyor.', 'apkoyun' ) ) );
        die();
    }

    if ( $comment == '' ) {
        echo json_encode( array( 'status' => false, 'message'=> __( 'Lütfen yorumunuzu girin.', 'apkoyun' ) ) );
        die();
    }
    if ( $name == '' ) {
        echo json_encode( array( 'status' => false, 'message'=> __( 'Lütfen adınızı girin.', 'apkoyun' ) ) );
        die();
    }
    if ( $email == '' ) {
        echo json_encode( array( 'status' => false, 'message'=> __( 'Lütfen e-postanızı girin.', 'apkoyun' ) ) );
        die();
    }
    if ( !is_email( $_POST['email'] ) ) {
        echo json_encode( array( 'status' => false, 'message'=> __( 'E-posta adresiniz yanlış.', 'apkoyun' ) ) );
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

    echo json_encode( array( 'status' => true, 'message'=> __( 'Thanks for your comment', 'apkoyun' ) ) );
    die();
}
add_action( 'wp_ajax_nopriv_app_comment', 'app_comment' );
add_action( 'wp_ajax_app_comment', 'app_comment' );

function handle_report_form() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ( // nonce check
            isset($_POST['report_form_nonce']) &&
            wp_verify_nonce($_POST['report_form_nonce'], 'report_form_action')
        ) {
            header('Content-Type: application/json');

            //form fields
            $url_report = sanitize_url($_POST["url-report"]);
            $user_email = sanitize_email($_POST["user-email"]);
            $report_type = sanitize_text_field($_POST["report-type"]);
            $report_content = substr(sanitize_text_field($_POST["report-content"]), 0, 500);

            if(!$url_report || !$user_email || !$report_type){
                echo json_encode(array( 'status' => false, 'message' => __('Please input the required field.', '1mod') ));
                die();
            }

            // set cookie
            $reports = array();
            if ( isset($_COOKIE['reports']) ) {
                $reports = json_decode(stripslashes($_COOKIE['reports']), true);
                if ( ! in_array($url_report, $reports) )
                    array_unshift($reports ,  $url_report);
            } else {
                array_unshift($reports ,  $url_report);
            }

            $json_ratings = json_encode($reports);
            $result = setcookie('reports', $json_ratings, time() + (10 * 60), '/');

            // Insert data
            global $wpdb;
            $table_name = $wpdb->prefix . 'user_report';
            $res = $wpdb->insert(
                $table_name,
                array(
                    'url_report' => $url_report,
                    'user_email' => $user_email,
                    'report_type' => $report_type,
                    'report_content' => $report_content,
                    'report_date' => current_time('timestamp')
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );

            if($result)
                echo json_encode(array('status' => true, 'message' => __('Raporunuz gönderildi.', 'apkoyun')) );
            else
                echo json_encode(array('status' => false, 'message' => __('Raporu gönderirken hata oluştu.', 'apkoyun')) );
            exit;
        } else {
            // nonce invalid, error or reject request
            wp_die('Invalid request');
        }
    }
}
add_action('wp_ajax_nopriv_handle_report_form', 'handle_report_form');
add_action( 'wp_ajax_handle_report_form', 'handle_report_form' );


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