<div class="col-12 col-sm-6 col-lg-4 mb-4">
	<a class="embed-responsive embed-responsive-16by9 bg-cover shadow-sm d-block" style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>);" href="<?php the_permalink(); ?>">
		<div class="d-flex align-items-end p-3 position-absolute" style="background-color: rgba(0, 0, 0, 0.5); top: 0; bottom: 0; left: 0; right: 0;">
			<h3 class="h6 font-weight-bold text-white mb-0"><?php the_title(); ?></h3>
		</div>
	</a>
</div>