<?php
/**
 * Visual Composer Image Grid
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
class WPBakeryShortCode_vcex_image_grid extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		ob_start();
		include( locate_template( 'vcex_templates/vcex_image_grid.php' ) );
		return ob_get_clean();
	}
}

/**
 * Adds the shortcode to the Visual Composer
 *
 * @since 1.4.1
 */
function vcex_image_grid_vc_map() {
	return array(
		'name' => esc_html__( 'Image Grid', 'total' ),
		'description' => esc_html__( 'Responsive image gallery', 'total' ),
		'base' => 'vcex_image_grid',
		'icon' => 'vcex-image-grid vcex-icon fa fa-picture-o',
		'category' => wpex_get_theme_branding(),
		'params' => array(
			// General
			array(
				'type' => 'attach_images',
				'admin_label' => true,
				'heading' => esc_html__( 'Attach Images', 'total' ),
				'param_name' => 'image_ids',
				'group' => esc_html__( 'Images', 'total' ),
				'description' => esc_html__( 'Click the plus icon to add images to your gallery. Once images are added they can be drag and dropped for sorting.', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Post Gallery', 'total' ),
				'param_name' => 'post_gallery',
				'group' => esc_html__( 'Images', 'total' ),
				'description' => esc_html__( 'Enable to display images from the current post "Image Gallery".', 'total' ),
				'value' => array(
					__( 'No', 'total' ) => 'false',
					__( 'Yes', 'total' ) => 'true',
				),
			),
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
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Hover Animation', 'total'),
				'param_name' => 'hover_animation',
				'value' => array_flip( wpex_hover_css_animations() ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Grid Style', 'total' ),
				'param_name' => 'grid_style',
				'value' => array(
					__( 'Fit Rows', 'total' ) => 'default',
					__( 'Masonry', 'total' ) => 'masonry',
					__( 'No Margins', 'total' ) => 'no-margins',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column clear',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Columns', 'total' ),
				'param_name' => 'columns',
				'std' => '4',
				'value' => array_flip( wpex_grid_columns() ),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Gap', 'total' ),
				'param_name' => 'columns_gap',
				'value' => array_flip( wpex_column_gaps() ),
				'std' => '',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Responsive', 'total' ),
				'param_name' => 'responsive_columns',
				'std' => '',
				'value' => array(
					__( 'True', 'total' ) => '',
					__( 'False', 'total' ) => 'false',
				),
				'edit_field_class' => 'vc_col-sm-3 vc_column',
			),
			array(
				'type' => 'dropdown',
				'admin_label' => true,
				'heading' => esc_html__( 'Randomize Images', 'total' ),
				'param_name' => 'randomize_images',
				'value' => array(
					__( 'False', 'total' ) => '',
					__( 'True', 'total' ) => 'true',
				),
			),
			array(
				'type' => 'textfield',
				'admin_label' => true,
				'heading' => esc_html__( 'Images Per Page', 'total' ),
				'param_name' => 'posts_per_page',
				'value' => '-1',
				'description' => esc_html__( 'This will enable pagination for your gallery. Enter -1 or leave blank to display all images without pagination.', 'total' ),
			),
			// Links
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image Link', 'total' ),
				'param_name' => 'thumbnail_link',
				'std' => 'lightbox',
				'value' => array(
					__( 'None', 'total' ) => 'none',
					__( 'Lightbox', 'total' ) => 'lightbox',
					__( 'Attachment Page', 'total' ) => 'attachment_page',
					__( 'Custom Links', 'total' ) => 'custom_link',
				),
				'group' => esc_html__( 'Links', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Lightbox Skin', 'total' ),
				'param_name' => 'lightbox_skin',
				'std' => '',
				'value' => vcex_ilightbox_skins(),
				'group' => esc_html__( 'Links', 'total' ),
				'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox', ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Lightbox Gallery', 'total' ),
				'param_name' => 'lightbox_gallery',
				'value' => array(
					__( 'Yes', 'total' ) => 'true',
					__( 'No', 'total' ) => 'false',
				),
				'group' => esc_html__( 'Links', 'total' ),
				'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Lightbox Thumbnails Placement', 'total' ),
				'param_name' => 'lightbox_path',
				'value' => array(
					__( 'Horizontal', 'total' ) => '',
					__( 'Vertical', 'total' ) => 'vertical',
				),
				'group' => esc_html__( 'Links', 'total' ),
				'dependency' => array( 'element' => 'lightbox_gallery', 'value' => 'true' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Lightbox Title', 'total' ),
				'param_name' => 'lightbox_title',
				'value' => array(
					__( 'Alt', 'total' ) => '',
					__( 'Title', 'total' ) => 'title',
					__( 'None', 'total' ) => 'false',
				),
				'group' => esc_html__( 'Links', 'total' ),
				'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Lightbox Caption', 'total' ),
				'param_name' => 'lightbox_caption',
				'value' => array(
					__( 'Enable', 'total' ) => 'true',
					__( 'Disable', 'total' ) => 'false',
				),
				'group' => esc_html__( 'Links', 'total' ),
				'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
			),
			array(
				'type' => 'exploded_textarea',
				'heading' => esc_html__( 'Custom links', 'total' ),
				'param_name' => 'custom_links',
				'description' => esc_html__( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol. And don\'t forget to include the http:// at the front.', 'total' ),
				'dependency' => array( 'element' => 'thumbnail_link', 'value' => array( 'custom_link' ) ),
				'group' => esc_html__( 'Links', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Link Target', 'total' ),
				'param_name' => 'custom_links_target',
				'group' => esc_html__( 'Links', 'total' ),
				'value' => array(
					__( 'Same window', 'total' ) => '_self',
					__( 'New window', 'total' ) => '_blank'
				),
				'dependency' => array( 'element' => 'thumbnail_link', 'value' => array( 'custom_link' ) ),
			),
			// Image
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image Size', 'total' ),
				'param_name' => 'img_size',
				'std' => 'wpex_custom',
				'value' => vcex_image_sizes(),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image Crop Location', 'total' ),
				'param_name' => 'img_crop',
				'std' => 'center-center',
				'value' => array_flip( wpex_image_crop_locations() ),
				'dependency' => array(
					'element' => 'img_size',
					'value' => 'wpex_custom',
				),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image Crop Width', 'total' ),
				'param_name' => 'img_width',
				'group' => esc_html__( 'Image', 'total' ),
				'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
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
				'heading' => esc_html__( 'Rounded Image?', 'total' ),
				'param_name' => 'rounded_image',
				'value' => array(
					__( 'No', 'total' ) => '',
					__( 'Yes', 'total' ) => 'yes'
				),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Overlay Style', 'total' ),
				'param_name' => 'overlay_style',
				'value' => array_flip( wpex_overlay_styles_array() ),
				'group' => esc_html__( 'Image', 'total' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Overlay Button Text', 'total' ),
				'param_name' => 'overlay_button_text',
				'group' => esc_html__( 'Image', 'total' ),
				'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'CSS3 Image Hover', 'total' ),
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
				'std' => '',
				'value' => array(
					__( 'No', 'total' ) => 'no',
					__( 'Yes', 'total' ) => 'yes'
				),
				'group' => esc_html__( 'Title', 'total' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Tag', 'total' ),
				'param_name' => 'title_tag',
				'value' => array(
					__( 'Default', 'total' ) => '',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'div' => 'div',
				),
				'group' => esc_html__( 'Title', 'total' ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Based On', 'total' ),
				'param_name' => 'title_type',
				'value' => array(
					__( 'Default', 'total' ) => '',
					__( 'Title', 'total' ) => 'title',
					__( 'Alt', 'total' ) => 'alt',
					__( 'Caption', 'total' ) => 'caption',
					__( 'Description', 'total' ) => 'description',
				),
				'group' => esc_html__( 'Title', 'total' ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Color', 'total' ),
				'param_name' => 'title_color',
				'group' => esc_html__( 'Title', 'total' ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Font Size', 'total' ),
				'param_name' => 'title_size',
				'group' => esc_html__( 'Title', 'total' ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Line Height', 'total' ),
				'param_name' => 'title_line_height',
				'group' => esc_html__( 'Title', 'total' ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Margin', 'total' ),
				'param_name' => 'title_margin',
				'group' => esc_html__( 'Title', 'total' ),
				'description' => esc_html__( 'Please use the following format: top right bottom left.', 'total' ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Font Weight', 'total' ),
				'param_name' => 'title_weight',
				'group' => esc_html__( 'Title', 'total' ),
				'std' => '',
				'value' => array_flip( wpex_font_weights() ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Text Transform', 'total' ),
				'param_name' => 'title_transform',
				'group' => esc_html__( 'Title', 'total' ),
				'std' => '',
				'value' => array_flip( wpex_text_transforms() ),
				'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
			),
			// Entry CSS
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Entry CSS', 'total' ),
				'param_name' => 'entry_css',
				'group' => esc_html__( 'Entry CSS', 'total' ),
			),
			// CSS
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Wrap CSS', 'total' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Container CSS', 'total' ),
			),
		)
	);
}
vc_lean_map( 'vcex_image_grid', 'vcex_image_grid_vc_map' );