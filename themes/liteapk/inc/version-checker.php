<?php
add_action('after_switch_theme', function () {
    global $wpdb;
	$table_name = $wpdb->prefix . 'version_checker';
	$curlarset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		ID bigint(20) NOT NULL AUTO_INCREMENT,
		post_id bigint(20) NOT NULL,
		new_version varchar(20) NOT NULL,
		views bigint(20) NOT NULL,
		PRIMARY KEY  (ID)
	) $curlarset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
});

function k_version_checker_init() {
	/**
	 * Create version checker page
	 */
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	  => 'Version Checker',
			'menu_title'	  => 'Version Checker',
			'menu_slug' 	  => 'version_checker',
			'capability'	  => 'manage_options',
			'id'			  => 'version_checker',
			'post_id' 		  => 'version_checker',
			'parent'		  => 'edit.php'
		));
	}

	/**
	 * Add version_checker field group
	 */
	if ( function_exists('acf_add_local_field_group') ) {
		acf_add_local_field_group(array(
			'location' => array (
				array (
					array (
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'version_checker',
					),
				),
			),
		));	
	}
}
add_action( 'init', 'k_version_checker_init' );

/**
 * Version checker
 */
function k_version_checker() {
	$offset = get_field('offset', 'options');
	$args = array(
		'post_type'		 => 'post',
		'posts_per_page' => 10,
		'post_status'	 => 'publish',
		'fields'         => 'ids',
		'meta_key' 		 => 'post_views',
        'orderby' 		 => 'meta_value_num',
        'order' 		 => 'DESC',
        'offset'		 => $offset
	);
	$posts = get_posts($args);

	if ( $posts ) {
		update_field('offset', $offset + 10, 'options');
	} else {
		update_field('offset', 0, 'options');
	}

	if ( $posts ) {
		global $wpdb;
		require_once(get_template_directory() . '/vendor/autoload.php');
		$gplay = new \Nelexa\GPlay\GPlayApps('en_US', 'gl');
		$packages = array();

		foreach ( $posts as $key => $post_id ) { 			
			$url = get_field('_playstore_url', $post_id);
			if ( $url ) {
				$url_components = parse_url($url);
				if ( ! empty($url_components['query']) ) {
					parse_str($url_components['query'], $params);
					if ( ! empty($params['id']) ) {
						$packages[$post_id] = $params['id'];
					}
				}
			}
		}

		$exists = $gplay->existsApps($packages);
		foreach ( $exists as $post_id => $exist ) {
			if ( ! $exist ) {
				unset($packages[$post_id]);
			}
		}

		$apps = $gplay->getAppsInfo($packages);
		foreach ( $apps as $post_id => $app ) {
    		if ( ! empty(get_field('_softwareVersion', $post_id)) && ! empty($app->getAppVersion()) ) {
    			$version = trim(get_field('_softwareVersion', $post_id));
    			$new_version = trim($app->getAppVersion());

    			$wpdb->delete( $wpdb->prefix . 'version_checker', array( 'post_id' => $post_id ) );

	    		if ( version_compare($new_version, $version) > 0 || ! preg_match('~[0-9]+~', $new_version) ) {
					$wpdb->insert( 
						$wpdb->prefix . 'version_checker', 
						array( 
							'post_id' 	  => $post_id, 
							'new_version' => $new_version
						)
					);
				}
			}
		}
	}

	return;
}

/**
 * Update post
 */
function k_version_checker_update_post() {
	$post_id = $_POST['post_id'];
	$url = get_field('_playstore_url', $post_id);
	$url_components = parse_url($url);
	parse_str($url_components['query'], $params);
	if ( ! empty($params['id']) ) {
		$old_version = trim(get_field('_softwareVersion', $post_id));

		require_once(get_template_directory() . '/vendor/autoload.php');
		$gplay = new \Nelexa\GPlay\GPlayApps('en_US', 'gl');
		$appInfo = $gplay->getAppInfo($params['id']);
    	$new_version = $appInfo->getAppVersion();

		update_field('_softwareVersion', $new_version, $post_id);

		$seo_title = get_field('_yoast_wpseo_title', $post_id);
		$seo_title = preg_replace('/(V?|v?|V-?|v-?|V_?|v_?|V ?|v ?)(' . $old_version . ')/', 'v' . $new_version, $seo_title);
		update_field('_yoast_wpseo_title', $seo_title, $post_id);

		$title = get_the_title($post_id);
		$title = preg_replace('/(V?|v?|V-?|v-?|V_?|v_?|V ?|v ?)(' . $old_version . ')/', 'v' . $new_version, $title);
		$temp_post = array(
			'ID'           => $post_id,
			'post_title'   => $title,
		);
		wp_update_post( $temp_post );

		header('Content-Type: application/json');
		echo json_encode( array( 'status' => true, 'version' => $new_version ) );
	}
	exit;
}
add_action( 'wp_ajax_k_version_checker_update_post', 'k_version_checker_update_post' );

/**
 * Check if a filename is valid
 */
function is_valid_name($file) {
	return preg_match('/^([-\.\w]+)$/', $file) > 0;
}

/**
 * Delete row
 */
