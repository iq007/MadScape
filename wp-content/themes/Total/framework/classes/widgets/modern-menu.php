<?php
/**
 * Modern Menu Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_Modern_Menu' ) ) {
	class WPEX_Modern_Menu extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_modern_menu',
				$branding . esc_html__( 'Modern Sidebar Menu', 'total' )
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 1.0.0
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		function widget( $args, $instance ) {

			// Set vars
			$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

			// Important hook
			echo $args['before_widget'];

			// Display title
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			if ( $nav_menu ) {
				echo wp_nav_menu( array(
						'menu_class'  => 'modern-menu-widget',
						'fallback_cb' => '',
						'menu'        => $nav_menu,
					)
				);
			}

			// Important hook
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 1.0.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		function update( $new_instance, $old_instance ) {
			$instance             = array();
			$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['nav_menu'] = ( ! empty( $new_instance['nav_menu'] ) ) ? strip_tags( $new_instance['nav_menu'] ) : '';
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 1.0.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			// Vars
			extract( wp_parse_args( ( array ) $instance, array(
				'title'    => esc_html__( 'Browse', 'total' ),
				'nav_menu' => '',

			) ) ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?>:</label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php esc_html_e( 'Select Menu', 'total' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
					<?php
					// Get all menus
					$menus = get_terms( 'nav_menu', array(
						'hide_empty' => false,
					) );
					// Loop through menus to add options
					if ( ! empty( $menus ) ) {
						$nav_menu = $nav_menu ? $nav_menu : '';
						foreach ( $menus as $menu ) {
							echo '<option value="' . esc_attr( $menu->term_id ) . '"' . selected( $nav_menu, $menu->term_id, false ) . '>'. esc_html( $menu->name ) . '</option>';
						}
					} ?>
				</select>
			</p>
			<?php
		}
	}
}

// Register the widget
if ( ! function_exists( 'register_wpex_modern_menu' ) ) {
	function register_wpex_modern_menu() {
		register_widget( 'WPEX_Modern_Menu' );
	}
}
add_action( 'widgets_init', 'register_wpex_modern_menu' );