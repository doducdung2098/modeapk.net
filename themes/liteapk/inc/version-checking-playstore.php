<?php
const POSTS_PER_PAGE_PLAYSTORE = 25;
function version_checking_playstore_db_init() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'version_checking_playstore';
    $curlarset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		ID bigint(20) NOT NULL AUTO_INCREMENT,
		post_id bigint(20) NOT NULL,
		new_version varchar(30) NOT NULL,
		packname varchar(80),
		times varchar(40),
		views int,
		PRIMARY KEY (ID)
	) $curlarset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
add_action( 'after_switch_theme', 'version_checking_playstore_db_init' );

function version_checking_playstore_init() {
	/**
	 * Create version checking page
	 */
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	  => 'V-Checking Playstore',
			'menu_title'	  => 'V-Checking Playstore',
			'menu_slug' 	  => 'version_checking_playstore',
			'capability'	  => 'manage_options',
			'id'			  => 'version_checking_playstore',
			'post_id' 		  => 'version_checking_playstore',
			'parent'		  => 'edit.php'
		));
	}

	/**
	 * Add version_checking field group
	 */
	if ( function_exists('acf_add_local_field_group') ) {
		acf_add_local_field_group(array(
			'location' => array (
				array (
					array (
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'version_checking_playstore',
					),
				),
			),
		));
	}
}
add_action( 'init', 'version_checking_playstore_init' );

function playstore_update_record($post_id, $appId, $new_version){
    
    if ( record_duplicated_playstore($post_id) && !record_exist_playstore($post_id,$new_version) ): //exist
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'version_checking_playstore',
            array(
                'new_version' => $new_version,
                'times' => current_time('timestamp'),
                'views' => (int) get_field('post_views', $post_id)
            ),
            array(
                'post_id' => $post_id
            )
        );

    elseif ( !record_exist_playstore($post_id,$new_version) ) :
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'version_checking_playstore',
            array(
                'post_id' 	  => $post_id,
                'new_version' => $new_version,
                'times' => current_time('timestamp'),
                'packname' => $appId,
                'views' => (int) get_field('post_views', $post_id)
            )
        );

    endif;
}

function record_exist_playstore($post_id, $new_version): bool {
    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checking_playstore WHERE post_id = '.$post_id.' AND new_version = "'.$new_version.'";');

    if($rows) return true;
    
    return false;
}

function record_duplicated_playstore($post_id): bool {
    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checking_playstore WHERE post_id = '.$post_id);

    if($rows) return true;
    
    return false;
}
add_action('posts_page_version_checking_playstore', 'before_version_checking_playstore_page', 5);
function before_version_checking_playstore_page() {
	// Before ACF outputs the options page content start an object buffer so that we can capture the output
	ob_start();
}

