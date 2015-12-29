<?php
/**
 * Visual Composer Login Form
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Not needed in admin ever
if ( is_admin() ) {
    return;
}

// Get and extract shortcode attributes
extract( vc_map_get_attributes( $this->getShortcode(), $atts ) );

// Get classes
$add_classes = 'vcex-login-form clr';
if ( $classes ) {
	$add_classes .= $this->getExtraClass( $classes );
}
if ( $css_animation ) {
	$add_classes .= $this->getCSSAnimation( $css_animation );
}

// Check if user is logged in
if ( is_user_logged_in() && ! wpex_is_front_end_composer() ) :

	// Add logged in class
	$add_classes .= ' logged-in'; ?>

	<div class="<?php echo $add_classes; ?>" <?php vcex_unique_id( $unique_id ); ?>>
		<?php echo do_shortcode( $content ); ?>
	</div><!-- .vcex-login-form -->

<?php
// If user is not logged in display login form
else :

	// Redirection URL
	if ( ! $redirect ) {
		$redirect = site_url( $_SERVER['REQUEST_URI'] );
	}
	
	// Form args
	$args = array(
		'echo'           => true,
		'redirect'       => $redirect ? esc_url( $redirect ) : false,
		'form_id'        => 'vcex-loginform',
		'label_username' => $label_username ? $label_username : esc_html__( 'Username', 'total' ),
		'label_password' => $label_password ? $label_password : esc_html__( 'Password', 'total' ),
		'label_remember' => $label_remember ? $label_remember : esc_html__( 'Remember Me', 'total' ),
		'label_log_in'   => $label_log_in ? $label_log_in : esc_html__( 'Log In', 'total' ),
		'remember'       => true,
		'value_username' => NULL,
		'value_remember' => false,
	); ?>

	<div class="<?php echo $add_classes; ?>" <?php vcex_unique_id( $unique_id ); ?>>
		<?php wp_login_form( $args ); ?>
	</div><!-- .vcex-login-form -->

<?php endif; ?>