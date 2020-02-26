<?php
namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

use \Inc\Api\Endpoints\Login;

use \Inc\Api\Endpoints\Brands;


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

	public function woocappWcClient()
	{
		$value = esc_attr( get_option( 'wc_client' ) );
		$disable = ($value) ? 'disabled' : '';
		echo '<input type="text" class="regular-text" name="wc_client" value="' . $value . '" placeholder="Write wc client"'.$disable.'>';
		
	}
    
    public function woocappWcSecret()
	{
		$value = esc_attr( get_option( 'wc_secret' ) );
		$disable = ($value) ? 'disabled' : '';
		echo '<input type="text" class="regular-text" name="wc_secret" value="' . $value . '" placeholder="Write wc secret"'.$disable.'>';
	}

	public function woocappLoginEnpoint()
	{
		$login = new Login;
		
		return $login->checkloggedinuser();
	}

	public function woocappBrandsEnpoint()
	{
		$brands = new Brands;
		return $brands->listBrands();
	}

	public function woocappBrandsProductsEnpoint($request)
	{
		$brands = new Brands;
		return $brands->productsBrands($request);
	}
}
