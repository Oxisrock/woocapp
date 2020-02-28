<?php
namespace Inc\Base;

use Automattic\WooCommerce\Client;

class BaseController 
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;
    public $woocommerce;
    public function __construct() {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3))   .   '/woocapp.php';
        $this->woocommerce = $this->client();
    }

    public function client() {
        $woocommerce = new Client(
            get_site_url(),
            get_option( 'wc_client' ),
            get_option( 'wc_secret' ),
            [
                'wp_api' => true,
                'version' => 'wc/v3',
                'query_string_auth' => true // Force Basic Authentication as query string true and using under HTTPS
            ]
        );

        return $woocommerce;
    }

    

    public function my_acf_settings_url( $url ) {

        return $this->plugin_url.'includes/acf/';
    }
    
    // (Optional) Hide the ACF admin menu item.
    public function my_acf_settings_show_admin( $show_admin ) {
        return true;
    }
 
    public function p2c_acf_json_save_point( $path ) {
        $path = $this->plugin_path . 'includes/acf-json';
        return $path;
    }

    public function p2c_acf_json_load_point( $paths ) {
        unset($paths[0]);
        $paths[] = $this->plugin_path . 'includes/acf-json';

        return $paths;
    }

    

}

