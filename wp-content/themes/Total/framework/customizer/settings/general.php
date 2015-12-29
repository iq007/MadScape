<?php
/**
 * Customizer => General Panel
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Accent Colors
$this->sections['wpex_accent_colors'] = array(
	'title' => esc_html__( 'Accent Colors', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'accent_color',
			'default' => '#3b86b0',
			'control' => array(
				'label' => esc_html__( 'Accent Color', 'total' ),
				'type' => 'color',
			),
		),
	)
);

// Background
$patterns_url = get_template_directory_uri() .'/images/patterns/';
$this->sections['wpex_background_background'] = array(
	'title'  => esc_html__( 'Site Background', 'total' ),
	'panel'  => 'wpex_general',
	'desc' => esc_html__( 'Here you can alter the global site background. It is highly recommended that you first set the site layout to "Boxed" under the Layout options.', 'total' ),
	'settings' => array(
		array(
			'id' => 'background_color',
			'control' => array(
				'label' => esc_html__( 'Background Color', 'total' ),
				'type' => 'color',
			),
		),
		array(
			'id' => 'background_image',
			'control' => array(
				'label' => esc_html__( 'Custom Background Image', 'total' ),
				'type' => 'image',
				'active_callback' => 'wpex_cac_hasnt_background_pattern',
			),
		),
		array(
			'id' => 'background_style',
			'default' => 'stretched',
			'control' => array(
				'label' => esc_html__( 'Background Image Style', 'total' ),
				'type'  => 'image',
				'type'  => 'select',
				'active_callback' => 'wpex_cac_has_background_image',
				'choices' => array(
					'stretched' => esc_html_x( 'Stretched', 'Background Style', 'total' ),
					'repeat' => esc_html_x( 'Repeat', 'Background Style', 'total' ),
					'fixed'  => esc_html_x( 'Center Fixed', 'Background Style', 'total' ),
					'repeat-x' => esc_html_x( 'Repeat-x', 'Background Style', 'total' ),
					'repeat-y' => esc_html_x( 'Repeat-y', 'Background Style', 'total' ),
					'repeat-y' => esc_html_x( 'Repeat-y', 'Background Style', 'total' ),
				),
			),
		),
		array(
			'id' => 'background_pattern',
			'control' => array(
				'label' => esc_html__( 'Background Pattern', 'total' ),
				'type'  => 'image',
				'type'  => 'select',
				'active_callback' => 'wpex_cac_hasnt_background_image',
				'choices' => array(
					'' => esc_html__( 'None', 'total' ),
					$patterns_url .'dark_wood.png'  => esc_html__( 'Dark Wood', 'total' ),
					$patterns_url .'diagmonds.png'  => esc_html__( 'Diamonds', 'total' ),
					$patterns_url .'grilled.png' => esc_html__( 'Grilled', 'total' ),
					$patterns_url .'lined_paper.png' => esc_html__( 'Lined Paper', 'total' ),
					$patterns_url .'old_wall.png' => esc_html__( 'Old Wall', 'total' ),
					$patterns_url .'ricepaper2.png' => esc_html__( 'Rice Paper', 'total' ),
					$patterns_url .'tree_bark.png'  => esc_html__( 'Tree Bark', 'total' ),
					$patterns_url .'triangular.png' => esc_html__( 'Triangular', 'total' ),
					$patterns_url .'white_plaster.png' => esc_html__( 'White Plaster', 'total' ),
					$patterns_url .'wild_flowers.png' => esc_html__( 'Wild Flowers', 'total' ),
					$patterns_url .'wood_pattern.png' => esc_html__( 'Wood Pattern', 'total' ),
				),
			),
		),
	),
);

// Social Sharing Section
$this->sections['wpex_social_sharing'] = array(
	'title'  => esc_html__( 'Social Sharing', 'total' ),
	'panel'  => 'wpex_general',
	'settings' => array(
		array(
			'id'  => 'social_share_sites',
			'default' => array( 'twitter', 'facebook', 'google_plus', 'pinterest' ),
			'control' => array(
				'label'  => esc_html__( 'Sites', 'total' ),
				'type' => 'multiple-select',
				'object' => 'WPEX_Customize_Multicheck_Control',
				'choices' => array(
					'twitter'  => esc_html__( 'Twitter', 'total' ),
					'facebook' => esc_html__( 'Facebook', 'total' ),
					'google_plus' => esc_html__( 'Google Plus', 'total' ),
					'pinterest' => esc_html__( 'Pinterest', 'total' ),
					'linkedin' => esc_html__( 'LinkedIn', 'total' ),
				),
			),
		),
		array(
			'id' => 'social_share_position',
			'default' => '',
			'control' => array(
				'label' => esc_html__( 'Position', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => esc_html__( 'Default', 'total' ),
					'horizontal' => esc_html__( 'Horizontal', 'total' ),
					'vertical' => esc_html__( 'Vertical', 'total' ),
				),
				'active_callback' => 'wpex_has_social_share_sites',
			),
		),
		array(
			'id' => 'social_share_heading_enable',
			'default' => true,
			'control' => array(
				'label' => esc_html__( 'Enable Heading', 'total' ),
				'type'  => 'checkbox',
				'active_callback' => 'wpex_social_sharing_supports_heading',
			),
		),
		array(
			'id' => 'social_share_heading',
			'transport' => 'postMessage',
			'default' => esc_html__( 'Please Share This', 'total' ),
			'control' => array(
				'label' => esc_html__( 'Heading on Posts', 'total' ),
				'type'  => 'text',
				'active_callback' => 'wpex_social_sharing_supports_heading',
			),
		),
		array(
			'id' => 'social_share_style',
			'default' => 'flat',
			'control' => array(
				'label' => esc_html__( 'Style', 'total' ),
				'type'  => 'select',
				'choices' => array(
					'flat' => esc_html_x( 'Flat', 'Social Share Style', 'total' ),
					'minimal' => esc_html_x( 'Minimal', 'Social Share Style', 'total' ),
					'three-d' => esc_html_x( '3D', 'Social Share Style', 'total' ),
				),
				'active_callback' => 'wpex_has_social_share_sites',
			),
		),
		array(
			'id' => 'social_share_twitter_handle',
			'transport' => 'postMessage',
			'control' => array(
				'label' => esc_html__( 'Twitter Handle', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_has_social_share_sites',
			),
		),
		array(
			'id' => 'social_share_pages',
			'default' => false,
			'control' => array(
				'label' => esc_html__( 'Enable for Pages', 'total' ),
				'type' => 'checkbox',
				'active_callback' => 'wpex_has_social_share_sites',
			),
		),
	)
);

// Breadcrumbs
$this->sections['wpex_breadcrumbs'] = array(
	'title' => esc_html__( 'Breadcrumbs', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'breadcrumbs',
			'default' => true,
			'control' => array(
				'label' => esc_html__( 'Enable', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'breadcrumbs_position',
			//'transport' => 'postMessage', // Don't do this incase user moved breadcrumbs via hook
			'control' => array(
				'label' => esc_html__( 'Position', 'total' ),
				'type'  => 'select',
				'choices' => array(
					''  => esc_html__( 'Absolute Right', 'total' ),
					'under-title' => esc_html__( 'Under Title', 'total' ),
				),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
		),
		array(
			'id' => 'breadcrumbs_home_title',
			'control' => array(
				'label' => esc_html__( 'Custom Home Title', 'total' ),
				'type'  => 'text',
				'active_callback' => 'wpex_cac_enabled_not_yoast',
			),
		),
		array(
			'id' => 'breadcrumbs_title_trim',
			'control' => array(
				'label' => esc_html__( 'Title Trim Length', 'total' ),
				'type'  => 'text',
				'desc'  => esc_html__( 'Enter the max number of words to display for your breadcrumbs post title', 'total' ),
				'active_callback' => 'wpex_cac_enabled_not_yoast',
			),
		),
		array(
			'id' => 'breadcrumbs_text_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Text Color', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'breadcrumbs_seperator_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Separator Color', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs .sep',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'breadcrumbs_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Link Color', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'breadcrumbs_link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Link Color: Hover', 'total' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
			),
			'inline_css' => array(
				'target' => '.site-breadcrumbs a:hover',
				'alter' => 'color',
			),
		),
	),
);

// Page Title
$this->sections['wpex_page_header'] = array(
	'title' => esc_html__( 'Page Title', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'page_header_style',
			//'transport' => 'postMessage', Too complex for this
			'default' => '',
			'control' => array(
				'label'  => esc_html__( 'Page Header Style', 'total' ),
				'type' => 'image',
				'type' => 'select',
				'choices' => array(
					'' => esc_html__( 'Default','total' ),
					'centered' => esc_html__( 'Centered', 'total' ),
					'centered-minimal' => esc_html__( 'Centered Minimal', 'total' ),
					'hidden' => esc_html__( 'Hidden', 'total' ),
				),
			),
		),
		array(
			'id' => 'page_header_top_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Top Padding', 'total' ),
				'description' => esc_html__( 'Enter a value in pixels. Example: 20px.', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'padding-top',
			),
		),
		array(
			'id' => 'page_header_bottom_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Bottom Padding', 'total' ),
				'description' => esc_html__( 'Enter a value in pixels. Example: 20px.', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'padding-bottom',
			),
		),
		array(
			'id' => 'page_header_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'page_header_title_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Text Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods .page-header-title',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'page_header_top_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Top Border Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'border-top-color',
			),
		),
		array(
			'id' => 'page_header_bottom_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Bottom Border Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '.page-header.wpex-supports-mods',
				'alter' => 'border-bottom-color',
			),
		),
	),
);

// Pages
$this->sections['wpex_pages'] = array(
	'title'  => esc_html__( 'Pages', 'total' ),
	'panel'  => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'page_single_layout',
			'default' => true,
			'default' => '',
			'control' => array(
				'label' => esc_html__( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => esc_html__( 'Default', 'total' ),
					'right-sidebar' => esc_html__( 'Right Sidebar','total' ),
					'left-sidebar' => esc_html__( 'Left Sidebar','total' ),
					'full-width' => esc_html__( 'No Sidebar','total' ),
					'full-screen' => esc_html__( 'Full Screen','total' ),
				),
			),
		),
		array(
			'id' => 'pages_custom_sidebar',
			'default' => true,
			'control' => array(
				'label' => esc_html__( 'Custom Sidebar', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'page_comments',
			'control' => array(
				'label' => esc_html__( 'Comments on Pages', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'page_featured_image',
			'control' => array(
				'label' => esc_html__( 'Display Featured Images', 'total' ),
				'type' => 'checkbox',
			),
		),
	),
);

// Search
$this->sections['wpex_search'] = array(
	'title'  => esc_html__( 'Search', 'total' ),
	'panel'  => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'search_style',
			'default' => 'default',
			'transport' => 'postMessage',
			'control' => array(
				'label' => esc_html__( 'Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'default' => esc_html__( 'Left Thumbnail', 'total' ),
					'blog' => esc_html__( 'Inherit From Blog','total' ),
				),
			),
		),
		array(
			'id' => 'search_layout',
			'transport' => 'postMessage',
			'control' => array(
				'label' => esc_html__( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => esc_html__( 'Default', 'total' ),
					'right-sidebar' => esc_html__( 'Right Sidebar','total' ),
					'left-sidebar' => esc_html__( 'Left Sidebar','total' ),
					'full-width' => esc_html__( 'No Sidebar','total' ),
				),
			),
		),
		array(
			'id' => 'search_posts_per_page',
			'default' => '10',
			'transport' => 'postMessage',
			'control' => array(
				'label' => esc_html__( 'Posts Per Page', 'total' ),
				'type' => 'text',
			),
		),
		array(
			'id' => 'search_custom_sidebar',
			'default' => true,
			'transport' => 'postMessage',
			'control' => array(
				'label' => esc_html__( 'Custom Sidebar', 'total' ),
				'type' => 'checkbox',
			),
		),
	),
);

// Scroll to top
$this->sections['wpex_scroll_top'] = array(
	'title' => esc_html__( 'Scroll To Top', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'scroll_top',
			'default' => true,
			'active_callback' => 'wpex_cac_has_scrolltop',
			'control' => array(
				'label' => esc_html__( 'Scroll Up Button', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'scroll_top_arrow',
			'default' => 'chevron-up',
			'transport' => 'postMessage',
			'active_callback' => 'wpex_cac_has_scrolltop',
			'control' => array(
				'label' => esc_html__( 'Arrow', 'total' ),
				'type' => 'select',
				'choices' => wpex_get_awesome_icons( 'up_arrows' ),
			),
		),
		array(
			'id' => 'scroll_top_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Button Size', 'total' ),
				'description' => esc_html__( 'Enter a value in pixels. Example: 20px.', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'sanitize' => 'px',
				'alter' => array(
					'width',
					'height',
					'line-height',
				),
			),
		),
		array(
			'id' => 'scroll_top_icon_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Icon Size', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'font-size',
			),
		),
		array(
			'id' => 'scroll_top_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Border Radius', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'border-radius',
			),
		),
		array(
			'id' => 'scroll_top_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'scroll_top_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'scroll_top_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'scroll_top_bg_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'scroll_top_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Border', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top',
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'scroll_top_border_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Border: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-scroll-top:hover',
				'alter' => 'border-color',
			),
		),
	),
);

// Forms
$this->sections['wpex_general_forms'] = array(
	'title' => esc_html__( 'Forms', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'label_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Labels', 'total' ),
			),
			'inline_css' => array(
				'target' => 'label',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'input_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Padding', 'total' ),
				'description' => esc_html__( 'Format: top right bottom left.', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.entry input[type="text"],.site-content input[type="password"],.site-content input[type="email"],.site-content input[type="tel"],.site-content input[type="url"],.site-content input[type="search"],.site-content textarea',
				),
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'input_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Border Radius', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.entry input[type="text"],.site-content input[type="password"],.site-content input[type="email"],.site-content input[type="tel"],.site-content input[type="url"],.site-content input[type="search"],.site-content textarea',
				),
				'alter' => 'border-radius',
			),
		),
		array(
			'id' => 'input_font_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Font-Size', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.entry input[type="text"],.site-content input[type="password"],.site-content input[type="email"],.site-content input[type="tel"],.site-content input[type="url"],.site-content input[type="search"],.site-content textarea',
				),
				'alter' => 'font-size',
			),
		),
		array(
			'id' => 'input_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.entry input[type="text"],.site-content input[type="password"],.site-content input[type="email"],.site-content input[type="tel"],.site-content input[type="url"],.site-content input[type="search"],.site-content textarea',
				),
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'input_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Border', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.entry input[type="text"],.site-content input[type="password"],.site-content input[type="email"],.site-content input[type="tel"],.site-content input[type="url"],.site-content input[type="search"],.site-content textarea',
				),
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'input_border_width',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Border Width', 'total' ),
				'description' => esc_html__( 'Enter a value in pixels. Example: 20px.', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.entry input[type="text"],.site-content input[type="password"],.site-content input[type="email"],.site-content input[type="tel"],.site-content input[type="url"],.site-content input[type="search"],.site-content textarea',
				),
				'alter' => 'border-width',
			),
		),
		array(
			'id' => 'input_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Color', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.entry input[type="text"],.site-content input[type="password"],.site-content input[type="email"],.site-content input[type="tel"],.site-content input[type="url"],.site-content input[type="search"],.site-content textarea',
				),
				'alter' => 'color',
			),
		),
	),
);


// Links & Buttons
$this->sections['wpex_general_links_buttons'] = array(
	'title' => esc_html__( 'Links & Buttons', 'total' ),
	'panel' => 'wpex_general',
	'settings' => array(
		array(
			'id' => 'link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Links Color', 'total' ),
			),
			'inline_css' => array(
				'target' => 'a, h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, .entry-title a:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Links Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => 'a:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'theme_button_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Theme Button Padding', 'total' ),
				'description' => esc_html__( 'Format: top right bottom left.', 'total' ),
			),
			'inline_css' => array(
				'target' => '.theme-button,input[type="submit"],button',
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'theme_button_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => esc_html__( 'Theme Button Border Radius', 'total' ),
			),
			'inline_css' => array(
				'target' => '.theme-button,input[type="submit"],button',
				'alter' => 'border-radius',
			),
		),
		array(
			'id' => 'theme_button_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Theme Button Color', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.theme-button,input[type="submit"],button',
					'.navbar-style-one .menu-button > a > span.link-inner:hover',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'theme_button_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Theme Button Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.theme-button:hover,input[type="submit"]:hover,button:hover',
					'.navbar-style-one .menu-button > a > span.link-inner:hover',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'theme_button_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Theme Button Background', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.theme-button,input[type="submit"],button',
					'.navbar-style-one .menu-button > a > span.link-inner:hover',
				),
				'alter' => 'background',
			),
		),
		array(
			'id' => 'theme_button_hover_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => esc_html__( 'Theme Button Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.theme-button:hover,input[type="submit"]:hover,button:hover',
					'.navbar-style-one .menu-button > a > span.link-inner:hover',
				),
				'alter' => 'background',
			),
		),
	),
);