<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$style              = $el_class = $order = $overlay_style = $animation = $filter_wrap = $filter_enable = $filter_align = $filter_counter = $pagination_align = $pagination_button_text = '';
$carousel_direction = $carousel_items_display = $carousel_gutter = $carousel_nav = $carousel_pagination = $carousel_auto_play = '';
$justify_row_height = $justify_max_row_height = $justify_last_row_alignment = '';
$gutter             = 0;
$main_query         = '';

$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
$css_id = uniqid( 'tm-portfolio-' );
$this->get_inline_css( "#$css_id", $atts );
extract( $atts );

$businext_post_args = array(
	'post_type'      => 'portfolio',
	'posts_per_page' => $number,
	'orderby'        => $orderby,
	'order'          => $order,
	'paged'          => 1,
	'post_status'    => 'publish',
);

if ( in_array( $orderby, array( 'meta_value', 'meta_value_num' ), true ) ) {
	$businext_post_args['meta_key'] = $meta_key;
}

if ( get_query_var( 'paged' ) ) {
	$businext_post_args['paged'] = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
	$businext_post_args['paged'] = get_query_var( 'page' );
}

$businext_post_args = Businext_VC::get_tax_query_of_taxonomies( $businext_post_args, $taxonomies );

if ( $main_query === '1' ) {
	global $wp_query;
	$businext_query = $wp_query;
} else {
	$businext_query = new WP_Query( $businext_post_args );
}

$el_class = $this->getExtraClass( $el_class );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'tm-portfolio ' . $el_class, $this->settings['base'], $atts );
$css_class .= " style-$style";

$is_swiper = false;
if ( in_array( $style, array( 'carousel', 'full-wide-slider' ), true ) ) {
	$is_swiper = true;
}

if ( $filter_wrap === '1' ) {
	$css_class .= ' filter-wrap';
}

$grid_classes = 'tm-grid';

if ( $is_swiper ) {
	$grid_classes   .= ' swiper-wrapper';
	$slider_classes = 'tm-swiper';
	if ( $carousel_nav !== '' ) {
		$slider_classes .= " nav-style-$carousel_nav";
	}
	if ( $carousel_pagination !== '' ) {
		$slider_classes .= " pagination-style-$carousel_pagination";
	}
}

if ( ! $is_swiper ) {
	$grid_classes .= Businext_Helper::get_grid_animation_classes( $animation );
}

