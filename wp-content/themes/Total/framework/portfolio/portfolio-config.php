<?php
/**
 * Portfolio Post Type Configuration file
 *
 * @package Total WordPress Theme
 * @subpackage Portfolio Functions
 * @version 3.3.0
 */

// Set global var
global $wpex_portfolio_config;

// The class
class WPEX_Portfolio_Config {

	/**
	 * Get things started.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		// Include helper functions first so we can use them in the class
		require_once( WPEX_FRAMEWORK_DIR .'portfolio/portfolio-helpers.php' );

		// Adds the portfolio post type
		add_action( 'init', array( $this, 'register_post_type' ), 0 );

		// Adds the portfolio taxonomies
		if ( 'off' != wpex_get_mod( 'portfolio_tags', 'on' ) ) {
			add_action( 'init', array( $this, 'register_tags' ), 0 );
		}
		if ( 'off' != wpex_get_mod( 'portfolio_categories', 'on' ) ) {
			add_action( 'init', array( $this, 'register_categories' ), 0 );
		}

		// Admin only actions
		if ( is_admin() ) {

			// Adds columns in the admin view for taxonomies
			add_filter( 'manage_edit-portfolio_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_portfolio_posts_custom_column', array( $this, 'column_display' ), 10, 2 );

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( $this, 'tax_filters' ) );

			// Create Editor for altering the post type arguments
			add_action( 'admin_menu', array( $this, 'add_page' ) );
			add_action( 'admin_init', array( $this,'register_page_options' ) );
			add_action( 'admin_notices', array( $this, 'notices' ) );
			add_action( 'admin_print_styles-portfolio_page_wpex-portfolio-editor', array( $this,'css' ) );

		}

		// Adds the portfolio custom sidebar
		add_filter( 'widgets_init', array( $this, 'register_sidebar' ) );
		add_filter( 'wpex_get_sidebar', array( $this, 'display_sidebar' ) );

		// Alter the post layouts for portfolio posts and archives
		add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ) );

		// Posts per page
		add_action( 'pre_get_posts', array( $this, 'posts_per_page' ) );

		// Add image sizes
		add_filter( 'wpex_image_sizes', array( $this, 'add_image_sizes' ) );

		// Single next/prev visibility
		add_filter( 'wpex_has_next_prev', array( $this, 'next_prev' ) );

		// Tweak page header title
		add_filter( 'wpex_page_header_title_args', array( $this, 'alter_title' ) );

		// Add gallery metabox to portfolio
		add_filter( 'wpex_gallery_metabox_post_types', array( $this, 'add_gallery_metabox' ), 20 );

		// Return true for social share check so it can use the builder
		add_filter( 'wpex_has_social_share', array( $this, 'social_share' ) );

		// Register translation strings
		add_filter( 'wpex_register_theme_mod_strings', array( $this, 'register_theme_mod_strings' ) );
		
	}
	
	/**
	 * Register post type.
	 *
	 * @since 2.0.0
	 */
	public function register_post_type() {

		// Get values and sanitize
		$name             = wpex_get_portfolio_name();
		$singular_name    = wpex_get_portfolio_singular_name();
		$slug             = wpex_get_mod( 'portfolio_slug' );
		$slug             = $slug ? $slug : 'portfolio-item';
		$menu_icon        = wpex_get_portfolio_menu_icon();
		$portfolio_search = wpex_get_mod( 'portfolio_search', true );
		$portfolio_search = ! $portfolio_search ? true : false;

		// Args
		$args = array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $singular_name,
				'add_new' => esc_html__( 'Add New', 'total' ),
				'add_new_item' => esc_html__( 'Add New Item', 'total' ),
				'edit_item' => esc_html__( 'Edit Item', 'total' ),
				'new_item' => esc_html__( 'Add New Item', 'total' ),
				'view_item' => esc_html__( 'View Item', 'total' ),
				'search_items' => esc_html__( 'Search Items', 'total' ),
				'not_found' => esc_html__( 'No Items Found', 'total' ),
				'not_found_in_trash' => esc_html__( 'No Items Found In Trash', 'total' )
			),
			'public' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'menu_icon' => 'dashicons-'. $menu_icon,
			'menu_position' => 20,
			'exclude_from_search' => $portfolio_search,
			'rewrite' => array(
				'slug' => $slug,
			),
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'comments',
				'custom-fields',
				'revisions',
				'author',
				'page-attributes',
			),
		);

		// Register the post type
		register_post_type( 'portfolio', apply_filters( 'wpex_portfolio_args', $args ) );

	}

	/**
	 * Register Portfolio tags.
	 *
	 * @since 2.0.0
	 */
	public static function register_tags() {

		// Define and sanitize options
		$name = wpex_get_mod( 'portfolio_tag_labels');
		$name = $name ? $name : esc_html__( 'Portfolio Tags', 'total' );
		$slug = wpex_get_mod( 'portfolio_tag_slug' );
		$slug = $slug ? $slug : 'portfolio-tag';

		// Define portfolio tag arguments
		$args = array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $name,
				'menu_name' => $name,
				'search_items' => esc_html__( 'Search','total' ),
				'popular_items' => esc_html__( 'Popular', 'total' ),
				'all_items' => esc_html__( 'All', 'total' ),
				'parent_item' => esc_html__( 'Parent', 'total' ),
				'parent_item_colon' => esc_html__( 'Parent', 'total' ),
				'edit_item' => esc_html__( 'Edit', 'total' ),
				'update_item' => esc_html__( 'Update', 'total' ),
				'add_new_item' => esc_html__( 'Add New', 'total' ),
				'new_item_name' => esc_html__( 'New', 'total' ),
				'separate_items_with_commas' => esc_html__( 'Separate with commas', 'total' ),
				'add_or_remove_items' => esc_html__( 'Add or remove', 'total' ),
				'choose_from_most_used' => esc_html__( 'Choose from the most used', 'total' ),
			),
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
			'rewrite' => array(
				'slug'  => $slug,
			),
			'query_var' => true,
		);

		// Apply filters
		$args = apply_filters( 'wpex_taxonomy_portfolio_tag_args', $args );

		// Register the portfolio tag taxonomy
		register_taxonomy( 'portfolio_tag', array( 'portfolio' ), $args );

	}

	/**
	 * Register Portfolio category.
	 *
	 * @since 2.0.0
	 */
	public static function register_categories() {

		// Define and sanitize options
		$name = esc_html( wpex_get_mod( 'portfolio_cat_labels' ) );
		$name = $name ? $name : esc_html__( 'Portfolio Categories', 'total' );
		$slug = wpex_get_mod( 'portfolio_cat_slug' );
		$slug = $slug ? esc_html( $slug ) : 'portfolio-category';

		// Define args and apply filters
		$args = apply_filters( 'wpex_taxonomy_portfolio_category_args', array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $name,
				'menu_name' => $name,
				'search_items' => esc_html__( 'Search','total' ),
				'popular_items' => esc_html__( 'Popular', 'total' ),
				'all_items' => esc_html__( 'All', 'total' ),
				'parent_item' => esc_html__( 'Parent', 'total' ),
				'parent_item_colon' => esc_html__( 'Parent', 'total' ),
				'edit_item' => esc_html__( 'Edit', 'total' ),
				'update_item' => esc_html__( 'Update', 'total' ),
				'add_new_item' => esc_html__( 'Add New', 'total' ),
				'new_item_name' => esc_html__( 'New', 'total' ),
				'separate_items_with_commas' => esc_html__( 'Separate with commas', 'total' ),
				'add_or_remove_items' => esc_html__( 'Add or remove', 'total' ),
				'choose_from_most_used' => esc_html__( 'Choose from the most used', 'total' ),
			),
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => $slug ),
			'query_var' => true
		) );

		// Register the portfolio category taxonomy
		register_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );

	}

	/**
	 * Adds columns to the WP dashboard edit screen.
	 *
	 * @since 2.0.0
	 */
	public static function edit_columns( $columns ) {
		if ( taxonomy_exists( 'portfolio_category' ) ) {
			$columns['portfolio_category'] = esc_html__( 'Category', 'total' );
		}
		if ( taxonomy_exists( 'portfolio_tag' ) ) {
			$columns['portfolio_tag']      = esc_html__( 'Tags', 'total' );
		}
		return $columns;
	}
	

	/**
	 * Adds columns to the WP dashboard edit screen.
	 *
	 * @since 2.0.0
	 */
	public static function column_display( $column, $post_id ) {

		switch ( $column ) :

			// Display the portfolio categories in the column view
			case 'portfolio_category':

				if ( $category_list = get_the_term_list( $post_id, 'portfolio_category', '', ', ', '' ) ) {
					echo $category_list;
				} else {
					echo '&mdash;';
				}

			break;

			// Display the portfolio tags in the column view
			case 'portfolio_tag':

				if ( $tag_list = get_the_term_list( $post_id, 'portfolio_tag', '', ', ', '' ) ) {
					echo $tag_list;
				} else {
					echo '&mdash;';
				}

			break;

		endswitch;

	}

	/**
	 * Adds taxonomy filters to the portfolio admin page.
	 *
	 * @since 2.0.0
	 */
	public static function tax_filters() {
		global $typenow;
		$taxonomies = array( 'portfolio_category', 'portfolio_tag' );
		if ( 'portfolio' == $typenow ) {
			foreach ( $taxonomies as $tax_slug ) {
				if ( ! taxonomy_exists( $tax_slug ) ) {
					continue;
				}
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}

	/**
	 * Add sub menu page for the Portfolio Editor.
	 *
	 * @since 2.0.0
	 * @link    http://codex.wordpress.org/Function_Reference/add_theme_page
	 */
	public function add_page() {
		$wpex_portfolio_editor = add_submenu_page(
			'edit.php?post_type=portfolio',
			esc_html__( 'Post Type Editor', 'total' ),
			esc_html__( 'Post Type Editor', 'total' ),
			'administrator',
			'wpex-portfolio-editor',
			array( $this, 'create_admin_page' )
		);
		add_action( 'load-'. $wpex_portfolio_editor, array( $this, 'flush_rewrite_rules' ) );
	}

	/**
	 * Flush re-write rules
	 *
	 * @since 3.3.0
	 */
	public static function flush_rewrite_rules() {
		$screen = get_current_screen();
		if ( $screen->id == 'portfolio_page_wpex-portfolio-editor' ) {
			flush_rewrite_rules();
		}

	}

	/**
	 * Function that will register the portfolio editor admin page.
	 *
	 * @since 2.0.0
	 * @link    http://codex.wordpress.org/Function_Reference/register_setting
	 */
	public function register_page_options() {
		register_setting( 'wpex_portfolio_options', 'wpex_portfolio_branding', array( $this, 'sanitize' ) );
	}

	/**
	 * Displays saved message after settings are successfully saved
	 *
	 * @since 2.0.0
	 * @link    http://codex.wordpress.org/Function_Reference/settings_errors
	 */
	public static function notices() {
		settings_errors( 'wpex_portfolio_editor_page_notices' );
	}

	/**
	 * Sanitizes input and saves theme_mods.
	 *
	 * @since 2.0.0
	 */
	public static function sanitize( $options ) {

		// Save values to theme mod
		if ( ! empty ( $options ) ) {

			// Checkboxes
			$checkboxes = array(
				'portfolio_categories',
				'portfolio_tags',
			);
			foreach ( $checkboxes as $checkbox ) {
				if ( ! empty( $options[$checkbox] ) ) {
					remove_theme_mod( $checkbox );
					unset( $options[$checkbox] );
				} else {
					set_theme_mod( $checkbox, 'off' );
				}
			}

			// Not checkboxes
			foreach( $options as $key => $value ) {
				if ( $value ) {
					set_theme_mod( $key, $value );
				} else {
					remove_theme_mod( $key );
				}
			}

		}

		// Add notice
		add_settings_error(
			'wpex_portfolio_editor_page_notices',
			esc_attr( 'settings_updated' ),
			esc_html__( 'Settings saved and rewrite rules flushed.', 'total' ),
			'updated'
		);

		// Lets delete the options as we are saving them into theme mods
		$options = '';
		return;
	}

	/**
	 * Output for the actual Portfolio Editor admin page.
	 *
	 * @since 2.0.0
	 */
	public static function create_admin_page() { ?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Post Type Editor', 'total' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'wpex_portfolio_options' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Admin Icon', 'total' ); ?></th>
						<td>
							<?php
							// Mod
							$mod = wpex_get_mod( 'portfolio_admin_icon', null );
							$mod = 'portfolio' == $mod ? '' : $mod;
							// Dashicons list
							$dashicons = wpex_get_dashicons_array(); ?>
							<div id="wpex-dashicon-select" class="wpex-clr">
								<?php foreach ( $dashicons as $key => $val ) :
									$value = 'portfolio' == $key ? '' : $key;
									$class = $mod == $value ? 'button-primary' : 'button-secondary'; ?>
									<a href="#" data-value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $key ); ?>"><span class="dashicons dashicons-<?php echo $key; ?>"></span></a>
								<?php endforeach; ?>
							</div>
							<input type="hidden" name="wpex_portfolio_branding[portfolio_admin_icon]" id="wpex-dashicon-select-input" value="<?php echo esc_attr( $mod ); ?>"></td>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Post Type: Name', 'total' ); ?></th>
						<td><input type="text" name="wpex_portfolio_branding[portfolio_labels]" value="<?php echo wpex_get_mod( 'portfolio_labels' ); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Post Type: Singular Name', 'total' ); ?></th>
						<td><input type="text" name="wpex_portfolio_branding[portfolio_singular_name]" value="<?php echo wpex_get_mod( 'portfolio_singular_name' ); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Post Type: Slug', 'total' ); ?></th>
						<td><input type="text" name="wpex_portfolio_branding[portfolio_slug]" value="<?php echo wpex_get_mod( 'portfolio_slug' ); ?>" /></td>
					</tr>
					<tr valign="top" id="wpex-tags-enable">
						<th scope="row"><?php esc_html_e( 'Enable Tags', 'total' ); ?></th>
						<?php
						// Get checkbox value
						$mod = wpex_get_mod( 'portfolio_tags', 'on' );
						$mod = 'off' != $mod ? 'on' : 'off'; // sanitize ?>
						<td><input type="checkbox" name="wpex_portfolio_branding[portfolio_tags]" value="<?php echo esc_attr( $mod ); ?>" <?php checked( $mod, 'on' ); ?> /></td>
					</tr>
					<tr valign="top" id="wpex-tags-label">
						<th scope="row"><?php esc_html_e( 'Tags: Label', 'total' ); ?></th>
						<td><input type="text" name="wpex_portfolio_branding[portfolio_tag_labels]" value="<?php echo wpex_get_mod( 'portfolio_tag_labels' ); ?>" /></td>
					</tr>
					<tr valign="top" id="wpex-tags-slug">
						<th scope="row"><?php esc_html_e( 'Tags: Slug', 'total' ); ?></th>
						<td><input type="text" name="wpex_portfolio_branding[portfolio_tag_slug]" value="<?php echo wpex_get_mod( 'portfolio_tag_slug' ); ?>" /></td>
					</tr>
					<tr valign="top" id="wpex-categories-enable">
						<th scope="row"><?php esc_html_e( 'Enable Categories', 'total' ); ?></th>
						<?php
						// Get checkbox value
						$mod = wpex_get_mod( 'portfolio_categories', 'on' );
						$mod = 'off' != $mod ? 'on' : 'off'; // sanitize ?>
						<td><input type="checkbox" name="wpex_portfolio_branding[portfolio_categories]" value="<?php echo esc_attr( $mod ); ?>" <?php checked( $mod, 'on' ); ?> /></td>
					</tr>
					<tr valign="top" id="wpex-categories-label">
						<th scope="row"><?php esc_html_e( 'Categories: Label', 'total' ); ?></th>
						<td><input type="text" name="wpex_portfolio_branding[portfolio_cat_labels]" value="<?php echo wpex_get_mod( 'portfolio_cat_labels' ); ?>" /></td>
					</tr>
					<tr valign="top" id="wpex-categories-slug">
						<th scope="row"><?php esc_html_e( 'Categories: Slug', 'total' ); ?></th>
						<td><input type="text" name="wpex_portfolio_branding[portfolio_cat_slug]" value="<?php echo wpex_get_mod( 'portfolio_cat_slug' ); ?>" /></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
			<script>
				( function( $ ) {
					"use strict";
					$( document ).ready( function() {
						// Dashicons
						var $buttons = $( '#wpex-dashicon-select a' ),
							$input   = $( '#wpex-dashicon-select-input' );
						$buttons.click( function() {
							var $activeButton = $( '#wpex-dashicon-select a.button-primary' );
							$activeButton.removeClass( 'button-primary' ).addClass( 'button-secondary' );
							$( this ).addClass( 'button-primary' );
							$input.val( $( this ).data( 'value' ) );
							return false;
						} );
						// Categories enable/disable
						var $catsEnable   = $( '#wpex-categories-enable input' ),
							$CatsTrToHide = $( '#wpex-categories-label, #wpex-categories-slug' );
						if ( 'off' == $catsEnable.val() ) {
							$CatsTrToHide.hide();
						}
						$( $catsEnable ).change(function () {
							if ( $( this ).is( ":checked" ) ) {
								$CatsTrToHide.show();
							} else {
								$CatsTrToHide.hide();
							}
						} );
						// Tags enable/disable
						var $tagsEnable   = $( '#wpex-tags-enable input' ),
							$tagsTrToHide = $( '#wpex-tags-label, #wpex-tags-slug' );
						if ( 'off' == $tagsEnable.val() ) {
							$tagsTrToHide.hide();
						}
						$( $tagsEnable ).change(function () {
							if ( $( this ).is( ":checked" ) ) {
								$tagsTrToHide.show();
							} else {
								$tagsTrToHide.hide();
							}
						} );
					} );
				} ) ( jQuery );
			</script>
		</div>
	<?php }

	/**
	 * Post Type Editor CSS
	 *
	 * @since 3.3.0
	 */
	public static function css() { ?>
	
		<style type="text/css">
			#wpex-dashicon-select { max-width: 800px; }
			#wpex-dashicon-select a { display: inline-block; margin: 2px; padding: 0; width: 32px; height: 32px; line-height: 32px; text-align: center; }
			#wpex-dashicon-select a .dashicons,
			#wpex-dashicon-select a .dashicons-before:before { line-height: inherit; }
		</style>

	<?php }

	/**
	 * Registers a new custom portfolio sidebar.
	 *
	 * @since 2.0.0
	 */
	public static function register_sidebar() {

		// Return if custom sidebar is disabled
		if ( ! wpex_get_mod( 'portfolio_custom_sidebar', true ) ) {
			return;
		}

		// Get heading tag
		$heading_tag = wpex_get_mod( 'sidebar_headings', 'div' );
		$heading_tag = $heading_tag ? $heading_tag : 'div';

		// Get post type object to name sidebar correctly
		$obj            = get_post_type_object( 'portfolio' );
		$post_type_name = $obj->labels->name;

		// Register custom sidebar
		register_sidebar( array (
			'name'          => $post_type_name .' '. esc_html__( 'Sidebar', 'total' ),
			'id'            => 'portfolio_sidebar',
			'before_widget' => '<div class="sidebar-box %2$s clr">',
			'after_widget'  => '</div>',
			'before_title'  => '<'. $heading_tag .' class="widget-title">',
			'after_title'   => '</'. $heading_tag .'>',
		) );
	}

	/**
	 * Alter main sidebar to display portfolio sidebar.
	 *
	 * @since 2.0.0
	 */
	public static function display_sidebar( $sidebar ) {

		// Display portfolio_sidebar where necessary
		if ( wpex_get_mod( 'portfolio_custom_sidebar', true ) && ( is_singular( 'portfolio' ) || wpex_is_portfolio_tax() ) ) {
			$sidebar = 'portfolio_sidebar';
		}

		// Return correct sidebar to display
		return $sidebar;

	}

	/**
	 * Alter the post layouts for portfolio posts and archives.
	 *
	 * @since 2.0.0
	 */
	public static function layouts( $class ) {
		if ( is_singular( 'portfolio' ) ) {
			$class = wpex_get_mod( 'portfolio_single_layout', 'full-width' );
		} elseif ( wpex_is_portfolio_tax() && ! is_search() ) {
			$class = wpex_get_mod( 'portfolio_archive_layout', 'full-width' );
		}
		return $class;
	}

	/**
	 * Alters posts per page for the portfolio taxonomies.
	 *
	 * @since 2.0.0
	 * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
	 */
	public static function posts_per_page( $query ) {
		if ( wpex_is_portfolio_tax() && $query->is_main_query() ) {
			$query->set( 'posts_per_page', wpex_get_mod( 'portfolio_archive_posts_per_page', '12' ) );
			return;
		}
	}

	/**
	 * Adds image sizes for the portfolio to the image sizes panel.
	 *
	 * @since 2.0.0
	 */
	public static function add_image_sizes( $sizes ) {
		$obj            = get_post_type_object( 'portfolio' );
		$post_type_name = $obj->labels->singular_name;
		$new_sizes  = array(
			'portfolio_entry' => array(
				'label'  => sprintf( esc_html__( '%s Entry', 'total' ), $post_type_name ),
				'width'  => 'portfolio_entry_image_width',
				'height' => 'portfolio_entry_image_height',
				'crop'    => 'portfolio_entry_image_crop',
			),
			'portfolio_post'  => array(
				'label'  => sprintf( esc_html__( '%s Post', 'total' ), $post_type_name ),
				'width'  => 'portfolio_post_image_width',
				'height' => 'portfolio_post_image_height',
				'crop'   => 'portfolio_post_image_crop',
			),
		);
		$sizes = array_merge( $sizes, $new_sizes );
		return $sizes;
	}

	/**
	 * Adds the portfolio post type to the gallery metabox post types array.
	 *
	 * @since 2.0.0
	 */
	public static function add_gallery_metabox( $types ) {
		$types[] = 'portfolio';
		return $types;
	}

	/**
	 * Disables the next/previous links if disabled via the customizer.
	 *
	 * @since 2.0.0
	 */
	public static function next_prev( $return ) {
		if ( is_singular( 'portfolio' ) && ! wpex_get_mod( 'portfolio_next_prev', true ) ) {
			$return = false;
		}
		return $return;
	}

	/**
	 * Tweak the page header title args
	 *
	 * @since 2.1.0
	 */
	public static function alter_title( $args ) {
		if ( is_singular( 'portfolio' ) ) {
			if ( ! in_array( 'title', wpex_portfolio_post_blocks() ) ) {
				$args['string']   = get_the_title();
				$args['html_tag'] = 'h1';
			}
		}
		return $args;
	}

	/**
	 * Enables social sharing
	 *
	 * @since 2.1.0
	 */
	public static function social_share( $return ) {
		if ( is_singular( 'portfolio' ) ) {
			$return = true;
		}
		return $return;
	}

	/**
	 * Register portfolio theme mod strings
	 *
	 * @since 2.1.0
	 */
	public static function register_theme_mod_strings( $strings ) {
		if ( is_array( $strings ) ) {
			$strings['portfolio_labels'] = 'Portfolio';
			$strings['portfolio_singular_name'] = 'Portfolio Item';
		}
		return $strings;
	}

}
$wpex_portfolio_config = new WPEX_Portfolio_Config;