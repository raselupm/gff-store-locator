<?php
/*
 * Plugin Name: GFF Store Locator
 * Description: Store Locator for GFF
 * Version: 1.0
 * Author: Rasel Ahmed
 * Author URI: https://getfoundfirst.com
 * Text Domain: gff-store-locator
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// include codestar framework
if(! class_exists('CSF')) {
    // add admin notice
    add_action('admin_notices', 'gff_store_locator_admin_notice');
    function gff_store_locator_admin_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Please install and activate <strong>CodeStar Framework</strong> plugin to use <strong>GFF Store Locator</strong> plugin.', 'gff-store-locator'); ?></p>
        </div>
        <?php
    }
}

require_once plugin_dir_path( __FILE__ ) . 'inc/codestar-framework.php';

// custom posts
require_once plugin_dir_path( __FILE__ ) . 'inc/custom-posts.php';

// shortcodes
require_once plugin_dir_path( __FILE__ ) . 'inc/shortcodes.php';

// include css and js from assets folder
function gff_store_locator_assets() {
    wp_enqueue_style( 'leaflet', plugin_dir_url( __FILE__ ) . 'libs/leaflet-js/leaflet.css', null, '1.0.0' );
    wp_enqueue_script( 'leaflet', plugin_dir_url( __FILE__ ) . 'libs/leaflet-js/leaflet.js', [], '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'gff_store_locator_assets' );
