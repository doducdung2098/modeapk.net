<?php
/**
 * Show versions
 */
function k_show_versions() {
	$post_id = $_GET['post_id'];
	?>
		<div class="k-popover-versions" style="background-color: #fff; border: 1px solid #ccd0d4; width: 750px; padding: 10px; position: absolute; z-index: 10; top: 100%; left: 50%; transform: translateX(-50%);">
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
				<a class="button button-primary k-close-download-links"ref="javascript:void(0);">Close</a>
			</div>
		</div>
	<?php 
	exit;
}
add_action( 'wp_ajax_k_show_versions', 'k_show_versions' );

/**
 * Edit download links
 */
function k_edit_download_links() {
	$post_id = $_GET['post_id'];
	?>
		<form class="k-popover-download-links" method="POST" style="background-color: #fff; border: 1px solid #ccd0d4; width: 750px; padding: 10px; position: absolute; z-index: 10; top: 100%; left: 50%; transform: translateX(-50%);">
			<input type="hidden" name="action" value="k_save_download_links">
			<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
			<table class="widefat">
				<tr>
					<th>Name</th>
					<th>Version</th>
					<th>Type</th>
					<th>Size</th>
					<th>Link</th>
					<th>Note</th>
					<th>Delete</th>
				</tr>

				<?php
				if ( have_rows('downloads', $post_id) ):
    				while ( have_rows('downloads', $post_id) ) : the_row();
    				?>
    					<tr>
    						<td><input style="width: 100%;" type="text" name="names[]" value="<?php the_sub_field('download_name'); ?>"></td>
    						<td><input style="width: 100%;" type="text" name="versions[]" value="<?php the_sub_field('download_version'); ?>"></td>
    						<td><input style="width: 100%;" type="text" name="types[]" value="<?php the_sub_field('download_type'); ?>"></td>
    						<td><input style="width: 100%;" type="text" name="sizes[]" value="<?php the_sub_field('download_size'); ?>"></td>
    						<td><input style="width: 100%;" type="text" name="links[]" value="<?php the_sub_field('download_link'); ?>"></td>
    						<td><input style="width: 100%;" type="text" name="notes[]" value="<?php the_sub_field('download_note'); ?>"></td>
    						<td>
    							<a class="k-remove-download-link" style="color: #a00;" href="javascript:void(0);">
									<span class="dashicons dashicons-no-alt"></span>
								</a>
    						</td>
    					</tr>
    				<?php
    				endwhile;
    			endif;
				?>
			</table>
			<div style="display: flex; justify-content: space-between; margin-top: 10px;">
				<a class="button button-primary k-close-download-links"ref="javascript:void(0);">Close</a>
				<button class="button button-primary k-save-download-links" type="submit">Save</button>
				<a class="button button-primary k-add-download-link" href="javascript:void(0);">Add</a>
			</div>
		</form>
	<?php 
	exit;
}
add_action( 'wp_ajax_k_edit_download_links', 'k_edit_download_links' );

/**
 * Add download link
 */
function k_add_download_link() {
	?>
	<tr>
		<td><input style="width: 100%;" type="text" name="names[]"></td>
		<td><input style="width: 100%;" type="text" name="versions[]"></td>
		<td><input style="width: 100%;" type="text" name="types[]"></td>
		<td><input style="width: 100%;" type="text" name="sizes[]"></td>
		<td><input style="width: 100%;" type="text" name="links[]"></td>
		<td><input style="width: 100%;" type="text" name="notes[]"></td>
		<td>
			<a class="k-remove-download-link" style="color: #a00;" href="javascript:void(0);">
				<span class="dashicons dashicons-no-alt"></span>
			</a>
		</td>
	</tr>
	<?php 
	exit;
}
add_action( 'wp_ajax_k_add_download_link', 'k_add_download_link' );

/**
 * Save download links
 */
function k_save_download_links() {
	$post_id = $_POST['post_id'];
	$names = $_POST['names'];
	$versions = $_POST['versions'];
	$types = $_POST['types'];
	$sizes = $_POST['sizes'];
	$links = $_POST['links'];
	$notes = $_POST['notes'];

	if ( ! empty($names) || ! empty($versions) || ! empty($types) || ! empty($sizes) || ! empty($links) || ! empty($notes) ) {
		delete_field('downloads', $post_id);
		foreach ( $names as $key => $value ) {
			$row = array(
	        	'download_name'    => $names[$key],
	        	'download_version' => $versions[$key],
	        	'download_type'    => $types[$key],
	        	'download_size'    => $sizes[$key],
	        	'download_link'    => $links[$key],
	        	'download_note'    => $notes[$key]
	        );
	        add_row('downloads', $row, $post_id);
		}
	} else {
		delete_field('downloads', $post_id);
	}

	if ( $links = get_field('downloads', $post_id) ) {
		foreach ( $links as $value ) {
			echo $value['download_name'] . '<br>';
		}
	} else {
		echo '';
	}

	exit;
}
add_action( 'wp_ajax_k_save_download_links', 'k_save_download_links' );

/**
 * Edit text field
 */
function k_edit_text_field() {
	$post_id = $_GET['post_id'];
	$field = $_GET['field'];
	?>
		<form class="k-popover-text-field" method="POST" style="background-color: #fff; border: 1px solid #ccd0d4; width: 400px; padding: 10px; position: absolute; z-index: 10; top: 100%; right: 0;">
			<input type="hidden" name="action" value="k_save_text_field">
			<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
			<input type="hidden" name="field" value="<?php echo $field; ?>">
			<textarea style="width: 100%; height: 80px;" name="text"><?php the_field($field, $post_id); ?></textarea>
			<div style="display: flex; justify-content: space-between; margin-top: 10px;">
				<a class="button button-primary k-close-text-field"ref="javascript:void(0);">Close</a>
				<button class="button button-primary k-save-text-field" type="submit">Save</button>
			</div>
		</form>
	<?php 
	exit;
}
add_action( 'wp_ajax_k_edit_text_field', 'k_edit_text_field' );

/**
 * Save text field
 */
function k_save_text_field() {
	$post_id = $_POST['post_id'];
	$field = $_POST['field'];
	$text = $_POST['text'];
	update_field($field, $text, $post_id);

	echo get_field($field, $post_id);
	echo '<br><a class="k-edit-text-field" href="javascript:void(0);" data-post_id=' . $post_id . ' data-field="' . $field . '"><i class="dashicons dashicons-edit"></i></a>';
	exit;
}
add_action( 'wp_ajax_k_save_text_field', 'k_save_text_field' );