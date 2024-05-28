<div class="mb-4">
	<a class="d-flex position-relative archive-post" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<div class="flex-shrink-0 mr-2" style="width: 4rem;">
			<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'rounded-lg' ) ); ?>
		</div>
		<h3 class="font-size-body font-weight-normal text-body mb-0">
			<?php the_title(); ?>		
		</h3>
	</a>
</div>