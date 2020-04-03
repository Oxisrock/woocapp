<?php

namespace Inc\Api\Endpoints;

use WP_Error;

use \Inc\Base\BaseController;

use WP_REST_Response;

use WP_REST_Request;

use WP_Query;

use Automattic\WooCommerce\HttpClient\HttpClientException;

class Customer extends BaseController {
    // API custom endpoints for WP-REST API
    public function getCustomer() {
        $client = $this->getClient();
        
        $user = [
            'ID' => $client->id,
            'email' => $client->email,
            'username' => $client->username,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'role' => $client->role,
            'customer' => [
                'billing' => $client->billing,
                'shipping' => $client->shipping
            ],
            'avatar' => $client->avatar_url
        ];
        
        $user = json_encode($user);

        $response = new WP_REST_Response($user);

        $response->set_status(200);
    
        return $response;
    }

       // API custom endpoints for WP-REST API
       public function updateCustomer($data) {

        $client = $this->getClient();
        
        $first_name = ($data['first_name']) ? $data['first_name'] : $client->first_name;
        
        $last_name = ($data['last_name']) ? $data['last_name'] : $client->last_name;
        
        $email = ($data['email']) ? $data['email'] : $client->email;
        
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ];
        
        $update = $this->woocommerce->put('customers/'.$client->id, $data);
        
        $response = new WP_REST_Response($update);

        $response->set_status(200);
    
        return $response;
    }

    // API custom endpoints for WP-REST API
    public function updateShippingCustomer($data) {

        $client = $this->getClient();
        
        $data = json_decode($data['shipping']);

        $source = [
            'shipping' => $data
        ];

        $update = $this->woocommerce->put('customers/'.$client->id, $source);
        
        $response = new WP_REST_Response($update);

        $response->set_status(200);
    
        return $response;
    }

}
