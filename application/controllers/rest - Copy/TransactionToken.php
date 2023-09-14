<?php
require_once( APPPATH .'libraries/REST_Controller.php' );
require_once('vendor/autoload.php');

	use GlobalPayments\Api\Entities\Address;
	use GlobalPayments\Api\Entities\Enums\AddressType;
	use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
	use GlobalPayments\Api\HostedPaymentConfig;
	use GlobalPayments\Api\Entities\HostedPaymentData;
	use GlobalPayments\Api\Entities\Enums\HppVersion;
	use GlobalPayments\Api\Entities\Exceptions\ApiException;
	use GlobalPayments\Api\Services\HostedService;


/**
 * REST API for News
 */
class TransactionToken extends API_Controller
{
	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct('Shop');
	}

	/**
	 * Default Query for API
	 * @return [type] [description]
	 */
	function default_conds()
	{
		$conds = array();		

		return $conds;
	}

	function global_token_post()
	{
		$shop_id = 'shop0b69bc5dbd68bbd57ea13dfc5488e20a';
		//$shop_id = $this->get( $this->$shop_id);
		

		//configure client, request and HPP settings
		$config = new GpEcomConfig();
		$config->merchantId = $this->Shop->get_one($shop_id)->global_merchantid;
		$config->accountId = $this->Shop->get_one($shop_id)->global_account;
		$config->sharedSecret = $this->Shop->get_one($shop_id)->global_secretkey;
		$config->serviceUrl = $this->Shop->get_one($shop_id)->global_url;

		$config->hostedPaymentConfig = new HostedPaymentConfig();
		$config->hostedPaymentConfig->version = HppVersion::VERSION_2;
		$service = new HostedService($config);

		if($this->post('json_response') == 0)
		{
		
		// Add 3D Secure 2 Mandatory and Recommended Fields
			$hostedPaymentData = new HostedPaymentData();
			$hostedPaymentData->customerEmail = $this->post('user_email');
			$hostedPaymentData->customerPhoneMobile = "44|".$this->post('user_phone');;
			$hostedPaymentData->addressesMatch = false;

			$billingAddress = new Address();
			$billingAddress->streetAddress1 = $this->post('user_address1');
			$billingAddress->streetAddress2 = $this->post('user_address2');
			$billingAddress->city = $this->post('user_city');
			$billingAddress->postalCode = $this->post('user_postcode');
			$billingAddress->country = "826";

			$shippingAddress = new Address();
			$shippingAddress->streetAddress1 = $this->post('user_address1');
			$shippingAddress->streetAddress2 = $this->post('user_address2');
			$shippingAddress->city =$this->post('user_city');
			$shippingAddress->state = "GB";
			$shippingAddress->postalCode = $this->post('user_postcode');
			$shippingAddress->country = "826";

			try 
			{
				$hppJson = $service->charge($this->post('user_total'))
				->withCurrency("GBP")
				->withHostedPaymentData($hostedPaymentData)
				->withAddress($billingAddress, AddressType::BILLING)
				->withAddress($shippingAddress, AddressType::SHIPPING)
				->serialize(); 
				$this->response($hppJson, 200);
				//json_encode($hppJson);
			} 
			catch (ApiException $e) 
			{	
				$this->response( array( 'status' => false, 'error' => $e->getMessage() ), 400 );			
			}
		}
		else if(strlen($this->post('json_response'))>25)
		{
			// configure client settings
			// $config = new GpEcomConfig();
			// $config->merchantId = $this->Shop->get_one($shop_id)->global_merchantid;
			// $config->accountId = $this->Shop->get_one($shop_id)->global_account;
			// $config->sharedSecret = $this->Shop->get_one($shop_id)->global_secretkey;
			//$config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";
			$config->serviceUrl = "https://apis.sandbox.globalpay.com/ucp/hpp/transactions";

			//$service = new HostedService($config);

			/*
			* TODO: grab the response JSON from the client-side.
			* sample response JSON (values will be Base64 encoded):
			* $responseJson ='{"MERCHANT_ID":"MerchantId","ACCOUNT":"internet","ORDER_ID":"GTI5Yxb0SumL_TkDMCAxQA","AMOUNT":"1999",' .
			* '"TIMESTAMP":"20170725154824","SHA1HASH":"843680654f377bfa845387fdbace35acc9d95778","RESULT":"00","AUTHCODE":"12345",' .
			* '"CARD_PAYMENT_BUTTON":"Place Order","AVSADDRESSRESULT":"M","AVSPOSTCODERESULT":"M","BATCHID":"445196",' .
			* '"MESSAGE":"[ test system ] Authorised","PASREF":"15011597872195765","CVNRESULT":"M","HPP_FRAUDFILTER_RESULT":"PASS"}";
			*/

			try {
				// create the response object from the response JSON
				$responseGP=htmlspecialchars_decode($this->post('json_response'));
				$parsedResponse = $service->parseResponse(substr($responseGP,1,strlen($responseGP)-2), true);
				
				if(!strpos(strtoupper($parsedResponse->responseMessage), 'AUTHORISED') )
				{
					$this->response(array( 'status' => False, 'error' => "Payment not successful" ), 400 );
				}

				// get the values from the response object
				$responseData['order_id'] = $parsedResponse->orderId; // GTI5Yxb0SumL_TkDMCAxQA
				$responseData['response_code'] = $parsedResponse->responseCode; // 00
				$responseData['result'] = $parsedResponse->responseMessage; // [ test system ] Authorised
				$responseData['response_value'] =json_encode($parsedResponse); // get values accessible by key
				
				

				//$responseCardRef=$parsedResponse->responseValues->; // get card details
				//$responseData['auth_code']=$parsedResponse->authCode; // get auth code
				$responseData['pas_ref']=$parsedResponse->responseValues['PASREF']; // get pas ref
				$responseData['SRD']=$parsedResponse->responseValues['SRD']; // get srd
				$responseData['UUID']=$parsedResponse->responseValues['pas_uuid']; // get uuid
				$responseData['batchId']=$parsedResponse->responseValues['BATCHID']; // get batch id
				
				$this->db->insert('paymentdetails',$responseData);

				$this->response( array( 'status' => True, 'error' => "" ), 400 );

			} catch (ApiException $e) {
				$this->response( array( 'status' => False, 'error' => $e->getMessage() ), 400 );			
			}			
		}
		else {
			$this->response( array( 'status' => False, 'error' => "Not valid json" ), 400 );			
		}
	}



	/**
	 * Convert Object
	 */
	function convert_object( &$obj )
	{
		// call parent convert object
		parent::convert_object( $obj );

		// convert customize shop object
		$this->ps_adapter->convert_shop( $obj );

	}





}