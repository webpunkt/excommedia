<?php
/**
 * The home latest posts template.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Businext
 * @since   1.0
 */
get_header();
?>
<?php Businext_Templates::title_bar(); ?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Businext_Templates::render_sidebar( 'left' ); ?>

				<div class="page-main-content">
					<?php get_template_part( 'components/content', 'blog' ); ?>
				</div>

				<?php Businext_Templates::render_sidebar( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
