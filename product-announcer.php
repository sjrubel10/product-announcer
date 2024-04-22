<?php
/**
 * Plugin Name: Product Announcer
 * Description: Product Announcer sends automatic email notifications for new product creations on your WordPress site. Customize emails to match your brand and enhance user engagement. Integrated with the WordPress dashboard, it ensures users stay informed without missing updates.
 * Plugin URI: https://product-announcer.co
 * Author: Rubel
 * Author URI: https://rubelmia.co
 * Text Domain: product-announcer
 * Version: 1.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Product_Announcer {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Product_Announcer
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'PA_ProductAnnouncer_VERSION', self::version );
        define( 'PA_ProductAnnouncer_FILE', __FILE__ );
        define( 'PA_ProductAnnouncer_PATH', __DIR__ );
        define( 'PA_ProductAnnouncer_URL', plugins_url( '', PA_ProductAnnouncer_FILE ) );
        define( 'PA_ProductAnnouncer_ASSETS', PA_ProductAnnouncer_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        if ( is_admin() ) {
            new Product\Announcer\Admin();
        } else {
            new Product\Announcer\Frontend();
        }

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'product-announcer_installed' );

        if ( ! $installed ) {
            update_option( 'product-announcer_installed', time() );
        }

        update_option( 'product-announcer_version', PA_ProductAnnouncer_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Product_Announcer
 */
function product_announcer() {
    return Product_Announcer::init();
}

// kick-off the plugin
product_announcer();