add_action('posts_page_version_checking_playstore', 'content_page_playstore', 20);
function content_page_playstore() {

	// After ACF finishes get the output and modify it
	$content = ob_get_clean();

	// the number of times we should replace any string
	$count = 1;

	// insert something after tag <div>
	ob_start();

    $base_url =  get_home_url();
    $ord = 'times';
    if (isset($_GET['ord']) && $_GET['ord'] == 'views') $ord = 'views';
    if (isset($_GET['ord']) && $_GET['ord'] == 'post_id') $ord = 'post_id';
    ?>

    <style>
        .alternate, .striped>tbody>:nth-child(odd), ul.striped>:nth-child(odd) {
            background-color: #dfdfdf;
        }
        td.first-col a{
            color: #b92222;
        }
    </style>

    <div class="wrap">
        <p>
            <b>Sort by:
                <a href="<?php echo $base_url.'/wp-admin/edit.php?page=version_checking_playstore'; ?>">Times</a> /
                <a href="<?php echo $base_url.'/wp-admin/edit.php?page=version_checking_playstore&ord=post_id'; ?>">Title</a> /
                <a href="<?php echo $base_url.'/wp-admin/edit.php?page=version_checking_playstore&ord=views'; ?>">Views</a>
            </b>
        </p>
        <p><b>Status: <?php echo get_post_meta(POST_ID,'update_current_page_playstore', true).'/'.get_post_meta(POST_ID,'max_num_pages_playstore', true) ?> </b></p>

        <p><b>Batch: <?php echo POSTS_PER_PAGE_PLAYSTORE ?> </b></p>
<?php
    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checking_playstore ORDER BY '.$ord.' DESC LIMIT 0, 1000');

    if ( $rows ) : ?>

        <p><b>Total: <span id="posts-total"><?php echo count($rows); ?></span></b></p>

        <table class="widefat striped">

            <thead>
                <tr>
                    <th>Title</th>
                    <th>Old</th>
                    <th>View</th>
                    <th>MOD</th>
                    <th>Current</th>
                    <th>New</th>
                    <th>Update</th>
                    <th>Action</th>
                    <th>Time</th>
                </tr>
            </thead>

            <tbody>
            <?php
            foreach ( $rows as $row ) :
                $app_version = get_field('_softwareVersion', $row->post_id);
                $app_title = get_the_title($row->post_id);
            ?>
                <tr style="position: relative">

                    <td class="first-col">
                        <h3 style="margin: 3px 0 8px 0"><a href="<?php echo get_permalink($row->post_id) ?>" target="_blank"><?php echo $app_title; ?></a></h3>
                        <hr>
                        <a target="_blank" href="https://apkcombo.com/id/<?php echo $row->packname.'/download/apk' ?>">COMBO</a> /
                        <a target="_blank" href="https://apkmody.com/?s=<?php echo str_replace(' ', '+', $app_title) ?>">MODY</a> /
                        <a target="_blank" href="https://an1.com/?story=<?php echo str_replace(' ', '+', $app_title) ?>&do=search&subaction=search">AN1</a> /
                        <a target="_blank" href="https://liteapks.com/?s=<?php echo str_replace(' ', '+', $app_title) ?>">LITAPKS</a>
                        <br>
                        <a target="_blank" href="https://www.google.com/search?q=<?php echo str_replace(' ', '+', $app_title).'+liteapk.ru' ?>">Google</a> /
                        <a target="_blank" href="<?php echo get_field('_playstore_url', $row->post_id) ?>">Play</a> /
                        <a target="_blank" href="https://androeed.store/index.php?m=search&f=s&w=<?php echo str_replace(' ', '+', $app_title) ?>">Androeed</a> /
                        <a target="_blank" style="font-weight: bold" href="https://apkoyun.club?s=<?php echo str_replace(' ', '-', $app_title) ?>">APKOYUN</a>
                    </td>

                    <td>
                        <?php echo number_format($row->views); ?>
                    </td>

                    <td>
                        <?php echo number_format((int) get_field('post_views', $row->post_id)); ?>
                    </td>

                    <td>
                        <?php $MOD = get_field('mod_info' , $row->post_id);
                            if(empty($MOD))
                                echo '<span style="color: black; font-weight: bold">APK only</span>';
                            else echo '<span style="color: #d75959; font-weight: bold">' .$MOD.'</span>';
                        ?>
                    </td>

                    <td><?php echo $app_version; ?></td>
                    <td><strong><?php echo $row->new_version; ?></strong></td>

                    <td>
                        <?php if ( $app_version == $row->new_version ) : ?>
                            <span class="button button-primary">Updated</span>
                        <?php else : ?>
                            <a class="button update hit_update_btn" data-new_version="<?php echo $row->new_version; ?>" data-post_id="<?php echo $row->post_id; ?>" href="javascript:void(0);">
                                Update
                            </a>
                        <?php endif; ?>
                    </td>

                    <td class="plugins">

                        <a class="app-edit-download-links" href="javascript:void(0);" data-post_id="<?php echo $row->post_id ?>"><i class="dashicons dashicons-visibility"></i></a>

                        <a class="edit" target="_blank" href="<?php echo get_edit_post_link($row->post_id)?>">
                            <i class="dashicons dashicons-edit"></i>
                        </a>

                        <a class="delete hit_delete_btn" data-id="<?php echo $row->ID; ?>" href="javascript:void(0);">
                            <i class="dashicons dashicons-no-alt"></i>
                        </a>

                    </td>

                    <td><?php echo esc_html(human_time_diff($row->times, current_time('timestamp'))); ?> </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <div class="acf-admin-notice notice notice-error">
            <p>No version to update.</p>
        </div>
    <?php endif;
    ?>
    </div>

<?php

	$my_content = ob_get_clean();

	$content = str_replace('<div id="postbox-container-2" class="postbox-container">', '<div id="postbox-container-2" class="postbox-container">'.$my_content, $content, $count);

	echo $content;

}

