<?php
/**
 * Adds image sizes for use with the theme
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Image_Sizes' ) ) {
	class WPEX_Image_Sizes {
		private $sizes;

		/**
		 * Main constructor
		 *
		 * @since Total 1.6.3
		 */
		public function __construct() {
		
			// Array of image sizes
			$this->sizes = array();

			// Define and add image sizes => Needs low priority for Visual Composer
			add_filter( 'init', array( $this, 'define_sizes' ), 0 );
			add_filter( 'init', array( $this, 'add_sizes' ), 1 );

			// Prevent images from cropping when on the fly is enabled
			add_filter( 'intermediate_image_sizes_advanced', array( $this, 'do_not_crop_on_upload' ) );

			// Create admin panel
			add_action( 'admin_menu', array( $this, 'add_admin_page' ), 10 );
			add_action( 'admin_init', array( $this,'register_settings' ) );

		}

		/**
		 * Define array of image sizes used by the theme
		 *
		 * @since  2.0.0
		 */
		public function define_sizes() {
			$this->sizes = apply_filters( 'wpex_image_sizes', array(
				'lightbox' => array(
					'label'  => esc_html__( 'Lightbox Images', 'total' ),
					'width'  => 'lightbox_image_width',
					'height' => 'lightbox_image_height',
					'crop'   => 'lightbox_image_crop',
				),
				'blog_entry' => array(
					'label'  => esc_html__( 'Blog Entry', 'total' ),
					'width'  => 'blog_entry_image_width',
					'height' => 'blog_entry_image_height',
					'crop'   => 'blog_entry_image_crop',
				),
				'blog_post' => array(
					'label'  => esc_html__( 'Blog Post', 'total' ),
					'width'  => 'blog_post_image_width',
					'height' => 'blog_post_image_height',
					'crop'   => 'blog_post_image_crop',
				),
				'blog_post_full' => array(
					'label'  => esc_html__( 'Blog Post: Full-Width', 'total' ),
					'width'  => 'blog_post_full_image_width',
					'height' => 'blog_post_full_image_height',
					'crop'   => 'blog_post_full_image_crop',
				),
				'blog_related' => array(
					'label'  => esc_html__( 'Blog Post: Related', 'total' ),
					'width'  => 'blog_related_image_width',
					'height' => 'blog_related_image_height',
					'crop'   => 'blog_related_image_crop',
				),
			) );
		}

		/**
		 * Filter the image sizes automatically generated when uploading an image.
		 *
		 * @since 2.0.0
		 */
		public function do_not_crop_on_upload( $sizes ) {

			// Remove my image sizes from cropping if image resizing is enabled
			if ( wpex_get_mod( 'image_resizing', true ) && ! empty ( $this->sizes ) ) {
				foreach( $this->sizes as $size => $args ) {
					unset( $sizes[$size] );
				}
			}

			// Return $meta
			return $sizes;

		}

		/**
		 * Register image sizes in WordPress
		 *
		 * @since 2.0.0
		 */
		public function add_sizes() {
			
			// Get sizes array
			$sizes = $this->sizes;

			// Loop through sizes
			foreach ( $sizes as $size => $args ) {

				// Define dims
				$size = $size;

				// Extract args
				extract( $args );

				// Get theme mods
				$width  = wpex_get_mod( $width, '9999' );
				$height = wpex_get_mod( $height, '9999' );
				$crop   = wpex_get_mod( $crop );
				$crop   = $crop ? $crop : 'center-center';

				// Turn crop into array
				$crop = ( 'center-center' == $crop ) ? 1 : explode( '-', $crop );

				// If image resizing is disabled and a width or height is defined add image size
				if ( $width || $height ) {
					add_image_size( $size, $width, $height, $crop );
				}

			}

		}

		/**
		 * Add sub menu page
		 *
		 * @since 2.0.0
		 */
		public function add_admin_page() {
			add_submenu_page(
				WPEX_THEME_PANEL_SLUG,
				esc_html__( 'Image Sizes', 'total' ),
				esc_html__( 'Image Sizes', 'total' ),
				'administrator',
				WPEX_THEME_PANEL_SLUG . '-image-sizes',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @since 2.0.0
		 */
		public function register_settings() {
			register_setting( 'wpex_image_sizes', 'wpex_image_sizes', array( $this, 'admin_sanitize' ) ); 
		}

		/**
		 * Main Sanitization callback
		 *
		 * @since 2.0.0
		 */
		public function admin_sanitize( $options ) {

			// Check options first
			if ( ! is_array( $options ) || empty( $options ) || ( false === $options ) ) {
				return array();
			}

			// Save checkboxes
			$checkboxes = array( 'retina', 'image_resizing' );

			// Remove thememods for checkboxes not in array
			foreach ( $checkboxes as $checkbox ) {
				if ( isset( $options[$checkbox] ) ) {
					set_theme_mod( $checkbox, 1 );
				} else {
					set_theme_mod( $checkbox, 0 );
				}
			}

			// Standard options
			foreach( $options as $key => $value ) {
				if ( in_array( $key, $checkboxes ) ) {
					continue; // checkboxes already done
				}
				if ( ! empty( $value ) ) {
					set_theme_mod( $key, $value );
				} else {
					remove_theme_mod( $key );
				}
			}

			// No need to save in options table
			$options = '';
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 2.0.0
		 */
		public function create_admin_page() { ?>
			<div class="wrap">
				<h2><?php esc_html_e( 'Image Sizes', 'total' ); ?></h2>
				<p><?php esc_html_e( 'Define the exact cropping for all the featured images on your site. Leave the width and height empty to display the full image. Set any height to "9999" or empty to disable cropping and simply resize the image to the corresponding width. All image sizes defined below will be added to the list of WordPress image sizes.', 'total' ); ?></p>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpex_image_sizes' ); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Image Resizing', 'total' ); ?></th>
							<td>
								<fieldset>
									<label>
										<input id="wpex_image_resizing" type="checkbox" name="wpex_image_sizes[image_resizing]" <?php checked( wpex_get_mod( 'image_resizing', true ) ); ?>>
											<?php esc_html_e( 'Enable on the fly image cropping.', 'total' ); ?>
											<p class="description"><?php esc_html_e( 'This theme includes an advanced "on the fly" cropping function that uses the safe and native WordPress function "wp_get_image_editor". If enabled whenever you upload a new image it will NOT be cropped into all the different sizes defined below, but rather cropped when loaded on the front-end (cropped once then saved to your uploads directory), thus saving precious server space. However it may conflict with with certain CDN\'s, so you can disable if needed. If disabled you will need to "regenerate your thumbnails".', 'total' ); ?></p>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Retina', 'total' ); ?></th>
							<td>
								<fieldset>
									<label>
										<input id="wpex_retina" type="checkbox" name="wpex_image_sizes[retina]" <?php checked( wpex_get_mod( 'retina' ), true ); ?>> <?php esc_html_e( 'Enable retina support for your site (via retina.js).', 'total' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Retina Mode', 'total' ); ?></th>
							<?php $retina_mode = wpex_get_mod( 'retina_mode', 1 ); ?>
							<td>
								<fieldset>
									<label>
										<select id="wpex_retina_mode" name="wpex_image_sizes[retina_mode]">
											<option value="1" <?php selected( $retina_mode, 1, true ); ?>><?php esc_html_e( 'Auto search for retina images', 'total' ); ?></option>
											<option value="2" <?php selected( $retina_mode, 2, true ); ?>><?php esc_html_e( 'Require data-at2x for retina replacement', 'total' ); ?></option>
										</select>
									</label>
								</fieldset>
							</td>
						</tr>

						<?php
						// Get sizes & crop locations
						$sizes          = $this->sizes;
						$crop_locations = wpex_image_crop_locations(); ?>

						<?php
						// Loop through all sizes
						foreach ( $sizes as $size => $args ) : ?>

							<?php
							// Extract args
							extract( $args );

							// Label is required
							if ( ! $label ) {
								continue;
							}

							// Define values
							$width_value  = wpex_get_mod( $width );
							$height_value = wpex_get_mod( $height );
							$crop_value   = wpex_get_mod( $crop ); ?>

							<tr valign="top">
								<th scope="row"><?php echo strip_tags( $label ); ?></th>
								<td>
									<label for="<?php echo esc_attr( $width ); ?>"><?php esc_html_e( 'Width', 'total' ); ?></label>
									<input name="wpex_image_sizes[<?php echo esc_attr( $width ); ?>]" type="number" step="1" min="0" value="<?php echo esc_attr( $width_value ); ?>" class="small-text" />

									<label for="<?php echo esc_attr( $height ); ?>"><?php esc_html_e( 'Height', 'total' ); ?></label>
									<input name="wpex_image_sizes[<?php echo esc_attr( $height ); ?>]" type="number" step="1" min="0" value="<?php echo esc_attr( $height_value ); ?>" class="small-text" />
									<label for="<?php echo esc_attr( $crop ); ?>"><?php esc_html_e( 'Crop Location', 'total' ); ?></label>

									<select name="wpex_image_sizes[<?php echo esc_attr( $crop ); ?>]">
										<?php foreach ( $crop_locations as $key => $label ) { ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $crop_value, true ); ?>><?php echo strip_tags( $label ); ?></option>
										<?php } ?>
									</select>

								</td>
							</tr>

						<?php endforeach; ?>

					</table>

					<?php submit_button(); ?>

				</form>

				<div id="wpex_regenerating_tools" style="display:none;">
					<hr />
					<p><?php esc_html_e( 'Useful Plugins:', 'total' ); ?> <a href="https://wordpress.org/plugins/force-regenerate-thumbnails/" target="_blank"><?php esc_html_e( 'Regenerate Thumbnails', 'total' ); ?></a> | <a href="https://wordpress.org/plugins/image-cleanup/screenshots/" target="_blank"><?php esc_html_e( 'Image Cleanup', 'total' ); ?></a></p>
				</div><!-- #wpex_regenerating_tools -->

			</div><!-- .wrap -->

			<script>
				( function( $ ) {
					"use strict";

					// Disable and hide retina if image resizing is deleted
					var $imageResizing    = $( '#wpex_image_resizing' ),
						$imageResizingVal = $imageResizing.prop( 'checked' ),
						$retinaCheck      = $( '#wpex_retina' ),
						$retinaCheckVal   = $( '#wpex_retina' ).prop( 'checked' );

					// Check initial val
					if ( ! $imageResizingVal ) {
						$retinaCheck.attr('checked', false );
						$( '#wpex_retina' ).closest( 'tr' ).hide();
						$( '#wpex_retina_mode' ).closest( 'tr' ).hide();
						$( '#wpex_regenerating_tools' ).show();
					}

					if ( ! $retinaCheckVal ) {
						$( '#wpex_retina_mode' ).closest( 'tr' ).hide();
					}

					// Check on change
					$( $imageResizing ).change(function () {
						var $checked = $( this ).prop('checked');
						if ( $checked ) {
							$( '#wpex_retina' ).closest( 'tr' ).show();
							$( '#wpex_retina_mode' ).closest( 'tr' ).show();
							$( '#wpex_regenerating_tools' ).hide();
							$( '#wpex_retina' ).attr('checked', true );
						} else {
							$( '#wpex_retina' ).attr('checked', false );
							$( '#wpex_retina' ).closest( 'tr' ).hide();
							$( '#wpex_retina_mode' ).closest( 'tr' ).hide();
							$( '#wpex_regenerating_tools' ).show();
						}
					} );

					// Check on change
					$( $retinaCheck ).change(function () {
						var $checked = $( this ).prop( 'checked' );
						if ( $checked ) {
							$( '#wpex_retina_mode' ).closest( 'tr' ).show();
						} else {
							$( '#wpex_retina_mode' ).closest( 'tr' ).hide();
						}
					} );

				} ) ( jQuery );

			</script>
		<?php
		}

	}
}
new WPEX_Image_Sizes();