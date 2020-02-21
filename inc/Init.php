<?php 

namespace Inc;

final class Init 
{
    /*
    *    store all class inside array
    *   return array full list classes
    */

    public static function get_services() {
        return [
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class
        ];
    }

    public static function register_services() 
    {
        foreach (self::get_services() as $class) :
            $service = self::instantiate($class);

            if (method_exists($service, 'register')) {
                $service->register();
            }
        endforeach;
    }
    
    private static function instantiate($class) {
        $service = new $class();
        
        return $service;
    }
}

// use Inc\Activate;

// use Inc\Deactivate;

// use Inc\Admin\AdminPages;

// if (!class_exists('woocapp')) {

// class woocapp {

//     public $plugin;

//     function __construct() {

//         $this->plugin = plugin_basename(__FILE__);

//     }

//     function register() {
//         add_action('admin_enqueue_scripts', [$this, 'enqueue']);

//         add_action('admin_menu', [$this, 'add_admin_pages']);

//         add_filter( 'plugin_action_links_'. $this->plugin, [$this, 'settings_link']);
//     }

//     public function settings_link($links) {
//         $settings_link = '<a href="options-general.php?page=woocapp">Configurar</a>';

//         array_push($links, $settings_link);

//         return $links;
//     }

//     public function add_admin_pages() {
//         add_menu_page( 'Woocapp', 'woocapp', 'manage_options', 'woocapp', [$this, 'admin_index'], 'dashicons-smartphone', 110 );
//     }

//     public function admin_index() {
//         //require template

//         require_once plugin_dir_path( __FILE__ ).'templates/admin.php';


//     }

//     function enqueue() {
//         wp_enqueue_style('woocappstyle', MY_PLUGIN_URL.'assets/css/woocapp.css');
//         wp_enqueue_script('woocappscript', MY_PLUGIN_URL.'assets/js/woocapp.js');

//     }

//     function activate() {
//        Activate::activate();
//     }

//     }
// }
//     $woocapp = new woocapp();
//     $woocapp->register();

    // activacion

    // register_activation_hook( __FILE__, [$woocapp , 'activate'] );

    // deactivacion

    // register_deactivation_hook( __FILE__, ['Deactivate', 'deactivate'] );

    // uninstall

    // register_uninstall_hook( __FILE__, [$woocapp, 'alcabama_uninstall'] );
