<?php

namespace Inc\Pages;

use \Inc\Base\BaseController;

use \Inc\Api\SettingsApi;

use \Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{
    public $settings;

    public $callbacks;

    public $pages = array();

    public $subpages = array();

    public function register() {

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->setPages();

        $this->setSubPages();
        
        $this->setSettings();
        
        $this->setSections();
        
        $this->setFields();

        $this->setEndpoints();


        $this->settings->addPages($this->pages)->withSubPage('Dashboard')->addSubPages($this->subpages)->register();
    }

    public function setPages() {
        $this->pages = [
            [
                'page_title' => 'Woocapp', 
                'menu_title' => 'Woocapp', 
                'capability' => 'manage_options', 
                'menu_slug' =>  'woocapp', 
                'callback' => [$this->callbacks, 'adminDashboard'], 
                'icon_url'  =>  'dashicons-smartphone', 
                'position'  =>  110 
            ]
        ];
    }
    public function setSubPages() {
        $this->subpages = [
            [
                'parent_slug' => 'woocapp',
                'page_title' => 'Custom Post Types', 
                'menu_title' => 'CPT', 
                'capability' => 'manage_options', 
                'menu_slug' =>  'woocapp_cpt', 
                'callback' => [$this->callbacks, 'cptWoocapp'],
            ],
            [
                'parent_slug' => 'woocapp',
                'page_title' => 'Taxonomy Woocapp', 
                'menu_title' => 'Taxonomy', 
                'capability' => 'manage_options', 
                'menu_slug' =>  'woocapp_taxonomy', 
                'callback' => [$this->callbacks, 'taxonomyWoocapp'],
            ],
            
        ];
    }
    // mosca con esta funcion y los option name por cada campo
    public function setSettings() {
        
        $args = [
			[
				'option_group' => 'woocapp_options_group',
				'option_name' => 'wc_client',
				'callback' => [ $this->callbacks, 'woocappOptionsGroup' ]
            ],
			[
				'option_group' => 'woocapp_options_group',
				'option_name' => 'wc_secret'
            ]
        ];

		$this->settings->setSettings( $args );
    }

    public function setSections() {
        $args = [
			[
				'id' => 'woocapp_admin_index',
				'title' => 'Configuración',
				'callback' => [ $this->callbacks, 'woocappAdminSection' ],
				'page' => 'woocapp'
            ]
        ];

		$this->settings->setSections( $args );
    }

    public function setFields() {
        $args = [
                    [
                        'id' => 'wc_client',
                        'title' => 'Wc client',
                        'callback' => [ $this->callbacks, 'woocappWcClient' ],
                        'page' => 'woocapp',
                        'section' => 'woocapp_admin_index',
                        'args' => [
                            'label_for' => 'wc_client',
                            'class' => 'example-class'
                        ]
                    ],
                    [
                        'id' => 'wc_secret',
                        'title' => 'WC Secrect',
                        'callback' => [ $this->callbacks, 'woocappWcSecret' ],
                        'page' => 'woocapp',
                        'section' => 'woocapp_admin_index',
                        'args' => [
                            'label_for' => 'wc_secret',
                            'class' => 'example-class'
                        ]
                    ]
                ];

		$this->settings->setFields( $args );
    }

    public function setEndpoints() {
        $args = [
                    [
                        'route' => '/login/',
                        'method' => 'POST',
                        'callback' => [ $this->callbacks, 'woocappLoginEnpointPost' ],
                    ],
                    [
                        'route' => '/brands/',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappBrandsEnpoint' ],
                    ],
                  
                ];

		$this->settings->setEndpoints( $args );
    }

}
