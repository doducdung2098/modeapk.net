<?php
const POST_ID = 626;
const POSTS_PER_PAGE = 15;
const TOKEN_STRING = 'it.hieupq_';
const TOKEN_STRING_LITEAPKS = 'md5secret_';

function get_token_version_checking() {
	return md5(TOKEN_STRING) ;
}

function get_token_liteapks_version_checking() {
	return md5(TOKEN_STRING_LITEAPKS) ;
}
function version_checking_db_init() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'version_checking';
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
add_action( 'after_switch_theme', 'version_checking_db_init' );

function version_checking_init() {
    /**
     * Create version checking page
     */
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title' 	  => 'V-Checking',
            'menu_title'	  => 'V-Checking',
            'menu_slug' 	  => 'version_checking',
            'capability'	  => 'manage_options',
            'id'			  => 'version_checking',
            'post_id' 		  => 'version_checking',
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
                        'value'    => 'version_checking',
                    ),
                ),
            ),
        ));
    }
}
add_action( 'init', 'version_checking_init' );

function get_playstore_info_v1($appId){
    try {
        require_once(get_template_directory() . '/vendor/autoload.php');
        $gplay = new \Nelexa\GPlay\GPlayApps('ru_RU', 'RU');

        $appInfo = $gplay->getAppInfo($appId);
        $data = array();
        $data['version']    = $appInfo->getAppVersion();
        $data['title']      = $appInfo->getName();
        $data['category']      = $appInfo->getCategory()->getName();
        $data['android']    = trim('Android '.$appInfo->getMinAndroidVersion());
        $data['publisher']  = str_replace(',', '', $appInfo->getDeveloperName());
        $data['whats_new']  = $appInfo->getRecentChanges();
        $data['featured']  = $appInfo->getIcon()->getUrl() . '=w300-rw';
        $data['banner']  = $appInfo->getCover()->getUrl() . '=w1000-rw';

        return json_encode($data);
    } catch (Exception $e){
        return json_encode(array('error' => $e->getMessage()));
    }
}

function get_playstore_info_v2($appId){

    $url = 'https://play.google.com/store/apps/details?id='.$appId.'&hl=en&gl=gl';

    $response = wp_remote_get($url);

    if ( is_array($response) && !is_wp_error($response) && preg_match_all('/\{key: \'ds:([0-9])\', hash: \'([0-9])\', data(.*?)<\/script>/ui', $response["body"], $matches) ) {

        foreach ($matches[0] as $match) {

            $match = str_replace('</script>', '', $match);
            $match = str_replace(');', '', $match);

            if ( strpos($match, '/store/apps/category/') !== false && strpos($match, 'https://play-lh.googleusercontent.com') !== false ) {
                $match = preg_replace('/(key|hash|data|sideChannel):/ui', '"$1":', $match);
                $match = str_replace('\'', '"', $match);

                $json_object = json_decode($match);

                $json_object_data = $json_object->data[1][2];
                $data = $json_object_data;

                $result = array(
                    'package_id' => $data[77][0],
                    'title' => $data[0][0],
                    'icon' => $data[95][0][3][2],
                    'publisher' => $data[68][0],
                    'size' => false,
                    'price' => $data[57][0][0][0][0][1][0][2],
                    'version' => $data[140][0][0][0],
                    'android' => 'Android ' . $data[140][1][1][0][0][1],
//                    'type' => $data[118][6][0][0][0],
//                    'content' => $data[72][0][1],
//                    'whats_new' => $data[144][1][1],
                );

//                echo '<pre>';
//                var_dump($json_object_data);
//                echo '</pre>';
                return json_encode($result);
            }

        }
    } else {
        return json_encode(array());
    }
}


function get_playstore_version_v1($appId) {
    try {
        $info = get_playstore_info_v1($appId);
        $data = json_decode($info, true);
        if(!$data || !$data['version'] || empty($data['version']))
            return false;
        return $data['version'];
    } catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage();
        return false;
    }
}

function get_playstore_version_v2($appId){
    try {
        $response = get_playstore_info_v2($appId);
        $data = json_decode($response, true);
        if(!$data || !$data['version'] || empty($data['version']))
            return false;
        return $data['version'];
    } catch (Exception $e) {
        echo 'Caught exception: '.$e->getMessage();
        return false;
    }
}

function get_playstore_version($appId){
    return get_playstore_version_v2($appId);
}

