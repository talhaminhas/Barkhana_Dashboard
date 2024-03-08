<?php 
require_once(APPPATH.'/libraries/REST_Controller.php');

class Reservations extends API_Controller
{
	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'Reservation' );
		$this->jwtfilter->authenticate();
	}
		
	function add_post()
	{
		$send_user = false;
		$send_shop = false;
		
		$data = $this->post();	
		if ($data == null) {
			//$this->response(array('error' => array('message' => 'invalid_json')));
			$this->response(array(
				'status'=>'error',
				'data'	=> 'Invalid JSON')
			);
			
		}
		
		if (!array_key_exists('resv_date', $data)) {
			//$this->response(array('error' => array('message' => 'require_date')));
			$this->response(array(
				'status'=>'error',
				'data'	=> 'require_date')
			);
		}
			
		if (!array_key_exists('resv_time', $data)) {
			//$this->response(array('error' => array('message' => 'require_time')));
			$this->response(array(
				'status'=>'error',
				'data'	=> 'require_time')
			);
		}
		
		if (!array_key_exists('shop_id', $data)) {
			//$this->response(array('error' => array('message' => 'require_shop_id')));
			$this->response(array(
				'status'=>'error',
				'data'	=> 'require_shop_id')
			);
		}
		if (!array_key_exists('no_of_people', $data)) {
			//$this->response(array('error' => array('message' => 'require_shop_id')));
			$this->response(array(
				'status'=>'error',
				'data'	=> 'require_no_of_people')
			);
		}
		//$num_padded = sprintf("%02d", $num);
		$ts = explode(":",$data['resv_time']);
		
		$min_num_padded = sprintf("%02d", $ts[1]);
		$hour_num_padded = sprintf("%02d", $ts[0]);
		
		
		$t = $hour_num_padded . ":". $min_num_padded;
		
		$reservation_data = array(
			'resv_date'      => $data['resv_date'],
			'resv_time'      => $t,
			'note'           => $data['note'],
			'shop_id'        => $data['shop_id'],
			'user_id'        => $data['user_id'],
			'user_email'     => $data['user_email'],
			'user_phone_no'  => $data['user_phone_no'],
			'user_name'      => $data['user_name'],
			'status_id'      => 1 ,
			'no_of_people'   => $data['no_of_people'],
		);
		
		
		$this->Reservation->save($reservation_data);
		
		//Temp
		// $this->response(array(
		// 	'status'=>'success',
		// 	'data'	=> "Reservation is successfully submitted. Email successfully sent to User and Shop")
		// );
		
		if(send_email_to_user($data['user_id'],$data['user_email'],$data['user_name'],$data['user_phone_no'],$data['shop_id'],$reservation_data['id'],$data['resv_date'],$data['resv_time'],$data['note']))
		{
			$send_user = true;
		}
		
		if(send_email_to_shop($data['user_id'],$data['user_email'],$data['user_name'],$data['user_phone_no'],$data['shop_id'],
		  $reservation_data['id'],$data['resv_date'],$data['resv_time'],$data['note']))
		{
			$send_shop = true;
		}
		
		if($send_user && $send_shop) {
			
			//$this->response(array('success_status'=>1,'reserve_id'=>$reservation_data['id']));
			//Reservation is successfully inserted.
			$this->response(array(
				'status'=>'success',
				'data'	=> "Reservation is successfully submitted. Email successfully sent to User and Shop")
			);
		} else if($send_user && !$send_shop){
			
			//$this->response(array('success_status'=>2,'reserve_id'=>$reservation_data['id']));
			//Reservation is successfully submitted but email cannot send to shop.
			$this->response(array(
				'status'=>'success',
				'data'	=> "Reservation is successfully submitted. Email cannot send to Shop")
			);
		} else if(!$send_user && $send_shop){
			
			//$this->response(array('success_status'=>3,'reserve_id'=>$reservation_data['id']));
			//Reservation is successfully submitted but email cannot send to user.
			$this->response(array(
				'status'=>'success',
				'data'	=> "Reservation is successfully submitted. Email cannot send to User")
			);
		} else if(!$send_user && !$send_shop){
			
			//$this->response(array('success_status'=>4,'reserve_id'=>$reservation_data['id']));
			//Reservation is successfully submitted but email cannot send to both user and shop.
			$this->response(array(
				'status'=>'success',
				'data'	=> "Reservation is successfully submitted. Email cannot send to both User and Shop")
			);
		} else {
		
			//$this->response(array('error'=>'reservation_email_error'));
			//$this->response(array('success_status'=>1,'reserve_id'=>$reservation_data['id']));
			$this->response(array(
				'status'=>'error',
				'data'	=> "reservation_submit_error")
			);
		}
		
		
		
		
	}
	
	
	
	
	function get_reservation_status_by_id_get() 
	{
		$resv_id = $this->get('resv_id');
		if (!$resv_id) {
			$this->response(array('error' => array('message' => 'require_resv_id')));
		}
		
		$this->response(array('reserve_status'=>$this->reservation_status->get_info($this->reservation->get_info($resv_id)->status_id)->title));
	}
	
	function get_all_reservation_by_user_get() 
	{
		$this->is_get = true;
		// get limit & offset
		$limit = $this->get( 'limit' );
		$offset = $this->get( 'offset' );
		// get id
		$user_id = $this->get('user_id');
		$conds['user_id'] = $user_id;
		if (!$user_id) {
			$this->response(array('error' => array('message' => 'require_user_id')));
		}
		
		$data = array();
		$data = $this->Reservation->get_all_by($conds,$limit,$offset)->result();
		
		$this->custom_response( $data ,$offset);
		
	}

	function reservation_status_update_post()
	{
		//Need to save inside transaction header table 
		$reservation_id = $this->post( 'reservation_id' );
		$resv_data = array(
 			'user_id' 				=> $this->post( 'user_id' ),
 			'status_id' 			=> $this->post( 'status_id' )
 		);

 		if( !$this->Reservation->save($resv_data,$reservation_id) ) {
			// rollback the transaction
			$this->error_response( get_msg( 'err_model' ), 500);
		} 
		
		$reservation_obj = $this->Reservation->get_one($reservation_id);

		$this->convert_object($reservation_obj);

		$this->custom_response($reservation_obj);
	}

	/**
	 * Convert Object
	 */
	function convert_object( &$obj )
	{
		// call parent convert object
		parent::convert_object( $obj );

		// convert customize product object
		$this->ps_adapter->convert_reservation( $obj );
	}
	
}
?>