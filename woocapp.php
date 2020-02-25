<?php
/** 
 *  @package woocapp 
*/
/* Plugin Name: woocapp
* Plugin URI: 
* Description: integration app
* Donate Link: 
* Author: Francisco Aular
* Version: 2.2.5
* Author URI: 
*/
// Exit if accessed directly
if (! defined('ABSPATH')) {
   die;
}
define('JWT_AUTH_SECRET_KEY', '+rOXow0lM}4{Xq|yJ[LEe}4F%8>]L!ganAeYh]s yWn;3EXj4wS)?uI+eX_  ~xy');
define('JWT_AUTH_CORS_ENABLE', true);

// require once the composer Autoload
if( file_exists( dirname(__FILE__).'/vendor/autoload.php')) {
    require_once dirname(__FILE__).'/vendor/autoload.php';
}

// code runs during plugin activation

function ActivateWoocapp() {
    Inc\Base\Activate::activate();
}

// code runs during plugin deactivation

function DeactivateWoocapp() {
    Inc\Base\Deactivate::deactivate();
}

register_activation_hook( __FILE__, 'ActivateWoocapp' );
register_deactivation_hook( __FILE__, 'DeactivateWoocapp' );


if (class_exists('Inc\\Init')) {
    Inc\Init::register_services();
}