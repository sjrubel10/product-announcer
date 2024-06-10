<?php
/**
 * Plugin Name: Product Announcer
 * Description: Stay informed and keep your users engaged with automatic email notifications whenever a new product is created on your WordPress website.
 * Plugin URI: https://product-announcer.co
 * Requires at least: WP 4.9
 * Tested up to: WP 6.4
 * Author: sjrubel10
 * License:     GPL v2
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Author URI: https://profiles.wordpress.org/sjrubel10/
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Tags: product, announcer, email, notifier, notification
 * Text Domain: product-announcer
 * Domain Path: /languages
 * WC requires at least: 3.6
 * WC tested up to: 8.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
new Product\Announcer\Api();

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
        define( 'WOOBEMP_API_LINK', PA_ProductAnnouncer_FILE . 'api/' );
        define( 'PA_ProductAnnouncer_URL', plugins_url( '', PA_ProductAnnouncer_FILE ) );
        define( 'PA_ProductAnnouncer_ASSETS', PA_ProductAnnouncer_URL . '/assets/' );
        define( 'PA_ProductAnnouncer_PLUGIN_NAME', plugin_basename(__FILE__ ) );

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
