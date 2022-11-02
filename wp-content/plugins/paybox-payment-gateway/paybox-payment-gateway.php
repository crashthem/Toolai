<?php
/*
PayBox Payment Gateway is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

PayBox Payment Gateway is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with PayBox Payment GatewayPayBox Payment Gateway. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/**
 * Plugin Name: PayBox.money Payment Gateway
 * Description: Receive payments using the PayBox.money payments provider.
 * Author: PayBox.money
 * Author URI: https://paybox.money/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.8.7
 * WC tested up to: 5.8.2
 * WC requires at least: 2.6
 *
 * Copyright (c) 2014-2017 WooCommerce
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC Detection
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
    function is_woocommerce_active() {
        return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }
}

/**
 * Initialize the gateway.
 * @since 1.0.0
 */
function woocommerce_paybox_init() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	define( 'WC_GATEWAY_PAYBOX_VERSION', '1.8.7' );

	require_once( plugin_basename( 'paybox/includes/class-wc-paybox-payment-gateway.php' ) );
	load_plugin_textdomain( 'paybox-payment-gateway', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );
	add_filter( 'woocommerce_payment_gateways', 'woocommerce_paybox_add_gateway' );
}

add_action( 'plugins_loaded', 'woocommerce_paybox_init', 0 );

function woocommerce_paybox_plugin_links( $links ) {
	$settings_url = add_query_arg(
		array(
			'page' => 'wc-settings',
			'tab' => 'checkout',
			'section' => 'wc_paybox_payment_gateway',
		),
		admin_url( 'admin.php' )
	);

	$plugin_links = array(
		'<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings', 'paybox-payment-gateway' ) . '</a>'
	);

	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woocommerce_paybox_plugin_links' );


/**
 * Add the gateway to WooCommerce
 * @since 1.0.0
 */
function woocommerce_paybox_add_gateway( $methods ) {
	$methods[] = 'WC_PayBox_Payment_Gateway';
	return $methods;
}
