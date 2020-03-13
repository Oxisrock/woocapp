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
}
