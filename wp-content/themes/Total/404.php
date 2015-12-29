<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 3.3.0
 */

get_header(); ?>
    
    <div id="content-wrap" class="container clr">

        <?php wpex_hook_primary_before(); ?>

        <div id="primary" class="content-area clr">

            <?php wpex_hook_content_before(); ?>

            <main id="content" class="clr site-content" role="main">

                <?php wpex_hook_content_top(); ?>

                <article class="entry clr">

                    <?php
                    // Display custom text
                    if ( ! empty( wpex_get_translated_theme_mod( 'error_page_text' ) ) )  : ?>

                        <div class="custom-error404-content clr">
                            <?php echo apply_filters(
                                'the_content',
                                wpex_get_translated_theme_mod( 'error_page_text' )
                            ); ?>
                        </div><!-- .custom-error404-content -->

                    <?php
                    // Display default text
                    else : ?>

                        <div class="error404-content clr">

                            <h1><?php echo esc_html_x( 'You Broke The Internet!', '404 Page Header', 'total' ) ?></h1>
                            <p><?php echo esc_html_x( 'We are just kidding...but sorry the page you were looking for can not be found.', '404 Page Text', 'total' ); ?></p>

                        </div><!-- .error404-content -->

                    <?php endif; ?>

                </article><!-- .entry -->

                <?php wpex_hook_content_bottom(); ?>

            </main><!-- #content -->

            <?php wpex_hook_content_after(); ?>

        </div><!-- #primary -->

        <?php wpex_hook_primary_after(); ?>

    </div><!-- .container -->
    
<?php get_footer(); ?>