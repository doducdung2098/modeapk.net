<?php
/**
 * Show versions
 */
function app_show_versions() {
	$post_id = $_GET['post_id'];
	?>
		<div class="app-popup-versions" style="background-color: #fff; border: 1px solid #ccd0d4; width: 750px; padding: 10px; position: absolute; z-index: 10; top: 100%; left: 50%; transform: translateX(-50%);">
			<?php 
			if ( $versions = get_field('versions', $post_id) ) : 
				foreach ( $versions as $version ) :
			?>
				<div style="display: flex;">
					<span style="font-weight: 700; flex: 0 0 120px; max-width: 120px;">Version</span>
					<span><?php echo $version['version']; ?></span>		
				</div>
				<div style="display: flex;">
					<span style="font-weight: 700; flex: 0 0 120px; max-width: 120px;">Note for version</span>
					<span><?php echo $version['version_note']; ?></span>
				</div>
				<div style="display: flex; margin-bottom: 16px;">
					<span style="font-weight: 700; flex: 0 0 120px; max-width: 120px;">Downloads</span>
					<div style="flex-grow: 1; padding-top: 8px;">
						<?php if ( $version['version_downloads'] ) : ?>
							<table class="widefat">
								<tr>
									<th style="width: 50px;">Type</th>
									<th style="width: 100px;">Size</th>
									<th>Link</th>
									<th>Note</th>
								</tr>

								<?php foreach ( $version['version_downloads'] as $download ) : ?>
									<tr>
										<td><?php echo $download['version_download_type']; ?></td>
										<td><?php echo $download['version_download_size']; ?></td>
										<td><?php echo $download['version_download_link']; ?></td>
										<td><?php echo $download['version_download_note']; ?></td>
									</tr>
				    			<?php endforeach; ?>
							</table>
						<?php endif; ?>
					</div>
				</div>
			<?php
				endforeach; 
			endif; 
			?>

			<div style="display: flex; justify-content: flex-end; margin-top: 10px;">
				<a class="button button-primary app-close-download-links" href="javascript:void(0);">Close</a>
			</div>
		</div>
	<?php 
	exit;
}
add_action( 'wp_ajax_app_show_versions', 'app_show_versions' );

/**
 * Edit versions
 */
function app_edit_versions() {
	$post_id = $_GET['post_id'];
	?>
		<form class="app-popup-versions-form" method="POST" style="background-color: #fff; border: 1px solid #ccd0d4; width: 1000px; padding: 10px; position: absolute; z-index: 10; top: 100%; left: 50%; transform: translateX(-50%);">
			<input type="hidden" name="action" value="app_save_versions" />
			<input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />

            <div style="display: flex; margin: 16px 0;">
                <div style="width: 120px; margin-right: 10px">
                    <label for="">Version</label>
                    <input style="width: 100%;border-radius: 0;border: 0;border-bottom: 2px solid #2271b1; font-weight: bold" type="text" name="version" value="<?php the_field('_softwareVersion', $post_id); ?>">
                </div>

                <div style="flex-grow: 1">
                    <label for="">SEO Title</label>
                    <input style="width: 100%;border-radius: 0;border: 0;border-bottom: 2px solid #2271b1; font-weight: bold" type="text" name="seo_title" value="<?php the_field('_yoast_wpseo_title', $post_id); ?>">
                </div>
            </div>

            <?php
            if ( $versions = get_field('versions', $post_id) ) :
                foreach ($versions as $version) :
                ?>
                    <div class="versions-item" style="margin: 16px">

                        <div style="text-align: right">
                            <button class="app-remove-versions button button-primary" style="background: #a00; color: white; border: 0">Remove Versions</button>
                        </div>

                        <div style="display: flex; margin: 16px 0; align-items: end">
                            <div style="font-weight: 700; width: 120px; margin-right: 10px">Versions Title</div>
                            <div style="flex-grow: 1"> <input style="width: 100%;border-radius: 0;border: 0;border-bottom: 2px solid #2271b1;" type="text" name="version_titles[]" value="<?php echo $version['version']; ?>" /> </div>
                        </div>

                        <div style="display: flex; margin: 16px 0; align-items: end">
                            <div style="font-weight: 700; width: 120px; margin-right: 10px">Versions Note</div>
                            <div style="flex-grow: 1"> <input style="width: 100%;border-radius: 0;border: 0;border-bottom: 2px solid #2271b1;" type="text" name="version_notes[]" value="<?php echo $version['version_note']; ?>" /> </div>
                        </div>

                        <div style="margin-bottom: 16px;">

                            <div style="font-weight: 700;">Downloads</div>

                            <div style="flex-grow: 1; padding-top: 8px;">

                                    <table class="widefat">

                                        <tr>
                                            <th style="width: 100px;">Type</th>
                                            <th style="width: 70px;">Size</th>
                                            <th >Link</th>
                                            <th style="width: 120px">Note</th>
                                            <th style="width: 10px">Action</th>
                                        </tr>

                                        <input type="hidden" name="total_links[]" value="<?php echo $version['version_downloads'] ? sizeof( $version['version_downloads'] ) : 0  ?>" >

                                            <?php foreach ( $version['version_downloads'] as $download ) : ?>
                                                <tr>
                                                    <td><input style="width: 100%" type="text" name="types[]" value="<?php echo $download['version_download_type']; ?>"> </td>
                                                    <td><input style="width: 100%" type="text" name="sizes[]" value="<?php echo $download['version_download_size']; ?>"> </td>
                                                    <td><input style="width: 100%" type="text" name="links[]" value="<?php echo $download['version_download_link']; ?>"> </td>
                                                    <td><input style="width: 100%" type="text" name="notes[]" value="<?php echo $download['version_download_note']; ?>"> </td>

                                                    <td style="text-align: center">
                                                        <a class="app-remove-download-link" style="color: #a00;" href="javascript:void(0);">
                                                            <span class="dashicons dashicons-no-alt"></span>
                                                        </a>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>

                                        <tr>
                                            <td colspan="5" style="text-align: right">
                                                <button class="app-add-versions-link button button-primary">Add Row</button>
                                            </td>
                                        </tr>

                                    </table>

                            </div>
                        </div>

                    </div>
                <?php
                endforeach;
            endif;
            ?>

			<div style="display: flex; justify-content: space-between; margin-top: 10px;">
				<button class="button button-primary app-close-versions-list">Close</button>
				<button class="button button-primary app-save-versions" type="submit">Save</button>
				<a class="button button-primary app-add-versions" href="javascript:void(0);">Add Versions</a>
			</div>
		</form>
	<?php 
	exit;
}
add_action( 'wp_ajax_app_edit_versions', 'app_edit_versions' );

