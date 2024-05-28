<?php
add_action('after_switch_theme', function () {
    global $wpdb;
	$table_name = $wpdb->prefix . 'version_checker_json';
	$curlarset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		ID bigint(20) NOT NULL AUTO_INCREMENT,
		post_id bigint(20) NOT NULL,
		new_version varchar(20) NOT NULL,
		PRIMARY KEY  (ID)
	) $curlarset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
});

function k_version_checker_json_init() {
	/**
	 * Create version checker json page
	 */
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	  => 'Version Checker Json',
			'menu_title'	  => 'Version Checker Json',
			'menu_slug' 	  => 'version_checker_json',
			'capability'	  => 'manage_options',
			'id'			  => 'version_checker_json',
			'post_id' 		  => 'version_checker_json',
			'parent'		  => 'edit.php'
		));
	}

	/**
	 * Add version_checker_json field group
	 */
	if ( function_exists('acf_add_local_field_group') ) {
		acf_add_local_field_group(array(
			'key'		=> 'version_checker_json_settings',
			'title' 	=> 'Settings',
			'fields' 	=> array (
				array(
					'label'    => 'Json Url',
					'name'     => 'version_checker_json_url',
					'key'      => 'version_checker_json_url',
					'type'     => 'url',
					'required' => 1
				),
			),
			'location' => array (
				array (
					array (
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'version_checker_json',
					),
				),
			),
			'position' => 'side',
		));	
	}
}
add_action( 'init', 'k_version_checker_json_init' );

/**
 * Version checker
 */
function k_version_checker_json() {
	$params = $_REQUEST['acf'];
	$json_url = $params['version_checker_json_url'];
	update_field('version_checker_json_url', $json_url, 'version_checker_json');

	$args = array(
		'post_type'		 => 'post',
		'posts_per_page' => -1,
		'post_status'	 => 'publish',
		'fields'         => 'ids',
		'meta_key' 		 => 'post_views',
        'orderby' 		 => 'meta_value_num',
        'order' 		 => 'DESC',
	);
	$posts = get_posts($args);
	if ( $posts ) {
		global $wpdb;
		$wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'version_checker_json');

		$json = file_get_contents($json_url);
		$data = json_decode($json, true);
		$apps = $data['apps'];
		$checked = 0;

		foreach ( $posts as $post_id ) { 			
			$url = get_field('_playstore_url', $post_id);
			if ( ! $url ) continue;

			$url_components = parse_url($url);
			if ( empty($url_components['query']) ) continue;

			parse_str($url_components['query'], $params);
			if ( empty($params['id']) ) continue;

			foreach ( $apps as $key => $app ) {
				if ( trim($app['id']) != trim($params['id']) ) continue;

				$checked++;

				unset($apps[$key]);

				if ( ! empty(get_field('_softwareVersion', $post_id)) && ! empty($app['versionName']) ) {
	    			$version = trim(get_field('_softwareVersion', $post_id));
	    			$new_version = trim($app['versionName']);

		    		if ( version_compare($new_version, $version) > 0 || ! preg_match('~[0-9]+~', $new_version) ) {
						$wpdb->insert( 
							$wpdb->prefix . 'version_checker_json', 
							array( 
								'post_id' 	  => $post_id, 
								'new_version' => $new_version
							)
						);
					}
				}
			}
		}
	} 
	
	$result = k_get_version_checker_json_posts();
	header('Content-Type: application/json');
	echo json_encode( array( 'status' => $checked . ' posts checked', 'result' => $result ) );
	exit;
}
add_action( 'wp_ajax_k_version_checker_json', 'k_version_checker_json' );

/**
 * Update post
 */
function k_version_checker_json_update_post() {
	$post_id = $_POST['post_id'];
	$url = get_field('_playstore_url', $post_id);
	$url_components = parse_url($url);
	parse_str($url_components['query'], $params);
	if ( empty($params['id']) ) {
		header('Content-Type: application/json');
		echo json_encode( array( 'status' => false, 'message' => 'App ID not exist in post' ) );
		exit;
	}

	$old_version = trim(get_field('_softwareVersion', $post_id));

	$json_url = get_field('version_checker_json_url', 'version_checker_json');
	$json = file_get_contents($json_url);
	$data = json_decode($json, true);
	$apps = $data['apps'];
	foreach ( $apps as $key => $app ) {
		if ( trim($app['id']) == trim($params['id']) )
			$new_version = $app['versionName'];
	}

	if ( empty($new_version) ) {
		header('Content-Type: application/json');
		echo json_encode( array( 'status' => false, 'message' => 'New version not exist in json' ) );
		exit;
	}

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
	exit;
}
add_action( 'wp_ajax_k_version_checker_json_update_post', 'k_version_checker_json_update_post' );

/**
 * Delete row
 */
function k_version_checker_json_delete_row() {
	global $wpdb;
	$id = $_POST['id'];
	$result = $wpdb->delete( $wpdb->prefix . 'version_checker_json', array( 'id' => $id ) );
	if ( $result ) {
		echo 'Deleted';
		exit;
	} else {
		echo 'Error. Please contact coder.';
		exit;
	}
}
add_action( 'wp_ajax_k_version_checker_json_delete_row', 'k_version_checker_json_delete_row' );

/**
 * Delete rows
 */
function k_version_checker_json_delete_rows() {
	global $wpdb;
	$ids = $_POST['ids'];

	$ids = implode(',', array_map('absint', $ids));
	$result = $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'version_checker_json WHERE ID IN(' . $ids . ')');
	if ( $result ) {
		echo 'Deleted';
		exit;
	} else {
		echo 'Error. Please contact coder.';
		exit;
	}
}
add_action( 'wp_ajax_k_version_checker_json_delete_rows', 'k_version_checker_json_delete_rows' );

/**
 * create an action for your options page that will run before the ACF callback function
 * see above for information on the hook you need to use
 */
add_action('posts_page_version_checker_json', 'k_before_version_checker_json_page', 5);
function k_before_version_checker_json_page() {
	// Before ACF outputs the options page content start an object buffer so that we can capture the output
	ob_start();
}
	
/**
 * create an action for your options page that will run after the ACF callback function
 * see above for information on the hook you need to use
 */
add_action('posts_page_version_checker_json', 'k_after_version_checker_json_page', 20);
function k_after_version_checker_json_page() {
	// After ACF finishes get the output and modify it
	$content = ob_get_clean();
	
	// the number of times we should replace any string
	$count = 1; 
	
	// insert something after tag <div>
	$my_content = k_get_version_checker_json_posts();

	$content = str_replace('<div id="postbox-container-2" class="postbox-container">', '<div id="postbox-container-2" class="postbox-container">'.$my_content, $content, $count);
	
	// output the new content
	echo $content;
}

function k_get_version_checker_json_posts() {
	ob_start();	
	global $wpdb;
	$rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checker_json LIMIT 0, 10000');
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
									<option value="">xxxx</option>
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