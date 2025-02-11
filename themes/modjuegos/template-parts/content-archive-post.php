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
                    <svg class="svg-6 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path d="M567.938 243.908L462.25 85.374A48.003 48.003 0 0 0 422.311 64H153.689a48 48 0 0 0-39.938 21.374L8.062 243.908A47.994 47.994 0 0 0 0 270.533V400c0 26.51 21.49 48 48 48h480c26.51 0 48-21.49 48-48V270.533a47.994 47.994 0 0 0-8.062-26.625zM162.252 128h251.497l85.333 128H376l-32 64H232l-32-64H76.918l85.334-128z"></path>
                    </svg>
                    <span class="align-middle"><?php the_field('_softwareVersion') ?></span>
                    <span class="align-middle"> + </span>
                    <span class="align-middle"><?php the_field('_apkfilesize') ?></span>
                </div>

                <div class="small text-truncate text-muted">
                    <svg class="svg-6 svg-secondary mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M501.1 395.7L384 278.6c-23.1-23.1-57.6-27.6-85.4-13.9L192 158.1V96L64 0 0 64l96 128h62.1l106.6 106.6c-13.6 27.8-9.2 62.3 13.9 85.4l117.1 117.1c14.6 14.6 38.2 14.6 52.7 0l52.7-52.7c14.5-14.6 14.5-38.2 0-52.7zM331.7 225c28.3 0 54.9 11 74.9 31l19.4 19.4c15.8-6.9 30.8-16.5 43.8-29.5 37.1-37.1 49.7-89.3 37.9-136.7-2.2-9-13.5-12.1-20.1-5.5l-74.4 74.4-67.9-11.3L334 98.9l74.4-74.4c6.6-6.6 3.4-17.9-5.7-20.2-47.4-11.7-99.6.9-136.6 37.9-28.5 28.5-41.9 66.1-41.2 103.6l82.1 82.1c8.1-1.9 16.5-2.9 24.7-2.9zm-103.9 82l-56.7-56.7L18.7 402.8c-25 25-25 65.5 0 90.5s65.5 25 90.5 0l123.6-123.6c-7.6-19.9-9.9-41.6-5-62.7zM64 472c-13.2 0-24-10.8-24-24 0-13.3 10.7-24 24-24s24 10.7 24 24c0 13.2-10.7 24-24 24z"></path>
                    </svg>
                    <span class="align-middle">
                        <?php
                            if($mod = get_field('mod_info') ) echo $mod;
                            else echo  __('Original', 'mod');
                        ?>
                    </span>
                </div>

            </div>
        </div>
    </a>
</div>