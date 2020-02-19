<?php

namespace Inc\Api\Endpoints;
use WP_Error;
class Login {
// API custom endpoints for WP-REST API
    public function login() {

        $output = array();
        $output = 'funciona enpoint';
        // Your logic goes here.
        return $output;

    }

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
        $client = new WC_Customer($user->ID);
        $user = [
            'ID' => $user->ID,
            'username' => $user->data->user_login,
            'nicename' => $user->data->user_nicename,
            'email' => $user->data->user_email,
            'display_name' => $user->data->display_name,
            'user_registered' => $user->data->user_registered,
            'client' => $client
        ];

        $user = json_encode($user);
        
        return $user;
    }
}
