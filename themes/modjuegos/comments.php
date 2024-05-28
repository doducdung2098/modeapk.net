<section class="bg-white border rounded shadow-sm pt-3 px-2 px-md-3 mb-3 mx-auto" style="max-width: 880px;">
	<?php
	$args = array(
		'status'  => 'approve',
		'post_id' => $post->ID, 
		'parent'  => 0,
		'number'  => 10,
	);
	$comments_query = new WP_Comment_Query;
	$comments = $comments_query->query( $args );
	if ( $comments ) : 
	?>
	<h2 class="font-weight-bold mb-4"><?php _e('Comments', 'mod'); ?></h2>
		<div>

			<?php foreach ( $comments as $key => $comment ) : ?>
				<div class="d-flex mb-3 comment">
					<div class="text-center flex-shrink-0 mr-2">
						<?php echo get_avatar($comment->user_id, 40, '', '', array('class' => 'rounded-circle'))?>
					</div>
					<div class="bg-light flex-grow-1 p-2">
						<div class="h6 text-break mb-1"><?php echo get_comment_author($comment); ?></div>
						<div class="small text-break mb-1"><?php echo k_make_links($comment->comment_content); ?></div>
						<div class="small d-flex">
							<a class="reply" href="javascript:void(0)" data-parent="<?php echo $comment->comment_ID; ?>">
								<span><?php _e('Reply', 'mod'); ?></span>
							</a>
							<span class="mx-2">-</span>
							<time class="text-muted" datetime="<?php echo get_comment_date('', $comment->comment_ID); ?>">
								<?php echo get_comment_date('', $comment->comment_ID); ?>		
							</time>
						</div>
					</div>
				</div>

				<?php
				$args = array(
					'status'  => 'approve',
					'parent'  => $comment->comment_ID,
					'post_id' => $post->ID, 
				);
				$comments_query = new WP_Comment_Query;
				$child_comments = $comments_query->query( $args );
				if ( $child_comments ) : ?>
					<div class="pl-5">

						<?php foreach ( $child_comments as $child_comment ) : ?>
							<div class="d-flex mb-3 comment">
								<div class="text-center flex-shrink-0 mr-2">
									<img class="rounded-circle" src="<?php echo get_avatar_url($child_comment->user_id, array('size' => 40)); ?>" alt="<?php echo $child_comment->comment_author; ?>">
								</div>
								<div class="bg-light flex-grow-1 p-2">
									<div class="h6 text-break mb-1"><?php echo $child_comment->comment_author; ?></div>
									<div class="small text-break mb-1"><?php echo k_make_links($child_comment->comment_content); ?></div>
									<div class="small d-flex">
										<a class="reply" href="javascript:void(0)" data-parent="<?php echo $child_comment->comment_ID; ?>">
											<span><?php _e('Reply', 'mod'); ?></span>
										</a>
										<span class="mx-2">-</span>
										<time class="text-muted" datetime="<?php echo get_comment_date('', $child_comment->comment_ID); ?>">
											<?php echo get_comment_date('', $child_comment->comment_ID); ?>		
										</time>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

					</div>
				<?php endif; ?>

			<?php endforeach; ?>

		</div>
	<?php endif; ?>

	<form class="form-comment" method="POST" action="">
		<h2 class="h4 font-weight-bold mb-3"><?php _e('Leave a Comment', 'mod'); ?></h2>
		<input type="hidden" name="action" value="site_comment">
		<input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
		<div class="form-group">
			<textarea class="form-control" rows="3" name="comment" placeholder="<?php _e('Comment', 'mod'); ?>"></textarea>
		</div>
		<div class="row">
			<div class="col-12 col-sm-6 form-group">
				<input class="form-control" type="text" name="name" placeholder="<?php _e('Name', 'mod'); ?>">
			</div>
			<div class="col-12 col-sm-6 form-group">
				<input class="form-control" type="text" name="email" placeholder="<?php _e('Email', 'mod'); ?>">
			</div>
		</div>
		<div class="form-group text-right">
			<button class="btn btn-primary" type="submit"><?php _e('Send Comment', 'mod'); ?></button>
		</div>
	</form>
</section>