<?php
    /**
     * The template for displaying 404 pages (not found).
     *
     * @package Expatsport
     */
    get_header(null, array('headerClass'=>'solid'));

?>
    <div class="elWithHeaderPadding error-404 not-found">
        <section class="page-content">
            <div class="container">
                <h1 class="page-title">
                    <?php esc_html_e( "Oops! That page can't be found.", 'electra' ); ?>    
                </h1>
                <p><?php esc_html_e( 'Nothing was found at this location. Please use the menu to navigate the website.', 'electra' ); ?></p>
            </div>
        </section><!-- .page-content -->
    </div><!-- .error-404 -->
<?php
    get_footer();
