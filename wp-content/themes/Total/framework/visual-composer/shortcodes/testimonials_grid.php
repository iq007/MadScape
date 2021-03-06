<?php
/**
 * Visual Composer Testimonials Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 3.3.0
 */

/**
 * Register shortcode with VC Composer
 *
 * @since 2.0.0
 */
class WPBakeryShortCode_vcex_testimonials_grid extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		ob_start();
		include( locate_template( 'vcex_templates/vcex_testimonials_grid.php' ) );
		return ob_get_clean();
	}
}

/**
 * Adds the testimonials grid shortcode to the Visual Composer
 *
 * @since 1.4.1
 */
function vcex_testimonials_grid_vc_map() {
	return array(
		'name' => esc_html__( 'Testimonials Grid', 'total' ),
		'description' => esc_html__( 'Recent testimonials post grid', 'total' ),
		'base' => 'vcex_testimonials_grid',
		'category' => wpex_get_theme_branding(),
		'icon' => 'vcex-testimonials-grid vcex-icon fa fa-comments-o',
		'params' => array(
			// General
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
				'heading' => esc_html__( 'Visibility', 'total' ),
				'param_name' => 'visibility',
				'value' => array_flip( wpex_visibility() ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Appear Animation', 'total'),
				'param_name' => 'css_animation',
				'value' => array_flip( wpex_css_animations() ),
				'dependency' => array( 'element' => 'filter', 'value' => 'false' ),
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
					__( 'Yes', 'total' ) => 'yes',
					__( 'No', 'total' ) => 'false',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			// Query
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Posts Per Page', 'total' ),
				'param_name' => 'posts_per_page',
				'value' => '-1',
				'group' => esc_html__( 'Query', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Pagination', 'total' ),
				'param_name' => 'pagination',
				'value' => array(
					__( 'No', 'total' ) => 'false',
					__( 'Yes', 'total' ) => 'true',
				),
				'description' => esc_html__( 'Important: Pagination will not work on your homepage due to how WordPress Queries function.', 'total' ),
				'group' => esc_html__( 'Query', 'total' ),
			),
			array(
				'type' => 'autocomplete',
				'heading' => esc_html__( 'Include Categories', 'total' ),
				'param_name' => 'include_categories',
				'param_holder_class' => 'vc_not-for-custom',
				'admin_label' => true,
				'settings' => array(
					'multiple' => true,
					'min_length' => 1,
					'groups' => false,
					'unique_values' => true,
					'display_inline' => true,
					'delay' => 0,
					'auto_focus' => true,
				),
				'group' => esc_html__( 'Query', 'total' ),
			),
			array(
				'type' => 'autocomplete',
				'heading' => esc_html__( 'Exclude Categories', 'total' ),
				'param_name' => 'exclude_categories',
				'param_holder_class' => 'vc_not-for-custom',
				'admin_label' => true,
				'settings' => array(
					'multiple' => true,
					'min_length' => 1,
					'groups' => false,
					'unique_values' => true,
					'display_inline' => true,
					'delay' => 0,
					'auto_focus' => true,
				),
				'group' => esc_html__( 'Query', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order', 'total' ),
				'param_name' => 'order',
				'group' => esc_html__( 'Query', 'total' ),
				'value' => array(
					__( 'Default', 'total' ) => '',
					__( 'DESC', 'total' ) => 'DESC',
					__( 'ASC', 'total' ) => 'ASC',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order By', 'total' ),
				'param_name' => 'orderby',
				'value' => vcex_orderby_array(),
				'group' => esc_html__( 'Query', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Orderby: Meta Key', 'total' ),
				'param_name' => 'orderby_meta_key',
				'group' => esc_html__( 'Query', 'total' ),
				'dependency' => array(
					'element' => 'orderby',
					'value' => array( 'meta_value_num', 'meta_value' ),
				),
			),
			// Filter
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Enable', 'total' ),
				'param_name' => 'filter',
				'value' => array(
					__( 'No', 'total' ) => 'false',
					__( 'Yes', 'total' ) => 'true',
				),
				'group' => esc_html__( 'Filter', 'total' ),
			),
			array(
				'type' => 'autocomplete',
				'heading' => esc_html__( 'Default Active Category', 'total' ),
				'param_name' => 'filter_active_category',
				'param_holder_class' => 'vc_not-for-custom',
				'admin_label' => true,
				'settings' => array(
					'multiple' => false,
					'min_length' => 1,
					'groups' => false,
					'unique_values' => true,
					'display_inline' => true,
					'delay' => 0,
					'auto_focus' => true,
				),
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Display All Link?', 'total' ),
				'param_name' => 'filter_all_link',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total' ) => 'false',
				),
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Custom Filter "All" Text', 'total' ),
				'param_name' => 'all_text',
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter_all_link', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Button Style', 'total' ),
				'param_name' => 'filter_button_style',
				'value' => array_flip( wpex_button_styles() ),
				'group' => esc_html__( 'Filter', 'total' ),
				'std' => 'minimal-border',
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Button Color', 'total' ),
				'param_name' => 'filter_button_color',
				'std' => '',
				'value' => array_flip( wpex_button_colors() ),
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Layout Mode', 'total' ),
				'param_name' => 'masonry_layout_mode',
				'value' => array(
					__( 'Masonry', 'total' ) => 'masonry',
					__( 'Fit Rows', 'total' ) => 'fitRows',
				),
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Custom Filter Speed', 'total' ),
				'param_name' => 'filter_speed',
				'description' => esc_html__( 'Default is 0.4 seconds. Enter 0.0 to disable.', 'total' ),
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Center Filter Links', 'total' ),
				'param_name' => 'center_filter',
				'value' => array(
					__( 'No', 'total' ) => 'no',
					__( 'Yes', 'total' ) => 'yes',
				),
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Font Size', 'total' ),
				'param_name' => 'filter_font_size',
				'group' => esc_html__( 'Filter', 'total' ),
				'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
			),
			// Image
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Enable', 'total' ),
				'param_name' => 'entry_media',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total' ) => 'false',
				),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Border Radius', 'total' ),
				'param_name' => 'img_border_radius',
				'group' => esc_html__( 'Image', 'total' ),
				'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image Size', 'total' ),
				'param_name' => 'img_size',
				'std' => 'wpex_custom',
				'value' => vcex_image_sizes(),
				'group' => esc_html__( 'Image', 'total' ),
				'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
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
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image Crop Height', 'total' ),
				'param_name' => 'img_height',
				'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				'description' => esc_html__( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
				'group' => esc_html__( 'Image', 'total' ),
			),
			// Title
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Details', 'total' ),
				'param_name' => 'title',
				'value' => array(
					__( 'No', 'total' ) => 'false',
					__( 'Yes', 'total' ) => 'true',
				),
				'group' => esc_html__( 'Title', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Font Size', 'total' ),
				'param_name' => 'title_font_size',
				'group' => esc_html__( 'Title', 'total' ),
				'dependency' => array( 'element' => 'title', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'HTML Tag', 'total' ),
				'param_name' => 'title_tag',
				'group' => esc_html__( 'Title', 'total' ),
				'std' => 'h2',
				'value' => array(
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'div' => 'div',
				),
				'dependency' => array( 'element' => 'title', 'value' => 'true' ),
			),
			// Author
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Author', 'total' ),
				'param_name' => 'author',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total' ) => 'false',
				),
				'group' => esc_html__( 'Details', 'total' ),
			),
			// Author
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Company', 'total' ),
				'param_name' => 'company',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total' ) => 'false',
				),
				'group' => esc_html__( 'Details', 'total' ),
			),
			// Excerpt
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Font Size', 'total' ),
				'param_name' => 'content_font_size',
				'group' => esc_html__( 'Content', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Excerpt', 'total' ),
				'param_name' => 'excerpt',
				'value' => array(
					__( 'No', 'total' ) => 'false',
					__( 'Yes', 'total' ) => 'true',
				),
				 'group' => esc_html__( 'Content', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Excerpt Length', 'total' ),
				'param_name' => 'excerpt_length',
				'value' => '20',
				'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
				'group' => esc_html__( 'Content', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Read More', 'total' ),
				'param_name' => 'read_more',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total' ) => 'false',
				),
				'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
				'group' => esc_html__( 'Content', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Read More Text', 'total' ),
				'param_name' => 'read_more_text',
				'value' => esc_html__( 'read more', 'total' ),
				'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
				'group' => esc_html__( 'Content', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Read More Arrow', 'total' ),
				'param_name' => 'read_more_rarr',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total') => 'false',
				),
				'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
				'group' => esc_html__( 'Content', 'total' ),
			),
		),
	);
}
vc_lean_map( 'vcex_testimonials_grid', 'vcex_testimonials_grid_vc_map' );

// Get autocomplete suggestion
add_filter( 'vc_autocomplete_vcex_testimonials_grid_include_categories_callback', 'vcex_suggest_testimonials_categories', 10, 1 );
add_filter( 'vc_autocomplete_vcex_testimonials_grid_exclude_categories_callback', 'vcex_suggest_testimonials_categories', 10, 1 );
add_filter( 'vc_autocomplete_vcex_testimonials_grid_filter_active_category_callback', 'vcex_suggest_testimonials_categories', 10, 1 );

// Render autocomplete suggestions
add_filter( 'vc_autocomplete_vcex_testimonials_grid_include_categories_render', 'vcex_render_testimonials_categories', 10, 1 );
add_filter( 'vc_autocomplete_vcex_testimonials_grid_exclude_categories_render', 'vcex_render_testimonials_categories', 10, 1 );
add_filter( 'vc_autocomplete_vcex_testimonials_grid_filter_active_category_render', 'vcex_render_testimonials_categories', 10, 1 );