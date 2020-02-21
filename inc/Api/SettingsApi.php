<?php
namespace Inc\Api;

use \Inc\Base\BaseController;


class SettingsApi extends BaseController {

    public $admin_pages = [];

    public $admin_subpages = [];

    public $settings = [];

	public $sections = [];

	public $fields = [];

    public $endpoints = [];

    public $taxonomies = [];
    
    public function register() {
        if (! empty($this->admin_pages)) :
            add_action('admin_menu', [$this, 'addAdminMenu']);
        endif;

        if ( !empty($this->settings) ) :
			add_action( 'admin_init', [$this, 'registerCustomFields' ] );
        endif;
        
        if ( !empty($this->endpoints) ) :
			add_action( 'rest_api_init', [$this, 'registerApiEnpoints' ] );
        endif;

        add_action( 'init', [$this, 'registerTaxonomy' ] );

        // add_action( 'admin_init', [$this, 'registerAcf' ] );

        // Include the ACF plugin.
        include_once( $this->plugin_path . '/includes/acf/acf.php' );
    
        // Customize the url setting to fix incorrect asset URLs.
        add_filter('acf/settings/url', [$this,'my_acf_settings_url']);

        add_filter('acf/settings/show_admin', [$this,'my_acf_settings_show_admin']);
        
        add_filter('acf/settings/save_json', [$this,'p2c_acf_json_save_point']);

        add_filter('acf/settings/load_json', [$this,'p2c_acf_json_load_point']);
    }

    public function addPages(array $pages) {
        $this->admin_pages = $pages;

        return $this;
    }

    public function withSubPage(string $title = null) {
        
        if (empty($this->admin_pages)) {
            return $this;
        }

        $admin_page = $this->admin_pages[0];

        $subpage = [
            [
                'parent_slug' => $admin_page['menu_slug'],
                'page_title' => $admin_page['page_title'], 
                'menu_title' => ($title) ? $title : $admin_page['menu_title'], 
                'capability' => $admin_page['capability'], 
                'menu_slug' =>  $admin_page['menu_slug'], 
                'callback' => $admin_page['callback'],
            ]
        ];

        $this->admin_subpages = $subpage;

        return $this;
    }

    public function addSubPages( array $pages ) {
        
        $this->admin_subpages = array_merge($this->admin_subpages,$pages);

        return $this;
    }

    public function addAdminMenu() {
        
        foreach ( $this->admin_pages as $page ) :
			add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		endforeach;

		foreach ( $this->admin_subpages as $page ) :
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
        endforeach;
    }

    public function setSettings( array $settings )
	{
		$this->settings = $settings;

		return $this;
	}

	public function setSections( array $sections )
	{
		$this->sections = $sections;

		return $this;
	}

	public function setFields( array $fields )
	{
		$this->fields = $fields;

		return $this;
    }
    
    public function setEndpoints( array $endpoints )
	{
		$this->endpoints = $endpoints;

		return $this;
	}

    public function registerCustomFields() {

    // register setting
		foreach ( $this->settings as $setting ) :
			register_setting( $setting['option_group'], $setting['option_name'], ( isset( $setting['callback'] ) ? $setting['callback'] : '' ) );
        endforeach;

		// add settings section
		foreach ( $this->sections as $section ) :
			add_settings_section( $section['id'], $section['title'], ( isset( $section['callback'] ) ? $section['callback'] : '' ), $section['page'] );
        endforeach;

		// add settings field
		foreach ( $this->fields as $field ) :
			add_settings_field( $field['id'], $field['title'], ( isset( $field['callback'] ) ? $field['callback'] : '' ), $field['page'], $field['section'], ( isset( $field['args'] ) ? $field['args'] : '' ) );
        endforeach;
    }

    public function registerApiEnpoints() {

        foreach ( $this->endpoints as $endpoint ) :
			register_rest_route(
                'woocapp', $endpoint['route'],
                [
                    'methods'  => $endpoint['method'],
                    'callback' => $endpoint['callback'],
        
                ]
            );
        endforeach;
    }

    public function registerTaxonomy() {

           /**
         * Taxonomy: marcas.
         */
    
        $labels = [
            "name" => __( "Marcas", "storefront" ),
            "singular_name" => __( "Marca", "storefront" ),
        ];
    
        $args = [
            "label" => __( "marcas", "storefront" ),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => true,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => [ 'slug' => 'marca', 'with_front' => true, ],
            "show_admin_column" => false,
            "show_in_rest" => true,
            "rest_base" => "marcas",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "show_in_quick_edit" => false,
            ];

            register_taxonomy( "marca", [ "product" ], $args );
    }
    public function my_acf_settings_url( $url ) {

        return $this->plugin_url.'includes/acf/';
    }
    
    // (Optional) Hide the ACF admin menu item.
    public function my_acf_settings_show_admin( $show_admin ) {
        return false;
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