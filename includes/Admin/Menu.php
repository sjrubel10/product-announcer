<?php

namespace Product\Announcer\Admin;

/**
 * The Menu handler class
 */
class Menu {

    /**
     * Initialize the class
     */
    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page(
            __( 'Product Announcer', 'product-announcer' ), // Menu title
            __( 'Announcer', 'product-announcer' ), // Menu label
            'manage_options', // Capability required to access the menu
            'product-announcer', // Menu slug
            [ $this, 'plugin_page' ], // Callback function to render the page
            'dashicons-megaphone' // Icon
        );
    }



    /**
     * Render the plugin page
     *
     * @return void
     */
    public function plugin_page() {
        require_once plugin_dir_path( __FILE__ ) . 'templates/createmailsettings.php';
    }
}
