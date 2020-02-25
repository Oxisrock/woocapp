<?php

namespace Inc\Api\Endpoints;
use WP_Error;

use \Inc\Base\BaseController;

class Login extends BaseController {

    public function loginPost($request) {

        $creds = array();
        
        $creds['user_login'] = $request["username"];
        
        $creds['user_password'] =  $request["password"];
        
        $creds['remember'] = true;
        
        $user = wp_signon( $creds, false );
    
        if ( is_wp_error($user) ) :

            return new WP_Error( 404, 'Unregistered user', []);
    
        endif;

        $userJson = $this->formatterUser($user);

        return $userJson;
    
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

        $user = json_encode($user);
        
        echo $user;
    }

    public function get_customer($user_id) {
        $customer = $this->woocommerce->get('customers/'.$user_id);

        return $customer;
    }
}
