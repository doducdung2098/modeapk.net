<section class="text-dark my-4 comments-section">
    <?php
    global $post;
    $args = array(
        'status'  => 'approve',
        'post_id' => $post->ID,
        'parent'  => 0,
        'number'  => 10,
    );
    $comments_query = new WP_Comment_Query;
    $comments = $comments_query->query( $args );
    ?>

    <h3 class="h4 font-weight-bold mb-4"><?php _e('Comments', 'mod'); ?> <span class="text-muted"><?php echo ': '. get_comments_number() ?></span></h3>

    <form class="form-comment" method="POST" action="">
        <div class="add-comment p-4 add-comment p-4 border rounded shadow-sm">
            <?php wp_nonce_field('app_comment_nonce', 'app_comment_nonce'); ?>
            <input type="hidden" name="action" value="app_comment">
            <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
            <div class="form-group">
                <textarea class="form-control text-dark" rows="3" name="comment" placeholder="<?php _e('Comment', 'mod'); ?>" required></textarea>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 form-group">
                    <input class="form-control" type="text" name="name" placeholder="<?php _e('Name', 'mod'); ?>" required>
                </div>
                <div class="col-12 col-sm-6 form-group">
                    <input class="form-control" type="text" name="email" placeholder="@<?php _e('Email', 'mod'); ?>" required>
                </div>
            </div>
            <div class="form-group mb-0 d-flex justify-content-end">
                <button class="comment-submit px-3 py-2 btn btn-primary d-flex align-items-center" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send mr-2" viewBox="0 0 16 16">
                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                    </svg>
                    <?php _e('Send', 'mod'); ?>
                </button>
            </div>
        </div>
    </form>

    <?php
    if ( $comments ) :
        ?>

        <div class="my-4 comment-tree">

            <?php foreach ( $comments as $key => $comment ) : ?>
                <div class="comments-tree-item p-3 mb-3 border rounded shadow-sm">

                    <div class="comment">
                        <div class="d-flex justify-content-between align-items-center">

                            <div class="comment-left d-flex">
                                <div class="d-flex mr-2 align-items-center">
                                    <?php echo get_avatar($comment->user_id, 40, '', '', array('class' => 'rounded-circle'))?>
                                </div>

                                <div class="p-2">
                                    <div class="text-break mb-2"><?php echo get_comment_author($comment); ?></div>

                                    <div class="d-flex">
                                        <time class="small text-muted" datetime="<?php echo get_comment_date('', $comment->comment_ID); ?>">
                                            <?php echo get_comment_date('', $comment->comment_ID); ?>
                                        </time>
                                    </div>
                                </div>
                            </div>

                            <div class="comment-right">
                                <a class="reply text-dark text-decoration-none d-flex align-items-center px-2 py-1" href="javascript:void(0)" data-parent="<?php echo $comment->comment_ID; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16">
                                        <path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"/>
                                    </svg>
                                    <span class="ml-1 small"><?php _e('Reply', 'mod'); ?></span>
                                </a>
                            </div>

                        </div>

                        <div class="text-break mt-3"><?php echo app_make_links($comment->comment_content); ?></div>

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
                        <div class="pl-5 comment mt-4 comment-children-list">

                            <?php foreach ( $child_comments as $child_key => $child_comment ) : ?>
                                <div class="d-flex justify-content-between align-items-center mt-3">

                                    <div class="comment-left d-flex">
                                        <div class="d-flex mr-2 align-items-center">
                                            <img class="rounded-circle" src="<?php echo get_avatar_url($child_comment->user_id, array('size' => 40)); ?>" alt="<?php echo $child_comment->comment_author; ?>">
                                        </div>

                                        <div class="p-2">

                                            <div class="text-break mb-2"><?php echo $child_comment->comment_author; ?></div>
                                            <div class="small d-flex">
                                                <time class="text-muted" datetime="<?php echo get_comment_date('', $child_comment->comment_ID); ?>">
                                                    <?php echo get_comment_date('', $child_comment->comment_ID); ?>
                                                </time>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="comment-right">
                                        <a class="reply text-dark text-decoration-none d-flex align-items-center px-2 py-1" href="javascript:void(0)" data-parent="<?php echo $comment->comment_ID; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16">
                                                <path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"/>
                                            </svg>
                                            <span class="ml-1 small"><?php _e('Reply', 'mod'); ?></span>
                                        </a>
                                    </div>

                                </div>

                                <div class="text-break mt-3"><?php echo app_make_links($child_comment->comment_content); ?></div>

                                <?php
                                $sub_args = array(
                                    'status'  => 'approve',
                                    'parent'  => $child_comment->comment_ID,
                                    'post_id' => $post->ID,
                                );
                                $sub_comments_query = new WP_Comment_Query;
                                $sub_child_comments = $sub_comments_query->query( $sub_args );
                                if ( $sub_child_comments ) : ?>
                                    <div class="pl-5 comment mt-4 comment-children-list">
                                        <?php foreach ( $sub_child_comments as $sub_child_key => $sub_child_comment ) : ?>
                                            <div class="d-flex justify-content-between align-items-center mt-3">

                                                <div class="comment-left d-flex">
                                                    <div class="d-flex mr-2 align-items-center">
                                                        <img class="rounded-circle" src="<?php echo get_avatar_url($sub_child_comment->user_id, array('size' => 40)); ?>" alt="<?php echo $sub_child_comment->comment_author; ?>">
                                                    </div>

                                                    <div class="p-2">

                                                        <div class="text-break mb-2"><?php echo $sub_child_comment->comment_author; ?></div>
                                                        <div class="small d-flex">
                                                            <time class="text-muted" datetime="<?php echo get_comment_date('', $sub_child_comment->comment_ID); ?>">
                                                                <?php echo get_comment_date('', $sub_child_comment->comment_ID); ?>
                                                            </time>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-break mt-3"><?php echo app_make_links($sub_child_comment->comment_content); ?></div>

                                        <?php endforeach; ?>
                                    </div>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</section>