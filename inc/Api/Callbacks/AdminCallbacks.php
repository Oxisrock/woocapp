<?php
namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

use \Inc\Api\Endpoints\Login;

use \Inc\Api\Endpoints\Brands;

use \Inc\Api\Endpoints\Orders;

use \Inc\Api\Endpoints\Offers;

use \Inc\Api\Endpoints\Customer;


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
	// LOGIN
	public function woocappLoginEndpoint()
	{
		$login = new Login;
		
		return $login->checkloggedinuser();
	}
	// Brands
	public function woocappBrandsEndpoint()
	{
		$brands = new Brands;
		
		return $brands->listBrands();
	}

	public function woocappBrandsProductsEndpoint($request)
	{
		$brands = new Brands;
		
		return $brands->productsBrands($request);
	}
	// Orders

	public function woocappOrderEndpoint($data) {
		
		$order = new Orders;

		return $order->createOrder($data);
	}

	public function woocappOrderClientEndpoint() {
		
		$order = new Orders;

		return $order->getOrdersClient();
	}

	public function woocappOffertsEndpoint() {
		
		$Offers = new Offers;

		return $Offers->getOffers();
	}
	public function woocappOffertsProductsEndpoint($request) {
		
		$Offers = new Offers;

		return $Offers->getProductOffer($request);
	}

	public function woocappCustomerEndpoint() {
		
		$customer = new Customer;

		return $customer->getCustomer();
	}

	public function woocappUpdateCustomerEndpoint($data) {
		
		$customer = new Customer;

		return $customer->updateCustomer($data);
	}
	
	public function woocappUpdateShippingCustomerEndpoint($data) {
		
		$customer = new Customer;

		return $customer->updateShippingCustomer($data);
	}
}
