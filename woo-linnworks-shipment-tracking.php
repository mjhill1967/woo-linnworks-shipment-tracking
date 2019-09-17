<?php

/**
* @link              https://andrewbarber.me
* @since             0.1
* @package           woo-linnworks-shipment-tracking
*
* @wordpress-plugin
* Plugin Name: Shipment Tracking for Linnworks on WooCommerce
* Plugin URI:        https://andrewbarber.me

* Description: A plugin to pull the shipment details from Linnworks -> WooCommerce intergration to the frontend for customers to view.
* Version: 0.1
* Author: Andrew A. Barber
* Author URI: https://andrewbarber.me/
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: woo-linnworks-shipment-tracking
**/

if ( ! defined( 'WPINC' ) ) {
	die;
}


function linnworks_shipping_tracking($order){
    if (empty(get_post_meta($order->get_order_number(), 'linnworks_tracking_number', true))){
        $shippingPartner = 'Details coming soon.';
        $shippingNumber = 'Details coming soon.'; 
    } else {
        $shippingPartner = get_post_meta($order->get_order_number(), 'linnworks_tracking_provider', true);
        $shippingNumber = get_post_meta($order->get_order_number(), 'linnworks_tracking_number', true);
        if (strcmp($shippingPartner, 'HLG') == 0){
            $shippingPartner = 'Hermes';
            $shippingNumber = "<a href=\"https://new.myhermes.co.uk/track.html#/parcel/" . $shippingNumber . "\" target=\"_blank\">" . $shippingNumber ."</a>";
        }
    }
    echo "
    <h2 class=\"woocommerce-order-details__title\">Shipping details</h2>
    <table class=\"woocommerce-table woocommerce-table--order-details shop_table order_details\">
        <tbody>
            <tr>
                <th scope=\"row\">Shipping Partner:</th>
                <td>" . $shippingPartner. "</td>
            </tr>
            <tr>
                <th scope=\"row\">Tracking Number:</th>
                <td>" . $shippingNumber . "</td>
            </tr>
        </tbody>
    </table>";
}

add_action( 'woocommerce_order_details_after_order_table', 'linnworks_shipping_tracking', 10, 1 );

?>