/**
 * Add versions download link
 */
function app_add_versions_link() {
	?>

    <tr>
        <td><input style="width: 100%" type="text" name="types[]"> </td>
        <td><input style="width: 100%" type="text" name="sizes[]"> </td>
        <td><input style="width: 100%" type="text" name="links[]"> </td>
        <td><input style="width: 100%" type="text" name="notes[]"> </td>

        <td style="text-align: center">
            <a class="app-remove-download-link" style="color: #a00;" href="javascript:void(0);">
                <span class="dashicons dashicons-no-alt"></span>
            </a>
        </td>

    </tr>

	<?php 
	exit;
}
add_action( 'wp_ajax_app_add_versions_link', 'app_add_versions_link' );

/**
 * Save Versions
 */
function app_save_versions() {
	$post_id = $_POST['post_id'];

    $_softwareVersion = $_POST['version'];
    $seo_title = $_POST['seo_title'];

    if ($_softwareVersion && $seo_title) {
        update_field('_softwareVersion', $_softwareVersion, $post_id);
        update_field('_yoast_wpseo_title', $seo_title, $post_id);
    }

	$version_titles = $_POST['version_titles'];
	$version_notes = $_POST['version_notes'];
	$total_links = $_POST['total_links'];
	$types = $_POST['types'];
	$sizes = $_POST['sizes'];
	$links = $_POST['links'];
	$notes = $_POST['notes'];

    delete_field('versions', $post_id);

	if ( ! empty($version_titles) || ! empty($version_notes) || ! empty($types) || ! empty($sizes) || ! empty($links) || ! empty($notes) ) {
        $index = 0;

		foreach ( $version_titles as $key => $title ) {
            $versions = array();

            $versions['version'] = $version_titles[$key];
            $versions['version_note'] = $version_notes[$key];

            for($i = 1; $i <= $total_links[$key]; $i++) {
                $versions['version_downloads'][] = array(
                    'version_download_type'    => $types[$index],
                    'version_download_size'    => $sizes[$index],
                    'version_download_link'    => $links[$index],
                    'version_download_note'    => $notes[$index]
                );
                $index++;
            }

	        add_row('versions', $versions, $post_id);

            print_r($versions);
		}

	} else {
        echo 'No data';
    }


	exit;
}
add_action( 'wp_ajax_app_save_versions', 'app_save_versions' );

/**
 * Add versions
 */
