<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for News
 */
class Delivery_status extends API_Controller
{

	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'Deliverystatus' );

		// set the validation rules for create and update
		$this->validation_rules();

		$this->jwtfilter->authenticate();
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
	        	'field' => 'trans_status_id',
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

		$this->ps_adapter->convert_delivery_status( $obj );

	}

	/**
	 * Update Delivery Status a post.
	 */
	function update_delivery_status_post()
	{

		// set the add flag for custom response
		$this->is_add = true;

		if ( !$this->is_valid( $this->create_validation_rules )) {
		// if there is an error in validation,
			
			return;
		}

		// get the post data
		$id = $this->post('transactions_header_id');
		$data['trans_status_id'] = $this->post('trans_status_id');
		date_default_timezone_set('Europe/London');
		$data['updated_date'] = date('Y-m-d H:i:s');
		
		if ( !$this->Transactionheader->save( $data, $id )) {
			$this->error_response( get_msg( 'err_model' ), 500);
		}
		$trans_status_id = $data['trans_status_id'];
		$title = $this->Transactionstatus->get_one($trans_status_id)->title;
		$trans_code = $this->Transactionheader->get_one($id)->trans_code;
		$message = "Order No. ".$trans_code."\nYour Order Is " . $title;

		//@start\
		//// Start - Send Noti to user /////
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

		//// Start - Send Noti to delivery boy /////
		$message = "Your order delivery status is " . $title;
		$data['message'] = $message;
		$data['flag'] = 'transaction';
		$data['trans_header_id'] = $id;

		$delivery_boy_id = $this->Transactionheader->get_one($id)->delivery_boy_id;
		$devices = $this->Notitoken->get_all_device_in($delivery_boy_id)->result();

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


	function completed_order_post()
	{
		// validation rules for user register
		$rules = array(
			array(
				'field' => 'delivery_boy_id',
				'rules' => 'required'
			)
		);

		// exit if there is an error in validation
		if (!$this->is_valid($rules)) exit;

		$user_id = $this->post('delivery_boy_id');
		$users = global_user_check($user_id);

		// get trans status id that is the final stage
		$conds_stage['final_stage'] = '1';
		$trans_data = $this->Transactionstatus->get_one_by($conds_stage);
		$trans_status_id = $trans_data->id;

		$conds['delivery_boy_id'] = $this->post('delivery_boy_id');
		$conds['trans_status_id'] = $trans_status_id;
		//$conds['title'] = "refunded";
		$conds['updated_date >='] = date('Y-m-d 00:00:00');
		$conds['updated_date <='] = date('Y-m-d 23:59:59');

		// Check if 'updated_date' parameter is provided
		$justToday = $this->post('just_today');
		

		// response the inserted object
		$tmp_deli_com = $this->Transactionheader->get_all_order_delivery($conds)->result();

		if ($justToday == '1') {

			$today = date('Y-m-d');
			$tmp_deli_com = array_filter($tmp_deli_com, function($item) use ($today) {
			return substr($item->updated_date, 0, 10) === $today;
		});

		
		}
		// Filter the results to include only today's date
		

		// Sort the result based on 'updated_date' column
		usort($tmp_deli_com, function ($a, $b) {
			return strtotime($b->updated_date) - strtotime($a->updated_date);
		});

		$this->custom_response($tmp_deli_com);

	}


	/**
	 * Pending order a post.
	 */
	function pending_order_post()
	{

		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'delivery_boy_id',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $user_id = $this->post('delivery_boy_id');
        $users = global_user_check($user_id);

		// get trans status id that is final stage
		$conds_stage['optional'] = '1';
		$conds_stage['final'] = '1';
		$conds_stage['start'] = '1';
		$trans_data = $this->Transactionstatus->get_all_by($conds_stage)->result();
		$ids = array_column($trans_data,'id');
		$trans_status_id = $ids;


		$conds['delivery_boy_id'] = $this->post('delivery_boy_id');
		$conds['trans_status_id'] = $trans_status_id;
		$conds['status'] = "pending";

		// response the inserted object	
		$tmp_deli_com = $this->Transactionheader->get_all_order_delivery( $conds )->result();

		$this->custom_response( $tmp_deli_com );

		//$this->ps_adapter->convert_transaction_header( $tmp_deli_com );
	}

}