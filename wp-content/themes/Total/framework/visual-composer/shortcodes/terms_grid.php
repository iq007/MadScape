<?php
/**
 * Visual Composer Terms Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 3.3.0
 */

/**
 * Register shortcode with VC Composer
 *
 * @since 2.1.0
 */
class WPBakeryShortCode_vcex_terms_grid extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		ob_start();
		include( locate_template( 'vcex_templates/vcex_terms_grid.php' ) );
		return ob_get_clean();
	}
}

/**
 * Adds the shortcode to the Visual Composer
 *
 * @since 2.1.0
 */
function vcex_terms_grid_vc_map() {
	return array(
		'name' => esc_html__( 'Categories Grid', 'total' ),
		'description' => esc_html__( 'Displays a grid of terms', 'total' ),
		'base' => 'vcex_terms_grid',
		'category' => wpex_get_theme_branding(),
		'icon' => 'vcex-terms-grid vcex-icon fa fa-th-large',
		'params' => array(
			// General
			array(
				'type' => 'autocomplete',
				'heading' => esc_html__( 'Taxonomy', 'total' ),
				'param_name' => 'taxonomy',
				'std' => 'category',
				'settings' => array(
					'multiple' => false,
					'min_length' => 1,
					'groups' => false,
					'unique_values' => true,
					'display_inline' => true,
					'delay' => 0,
					'auto_focus' => true,
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Parent Terms Only', 'total' ),
				'param_name' => 'parent_terms',
				'value' => array(
					__( 'No', 'total' ) => false,
					__( 'Yes', 'total' ) => true,
				),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Unique Id', 'total' ),
				'param_name' => 'unique_id',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Custom Classes', 'total' ),
				'param_name' => 'classes',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'CSS Animation', 'total' ),
				'param_name' => 'css_animation',
				'value' => array_flip( wpex_css_animations() ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Visibility', 'total' ),
				'param_name' => 'visibility',
				'value' => array_flip( wpex_visibility() ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Grid Style', 'total' ),
				'param_name' => 'grid_style',
				'value' => array(
					__( 'Fit Columns', 'total' ) => 'fit_columns',
					__( 'Masonry', 'total' ) => 'masonry',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column clear',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Columns', 'total' ),
				'param_name' => 'columns',
				'value' => array_flip( wpex_grid_columns() ),
				'std' => '3',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Gap', 'total' ),
				'param_name' => 'columns_gap',
				'value' => array_flip( wpex_column_gaps() ),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Responsive', 'total' ),
				'param_name' => 'columns_responsive',
				'value' => array(
					__( 'Yes', 'total' ) => '',
					__( 'No', 'total' ) => 'false',
				),
				'std' => '',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			// Image
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image Size', 'total' ),
				'param_name' => 'img_size',
				'std' => 'full',
				'value' => vcex_image_sizes(),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image Crop Location', 'total' ),
				'param_name' => 'img_crop',
				'std' => 'center-center',
				'value' => array_flip( wpex_image_crop_locations() ),
				'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image Crop Width', 'total' ),
				'param_name' => 'img_width',
				'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				'description' => esc_html__( 'Enter a width in pixels.', 'total' ),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image Crop Height', 'total' ),
				'param_name' => 'img_height',
				'description' => esc_html__( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
				'group' => esc_html__( 'Image', 'total' ),
				'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'CSS3 Image Link Hover', 'total' ),
				'param_name' => 'img_hover_style',
				'value' => array_flip( wpex_image_hovers() ),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image Filter', 'total' ),
				'param_name' => 'img_filter',
				'value' => array_flip( wpex_image_filters() ),
				'group' => esc_html__( 'Image', 'total' ),
			),
			// Title
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Enable', 'total' ),
				'param_name' => 'title',
				'std' => 'true',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total') => 'false',
				),
				'group' => esc_html__( 'Title', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Overlay Title', 'total' ),
				'param_name' => 'title_overlay',
				'std' => 'false',
				'value' => array(
					__( 'No', 'total') => 'false',
					__( 'Yes', 'total' ) => 'true',
				),
				'group' => esc_html__( 'Title', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Display Term Count', 'total' ),
				'param_name' => 'term_count',
				'std' => 'false',
				'value' => array(
					__( 'No', 'total') => 'false',
					__( 'Yes', 'total' ) => 'true',
				),
				'group' => esc_html__( 'Title', 'total' ),
			),
			array(
				'type' => 'font_container',
				'param_name' => 'title_typo',
				'group' => esc_html__( 'Title', 'total' ),
				'settings' => array(
					'fields' => array(
						'tag' => 'span',
						'text_align',
						'font_size',
						'line_height',
						'color',
						'font_style_italic',
						'font_style_bold',
						'font_family',
					),
				),
				'dependency' => array( 'element' => 'title', 'value' => 'true' ),
			),
			// Description
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Enable', 'total' ),
				'param_name' => 'description',
				'std' => 'true',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total') => 'false',
				),
				'group' => esc_html__( 'Description', 'total' ),
				'dependency' => array( 'element' => 'title_overlay', 'value' => 'false' ),
			),
			array(
				'type' => 'font_container',
				'param_name' => 'description_typo',
				'group' => esc_html__( 'Description', 'total' ),
				'settings' => array(
					'fields' => array(
						'font_size',
						'text_align',
						'line_height',
						'color',
						'font_style_italic',
						'font_style_bold',
						'font_family',
					),
				),
				'dependency' => array( 'element' => 'description', 'value' => 'true' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS', 'total' ),
				'param_name' => 'entry_css',
				'group' => esc_html__( 'Entry CSS', 'total' ),
			),
		)
	);
}
vc_lean_map( 'vcex_terms_grid', 'vcex_terms_grid_vc_map' );

// Get autocomplete suggestion
add_filter( 'vc_autocomplete_vcex_terms_grid_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );

// Render autocomplete suggestions
add_filter( 'vc_autocomplete_vcex_terms_grid_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );