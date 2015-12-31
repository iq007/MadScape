<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles');
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts');

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

function theme_enqueue_scripts(){
    wp_enqueue_script('theme_enqueue_scripts', get_stylesheet_directory_uri() . '/js/total-child-ui-helpers.js', array( 'jquery' ), '1.0', true );
}

add_filter('show_admin_bar', '__return_false');

/*add_action('set_current_user', 'csstricks_hide_admin_bar');
function csstricks_hide_admin_bar() {
    if (!current_user_can('edit_posts')) {
        show_admin_bar(false);
    }
}*/

?>