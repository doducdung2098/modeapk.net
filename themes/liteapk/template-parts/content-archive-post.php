<div class="col-12 col-md-6 col-xl-4 mb-3">
    <a class="text-body bg-white border rounded shadow-sm overflow-hidden d-block h-100 position-relative archive-post"
       href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <div class="d-flex" style="padding: 0.75rem;">
            <div class="flex-shrink-0" style="width: 4rem; margin-right: 0.75rem;">
                <?php the_post_thumbnail('thumbnail', array('class' => 'rounded-lg')); ?>
            </div>
            <div style="min-width: 0;">
                <h3 class="h6 font-weight-semibold text-truncate w-100 mb-1">
                    <?php the_title(); ?>
                </h3>
                <div class="small text-truncate text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightning-charge svg-secondary mr-1" viewBox="0 0 16 16">
                        <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.732-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41z"/>
                    </svg>
                    <span class="align-middle"><?php the_field('_softwareVersion') ?></span>
                    <span class="align-middle"> + </span>
                    <span class="align-middle"><?php the_field('_apkfilesize') ?></span>
                </div>

                <div class="small text-truncate text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-code-slash svg-secondary mr-1" viewBox="0 0 16 16">
                        <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294l4-13zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0zm6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0z"/>
                    </svg>
                    <span class="align-middle">
                        <?php
                            if($mod = get_field('mod_info') ) echo $mod;
                            else _e('Original', 'mod');
                        ?>
                    </span>
                </div>

            </div>
        </div>
    </a>
</div>