<?php
/**
 * The header for blank template.
 *
 * @link     https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Businext
 * @since    1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php Businext_Templates::pre_loader(); ?>

<div id="page" class="site">
	<div class="content-wrapper">
