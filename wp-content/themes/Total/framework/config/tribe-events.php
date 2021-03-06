<?php
/**
 * Configure the Tribe Events Plugin
 *
 * @package Total WordPress Theme
 * @subpackage Configs
 * @version 3.3.0
 *
 * @since 1.6.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Global class var
global $wpex_tribe_events_config;

// Start Class
if ( ! class_exists( 'WPEX_Tribe_Events_Config' ) ) {

	class WPEX_Tribe_Events_Config {

		/**
		 * Start things up
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public function __construct() {

			// Actions
			add_action( 'wp_enqueue_scripts', array( $this, 'load_custom_stylesheet' ), 10 );

			// Filters
			add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ), 10 );
			add_filter( 'wpex_main_metaboxes_post_types', array( $this, 'metaboxes' ), 10 );
			add_filter( 'wpex_title', array( $this, 'page_header_title' ), 10 );
			add_filter( 'widgets_init', array( $this, 'register_events_sidebar' ), 10 );
			add_filter( 'wpex_get_sidebar', array( $this, 'display_events_sidebar' ), 10 );
			add_filter( 'wpex_has_next_prev', array( $this, 'next_prev' ) );
			add_filter( 'wpex_accent_backgrounds', array( $this, 'accent_backgrounds' ) );

		}

		/**
		 * Load custom CSS file for tweaks
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public static function load_custom_stylesheet() {
			wp_enqueue_style( 'wpex-tribe-events', WPEX_CSS_DIR_URI .'wpex-tribe-events.css' );
		}

		/**
		 * Alter the post layouts for all events
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public function layouts( $class ) {

			// Return full-width for event posts and archives
			if ( $this->is_tribe_events() ) {
				$class = 'full-width';
			}

			// Return class
			return $class;

		}

		/**
		 * Add the Page Settings metabox to the events calendar
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public function metaboxes( $types ) {
			$types[] = 'tribe_events';
			return $types;
		}

		/**
		 * Alter the main page header title text for tribe events
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public function page_header_title( $title ) {

			// Fixes issue with search results
			if ( is_search() ) {
				return $title;
			}

			// Customize title for event pages
			if ( tribe_is_event_category() ) {
				$title = get_the_archive_title();
			} elseif ( tribe_is_month() ) {
				$title = esc_html__( 'Events Calendar', 'total' );
			} elseif ( tribe_is_event() && ! tribe_is_day() && ! is_single() ) {
				$title = esc_html__( 'Events List', 'total' );
			} elseif ( tribe_is_day() ) {
				$title = esc_html__( 'Single Day', 'total' );
			}

			/*
			} elseif ( is_singular( 'tribe_events' ) ) {
				$title = '<span>'. esc_html__( 'Event:', 'total' ) .'</span> '. get_the_title();
			*/

			// Return title
			return $title;

		}

		/**
		 * Register a new events sidebar area
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public static function register_events_sidebar() {
			$headings = wpex_get_mod( 'sidebar_headings', 'div' );
			$headings = $headings ? $headings : 'div';
			register_sidebar( array (
				'name'          => esc_html__( 'Events Sidebar', 'total' ),
				'id'            => 'tribe_events_sidebar',
				'before_widget' => '<div class="sidebar-box %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<'. $headings .' class="widget-title">',
				'after_title'   => '</'. $headings .'>',
			) );
		}

		/**
		 * Alter main sidebar to display events sidebar
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public function display_events_sidebar( $sidebar ) {
			if ( $this->is_tribe_events() && is_active_sidebar( 'tribe_events_sidebar' ) ) {
				$sidebar = 'tribe_events_sidebar';
			}
			return $sidebar;
		}

		/**
		 * Helper function checks if we are currently on an events page/post/archive
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public static function is_tribe_events() {
			if ( is_search() ) {
				return false;
			}
			if ( tribe_is_event()
				|| tribe_is_event_category()
				|| tribe_is_in_main_loop()
				|| tribe_is_view()
				|| is_singular( 'tribe_events' ) ) {
				return true;
			}
		}

		/**
		 * Disables the next/previous links for tribe events because they already have some.
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public static function next_prev( $return ) {
			if ( is_singular( 'tribe_events' ) ) {
				$return = false;
			}
			return $return;
		}

		/**
		 * Adds background accents for tribe events
		 *
		 * @since  2.0.0
		 * @access public
		 */
		public static function accent_backgrounds( $backgrounds ) {
			return array_merge( $backgrounds, array(
				'#tribe-events .tribe-events-button',
				'#tribe-events .tribe-events-button:hover',
				'#tribe_events_filters_wrapper input[type=submit]',
				'.tribe-events-button',
				'.tribe-events-button.tribe-active:hover',
				'.tribe-events-button.tribe-inactive',
				'.tribe-events-button:hover',
				'.tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-]',
				'.tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-]>a',
			) );
		}

	}

}
$wpex_tribe_events_config = new WPEX_Tribe_Events_Config();