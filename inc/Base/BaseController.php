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
                'version' => 'wc/v3',
            ]
        );

        return $woocommerce;
    }

}

