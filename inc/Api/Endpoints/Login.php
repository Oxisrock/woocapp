<?php

namespace Inc\Api\Endpoints;
use WP_Error;

use \Inc\Base\BaseController;

use WP_REST_Response;


class Login extends BaseController {

    public function loginPost($request) {

        $creds = array();
        
        $creds['user_login'] = $request["username"];
        
        $creds['user_password'] =  $request["password"];
        
        $creds['remember'] = true;
        
        $user = wp_get_current_user();
    
        if ( is_wp_error($user) ) :

            return new WP_Error( 404, 'Unregistered user', []);
    
        endif;

        $userJson = $this->formatterUser($user);
        
        $response = new WP_REST_Response($userJson);
        
        $response->set_status(200);
        
        return rest_ensure_response($response);
    
    }

    public function formatterUser($user) {
        $user_id =  $user->ID;
        
        $customer = $this->get_customer($user_id);
        $user = [
            'ID' => $user_id,
            'username' => $user->data->user_login,
            'nickname' => $user->data->user_nicename,
            'email' => $user->data->user_email,
            'display_name' => $user->data->display_name,
            'user_registered' => $user->data->user_registered,
            'user_activation_key' =>  $user->data->user_activation_key,
            'customer' => [
                'billing' => $customer->billing,
                'shipping' => $customer->shipping
            ]
        ];
       
        return $user;
    }

    public function get_customer($user_id) {
        $customer = $this->woocommerce->get('customers/'.$user_id);

        return $customer;
    }

    public function checkloggedinuser()
    {
    $currentuserid_fromjwt = get_current_user_id();
    print_r($currentuserid_fromjwt);
    exit;
    }
}