if ( $style === 'justified' ) {
	wp_enqueue_style( 'justifiedGallery' );
	wp_enqueue_script( 'justifiedGallery' );
}
?>
<?php if ( $businext_query->have_posts() ) : ?>
	<div class="tm-grid-wrapper <?php echo esc_attr( trim( $css_class ) ); ?>" id="<?php echo esc_attr( $css_id ); ?>"
		<?php if ( $pagination !== '' && $businext_query->found_posts > $number ) : ?>
			data-pagination="<?php echo esc_attr( $pagination ); ?>"
		<?php endif; ?>

		<?php if ( in_array( $style, array( 'grid', 'metro', 'masonry' ), true ) ) { ?>
			data-type="masonry"
		<?php } elseif ( $is_swiper ) { ?>
			data-type="swiper"
		<?php } elseif ( in_array( $style, array( 'justified' ), true ) ) { ?>
			data-type="justified"
			<?php if ( $justify_row_height !== '' && $justify_row_height > 0 ) { ?>
				data-justified-height="<?php echo esc_attr( $justify_row_height ); ?>"
			<?php } ?>
			<?php if ( $justify_max_row_height !== '' && $justify_max_row_height > 0 ) { ?>
				data-justified-max-height="<?php echo esc_attr( $justify_max_row_height ); ?>"
			<?php } ?>
			<?php if ( $justify_last_row_alignment !== '' ) { ?>
				data-justified-last-row="<?php echo esc_attr( $justify_last_row_alignment ); ?>"
			<?php } ?>
		<?php } ?>

		<?php if ( in_array( $style, array( 'metro' ), true ) ) : ?>
			data-grid-ratio="1:1"
		<?php endif; ?>

		<?php if ( in_array( $style, array( 'grid', 'metro', 'masonry' ), true ) && $columns !== '' ): ?>
			<?php
			$arr = explode( ';', $columns );
			foreach ( $arr as $value ) {
				$tmp = explode( ':', $value );
				echo ' data-' . $tmp[0] . '-columns="' . esc_attr( $tmp[1] ) . '"';
			}
			?>
		<?php endif; ?>

		<?php if ( $gutter !== '' && $gutter !== 0 ) : ?>
			data-gutter="<?php echo esc_attr( $gutter ); ?>"
		<?php endif; ?>
	>
		<?php
		$count = $businext_query->post_count;

		$tm_grid_query                  = $businext_post_args;
		$tm_grid_query['action']        = 'portfolio_infinite_load';
		$tm_grid_query['max_num_pages'] = $businext_query->max_num_pages;
		$tm_grid_query['found_posts']   = $businext_query->found_posts;
		$tm_grid_query['taxonomies']    = $taxonomies;
		$tm_grid_query['style']         = $style;
		$tm_grid_query['overlay_style'] = $overlay_style;
		$tm_grid_query['pagination']    = $pagination;
		$tm_grid_query['count']         = $count;
		$tm_grid_query                  = htmlspecialchars( wp_json_encode( $tm_grid_query ) );
		?>

		<?php Businext_Templates::grid_filters( 'portfolio', $filter_enable, $filter_align, $filter_counter, $filter_wrap ); ?>

		<input type="hidden" class="tm-grid-query" <?php echo 'value="' . $tm_grid_query . '"'; ?>/>

		<?php if ( $is_swiper ) { ?>
		<div class="<?php echo esc_attr( $slider_classes ); ?>"
			<?php if ( $style === 'full-wide-slider' ) { ?>
				data-lg-items="1"
				data-effect="fade"
				data-loop="1"
			<?php } else { ?>
				<?php
				if ( $carousel_items_display !== '' ) {
					$arr = explode( ';', $carousel_items_display );
					foreach ( $arr as $value ) {
						$tmp = explode( ':', $value );
						echo ' data-' . $tmp[0] . '-items="' . $tmp[1] . '"';
					}
				}
				?>
			<?php } ?>

			<?php if ( $carousel_gutter > 1 ) : ?>
				data-lg-gutter="<?php echo esc_attr( $carousel_gutter ); ?>"
			<?php endif; ?>

			<?php if ( $carousel_nav !== '' ) : ?>
				data-nav="1"
			<?php endif; ?>

			<?php if ( $carousel_nav === 'custom' ) : ?>
				data-custom-nav="<?php echo esc_attr( $slider_button_id ); ?>"
			<?php endif; ?>

			<?php Businext_Helper::get_swiper_pagination_attributes( $carousel_pagination ); ?>

			<?php if ( $carousel_auto_play !== '' ) : ?>
				data-autoplay="<?php echo esc_attr( $carousel_auto_play ); ?>"
			<?php endif; ?>
		>
			<div class="swiper-container">
				<?php } ?>

				<div class="<?php echo esc_attr( $grid_classes ); ?>"
					<?php if ( ! in_array( $style, array( 'full-wide-slider' ) ) ) : ?>
						data-overlay-animation="<?php echo esc_attr( $overlay_style ); ?>"
					<?php endif; ?>
				>
					<?php if ( in_array( $style, array( 'grid', 'metro', 'masonry' ), true ) ): ?>
						<div class="grid-sizer"></div>
					<?php endif; ?>

					<?php if ( $style === 'grid' ) { ?>
						<?php
						while ( $businext_query->have_posts() ) :
							$businext_query->the_post();
							$classes = array( 'portfolio-item grid-item' );
							?>
							<div <?php post_class( implode( ' ', $classes ) ); ?>>
								<div class="post-item-wrapper">
									<div class="post-thumbnail-wrapper">
										<a href="<?php the_permalink(); ?>">
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
										</a>
										<?php if ( $overlay_style !== '' ) : ?>
											<?php get_template_part( 'loop/portfolio/overlay', $overlay_style ); ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					<?php } elseif ( $style === 'metro' ) {

						if ( $metro_layout ) {
							$metro_layout = (array) vc_param_group_parse_atts( $metro_layout );
							$_sizes       = array();
							foreach ( $metro_layout as $key => $value ) {
								$_sizes[] = $value['size'];
							}
							$metro_layout = $_sizes;
						} else {
							$metro_layout = array(
								'1:1',
								'2:2',
								'1:2',
								'1:1',
								'1:1',
								'2:1',
								'1:1',
							);
						}

						if ( count( $metro_layout ) < 1 ) {
							return;
						}

						$metro_layout_count = count( $metro_layout );
						$metro_item_count   = 0;

						while ( $businext_query->have_posts() ) :
							$businext_query->the_post();

							$classes   = array( 'portfolio-item grid-item' );
							$classes[] = $metro_layout[ $metro_item_count ];

							$_image_width  = 480;
							$_image_height = 480;
							if ( $metro_layout[ $metro_item_count ] === '2:1' ) {
								$_image_width  = 960;
								$_image_height = 480;
							} elseif ( $metro_layout[ $metro_item_count ] === '1:2' ) {
								$_image_width  = 480;
								$_image_height = 960;
							} elseif ( $metro_layout[ $metro_item_count ] === '2:2' ) {
								$_image_width  = 960;
								$_image_height = 960;
							}
							?>
							<div <?php post_class( implode( ' ', $classes ) ); ?>
								<?php if ( in_array( $metro_layout[ $metro_item_count ], array(
									'2:1',
									'2:2',
								), true ) ) : ?>
									data-width="2"
								<?php endif; ?>
								<?php if ( in_array( $metro_layout[ $metro_item_count ], array(
									'1:2',
									'2:2',
								), true ) ) : ?>
									data-height="2"
								<?php endif; ?>
							>
								<div class="post-item-wrapper">
									<div class="post-thumbnail">
										<?php
										if ( has_post_thumbnail() ) { ?>
											<?php
											$full_image_size = get_the_post_thumbnail_url( null, 'full' );
											Businext_Helper::get_lazy_load_image( array(
												'url'     => $full_image_size,
												'width'   => $_image_width,
												'height'  => $_image_height,
												'crop'    => true,
												'upscale' => true,
												'echo'    => true,
												'alt'     => get_the_title(),
											) );
											?>
											<?php
										} else {
											Businext_Templates::image_placeholder( $_image_width, $_image_height );
										}
										?>
										<?php if ( $overlay_style !== '' ) : ?>
											<?php get_template_part( 'loop/portfolio/overlay', $overlay_style ); ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<?php
							$metro_item_count ++;
							if ( $metro_item_count == $count || $metro_layout_count == $metro_item_count ) {
								$metro_item_count = 0;
							}
							?>
						<?php endwhile; ?>
					<?php } elseif ( $style === 'masonry' ) { ?>
						<?php
						while ( $businext_query->have_posts() ) :
							$businext_query->the_post();

							$classes = array( 'portfolio-item grid-item' );
							?>
							<div <?php post_class( implode( ' ', $classes ) ); ?>>
								<div class="post-item-wrapper">
									<a href="<?php the_permalink(); ?>">
										<div class="post-thumbnail">
											<?php
											if ( has_post_thumbnail() ) { ?>
												<?php
												$full_image_size = get_the_post_thumbnail_url( null, 'full' );
												Businext_Helper::get_lazy_load_image( array(
													'url'    => $full_image_size,
													'width'  => 480,
													'height' => 9999,
													'crop'   => false,
													'echo'   => true,
													'alt'    => get_the_title(),
												) );
												?>
											<?php } else {
												Businext_Templates::image_placeholder( 480, 480 );
											}
											?>
										</div>
									</a>

									<?php if ( $overlay_style !== '' ) : ?>
										<?php get_template_part( 'loop/portfolio/overlay', $overlay_style ); ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endwhile; ?>
					<?php } elseif ( $style === 'carousel' ) { ?>
						<?php
						while ( $businext_query->have_posts() ) :
							$businext_query->the_post();
							$classes = array( 'portfolio-item grid-item swiper-slide' );
							?>
							<div <?php post_class( implode( ' ', $classes ) ); ?>>
								<div class="post-item-wrapper">
									<div class="post-thumbnail-wrapper">
										<div class="post-thumbnail">
											<a href="<?php the_permalink(); ?>">
												<?php
												if ( has_post_thumbnail() ) { ?>
													<?php
													$full_image_size = get_the_post_thumbnail_url( null, 'full' );
													Businext_Helper::get_lazy_load_image( array(
														'url'    => $full_image_size,
														'width'  => 370,
														'height' => 560,
														'crop'   => true,
														'echo'   => true,
														'alt'    => get_the_title(),
													) );
													?>
												<?php } else {
													Businext_Templates::image_placeholder( 370, 560 );
												}
												?>
											</a>
										</div>
										<?php if ( $overlay_style !== '' ) : ?>
											<?php get_template_part( 'loop/portfolio/overlay', $overlay_style ); ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					<?php } elseif ( $style === 'full-wide-slider' ) { ?>
						<?php
						while ( $businext_query->have_posts() ) :
							$businext_query->the_post();
							$classes = array( 'portfolio-item grid-item swiper-slide' );
							?>
							<div <?php post_class( implode( ' ', $classes ) ); ?>>
								<div class="post-item-wrapper">
									<div class="post-thumbnail">
										<?php
										if ( has_post_thumbnail() ) {
											$full_image_size = get_the_post_thumbnail_url( null, 'full' );
											$image_url       = Businext_Helper::aq_resize( array(
												'url'     => $full_image_size,
												'width'   => 1920,
												'height'  => 700,
												'crop'    => true,
												'upscale' => true,
											) );

											$small_url = Businext_Helper::aq_resize( array(
												'url'     => $full_image_size,
												'width'   => 640,
												'height'  => 700,
												'crop'    => true,
												'upscale' => true,
											) );
											?>
											<img srcset="<?php echo esc_url( $image_url ); ?> 1920w,
														<?php echo esc_url( $small_url ); ?> 640w"
											     src="<?php echo esc_url( $image_url ); ?>"
											     alt="<?php get_the_title(); ?>"/>
										<?php } else {
											Businext_Templates::image_placeholder( 1920, 700 );
										}
										?>
										<div class="post-overlay-info">
											<div class="post-overlay-categories">
												<?php echo get_the_term_list( get_the_ID(), 'portfolio_category', '', ', ', '' ); ?>
											</div>
											<h5 class="post-overlay-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h5>
											<div class="post-overlay-icon">
												<a href="<?php the_permalink(); ?>"><span class="ion-plus"></span></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					<?php } elseif ( $style === 'justified' ) { ?>
						<?php
						while ( $businext_query->have_posts() ) :
							$businext_query->the_post();
							$classes = array( 'portfolio-item grid-item' );
							?>
							<div <?php post_class( implode( ' ', $classes ) ); ?>>

								<a href="<?php the_permalink(); ?>">
									<?php
									if ( has_post_thumbnail() ) {
										Businext_Templates::image_placeholder( 600, 600 );
									}
									?>
								</a>
								<div class="post-thumbnail">
									<?php if ( $overlay_style !== '' ) : ?>
										<?php get_template_part( 'loop/portfolio/overlay', $overlay_style ); ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endwhile; ?>
					<?php } ?>

				</div>

				<?php if ( $is_swiper ) { ?>
			</div>
		</div>
	<?php } ?>

		<?php Businext_Templates::grid_pagination( $businext_query, $number, $pagination, $pagination_align, $pagination_button_text ); ?>

	</div>
<?php endif; ?>
<?php wp_reset_postdata();
