<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for News
 */
class Payment_status extends API_Controller
{

	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'Paymentstatus' );

		// set the validation rules for create and update
		$this->validation_rules();
	}

	/**
	 * Determines if valid input.
	 */
	function validation_rules()
	{
		// validation rules for create
		$this->create_validation_rules = array(
			array(
	        	'field' => 'transactions_header_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'payment_status_id',
	        	'rules' => 'required'
	        ),
        );
	}


	/**
	 * Convert Object
	 */
	function convert_object( &$obj )
	{
		// call parent convert object
		parent::convert_object( $obj );

	}

	/**
	 * Adds a post.
	 */
	function update_payment_status_post()
	{

		// set the add flag for custom response
		$this->is_add = true;

		if ( !$this->is_valid( $this->create_validation_rules )) {
		// if there is an error in validation,
			
			return;
		}

		// get the post data
		$id = $this->post('transactions_header_id');
		$data['payment_status_id'] = $this->post('payment_status_id');
		if ( !$this->Transactionheader->save( $data, $id )) {
			$this->error_response( get_msg( 'err_model' ), 500);
		}
		
		//// Start - Send Noti to user /////
		$payment_id = $data['payment_status_id'];
		$title = $this->Transactionstatus->get_one($payment_id)->title;
		$message = "Your order payment status is " . $title;

		$data['message'] = $message;
		$data['flag'] = 'transaction';
		$data['trans_header_id'] = $id;

		$user_id = $this->Transactionheader->get_one($id)->user_id;
		$devices = $this->Notitoken->get_all_device_in($user_id)->result();

		$device_ids = array();
		if ( count( $devices ) > 0 ) {
			foreach ( $devices as $device ) {
				$device_ids[] = $device->device_id;
			}
		}

		$platform_names = array();
		if ( count( $devices ) > 0 ) {
			foreach ( $devices as $platform ) {
				$platform_names[] = $platform->platform_name;
			}
		}

		$status = send_android_fcm( $device_ids, $data, $platform_names );
		//// End - Send Noti /////

		if ( !$status ) $error_msg .= "Fail to push all android devices <br/>";

		// response the inserted object	
		$obj = $this->Transactionheader->get_one( $id );

		$this->custom_response( $obj );
	}

}