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

        $this->setTaxonomies();

        $this->setCustomPostType();

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
                        'route' => '/loggedinuser/',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappLoginEndpoint' ],
                    ],       
                    //customer 
                    [
                        'route' => '/customer/',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappLoginEndpointPost' ],
                    ],
                    // Brands
                    [
                        'route' => '/brands/',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappBrandsEndpoint' ],
                    ],
                    [
                        'route' => '/brands/(?P<brand_id>\d+)/products',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappBrandsProductsEndpoint' ],
                    ],
                    [
                        'route' => '/orders/',
                        'method' => 'POST',
                        'callback' => [ $this->callbacks, 'woocappOrderEndpoint' ],
                    ],
                    [
                        'route' => '/orders/',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappOrderClientEndpoint'],
                    ],
                    [
                        'route' => '/offers/',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappOffertsEndpoint' ],
                    ],
                    [
                        'route' => '/offers/(?P<offer_id>\d+)/products',
                        'method' => 'GET',
                        'callback' => [ $this->callbacks, 'woocappOffertsProductsEndpoint' ],
                    ],
                  
                ];

		$this->settings->setEndpoints( $args );
    }

    public function setTaxonomies() {

        $args = [
                    [
                        'base' => [
                                'slug' => 'marca',
                                'cpt' => ['product']
                        ],
                        'args' => [
                            'label' => 'marcas',
                            'labels' => [
                                'label' => [
                                    'name' => 'Marcas',
                                    'singular_name' => 'Marca'
                                ],
                            ],
                            'public' => true,
                            'publicly_queryable' => true,
                            'hierarchical' => true,
                            'show_ui' => true,
                            'show_in_menu' => true,
                            'show_in_nav_menus' => true,
                            'query_var' => true,
                            'rewrite' => [ 'slug' => 'marca', 'with_front' => true, ],
                            'show_admin_column' => false,
                            'show_in_rest' => true,
                            'rest_base' => 'marcas',
                            'rest_controller_class' => 'WP_REST_Terms_Controller',
                            'show_in_quick_edit' => false,
                            ]
                        ],
                        
                    ];

		$this->settings->setTaxonomies( $args );
    }

    public function setCustomPostType() {
        /**
         * Post Type: boletines.
         */
    
        $labels = [
            "name" => __( "boletines", "storefront" ),
            "singular_name" => __( "boletin", "storefront" ),
            "menu_name" => __( "Boletines", "storefront" ),
            "all_items" => __( "Todos los boletines", "storefront" ),
            "add_new" => __( "Añadir nuevo", "storefront" ),
            "add_new_item" => __( "Añadir nuevo boletín", "storefront" ),
            "edit_item" => __( "Editar boletín", "storefront" ),
            "new_item" => __( "Nuevo boletín", "storefront" ),
            "view_item" => __( "Ver boletín", "storefront" ),
            "view_items" => __( "Ver boletines", "storefront" ),
            "search_items" => __( "Buscar boletín", "storefront" ),
            "not_found" => __( "No se ha encontrado ningún boletín", "storefront" ),
            "not_found_in_trash" => __( "No se encontro boletines en papeleras", "storefront" ),
            "parent" => __( "boletin padre", "storefront" ),
            "featured_image" => __( "Imagen de la boletín", "storefront" ),
            "set_featured_image" => __( "Fijar imagen de boletín", "storefront" ),
            "remove_featured_image" => __( "Quitar imagen", "storefront" ),
            "use_featured_image" => __( "Usar imagen de boletin", "storefront" ),
            "name_admin_bar" => __( "Boletín", "storefront" ),
            "item_published" => __( "Boletin publicado", "storefront" ),
            "item_scheduled" => __( "Boletin programado", "storefront" ),
            "parent_item_colon" => __( "boletin padre", "storefront" ),
        ];
    
        $args = [
            "label" => __( "boletines", "storefront" ),
            "labels" => $labels,
            "description" => "Boletines para ofertas y descuentos",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => [ "slug" => "boletin", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "thumbnail" ],
        ];

        $cpt = [
                    [
                        'base' => $args['rewrite']['slug'],
                        'args' => $args
                    ] 
                ];

        $this->settings->setCustomPostType( $cpt );
        
    }

}
