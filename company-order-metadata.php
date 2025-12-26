<?php
/**
 * Plugin Name: Company Order Metadata
 * Description: Generates a custom internal reference code for WooCommerce orders.
 * Version: 1.0.0
 * Author: Brandon Sosa
 * Text Domain: company-order-metadata
 */

defined( 'ABSPATH' ) || exit;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

use Company\OrderMetadata\OrderGenerator;
use Company\OrderMetadata\AdminDisplay;


function com_init_plugin() {
    if ( class_exists( 'WooCommerce' ) ) {
        new OrderGenerator();
        new AdminDisplay();
    }
}
add_action( 'plugins_loaded', 'com_init_plugin' );