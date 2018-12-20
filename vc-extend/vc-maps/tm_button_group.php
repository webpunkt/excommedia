<?php

class WPBakeryShortCode_TM_Button_Group extends WPBakeryShortCodesContainer {

	public function get_inline_css( $selector = '', $atts ) {
		global $businext_shortcode_lg_css;
		global $businext_shortcode_md_css;
		global $businext_shortcode_sm_css;
		global $businext_shortcode_xs_css;
		$tmp = '';

		if ( $atts['gutter'] !== '' ) {
			$_gutter                  = $atts['gutter'] / 2;
			$tmp                      .= "margin: -{$_gutter}px;";
			$businext_shortcode_lg_css .= "$selector .tm-button-wrapper { padding: {$_gutter}px; }";
		}

		if ( $tmp !== '' ) {
			$businext_shortcode_lg_css .= "$selector { $tmp }";
		}

		if ( $atts['align'] === 'left' ) {
			$tmp .= 'justify-content: flex-start';
		} elseif ( $atts['align'] === 'center' ) {
			$tmp .= 'justify-content: center;';
		} elseif ( $atts['align'] === 'right' ) {
			$tmp .= 'justify-content: flex-end;';
		}

		$businext_shortcode_lg_css .= "$selector { $tmp }";

		$tmp = '';
		if ( $atts['md_align'] !== '' ) {
			if ( $atts['md_align'] === 'left' ) {
				$tmp .= 'justify-content: flex-start';
			} elseif ( $atts['md_align'] === 'center' ) {
				$tmp .= 'justify-content: center;';
			} elseif ( $atts['md_align'] === 'right' ) {
				$tmp .= 'justify-content: flex-end;';
			}

			$businext_shortcode_md_css .= "$selector { $tmp }";
		}

		$tmp = '';
		if ( $atts['sm_align'] !== '' ) {
			if ( $atts['sm_align'] === 'left' ) {
				$tmp .= 'justify-content: flex-start';
			} elseif ( $atts['sm_align'] === 'center' ) {
				$tmp .= 'justify-content: center;';
			} elseif ( $atts['sm_align'] === 'right' ) {
				$tmp .= 'justify-content: flex-end;';
			}

			$businext_shortcode_sm_css .= "$selector { $tmp }";
		}

		$tmp = '';
		if ( $atts['xs_align'] !== '' ) {
			if ( $atts['xs_align'] === 'left' ) {
				$tmp .= 'justify-content: flex-start';
			} elseif ( $atts['xs_align'] === 'center' ) {
				$tmp .= 'justify-content: center;';
			} elseif ( $atts['xs_align'] === 'right' ) {
				$tmp .= 'justify-content: flex-end;';
			}

			$businext_shortcode_xs_css .= "$selector { $tmp }";
		}

		Businext_VC::get_vc_spacing_css( $selector, $atts );
	}
}

vc_map( array(
	'name'                    => esc_html__( 'Button Group', 'businext' ),
	'base'                    => 'tm_button_group',
	'as_parent'               => array( 'only' => 'tm_button' ),
	'content_element'         => true,
	'show_settings_on_create' => false,
	'is_container'            => true,
	'category'                => BUSINEXT_VC_SHORTCODE_CATEGORY,
	'icon'                    => 'insight-i insight-i-divider',
	'js_view'                 => 'VcColumnView',
	'params'                  => array_merge( Businext_VC::get_alignment_fields( array( 'first_element' => true ) ), array(
		array(
			'heading'    => esc_html__( 'Gutter', 'businext' ),
			'type'       => 'number',
			'param_name' => 'gutter',
			'step'       => 1,
			'suffix'     => 'px',
		),
		Businext_VC::extra_class_field(),
	), Businext_VC::get_vc_spacing_tab() ),
) );

