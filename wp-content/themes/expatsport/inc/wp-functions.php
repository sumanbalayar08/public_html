<?php
$expatsport_version = '1.0';

function expatsport_setup() {
    register_nav_menus(
        apply_filters(
            'storefront_register_nav_menus',
            array(
                'primary'   => __( 'Primary Menu', 'expatsport' ),
                'footer-1'  => __( 'Footer Menu 1', 'expatsport' ),
                'footer-2'  => __( 'Footer Menu 2', 'expatsport' ),
                'footer-3'  => __( 'Footer Menu 3', 'expatsport' ),
            )
        )
    );
    add_filter( 'nav_menu_link_attributes', 'navigation_items_description', 10, 3 );

    add_image_size( 'banner', 1920 );

    add_filter('jpeg_quality', function($arg){ return 100; });
}
add_action( 'after_setup_theme', 'expatsport_setup', 15 );

function navigation_items_description($atts, $item, $args) {
    if(isset($item->description) && $item->description != ""){
        $atts['data-caption'] = $item->description;
    }
    return $atts;
}

function expatsport_scripts() {
    global $expatsport_version;
    global $wp_scripts;

    $version = defined( 'DEV_MODE' ) && DEV_MODE ? rand() : rand();
    
    // Enqueue Styles
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/assets/css/custom-style.css', array(), $version );
wp_enqueue_style( 'f1-style', get_stylesheet_directory_uri() . '/assets/css/f1-style.css', array(), $version );


    // Enqueue Scripts
    if (!wp_script_is('jquery', 'enqueued')) {
        wp_enqueue_script('jquery');
    }
    wp_scripts()->add_data( 'jquery', 'group', 1 );
    wp_scripts()->add_data( 'jquery-core', 'group', 1 );
    wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );

    wp_enqueue_script( 'vendors', get_stylesheet_directory_uri() . '/assets/js/vendors.js', array('jquery'), $version, true );
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js', array(), $version, true );
    wp_enqueue_script( 'scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/ScrollTrigger.min.js', array('gsap'), $version, true );
    wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/assets/js/custom-script.js', array('scrolltrigger'), $version, true );


    wp_enqueue_script( 'f1-script', get_stylesheet_directory_uri() . '/assets/js/f1-script.js', array('jquery'), $version, true );

    // Localize Script
    $electraVars = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'themeUrl' => get_stylesheet_directory_uri(),
        'ajax_nonce_add_to_cart' => wp_create_nonce('ajax_add_to_cart_nonce'),
        'ajax_nonce_quote_contact' => wp_create_nonce('ajax_quote_contact_nonce'),
        'ajax_nonce_checkout_id' => wp_create_nonce('ajax_checkout_id_nonce'),
        'ajax_nonce_checkout' => wp_create_nonce('ajax_checkout_nonce'),
        'tickets_amount' => WC()->session->get('tickets_amount'),
        'package_price' => WC()->session->get('package_price'),
    );
    if (defined( 'DEV_MODE' ) && DEV_MODE) {
        $electraVars['gmapsKey'] = DEV_GMAP;
    }
    wp_localize_script( 'custom-script', 'electraVars', $electraVars);

    // Uncomment if needed: Dequeue CF7 scripts on demand
    /*
    wp_dequeue_script( 'swv' );
    wp_dequeue_script( 'contact-form-7' );
    wp_dequeue_script( 'google-recaptcha' );
    wp_dequeue_script( 'wpcf7-recaptcha' );
    */
}
add_action( 'wp_enqueue_scripts', 'expatsport_scripts', 20 );

function expatsport_acf_init() {
    if ( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title' 	=> 'Website Options',
            'menu_title' 	=> 'Website Options',
            'menu_slug' 	=> 'nav-general-settings',
            'capability' 	=> 'edit_posts',
            'redirect' 	=> false
        ));
    }
}
add_action( 'acf/init', 'expatsport_acf_init' );

