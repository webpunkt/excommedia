<div class="post-overlay"></div>
<div class="post-overlay-content">
	<a href="<?php the_permalink(); ?>">
		<div class="post-overlay-icon"></div>
	</a>
	<div class="post-overlay-info">
		<div class="post-overlay-categories">
			<?php echo get_the_term_list( get_the_ID(), 'portfolio_category', '', ', ', '' ); ?>
		</div>
		<h5 class="post-overlay-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h5>
	</div>
</div>