function get_current_page_playstore(): int {
    $current_page = get_post_meta(POST_ID,'update_current_page_playstore', true);
    if (!$current_page)  $current_page = 1;
    return $current_page;
}

function update_current_page_playstore($current_page, $max_num_pages) {
    update_post_meta(POST_ID,'max_num_pages_playstore', $max_num_pages);
    if ($current_page >= $max_num_pages || $current_page < 1) {
        update_post_meta(POST_ID,'update_current_page_playstore', 1);
    } else {
        update_post_meta(POST_ID,'update_current_page_playstore', $current_page + 1);
    }
}

// Scheduled Action Hook
add_action('app_version_checking_playstore', 'app_version_checking_playstore');
add_action('wp_ajax_app_version_checking', 'app_version_checking_playstore');
function app_version_checking_playstore(){
    $current_page = get_current_page_playstore();
	
    $args = array(
        'post_status ' => 'publish',
        'post_type' => 'post',
        'posts_per_page' => POSTS_PER_PAGE_PLAYSTORE,
        'paged' => $current_page,
        'meta_key' => '_playstore_url',
        'meta_query' => array(
            'relation' => 'EXISTS',
        ),
    );

    $query = new WP_Query($args);
    $max_num_pages = $query->max_num_pages;

    if ( $query->have_posts() ) :
        
        while ( $query->have_posts() ) : $query->the_post();

            $url = get_field('_playstore_url');

            if (stripos($url, 'https://play.google.com/store/apps/details?id=') !== false) :

                $url_components = parse_url($url);
                parse_str($url_components['query'], $params);
                $appId = $params['id'];
                
                if( !$appId ) continue;

                //get new version
                $version = trim(get_field('_softwareVersion'));

                $new_version = get_playstore_version_v1($appId);

                if( $version && $new_version && version_compare($version, $new_version, '<') ) :
                    playstore_update_record(get_the_ID(), $appId, $new_version);
                    echo 'Update from v1';
                else:
                    $new_version = get_playstore_version_v2($appId);
                    if ( $version && $new_version && version_compare($version, $new_version, '<')) :
                        playstore_update_record(get_the_ID(), $appId, $new_version);
                        echo 'Update from v2';
                    endif;
                endif;

            endif;
        endwhile;

        wp_reset_postdata();

    endif;

    update_current_page_playstore($current_page, $max_num_pages);
}

/**
 * func to update and delete version in post
 * */
function version_checking_playstore_update_post() {

    $post_id = $_POST['post_id'];
    $new_version = $_POST['new_version'];

    if( $post_id && $new_version ) {

        //get old version
        $old_version = get_field('_softwareVersion', $post_id);

        //update new version
        update_field('_softwareVersion', $new_version, $post_id);

        //update seo title (replace old ver = new ver)
        $_seotitle = get_post_meta($post_id, '_yoast_wpseo_title', true);
        $new_seotitle = str_replace($old_version, $new_version, $_seotitle);
        update_post_meta($post_id, '_yoast_wpseo_title', $new_seotitle);
		
		//update post
		wp_update_post( array(
			'ID' => $post_id
		));

        header('Content-Type: application/json');
        echo json_encode( array( 'status' => true, 'version' => $new_version, 'seo_title' => $new_seotitle) );
    } else {
        header('Content-Type: application/json');
        echo json_encode( array( 'status' => false, 'message'=> 'An error has occurred.') );
    }
    exit;
}
add_action( 'wp_ajax_version_checking_playstore_update_post', 'version_checking_playstore_update_post' );

/**
 * Delete row
 */
function version_checking_playstore_delete_row() {
    global $wpdb;
    $id = $_POST['id'];
    $result = $wpdb->delete( $wpdb->prefix . 'version_checking_playstore', array( 'id' => $id ) );

    if ( !$result ) echo 'Delete row error';

    exit;
}
add_action( 'wp_ajax_version_checking_playstore_delete_row', 'version_checking_playstore_delete_row' );