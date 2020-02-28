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

define('JWT_AUTH_SECRET_KEY', 'i+NnG wT]Lfy|(ANuu2KnM$z)kZxSK$S7_+huP)7P?FRK0m<$+?+WE6s0+MYX&Tg');

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