function app_add_versions() {
    ?>

    <div class="versions-item" style="margin: 16px">

        <div style="text-align: right">
            <button class="app-remove-versions button button-primary" style="background: #a00; color: white; border: 0">Remove Versions</button>
        </div>


        <div style="display: flex; margin: 16px 0; align-items: end">
            <div style="font-weight: 700; width: 120px; margin-right: 10px">Versions Title</div>
            <div style="flex-grow: 1"> <input style="width: 100%;border-radius: 0;border: 0;border-bottom: 2px solid #2271b1;" type="text" name="version_titles[]" required /> </div>
        </div>

        <div style="display: flex; margin: 16px 0; align-items: end">
            <div style="font-weight: 700; width: 120px; margin-right: 10px">Versions Note</div>
            <div style="flex-grow: 1"> <input style="width: 100%;border-radius: 0;border: 0;border-bottom: 2px solid #2271b1;" type="text" name="version_notes[]" required /> </div>
        </div>

        <div style="margin-bottom: 16px;">

            <div style="font-weight: 700;">Downloads</div>

            <div style="flex-grow: 1; padding-top: 8px;">

                    <table class="widefat">

                        <tr>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 70px;">Size</th>
                            <th>Link</th>
                            <th style="width: 120px">Note</th>
                            <th style="width: 10px">Action</th>
                        </tr>

                        <tr>
                            <td colspan="5" style="text-align: right">
                                <button class="app-add-versions-link button button-primary">Add Row</button>
                            </td>
                        </tr>

                    </table>

            </div>
        </div>

    </div>

    <?php
    exit;
}
add_action( 'wp_ajax_app_add_versions', 'app_add_versions' );


/**
 * Edit text field
 */
function app_edit_text_field() {
	$post_id = $_GET['post_id'];
	$field = $_GET['field'];
	?>
		<form class="app-popover-text-field" method="POST" style="background-color: #fff; border: 1px solid #ccd0d4; width: 400px; padding: 10px; position: absolute; z-index: 10; top: 100%; right: 0;">
			<input type="hidden" name="action" value="app_save_text_field">
			<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
			<input type="hidden" name="field" value="<?php echo $field; ?>">
			<textarea style="width: 100%; height: 80px;" name="text"><?php the_field($field, $post_id); ?></textarea>
			<div style="display: flex; justify-content: space-between; margin-top: 10px;">
				<a class="button button-primary app-close-text-field" href="javascript:void(0);">Close</a>
				<button class="button button-primary app-save-text-field" type="submit">Save</button>
			</div>
		</form>
	<?php 
	exit;
}
add_action( 'wp_ajax_app_edit_text_field', 'app_edit_text_field' );

/**
 * Save text field
 */
function app_save_text_field() {
	$post_id = $_POST['post_id'];
	$field = $_POST['field'];
	$text = $_POST['text'];
	update_field($field, $text, $post_id);

	echo get_field($field, $post_id);
	echo '<br><a class="app-edit-text-field" href="javascript:void(0);" data-post_id=' . $post_id . ' data-field="' . $field . '"><i class="dashicons dashicons-edit"></i></a>';
	exit;
}
add_action( 'wp_ajax_app_save_text_field', 'app_save_text_field' );

/**
 *  Handle button update data
 */
add_action( 'wp_ajax_app_get_data_playstore', function(){
    header('Content-Type: application/json');
    $url = $_POST['url'];
    if (stripos($url, 'https://play.google.com/store/apps/details?id=') !== false) :

        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $appId = $params['id'];

        $result = get_playstore_info_v1($appId);

        echo $result;
    else:
        echo json_encode( array() );
    endif;

    exit;
} );

/**
 * Update image
 * @throws \Nelexa\GPlay\Exception\GooglePlayException
 */
function app_update_image(){
    header('Content-Type: application/json');

    $postID = $_POST['postID'];

    if ($google_play_url = get_field('_playstore_url', $postID)) {

        $url_components = parse_url($google_play_url);
        parse_str($url_components['query'], $params);

        if (isset($params['id'])) {
            $appId = $params['id'];

            $data = json_decode(get_playstore_info_v1($appId), TRUE);

            $title = $data['title'];
            $featured_url = $data['featured'];
            $banner_url = $data['banner'];

            $banner = get_field('post_banner', $postID);
            if ($banner) {
                delete_field('post_banner', $postID);
                $delete_banner  = removeAttachedImage($banner['ID']);
            }
            if($banner_url) {
                $attach_id = Generate_Featured_Image($banner_url, $title.'-banner');
                update_field('post_banner', $attach_id, $postID);
            }

            if($post_thumbnail_id = get_post_thumbnail_id($postID)) {
                delete_post_thumbnail($postID);
                $delete_featured = removeAttachedImage($post_thumbnail_id);
            }
            if($featured_url){
                Generate_Featured_Image($featured_url, $title.'-featured', $postID);
            }

            echo json_encode( array('banner' => $banner, 'thumbnailID' => $post_thumbnail_id, 'delete_banner' => $delete_banner, 'delete_featured' => $delete_featured) );
        }
    }
    exit;
}
add_action('wp_ajax_app_update_image', 'app_update_image');

/**
 * Delete image by attachment ID
 */
function removeAttachedImage($attachment_id) {
    $attachment_metadata = wp_get_attachment_metadata($attachment_id);

    if (wp_delete_attachment($attachment_id, true)) :
        foreach ($attachment_metadata['sizes'] as $size) :
            $image_path = path_join(wp_get_upload_dir()['basedir'], $size['file']);
            if (file_exists($image_path)) :
                unlink($image_path);
            endif;
        endforeach;

        return true;
    endif;

    return false;
}
