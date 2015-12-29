<?php
/**
 * Perform actions after updating the theme
 *
 * @package Total WordPress Theme
 * @subpackage Updates
 * @version 3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Hook to init to prevent any possible conflicts in main theme class
function wpex_after_update() {

	// Define dir
	$dir = WPEX_FRAMEWORK_DIR .'updates/';

	// Get theme version
	$theme_version   = WPEX_THEME_VERSION;
	//$theme_version = 1.0; print_r('testing_updates'); // for testing purposes to re-run all updates
	$initial_version = get_option( 'total_initial_version' );

	/*-------------------------------------------------------------------------------*/
	/* - Add initial version so we know the first time a user activated the theme
	/*-------------------------------------------------------------------------------*/
	if ( ! get_option( 'total_initial_version' ) ) {
		update_option( 'total_initial_version', $theme_version );
	}

	/*-------------------------------------------------------------------------------*/
	/* -  Get user theme version
	/*-------------------------------------------------------------------------------*/
	$old_version = get_option( 'total_version' );
	$old_version = $old_version ? $old_version : '2.1.3'; // Version is required and was added in v2.1.3

	/*-------------------------------------------------------------------------------*/
	/* -  Migrate OLD redux options
	/*-------------------------------------------------------------------------------*/
	if ( version_compare( '1.6.0', $initial_version, '>' )
		&& ! get_option( 'wpex_customizer_migration_complete' )
		&& get_option( 'wpex_options' )
	) {
		require_once( $dir .'redux-migrate.php' );
		update_option( 'wpex_customizer_migration_complete', 'completed' );
	} else {
		update_option( 'wpex_customizer_migration_complete', 'completed' );
	}

	/*-------------------------------------------------------------------------------*/
	/* - Functions that will always run after update
	/*-------------------------------------------------------------------------------*/
	if ( $old_version != $theme_version  ) {
		
		// Backup theme mods
		wpex_backup_mods();

		// Re-enable recommended plugins notice for updates
		set_theme_mod( 'recommend_plugins_enable', true );
		delete_metadata( 'user', null, 'tgmpa_dismissed_notice_wpex_theme', null, true );

	}

	/*-------------------------------------------------------------------------------*/
	/* -  UPDATE: 3.0.0
	/*-------------------------------------------------------------------------------*/
	if ( version_compare( '3.0.0', $old_version, '>' ) ) {
		require_once( $dir .'update-3_0_0.php' );
	}

	/*-------------------------------------------------------------------------------*/
	/* -  UPDATE: 3.3.0
	/*-------------------------------------------------------------------------------*/
	if ( version_compare( '3.3.0', $old_version, '>' ) ) {
		
		// Turn retina logo height into just logo height and delete old theme mod
		if ( $mod = wpex_get_mod( 'retina_logo_height' ) ) {
			set_theme_mod( 'logo_height', $mod );
			remove_theme_mod( 'retina_logo_height' );
		}

		// WooMenu cart enable/disable
		if ( ! wpex_get_mod( 'woo_menu_icon', true ) ) {
			set_theme_mod( 'woo_menu_icon_display', 'disabled' );
			remove_theme_mod( 'woo_menu_icon' );
		}

		// Sidebar heading color => remove duplicate setting
		if ( $mod = wpex_get_mod( 'sidebar_headings_color' ) ) {
			$mod2 = wpex_get_mod( 'sidebar_widget_title_typography' );
			if ( is_array( $mod2 ) ) {
				$mod2['color'] = $mod;
			} else {
				$mod2 = array( 'color' => $mod );
			}
			set_theme_mod( 'sidebar_widget_title_typography', $mod2 );
			remove_theme_mod( 'sidebar_headings_color' );
		}

		// Remove license key
		delete_option( 'wpex_product_license' );
		remove_theme_mod( 'envato_license_key' );

		// New single product thumb image sizes | Set equal to current post thumbnail size
		if ( WPEX_WOOCOMMERCE_ACTIVE ) {
			if ( $mod = wpex_get_mod( 'woo_post_width' ) ) {
				set_theme_mod( 'woo_post_thumb_width', $mod );
			}
			if ( $mod = wpex_get_mod( 'woo_post_height' ) ) {
				set_theme_mod( 'woo_post_thumb_height', $mod );
			}
			if ( $mod = wpex_get_mod( 'woo_post_image_crop' ) ) {
				set_theme_mod( 'woo_post_thumb_crop', $mod );
			}
		}

		// Auto updates removed
		delete_option( 'wpex_product_license' );

	}

	/*-------------------------------------------------------------------------------*/
	/* -  *** Update Theme Version ***
	/*-------------------------------------------------------------------------------*/
	update_option( 'total_version', $theme_version );

}
add_action( 'init', 'wpex_after_update' );