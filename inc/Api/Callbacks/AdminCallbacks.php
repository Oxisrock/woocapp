<?php
namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

use \Inc\Api\Endpoints\Login;


class AdminCallbacks extends BaseController
{
    public function adminDashboard() {
        return  require_once($this->plugin_path.'/templates/admin.php');
    }

    public function cptWoocapp() {
        return  require_once($this->plugin_path.'/templates/cpt-woocapp.php');
    }

    public function taxonomyWoocapp() {
        return  require_once($this->plugin_path.'/templates/taxonomy-woocapp.php');
    }

    public function woocappOptionsGroup( $input )
	{
		return $input;
	}

	public function woocappAdminSection()
	{
		echo 'Area de configuración de plugin woocapp integración';
	}

	public function woocappTextExample()
	{
		$value = esc_attr( get_option( 'text_example' ) );
		echo '<input type="text" class="regular-text" name="text_example" value="' . $value . '" placeholder="Write Something Here!">';
    }
    
    public function woocappFirstName()
	{
		$value = esc_attr( get_option( 'first_name' ) );
		echo '<input type="text" class="regular-text" name="first_name" value="' . $value . '" placeholder="Write your First Name">';
	}

	public function woocappLoginEnpoint()
	{
		$login = new Login;
		
		return $login->login();
	}

	public function woocappLoginEnpointPost($POST)
	{
		$login = new Login;
		return $login->loginPost($POST);
	}
}
