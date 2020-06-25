<?php

/**
* @link              https://andrewbarber.me
* @since             0.1
* @package           woo-linnworks-shipment-tracking
*
* @wordpress-plugin
* Plugin Name: Shipment Tracking for Linnworks on WooCommerce
* Plugin URI:        https://andrewbarber.me

* Description: A plugin to pull the shipment details from Linnworks -> WooCommerce integration to the frontend for customers to view.
* Version: 0.5
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
    if (get_post_meta($order->id, 'linnworks_tracking_number', true)){
        $shippingPartner = get_post_meta($order->id, 'linnworks_tracking_provider', true);
        $shippingNumber = get_post_meta($order->id, 'linnworks_tracking_number', true);
        if (strpos(strtolower($shippingPartner), 'hlg') !== false || strpos(strtolower($shippingPartner), 'hermes') !== false){
            $shippingPartner = 'Hermes';
            $shippingNumber = "<a href=\"https://new.myhermes.co.uk/track.html#/parcel/" . $shippingNumber . "\" target=\"_blank\">" . $shippingNumber ."</a>";
        }
        elseif (strpos(strtolower($shippingPartner), 'royal') !== false && strpos(strtolower($shippingPartner), 'mail') !== false){
            $shippingPartner = 'Royal Mail';
            $shippingNumber = "<a href=\"http://track2.royalmail.com/portal/rm/track?trackNumber=" . $shippingNumber . "\" target=\"_blank\">" . $shippingNumber ."</a>";
        }
        elseif (strpos(strtolower($shippingPartner), 'parcelforce') !== false){
            $shippingPartner = 'ParcelForce';
            $shippingNumber = "<a href=\"https://www.parcelforce.com/track-trace?trackNumber=" . $shippingNumber . "\" target=\"_blank\">" . $shippingNumber ."</a>";
        }
        elseif (strpos(strtolower($shippingPartner), 'ups') !== false){
            $shippingPartner = 'UPS';
            $shippingNumber = "<a href=\"https://www.ups.com/track?loc=en_GB&tracknum=" . $shippingNumber . "%250D%250A&requester=WT/trackdetails\" target=\"_blank\">" . $shippingNumber ."</a>";
        }


    } else {
        $shippingPartner = 'Details coming soon';
        $shippingNumber = 'Details coming soon'; 
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
    </table><br/><br/>";
}
add_action( 'woocommerce_order_details_after_order_table', 'linnworks_shipping_tracking', 10, 1 );


function linnworks_shipping_email( $order, $sent_to_admin, $plain_text ) {
    $shippingPartner = get_post_meta($order->id, 'linnworks_tracking_provider', true);
    $shippingNumber = get_post_meta($order->id, 'linnworks_tracking_number', true);
    if (strpos(strtolower($shippingPartner), 'hlg') !== false || strpos(strtolower($shippingPartner), 'hermes') !== false){
        $shippingPartner = 'Hermes';
        $shippingNumber = "<a href=\"https://new.myhermes.co.uk/track.html#/parcel/" . $shippingNumber . "\" target=\"_blank\">" . $shippingNumber ."</a>";
    }
    elseif (strpos(strtolower($shippingPartner), 'royal') !== false && strpos(strtolower($shippingPartner), 'mail') !== false){
        $shippingPartner = 'Royal Mail';
        $shippingNumber = "<a href=\"http://track2.royalmail.com/portal/rm/track?trackNumber=" . $shippingNumber . "\" target=\"_blank\">" . $shippingNumber ."</a>";
    }
    elseif (strpos(strtolower($shippingPartner), 'parcelforce') !== false){
        $shippingPartner = 'ParcelForce';
        $shippingNumber = "<a href=\"https://www.parcelforce.com/track-trace?trackNumber=" . $shippingNumber . "\" target=\"_blank\">" . $shippingNumber ."</a>";
    }
    elseif (strpos(strtolower($shippingPartner), 'ups') !== false){
        $shippingPartner = 'UPS';
        $shippingNumber = "<a href=\"https://www.ups.com/track?loc=en_GB&tracknum=" . $shippingNumber . "%250D%250A&requester=WT/trackdetails\" target=\"_blank\">" . $shippingNumber ."</a>";
    }

    if ( $plain_text === false ) {
 		echo "
         <h2>Shipping details</h2>
         <table>
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
         </table><br/><br/>";
 
	} else {
		echo "Shipping details\n
        Shipping partner: " . $shippingPartner . "\n
        Recipient name: " . $shippingNumber . "\n";	
 
	}
}
add_action( 'woocommerce_email_order_meta', 'linnworks_shipping_email', 10, 3 );


?>