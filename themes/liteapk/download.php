<?php
/**
 * The template for displaying download.
 *
 * @package mod
 */

$download_slug = $wp_query->query_vars['download'];
$download_slugs = explode('/', $download_slug);
preg_match('/-([0-9]+)$/', $download_slugs[0], $matches);
$post_id = $matches[1];

if (!wp_is_mobile() && get_field('hide_on_pc', $post_id)) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part(404);
    exit();
}

$versions = get_field('versions', $post_id);

$mod_info = get_field('mod_info', $post_id);
$_softwareVersion = get_field('_softwareVersion', $post_id);

$cats = wp_get_post_terms($post_id, 'category', array('fields' => 'all'));
if (!is_wp_error($cats) && !empty($cats)) $cat = $cats[0];

get_header();
?>
    <div class="container py-4">
        <div class="row">
            <main id="primary" class="content-area col-12 col-lg-10 mx-auto">
                <?php if (empty($download_slugs[1])) : ?>
                    <article class="mb-4">

                        <div class="d-flex align-items-center mb-3 bg-white border rounded-lg shadow-sm p-3">
                            <div class="flex-shrink-0 mr-3" style="width: 4.5rem;">
                                <img class="rounded-lg" style="width: 200px;" loading="lazy"
                                     src="<?php echo get_the_post_thumbnail_url($post_id); ?>"
                                     alt="<?php echo get_the_title($post_id); ?>">
                            </div>
                            <div>
                                <h1 class="h5 font-weight-semibold">
                                    <?php
                                    if ($title = get_post_meta($post_id, '_yoast_wpseo_title', true))
                                        echo __('Download', 'mod') . ' ' . $title;
                                    else {
                                        echo __('Download', 'mod') . ' ' . get_the_title($post_id) . ' v' . $_softwareVersion;
                                        if ($mod_info) echo ' MOD APK (' . $mod_info . ')';
                                        else ' APK';
                                    }
                                    ?>
                                </h1>
                                <time class="text-muted d-block">
                                    <em>
                                        <?php echo get_the_modified_date('', $post_id); ?>
                                        <?php echo '(' . esc_html(human_time_diff(get_the_modified_time('U', $post_id), current_time('timestamp'))) . ' ' . __('ago', 'mod') . ')'; ?>
                                    </em>
                                </time>
                            </div>
                        </div>

                        <?php
                        if ($intro = get_field('page_download_intro', 'options')) :
                            $intro = str_replace('[title]', get_field('app_name', $post_id), $intro);
                            ?>
                            <div class="text-muted mb-3 entry-content"><?php echo $intro; ?></div>
                        <?php endif; ?>

                        <?php if ($versions) :  $key = 0; ?>
                            <?php the_ads(); ?>

                            <div id="accordion-versions" class="accordion mb-3">
                                <?php foreach ($versions as $version_key => $version) : ?>
                                    <div class="border rounded mb-2">
                                        <a class="font-weight-semibold rounded d-flex align-items-center py-2 px-3 m-0 toggler collapsed"
                                           data-toggle="collapse" href="#version-<?php echo $version_key + 1; ?>">
                                            <?php echo $version['version']; ?>
                                        </a>
                                        <div id="version-<?php echo $version_key + 1; ?>" class="collapse" data-parent="#accordion-versions">
                                            <?php if ($version['version_note']) : ?>
                                                <div class="small p-3">
                                                    <?php echo nl2br($version['version_note']); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="p-3">
                                                <?php foreach ($version['version_downloads'] as $value) : $key++; ?>
                                                    <a class="btn btn-light btn-sm btn-block text-left d-flex align-items-center px-3"
                                                       style="min-height: 3.25rem;"
                                                       href="<?php echo home_url('download/' . $download_slug . '/' . $key); ?>"
                                                       target="_blank">
                                                        <svg class="svg-5 svg-primary mr-2"
                                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                            <path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/>
                                                        </svg>
                                                        <span class="d-block">
															<span class="text-uppercase d-block"><?php echo __('Download', 'mod') . ' ' . $value['version_download_type']; ?></span>
															<span class="text-muted d-block"><?php echo $value['version_download_note']; ?></span>
														</span>
                                                        <span class="text-muted d-block ml-auto"><?php echo $value['version_download_size']; ?></span>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php the_ads(); ?>

                            <?php
                            if ($note = get_field('page_download_note', 'options')) :
                                $note = str_replace('[title]', get_field('app_name', $post_id), $note);
                                ?>
                                <div class="small mb-3"><?php echo $note; ?></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php
                        $tags = get_the_tags($post_id);
                        if (!empty($tags)) :
                            ?>
                            <div class="d-flex flex-wrap mb-2">
                                <?php foreach ($tags as $tag) : ?>
                                    <a class="btn btn-light btn-sm mr-2 mb-2"
                                       href="<?php echo get_term_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </article>

                    <?php
                    $term_ids = wp_get_post_terms($post_id, 'publisher', array('fields' => 'ids'));
                    $args = array(
                        'posts_per_page' => 8,
                        'post__not_in' => array($post_id),
                        'no_found_rows' => true,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'publisher',
                                'field' => 'term_id',
                                'terms' => $term_ids
                            )
                        )
                    );
                    $query = new WP_Query($args);
                    if ($query->have_posts()) :
                        ?>
                        <section class="related-posts">
                            <h3 class="h4 mb-3">
                                <?php _e('More from Publisher', 'mod'); ?>
                            </h3>
                            <div class="row">
                                <?php
                                while ($query->have_posts()) : $query->the_post();
                                    get_template_part('template-parts/content', 'archive-post');
                                endwhile;
                                wp_reset_postdata();
                                ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php comments_template(); ?>


                <?php
                else :
                    $download_key = $download_slugs[1];
                    $other_link = false;
                    if ($versions) {
                        $key = 0;
                        foreach ($versions as $version_key => $version) {
                            foreach ($version['version_downloads'] as $value) {
                                $key++;
                                if ($download_key == $key) {
                                    $download_url = $value['version_download_link'];
                                    $download_size = $value['version_download_size'];
                                    $download_note = $value['version_download_note'];
                                    $subtitle = get_field('page_download_subtitle', 'options');
                                    $subtitle = str_replace('[name]', get_the_title($post_id), $subtitle);
                                    $subtitle = str_replace('[note]', $download_note, $subtitle);
                                    $subtitle = str_replace('[version]', $versions[$version_key]['version'], $subtitle);
                                    $tutorial = get_field('page_download_tutorial', 'options');
                                } else {
                                    $other_link = true;
                                }
                            }
                        }
                    }
                    ?>
                    <section class="mb-4">

                        <div class="d-flex align-items-center mb-3 bg-white border rounded-lg shadow-sm p-3">
                            <div class="flex-shrink-0 mr-3" style="width: 4.5rem;">
                                <img class="rounded-lg" style="width: 200px;" loading="lazy"
                                     src="<?php echo get_the_post_thumbnail_url($post_id); ?>"
                                     alt="<?php echo get_the_title($post_id); ?>">
                            </div>
                            <div>
                                <h1 class="h5 font-weight-semibold">
                                    <?php
                                    if ($title = get_post_meta($post_id, '_yoast_wpseo_title', true))
                                        echo __('Download', 'mod') . ' ' . $title;
                                    else {
                                        echo __('Download', 'mod') . ' ' . get_the_title($post_id) . ' v' . $_softwareVersion;
                                        if ($mod_info)
                                            echo ' MOD APK (' . $mod_info . ')';
                                        else ' APK';
                                    }
                                    ?>
                                </h1>
                                <time class="text-muted d-block">
                                    <em>
                                        <?php echo get_the_modified_date('', $post_id); ?>
                                        <?php echo '(' . esc_html(human_time_diff(get_the_modified_time('U', $post_id), current_time('timestamp'))) . ' ' . __('ago', 'mod') . ')'; ?>
                                    </em>
                                </time>
                            </div>
                        </div>

                        <?php if (!empty($subtitle) || !empty($tutorial)) : ?>
                            <p class="text-muted">
                                <span style="color: #f00;">*</span> <?php echo $subtitle; ?>
                                <br>
                                <span style="color: #f00;">*</span> <?php echo $tutorial; ?>
                            </p>
                        <?php endif; ?>

                        <?php the_ads(); ?>

                        <?php
                        if (!empty($download_url)) : ?>

                            <div class="mb-2 blockquote-footer">
                                <span> <i> <?php _e('If the file extension is in formats like: APKs or XAPK, please use a third-party application to install it (we recommend the SAI - Split APKs Installer app because they are easy to use).', 'mod') ?> </span> </i>
                            </div>

                            <?php if ($download_note) : ?>
                                <p class="text-muted">
                                    <span style="color: #f00;">*</span> <?php echo $download_note ?>
                                </p>
                            <?php endif; ?>

                            <p class="text-center text-muted mb-2"><?php _e('Your link is almost ready, please wait a few seconds...', 'mod'); ?></p>

                            <div id="download-loading" class="my-3">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                                         style="width: 100%"></div>
                                </div>
                            </div>

                            <div id="download" class="text-center mb-3 d-none">

                                <a class="btn btn-primary px-5 download" href="<?php echo $download_url; ?>" download>
                                    <svg class="svg-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/>
                                    </svg>
                                    <span class="align-middle">
									<?php echo __('Download', 'mod'); ?>
									(<?php echo $download_size; ?>)	
								</span>
                                </a>

                            </div>

                            <p class="text-center mb-0">
                                <?php _e('If the download doesn\'t start in a few seconds,', 'mod'); ?>
                                <a id="click-here" href="<?php echo $download_url; ?>" download>
                                    <?php _e('click here', 'mod'); ?>
                                </a>
                            </p>
                        <?php endif; ?>


                        <?php the_ads(); ?>

                        <?php if ($telegram = get_field('telegram', 'options')) : ?>
                            <div class="text-center mb-3">
                                <a class="btn btn-info rounded-pill" href="<?php echo $telegram['url']; ?>"
                                   target="<?php echo $telegram['target']; ?>" rel="nofollow">
                                    <svg class="svg-5 mr-1" fill="#fff" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 448 512">
                                        <path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"/>
                                    </svg>
                                    <?php echo $telegram['title']; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </section>

                    <?php if ($other_link) : ?>
                    <section class="mb-4">
                        <h3 class="h4 mb-3"><?php _e('Other available link(s)', 'mod'); ?></h3>

                        <?php if ($versions) : $key = 0; ?>
                            <div id="accordion-versions" class="accordion mb-3">
                                <?php foreach ($versions as $version_key => $version) : ?>
                                    <div class="border rounded mb-2">
                                        <a class="h6 font-weight-semibold rounded d-flex align-items-center py-2 px-3 mb-0 <?php if ($version_key > 0) : ?>collapsed<?php endif; ?> toggler"
                                           data-toggle="collapse" href="#version-<?php echo $version_key + 1; ?>">
                                            <?php echo $version['version']; ?>
                                        </a>
                                        <div id="version-<?php echo $version_key + 1; ?>"
                                             class="collapse <?php if ($version_key == 0) : ?>show<?php endif; ?>"
                                             data-parent="#accordion-versions">
                                            <?php if ($version['version_note']) : ?>
                                                <div class="small bg-light p-3">
                                                    <?php echo nl2br($version['version_note']); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="p-3">
                                                <?php foreach ($version['version_downloads'] as $value) : $key++;
                                                    if ($download_key == $key) continue; ?>
                                                    <a class="btn btn-light btn-sm btn-block text-left d-flex align-items-center px-3"
                                                       style="min-height: 3.25rem;"
                                                       href="<?php echo home_url('download/' . $download_slugs[0] . '/' . $key); ?>"
                                                       target="_blank">
                                                        <svg class="svg-5 svg-primary mr-2"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 576 512">
                                                            <path d="M528 288h-92.1l46.1-46.1c30.1-30.1 8.8-81.9-33.9-81.9h-64V48c0-26.5-21.5-48-48-48h-96c-26.5 0-48 21.5-48 48v112h-64c-42.6 0-64.2 51.7-33.9 81.9l46.1 46.1H48c-26.5 0-48 21.5-48 48v128c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48zm-400-80h112V48h96v160h112L288 368 128 208zm400 256H48V336h140.1l65.9 65.9c18.8 18.8 49.1 18.7 67.9 0l65.9-65.9H528v128zm-88-64c0-13.3 10.7-24 24-24s24 10.7 24 24-10.7 24-24 24-24-10.7-24-24z"/>
                                                        </svg>
                                                        <span class="d-block">
                                                            <span class="text-uppercase d-block"><?php echo __('Download', 'mod') . ' ' . $value['version_download_type']; ?></span>
                                                            <span class="text-muted d-block"><?php echo $value['version_download_note']; ?></span>
                                                        </span>
                                                        <span class="text-muted d-block ml-auto"><?php echo $value['version_download_size']; ?></span>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </section>
                <?php endif; ?>

                    <?php if ($faqs = get_field('page_download_faqs', 'options')) : ?>
                    <section class="mb-4">
                        <h3 class="h4 mb-3"><?php _e('Download FAQs', 'mod'); ?></h3>
                        <div id="accordion-faqs" class="accordion accordion-faqs">
                            <?php foreach ($faqs as $key => $value) : ?>
                                <div class="border rounded mb-2">
                                    <a class="h6 font-weight-semibold rounded d-flex align-items-center py-2 px-3 mb-0 collapsed toggler"
                                       data-toggle="collapse" href="#faq-<?php echo $key + 1; ?>">
                                        <?php echo $value['page_download_faq_question']; ?>
                                    </a>
                                    <div id="faq-<?php echo $key + 1; ?>" class="collapse"
                                         data-parent="#accordion-faqs">
                                        <div class="p-3">
                                            <?php echo $value['page_download_faq_answer']; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                    <?php
                    $term_ids = wp_get_post_terms($post_id, 'publisher', array('fields' => 'ids'));
                    $args = array(
                        'posts_per_page' => 8,
                        'post__not_in' => array($post_id),
                        'no_found_rows' => true,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'publisher',
                                'field' => 'term_id',
                                'terms' => $term_ids
                            )
                        )
                    );
                    $query = new WP_Query($args);
                    if ($query->have_posts()) :
                        ?>
                        <section class="related-posts">
                            <h3 class="h4 mb-3">
                                <?php _e('More from Publisher', 'mod'); ?>
                            </h3>
                            <div class="row">
                                <?php
                                while ($query->have_posts()) : $query->the_post();
                                    get_template_part('template-parts/content', 'archive-post');
                                endwhile;
                                wp_reset_postdata();
                                ?>
                            </div>
                        </section>
                    <?php endif; ?>

                <?php endif; ?>
            </main>
        </div>
    </div>
<?php
get_footer();