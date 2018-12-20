<?php
extract( $businext_shortcode_atts );

$testimonial_slides_template = '';
$testimonial_thumbs_template = '';
?>

<?php while ( $businext_query->have_posts() ) : $businext_query->the_post(); ?>
	<?php

	$image_url = BUSINEXT_THEME_IMAGE_URI . '/avatar-placeholder.jpg';

	if ( has_post_thumbnail() ):
		$full_image_size = get_the_post_thumbnail_url( null, 'full' );
		$image_url       = Businext_Helper::aq_resize( array(
			'url'    => $full_image_size,
			'width'  => 100,
			'height' => 100,
			'crop'   => true,
		) );
	endif;

	$testimonial_thumbs_template .= '<div class="swiper-slide"><div class="post-thumbnail"><img src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Slide Image', 'businext' ) . '"/></div></div>';

	?>

	<?php ob_start(); ?>

	<div class="swiper-slide">

		<?php $_meta = unserialize( get_post_meta( get_the_ID(), 'insight_testimonial_options', true ) ); ?>
		<div class="testimonial-item">
			<div class="testimonial-desc heading-color"><?php the_content(); ?></div>

			<div class="testimonial-main-info">
				<h6 class="testimonial-name"><?php the_title(); ?></h6>

				<?php if ( isset( $_meta['by_line'] ) ) : ?>
					<div
						class="testimonial-by-line"><?php echo esc_html( $_meta['by_line'] ); ?></div>
				<?php endif; ?>
			</div>

		</div>

	</div>

	<?php
	$testimonial_slides_template .= ob_get_contents();
	ob_end_clean();
	?>

<?php endwhile; ?>


<?php
$slider_classes = 'tm-swiper';

$slider_classes .= " equal-height";

if ( $nav !== '' ) {
	$slider_classes .= " nav-style-$nav";
}

if ( $pagination !== '' ) {
	$slider_classes .= " pagination-style-$pagination";
}
?>

<div class="<?php echo esc_attr( trim( $slider_classes ) ); ?>"
	<?php if ( $carousel_items_display !== '' ) {
		$arr = explode( ';', $carousel_items_display );
		foreach ( $arr as $value ) {
			$tmp = explode( ':', $value );
			echo ' data-' . $tmp[0] . '-items="' . $tmp[1] . '"';
		}
	}
	?>

	<?php if ( $carousel_gutter !== '' ) {
		$arr = explode( ';', $carousel_gutter );
		foreach ( $arr as $value ) {
			$tmp = explode( ':', $value );
			echo ' data-' . $tmp[0] . '-gutter="' . $tmp[1] . '"';
		}
	}
	?>

	<?php if ( $nav !== '' ) : ?>
		data-nav="1"
	<?php endif; ?>

	<?php if ( $nav === 'custom' ) : ?>
		data-custom-nav="<?php echo esc_attr( $slider_button_id ); ?>"
	<?php endif; ?>

	<?php Businext_Helper::get_swiper_pagination_attributes( $pagination ); ?>

	<?php if ( $auto_play !== '' ) : ?>
		data-autoplay="<?php echo esc_attr( $auto_play ); ?>"
	<?php endif; ?>

	<?php if ( $loop === '1' ) : ?>
		data-loop="1"
	<?php endif; ?>

	 data-equal-height="1"

	 data-queue-init="0"
>

	<div class="swiper-container">
		<div class="swiper-wrapper">
			<?php echo "{$testimonial_slides_template}"; ?>
		</div>
	</div>

	<div class="tm-swiper tm-testimonial-pagination equal-height v-center h-center"
	     data-lg-items="3"
	     data-lg-gutter="10"
	     data-centered="1"
	     data-queue-init="0"
	>
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<?php echo "{$testimonial_thumbs_template}"; ?>
			</div>
		</div>
	</div>
</div>
