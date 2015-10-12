<?php
/*
Plugin Name: WC Auto-Ship Price Shipping
Plugin URI: http://wooautoship.com
Description: Price-based shipping rates for WC Auto-Ship
Version: 1.0
Author: Patterns in the Cloud
Author URI: http://patternsinthecloud.com
License: Single-site
*/

define( 'WC_Autoship_Price_Shipping_Version', '1.0.0' );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce-autoship/woocommerce-autoship.php' ) ) {
	
	function wc_autoship_price_shipping_install() {
	}
	register_activation_hook( __FILE__, 'wc_autoship_price_shipping_install' );
	
	function wc_autoship_price_shipping_deactivate() {
	
	}
	register_deactivation_hook( __FILE__, 'wc_autoship_price_shipping_deactivate' );
	
	function wc_autoship_price_shipping_uninstall() {

	}
	register_uninstall_hook( __FILE__, 'wc_autoship_price_shipping_uninstall' );
	
	function wc_autoship_price_shipping_add_methods( $methods ) {
		require_once( 'classes/wc-autoship-price-shipping.php' );
		if ( ! in_array( 'WC_Autoship_Price_Shipping', $methods ) ) {
			$methods[] = 'WC_Autoship_Price_Shipping';
		}
		return $methods;
	}
	add_filter( 'woocommerce_shipping_methods', 'wc_autoship_price_shipping_add_methods', 10, 1 );
	
	function wc_autoship_price_shipping_valid_ids( $method_ids ) {
		return array( 'wc_autoship_price_shipping' );
	}
	add_filter( 'wc_autoship_valid_shipping_method_ids', 'wc_autoship_price_shipping_valid_ids', 10, 1 );
	
	function wc_autoship_price_shipping_compare_min_subtotal( $a, $b ) {
		$a_min_subtotal = intval( floatval( $a['min_subtotal'] ) * 100 );
		$b_min_subtotal = intval( floatval( $b['min_subtotal'] ) * 100 );
		return ( $b_min_subtotal - $a_min_subtotal );
	}
}
