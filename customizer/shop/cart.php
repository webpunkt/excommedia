<?php
$section  = 'shopping_cart';
$priority = 1;
$prefix   = 'shopping_cart_';

Businext_Kirki::add_field( 'theme', array(
	'type'        => 'radio-buttonset',
	'settings'    => $prefix . 'cross_sells_enable',
	'label'       => esc_html__( 'Cross-sells products', 'businext' ),
	'description' => esc_html__( 'Turn on to display the cross-sells products section. This is helpful if you have dozens of products with cross-sells and you don\'t want to go and edit each single page.', 'businext' ),
	'section'     => $section,
	'priority'    => $priority ++,
	'default'     => '1',
	'choices'     => array(
		'0' => esc_html__( 'Off', 'businext' ),
		'1' => esc_html__( 'On', 'businext' ),
	),
) );