function k_version_checker_delete_row() {
	global $wpdb;
	$id = $_POST['id'];
	$result = $wpdb->delete( $wpdb->prefix . 'version_checker', array( 'id' => $id ) );
	if ( $result ) {
		echo 'Deleted';
		exit;
	} else {
		echo 'Error. Please contact coder.';
		exit;
	}
}
add_action( 'wp_ajax_k_version_checker_delete_row', 'k_version_checker_delete_row' );

/**
 * Delete rows
 */
function k_version_checker_delete_rows() {
	global $wpdb;
	$ids = $_POST['ids'];

	$ids = implode(',', array_map('absint', $ids));
	$result = $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'version_checker WHERE ID IN(' . $ids . ')');
	if ( $result ) {
		echo 'Deleted';
		exit;
	} else {
		echo 'Error. Please contact coder.';
		exit;
	}
}
add_action( 'wp_ajax_k_version_checker_delete_rows', 'k_version_checker_delete_rows' );

/**
 * create an action for your options page that will run before the ACF callback function
 * see above for information on the hook you need to use
 */
add_action('posts_page_version_checker', 'k_before_version_checker_page', 5);
function k_before_version_checker_page() {
	// Before ACF outputs the options page content start an object buffer so that we can capture the output
	ob_start();
}
	
/**
 * create an action for your options page that will run after the ACF callback function
 * see above for information on the hook you need to use
 */
add_action('posts_page_version_checker', 'k_after_version_checker_page', 20);
function k_after_version_checker_page() {
	// After ACF finishes get the output and modify it
	$content = ob_get_clean();
	
	// the number of times we should replace any string
	$count = 1; 
	
	// insert something after tag <div>
	$my_content = k_get_version_checker_posts();

	$content = str_replace('<div id="postbox-container-2" class="postbox-container">', '<div id="postbox-container-2" class="postbox-container">'.$my_content, $content, $count);
	
	// output the new content
	echo $content;
}

function k_get_version_checker_posts() {
	ob_start();	
	global $wpdb;
	$rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checker LIMIT 0, 10000');
	if ( $rows ) : 
	?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th>Package</th>
					<th><?php echo count($rows) . ' posts'; ?></th>
					<th>Current</th>
					<th>New</th>
					<th>Views</th>
					<th>Update</th>
					<th>Get File</th>
					<th>Delete</th>
					<th style="white-space: nowrap;">
						<input style="vertical-align: middle; margin: 0 4px 0 0;" type="checkbox" name="all_ids" value="yes">
						<a class="button delete-rows" href="javascript:void(0);">
                            Delete
						</a>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<td>
							<?php
							$url = get_field('_playstore_url', $row->post_id);
							if ( $url ) :
								$url_components = parse_url($url);
								parse_str($url_components['query'], $params);
								
								if ( ! empty($params['id']) ) :
							?>
							<a href="https://play.google.com/store/apps/details?id=<?php echo $params['id']; ?>" target="_blank"> GooglePlay </a> <br>
                       		<a href="https://apkcombo.com/apk-downloader/#package=<?php echo $params['id']; ?>" target="_blank">ApkCombo</a>
                       		<?php 
                       			endif; 
                       		endif;
                       		?>
						</td>
						<td>
							<a href="<?php echo get_edit_post_link($row->post_id); ?>" target="_blank">
								<?php
								if ( $app_name = get_field('app_name', $row->post_id) )
									echo $app_name;
								else 
									echo get_the_title($row->post_id);
								?>
							</a>
						</td>
						<td><?php echo get_field('_softwareVersion', $row->post_id); ?></td>
						<td><?php echo $row->new_version; ?></td>
						<td><?php echo get_field('post_views', $row->post_id); ?></td>
						<td>
							<?php if ( get_field('_softwareVersion', $row->post_id) == $row->new_version ) : ?>
								<span class="button button-primary">Updated</span>
							<?php else : ?>
								<a class="button update" data-post_id="<?php echo $row->post_id; ?>" href="javascript:void(0);">
                                    Update
								</a>
							<?php endif; ?>
						</td>
						<td>
							<div style="display: flex;">
								<select style="width: 65px; padding-left: 5px;" name="type">
									<option value="">xxx</option>

								</select>
								<input style="min-width: 180px;" type="text" name="file" placeholder="Link file">
								<a class="button get-file" data-post_id="<?php echo $row->post_id; ?>" href="javascript:void(0);">
                                    Get
								</a>
							</div>
						</td>
						<td class="plugins" style="text-align: center;">
							<a class="delete" data-id="<?php echo $row->ID; ?>" href="javascript:void(0);">
								<i class="dashicons dashicons-no-alt"></i>
							</a>
						</td>
						<td style="text-align: center;">
							<input type="checkbox" name="ids" value="<?php echo $row->ID; ?>">
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php
	endif;
	$result = ob_get_clean();

	return $result;
}

/**
 * Add HTML after post title
 */
add_action( 'edit_form_after_title', function() {
	global $post;
	if ( $post->post_type !== 'post' ) return;
?>
<div style="display: flex; margin-top: 10px;">
	<select style="width: 65px; padding-left: 5px;" name="type">
		<option value="">xxxx</option>

	</select>
	<input style="flex-grow: 1;" type="text" name="file" placeholder="Link file">
	<a class="button get-file" data-post_id="<?php echo $post->ID; ?>" href="javascript:void(0);">
        Get
	</a>
</div>
<?php
});