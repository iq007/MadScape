<?php
/**
 * Yoast SEO Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage Configs
 * @version 3.3.0
 */

global $yoast_config;

if ( ! class_exists( 'WPEX_Yoast_Config' ) ) {

	class WPEX_Yoast_Config {

		/**
		 * Start things up
		 *
		 * @version 3.3.0
		 */
		public function __construct() {

			// Add support for Yoast SEO breadcrumb settings in the WP Customizer
			add_theme_support( 'yoast-seo-breadcrumbs' );

			// Filter the ancestors of the yoast seo breadcrumbs
			add_filter( 'wpseo_breadcrumb_links', array( $this, 'wpseo_breadcrumb_links' ) );

		}

		/**
		 * Filter the ancestors of the yoast seo breadcrumbs
		 * Adds the portfolio, staff, testimonials and blog links
		 *
		 * @version 3.3.0
		 */
		static function wpseo_breadcrumb_links( $links ) {

			global $post;
			$new_breadcrumb = '';

			// Loop through items
			$types = array( 'portfolio', 'staff', 'testimonials', 'post' );
			foreach ( $types as $type ) {
				if ( is_singular( $type ) ) {
					if ( 'post' == $type ) {
						$type = 'blog';
					}
					$page_id = wpex_parse_obj_id( wpex_get_mod( $type .'_page' ), 'page' );
					if ( $page_id ) {
						$page_title     = get_the_title( $page_id );
						$page_permalink = get_permalink( $page_id );
						if ( $page_permalink && $page_title ) {
							$new_breadcrumb[] = array(
								'url'  => $page_permalink,
								'text' => $page_title,
							);
						}
					}
				}
			} // End foreach loop

			// Combine new crumb
			if ( $new_breadcrumb ) {
				array_splice( $links, 1, -2, $new_breadcrumb );
			}

			// Return links
			return $links;
			
		}

	}
	
}
$yoast_config = new WPEX_Yoast_Config();