function get_playstore_info($appId){
//    return get_playstore_info_v1($appId);
    return get_playstore_info_v2($appId);
}

function get_combo_version($appId) {
	$url = 'https://v.apkoyun.club/version.php?package='.$appId.'&token='.get_token_version_checking();

	$version = wp_remote_retrieve_body(wp_remote_get($url));
	if( $version && $version !== 'false')
		return $version;
	return false;
}

function get_combo_version_liteapks($appId){
	$url = 'https://ver.liteapks.com/expv.php?package='.$appId.'&token='.get_token_liteapks_version_checking();
	$version = wp_remote_retrieve_body(wp_remote_get($url));

	if($version &&  $version !== 'false' )
		return $version;
	return false;
}

function update_record($post_id, $appId, $new_version){
    global $wpdb;
    if ( record_duplicated($post_id) && !record_exist($post_id,$new_version) ): //exist

        $wpdb->update(
            $wpdb->prefix . 'version_checking',
            array(
                'new_version' => $new_version,
                'times' => current_time('timestamp'),
                'views' => (int) get_field('post_views', $post_id)
            ),
            array(
                'post_id' => $post_id
            )
        );

    elseif ( !record_exist($post_id,$new_version) ) :

        $wpdb->insert(
            $wpdb->prefix . 'version_checking',
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

function record_exist($post_id, $new_version): bool {
    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checking WHERE post_id = '.$post_id.' AND new_version = "'.$new_version.'";');

    if($rows) return true;
    else return false;
}

function record_duplicated($post_id): bool {
    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checking WHERE post_id = '.$post_id);

    if($rows) return true;
    else return false;
}

/***
 * Update & get options
 */

function get_const_posts_per_page(): int {
    return POSTS_PER_PAGE;
}

function get_const_post_id(): int {
    return POST_ID;
}

function get_current_page(): int {
    $current_page = get_post_meta(POST_ID,'update_current_page', true);
    if (!$current_page)  $current_page = 1;
    return $current_page;
}

function update_current_page($current_page, $max_num_pages) {
    update_post_meta(POST_ID,'max_num_pages', $max_num_pages);
    if ($current_page >= $max_num_pages || $current_page < 1) {
        update_post_meta(POST_ID,'update_current_page', 1);
    } else {
        update_post_meta(POST_ID,'update_current_page', $current_page + 1);
    }
}

/**
 * Version check action
 * Scheduled Action Hook
 */
add_action('app_version_checking', 'app_version_checking');
add_action('wp_ajax_app_version_checking', 'app_version_checking');
function app_version_checking() {

    $current_page = get_current_page();

    $update_args = array(
        'post_status ' => 'publish',
        'post_type' => 'post',
        'posts_per_page' => POSTS_PER_PAGE,
        'paged' => $current_page,
        'meta_key' => '_playstore_url',
        'meta_query' => array(
	        'relation' => 'EXISTS',
        ),
    );

    $query = new WP_Query($update_args);
    $max_num_pages = $query->max_num_pages;

    if ($query->have_posts()) :

        $needle = 'https://play.google.com/store/apps/details?id=';

        while ($query->have_posts()) : $query->the_post();

            $post_id = get_the_ID();

            $url = get_field('_playstore_url', $post_id);

            if (stripos($url, $needle) !== false) :

	            $url_components = parse_url($url);
	            parse_str($url_components['query'], $params);
	            $appId = $params['id'];

	            if ( !$appId ) continue;

	            $new_version = get_combo_version($appId);
	            $version = trim(get_field('_softwareVersion'));

	            if( $new_version && $version && version_compare($version, $new_version, '<') ) :
		            update_record(get_the_ID(), $appId, $new_version);
                else :
                    // get version from liteapks
                    $new_version = get_combo_version_liteapks($appId);
                    if( $new_version && $version && version_compare($version, $new_version, '<') ) :
                        update_record(get_the_ID(), $appId, $new_version);
                    endif;
	            endif;

            endif;

        endwhile;
        wp_reset_postdata();
    endif;

    update_current_page($current_page, $max_num_pages);
}

/**
 * Update post
 */
function version_checking_update_post() {
    $post_id = $_POST['post_id'];
    $new_version = $_POST['new_version'];

    header('Content-Type: application/json');

    if( $post_id && $new_version ) {
        $new_seotitle = '';
        $old_version = get_field('_softwareVersion', $post_id);
        update_field('_softwareVersion', $new_version, $post_id);

        //update seo title (replace old ver = new ver)
        $_seotitle = get_post_meta($post_id, '_yoast_wpseo_title', true);
        $new_seotitle = str_replace($old_version, $new_version, $_seotitle);
        update_post_meta($post_id, '_yoast_wpseo_title', $new_seotitle);

        //update post
        wp_update_post( array(
            'ID' => $post_id
        ));

        echo json_encode( array( 'status' => true, 'version' => $new_version, 'seo_title' => $new_seotitle) );
    } else {
        echo json_encode( array( 'status' => false, 'message'=> 'An error has occurred.') );
    }
    exit;
}
add_action( 'wp_ajax_version_checking_update_post', 'version_checking_update_post' );

/**
 * Delete row
 */
function version_checking_delete_row() {
    global $wpdb;
    $id = $_POST['id'];
    $result = $wpdb->delete( $wpdb->prefix . 'version_checking', array( 'id' => $id ) );
    if ( $result ) echo 'Deleted';
    else echo 'Error. Please contact coder.';
    exit;
}
add_action( 'wp_ajax_version_checking_delete_row', 'version_checking_delete_row' );


/**
 * create an action for your options page that will run before the ACF callback function
 * see above for information on the hook you need to use
 */
add_action('posts_page_version_checking', 'mod_before_version_checking_page', 5);
function mod_before_version_checking_page() {
    // Before ACF outputs the options page content start an object buffer so that we can capture the output
    ob_start();
}

/**
 * create an action for your options page that will run after the ACF callback function
 * see above for information on the hook you need to use
 */
add_action('posts_page_version_checking', 'mod_after_version_checking_page', 20);
function mod_after_version_checking_page() {

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
            <a href="<?php echo $base_url.'/wp-admin/edit.php?page=version_checking'; ?>">Times</a> /
            <a href="<?php echo $base_url.'/wp-admin/edit.php?page=version_checking&ord=post_id'; ?>">Title</a> /
            <a href="<?php echo $base_url.'/wp-admin/edit.php?page=version_checking&ord=views'; ?>">Views</a>
        </b>
    </p>
    <p><b>Status: <?php echo get_post_meta(POST_ID,'update_current_page', true).'/'.get_post_meta(POST_ID,'max_num_pages', true) ?> </b></p>
<?php

    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'version_checking ORDER BY '.$ord.' DESC LIMIT 0, 1000');
	
    if ( $rows ) : ?>

        <table class="widefat striped">
            <thead>
            <tr>
                <th><span id="posts-total"><?php echo count($rows); ?></span></th>
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
            <?php foreach ( $rows as $row ) :
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
                        if(empty($MOD)){
                            $MOD = '<span style="color: black; font-weight: bold">APK only</span>';
                        } else $MOD = '<span style="color: #d75959; font-weight: bold">' .$MOD.'</span>';
                        echo $MOD;
                        ?>
                    </td>

                    <td><?php echo $app_version; ?></td>
                    <td><strong><?php echo $row->new_version; ?></strong></td>

                    <td>
                        <?php if ( $app_version == $row->new_version ) : ?>
                            <span class="button button-primary">Updated</span>
                        <?php else : ?>
                            <a class="button update" data-new_version="<?php echo $row->new_version; ?>" data-post_id="<?php echo $row->post_id; ?>" href="javascript:void(0);">
                                Update
                            </a>
                        <?php endif; ?>
                    </td>

                    <td class="plugins">

<!--                        <a class="app-show-versions" href="javascript:void(0);" data-post_id="--><?php //echo $row->post_id ?><!--"><i class="dashicons dashicons-visibility"></i></a>-->

                        <a class="app-edit-download-links" href="javascript:void(0);" data-post_id="<?php echo $row->post_id ?>"><i class="dashicons dashicons-visibility"></i></a>

                        <a class="edit" target="_blank" href="<?php echo get_edit_post_link($row->post_id)?>">
                            <i class="dashicons dashicons-edit"></i>
                        </a>

                        <a class="delete" data-id="<?php echo $row->ID; ?>" href="javascript:void(0);">
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
            <p>No version to update</p>
        </div>
    <?php
    endif;

    $my_content = ob_get_clean();

    $content = str_replace('<div id="postbox-container-2" class="postbox-container">', '<div id="postbox-container-2" class="postbox-container">'.$my_content, $content, $count);

    echo $content;
}