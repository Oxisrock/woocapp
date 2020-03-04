<?php

namespace Inc\Api\Endpoints;

use WP_Error;

use \Inc\Base\BaseController;

use WP_REST_Response;

use WP_REST_Request;

use WP_Query;

use Automattic\WooCommerce\HttpClient\HttpClientException;

class Orders extends BaseController {
    // API custom endpoints for WP-REST API
    public function createOrder($data) {

        $user_id = get_current_user_id();

        if (empty($user_id)) :
            return new WP_Error( '401', 'error login user', '' );
        endif;

        $client = $this->woocommerce->get('customers/'.$user_id);

        
        $line_items = json_decode($data['cart_products']);
        
        if (empty($line_items)) :
            return new WP_Error( '404', 'error not products in cart', '' );
        endif;
        
        $order = [
            'payment_method' => 'bacs',
            'payment_method_title' => 'Direct Bank Transfer',
            'set_paid' => true,
            'customer_id' => $client->id,
            'billing' => $client->billing,
            'shipping' => $client->shipping,
            'line_items' => $line_items,
        ];
        
         $order = $this->woocommerce->post('orders', $order);
        
        if (empty($order)) :
            return new WP_Error( '404', 'error to create order', '' );
        endif;

        $order = json_encode($order);

        $response = new WP_REST_Response($order);

        $response->set_status(200);
    
        return $response;
    }
}
