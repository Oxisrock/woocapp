<?php

namespace Inc\Base;

use \Inc\Base\BaseController;


class Enqueue extends BaseController
{

    public function register() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);

    }

    function enqueue() {
        //local
        wp_enqueue_style('woocappstyle', $this->plugin_url  .   'assets/css/woocapp.css');
        wp_enqueue_script('woocappscript', $this->plugin_url    .   'assets/js/woocapp.js');
        //cdn      
    }

   
}
