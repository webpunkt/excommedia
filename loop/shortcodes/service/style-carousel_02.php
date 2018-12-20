<?php
while ( $businext_query->have_posts() ) :
	$businext_query->the_post();
	$classes = array( 'service-item swiper-slide' );
	?>
	<div <?php post_class( implode( ' ', $classes ) ); ?>>
		<div class="post-item-wrap">
			<a href="<?php the_permalink(); ?>">
				<div class="post-thumbnail-wrap">
					<div class="post-thumbnail">
						<?php if ( has_post_thumbnail() ) { ?>
							<?php
							$image_url = get_the_post_thumbnail_url( null, 'full' );

							if ( $image_size !== '' ) {
								$_sizes  = explode( 'x', $image_size );
								$_width  = $_sizes[0];
								$_height = $_sizes[1];

								Businext_Helper::get_lazy_load_image( array(
									'url'    => $image_url,
									'width'  => $_width,
									'height' => $_height,
									'crop'   => true,
									'echo'   => true,
									'alt'    => get_the_title(),
								) );
							}
							?>
						<?php } else { ?>
							<?php Businext_Templates::image_placeholder( 480, 480 ); ?>
						<?php } ?>
					</div>

				</div>

				<div class="post-info">

					<h3 class="post-title">
						<?php the_title(); ?>
					</h3>

					<div class="post-excerpt text-color">
						<?php Businext_Templates::excerpt( array(
							'limit' => 70,
							'type'  => 'character',
						) ); ?>
					</div>

					<div class="post-read-more">
						<span class="btn-text">
							<?php esc_html_e( 'Read More', 'businext' ); ?>
						</span>
						<span class="btn-icon ion-arrow-right-c"></span>
					</div>

				</div>
			</a>
		</div>
	</div>
<?php endwhile;
