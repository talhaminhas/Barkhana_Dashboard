<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Active_orders_dashboard Controller
 */
class Active_orders_dashboard extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'ACTIVE ORDERS' );
		///start allow module check by MN
		$conds_mod['module_name'] = $this->router->fetch_class();
		$module_id = $this->Module->get_one_by($conds_mod)->module_id;
		
		$logged_in_user = $this->ps_auth->get_user_info();

		$user_id = $logged_in_user->user_id;
		if(empty($this->User->has_permission( $module_id,$user_id )) && $logged_in_user->user_is_sys_admin!=1){
			return redirect( site_url('/admin/') );
		}
		///end check

		// load the mail library
		$this->load->library( 'PS_Mail' );
	}

	/**
	 * active orders dashboard
	 */
	function index($shop_id = "shop0b69bc5dbd68bbd57ea13dfc5488e20a") {
		
		$sess_array = array('shop_id' => $shop_id);
		
		
		$conds['trans_status_id'] = 'trans_sts29a4b0cd2fa6ae0449e47e9568320f3a';
		$new_orders_row_count = $this->Transactionheader->count_all_by( $conds );
		$_SESSION['new_order_rows_count'] = $new_orders_row_count;


		$this->session->set_userdata('selected_shop_id', $sess_array);
		$this->load_template( 'active_orders_dashboard', false, false, true );

	}

	/**
	 * accept order
	 */
	function accept_order($id)
	{

		$status_id = $this->Transactionheader->get_one( $id )->trans_status_id;
		$trans_code = $this->Transactionheader->get_one( $id )->trans_code;
		$currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
		$currentDateTime->setTimezone(new DateTimeZone('Europe/London'));
		$data1['updated_date'] = $currentDateTime->format('Y-m-d H:i:s');
		$data1['trans_status_id'] = "trans_stsabda7751186eb039c98f7602553a0ba0";
		$this->Transactionheader->save($data1,$id);

		$title = $this->Transactionstatus->get_one("trans_stsabda7751186eb039c98f7602553a0ba0")->title;
		$message = "Order No. ".$trans_code. "\n".get_msg('order_status_changed') . " " . $title.".";
		//$message = "Your Order No. ".$trans_code . " Is " . $title.".";
		//$message = "Customer a" . $title;
		//$data['title'] = "Order No. ".$trans_code;
		$data['message'] = $message;
		$data['flag'] = 'transaction';
		$data['trans_header_id'] = $id;

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


		if ( !$status ) $error_msg .= get_msg('fail_push_noti') . "<br/>";
		//check manual or auto assign deliboy

		//if auto, send noti to deliboy
		$deli_auto_assign = $this->Shop->get_one($shop_id)->deli_auto_assign;

		if ($deli_auto_assign == 1) {
			
			//shop lat,lng

			$shop_lat = $this->Shop->get_one($shop_id)->lat;
			$shop_lng = $this->Shop->get_one($shop_id)->lng;

			//deliboy lat,lng

			$conds['role_id'] = 5;
			$conds['status'] = 1;

			$deli_data = $this->User->get_all_by($conds)->result();
			
			for ($i=0; $i <count($deli_data) ; $i++) { 
				$lat = $deli_data[$i]->user_lat;
				$lng = $deli_data[$i]->user_lng;
				$user_id = $deli_data[$i]->user_id;

				$user_data_update['current_distance'] = calculating_distance($lat, $lng, $shop_lat, $shop_lng);

				$this->User->save($user_data_update,$user_id);


			}


			$limit = $this->Shop->get_one($shop_id)->how_many_deli_to_broadcast;
			
			$conds['current_distance'] = 1 ;

			$usr_data = $this->User->get_all_by($conds,$limit)->result();

			for ($i=0; $i <count($usr_data) ; $i++) { 
				
				$user_id = $usr_data[$i]->user_id;

				// save log send noti for nearest deliboy
				$order_data['delivery_boy_id'] = $user_id;
				$order_data['trans_header_id'] = $id;

				if($this->New_order_assign->get_one_by($order_data)->is_empty_object == 1){
					$this->New_order_assign->save($order_data);
				}

				//// Start - Send Noti /////
				$data['message'] = get_msg('new_order_to_deliver');
				$data['flag'] = 'transaction';
				$data['trans_header_id'] = $id;

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
				
				$status = send_android_deli_fcm( $device_ids, $data, $platform_names );
				
				//// End - Send Noti /////
			}

		}

		

		redirect( site_url('/admin/active_orders_dashboard/index/'.$shop_id) );
	}
	function order_ready($id)
	{
		//shop id

		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];
		$currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
		$currentDateTime->setTimezone(new DateTimeZone('Europe/London'));
		$data1['updated_date'] = $currentDateTime->format('Y-m-d H:i:s');
		$data1['trans_status_id'] = "trans_sts3e03079b68d8c052480c22d91ca2a0b9";

		$this->Transactionheader->save($data1,$id);
		//check manual or auto assign deliboy

		//if auto, send noti to deliboy
		$deli_auto_assign = $this->Shop->get_one($shop_id)->deli_auto_assign;
		$delivery_boy_id = $this->Transactionheader->get_one( $id )->delivery_boy_id;
		$status_id = $this->Transactionheader->get_one( $id )->trans_status_id;
		$trans_code = $this->Transactionheader->get_one( $id )->trans_code;
		/*if ($deli_auto_assign == 1) {
			
			//shop lat,lng

			$shop_lat = $this->Shop->get_one($shop_id)->lat;
			$shop_lng = $this->Shop->get_one($shop_id)->lng;

			//deliboy lat,lng

			$conds['role_id'] = 5;
			$conds['status'] = 1;

			$deli_data = $this->User->get_all_by($conds)->result();
			
			for ($i=0; $i <count($deli_data) ; $i++) { 
				$lat = $deli_data[$i]->user_lat;
				$lng = $deli_data[$i]->user_lng;
				$user_id = $deli_data[$i]->user_id;

				$user_data_update['current_distance'] = calculating_distance($lat, $lng, $shop_lat, $shop_lng);

				$this->User->save($user_data_update,$user_id);


			}


			$limit = $this->Shop->get_one($shop_id)->how_many_deli_to_broadcast;
			
			//$conds['current_distance'] = 1 ;

			$usr_data = $this->User->get_all_by($conds,$limit)->result();

			for ($i=0; $i <count($usr_data) ; $i++) { 
				
				die;
				$user_id = $usr_data[$i]->user_id;

				// save log send noti for nearest deliboy
				$order_data['delivery_boy_id'] = $user_id;
				$order_data['trans_header_id'] = $id;

				if($this->New_order_assign->get_one_by($order_data)->is_empty_object == 1){
					$this->New_order_assign->save($order_data);
				}

				//// Start - Send Noti /////
				$data['message'] = get_msg('new_order_to_deliver');
				$data['flag'] = 'transaction';
				$data['trans_header_id'] = $id;

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
				
				$status = send_android_deli_fcm( $device_ids, $data, $platform_names );
				
				//// End - Send Noti /////
			}

		}*/

		$title = $this->Transactionstatus->get_one("trans_sts3e03079b68d8c052480c22d91ca2a0b9")->title;
						
		$message = "Order No. ".$trans_code. "\n".get_msg('order_status_changed') . " " . $title.".";
		//$message = "Your Order No. ".$trans_code . " Is " . $title.".";
		//$message = "Customer a" . $title;
		//$data['title'] = "Order No. ".$trans_code;
		$data['message'] = $message;
		$data['flag'] = 'transaction';
		$data['trans_header_id'] = $id;

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


		if ( !$status ) $error_msg .= get_msg('fail_push_noti') . "<br/>";


		if( $delivery_boy_id != '0' ) {

			if( $delivery_boy_id != "" ){

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
				
				$status = send_android_deli_fcm( $device_ids, $data, $platform_names );				
				if ( !$status ) $error_msg .= get_msg('fail_push_all_devices') . "<br/>";

			}

		}


		redirect( site_url('/admin/active_orders_dashboard/index/'.$shop_id) );
	}
	
	function order_completed($id)
	{
		//shop id

		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];
		$currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
		$currentDateTime->setTimezone(new DateTimeZone('Europe/London'));
		$data1['updated_date'] = $currentDateTime->format('Y-m-d H:i:s');
		$data1['trans_status_id'] = "trans_sts159cbfb84410ebea91919234532885ec";
		$this->Transactionheader->save($data1,$id);

		$status_id = $this->Transactionheader->get_one( $id )->trans_status_id;
		$trans_code = $this->Transactionheader->get_one( $id )->trans_code;
		
		$title = $this->Transactionstatus->get_one( "trans_sts159cbfb84410ebea91919234532885ec")->title;
						
		$message = "Order No. ".$trans_code. "\n".get_msg('order_status_changed') . " " . $title.".";
		//$message = "Your Order No. ".$trans_code . " Is " . $title.".";
		//$message = "Customer a" . $title;
		//$data['title'] = "Order No. ".$trans_code;
		$data['message'] = $message;
		$data['flag'] = 'transaction';
		$data['trans_header_id'] = $id;

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


		if ( !$status ) $error_msg .= get_msg('fail_push_noti') . "<br/>";
		//check manual or auto assign deliboy

		//if auto, send noti to deliboy
		$deli_auto_assign = $this->Shop->get_one($shop_id)->deli_auto_assign;

		if ($deli_auto_assign == 1) {
			
			//shop lat,lng

			$shop_lat = $this->Shop->get_one($shop_id)->lat;
			$shop_lng = $this->Shop->get_one($shop_id)->lng;

			//deliboy lat,lng

			$conds['role_id'] = 5;
			$conds['status'] = 1;

			$deli_data = $this->User->get_all_by($conds)->result();
			
			for ($i=0; $i <count($deli_data) ; $i++) { 
				$lat = $deli_data[$i]->user_lat;
				$lng = $deli_data[$i]->user_lng;
				$user_id = $deli_data[$i]->user_id;

				$user_data_update['current_distance'] = calculating_distance($lat, $lng, $shop_lat, $shop_lng);

				$this->User->save($user_data_update,$user_id);


			}


			$limit = $this->Shop->get_one($shop_id)->how_many_deli_to_broadcast;
			
			$conds['current_distance'] = 1 ;

			$usr_data = $this->User->get_all_by($conds,$limit)->result();

			for ($i=0; $i <count($usr_data) ; $i++) { 
				
				$user_id = $usr_data[$i]->user_id;

				// save log send noti for nearest deliboy
				$order_data['delivery_boy_id'] = $user_id;
				$order_data['trans_header_id'] = $id;

				if($this->New_order_assign->get_one_by($order_data)->is_empty_object == 1){
					$this->New_order_assign->save($order_data);
				}

				//// Start - Send Noti /////
				$data['message'] = get_msg('new_order_to_deliver');
				$data['flag'] = 'transaction';
				$data['trans_header_id'] = $id;

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
			}

		}

		redirect( site_url('/admin/active_orders_dashboard/index/'.$shop_id) );
	}

	/**
	 * reject order
	 */
	function reject_order($id)
	{
		//shop id

		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];
		$status_id = $this->Transactionheader->get_one( $id )->trans_status_id;
		$trans_code = $this->Transactionheader->get_one( $id )->trans_code;
		
		$title = $this->Transactionstatus->get_one("trans_stsef071eefcc46df677fe52e7afe414199")->title;
						
		$message = "Order No. ".$trans_code. "\n".get_msg('order_status_changed') . " " . $title.".";
		//$message = "Your Order No. ".$trans_code . " Is " . $title.".";
		//$message = "Customer a" . $title;
		//$data['title'] = "Order No. ".$trans_code;
		$data['message'] = $message;
		$data['flag'] = 'transaction';
		$data['trans_header_id'] = $id;

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


		if ( !$status ) $error_msg .= get_msg('fail_push_noti') . "<br/>";
		// update trans status to reject

		$trans_data['trans_status_id'] = "trans_stsef071eefcc46df677fe52e7afe414199";

		$this->Transactionheader->save($trans_data,$id);

		redirect( site_url('/admin/active_orders_dashboard/index/'.$shop_id) );
	}

	/**
	 * re-trigger
	 */
	function retrigger($id)
	{
		//shop id

		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];

		//shop lat,lng

			$shop_lat = $this->Shop->get_one($shop_id)->lat;
			$shop_lng = $this->Shop->get_one($shop_id)->lng;

			//deliboy lat,lng

			$conds['role_id'] = 5;
			$conds['status'] = 1;

			$deli_data = $this->User->get_all_by($conds)->result();

			for ($i=0; $i <count($deli_data) ; $i++) { 
				$lat = $deli_data[$i]->user_lat;
				$lng = $deli_data[$i]->user_lng;
				$user_id = $deli_data[$i]->user_id;

				$user_data_update['current_distance'] = calculating_distance($lat, $lng, $shop_lat, $shop_lng);

				$this->User->save($user_data_update,$user_id);


			}


			$limit = $this->Shop->get_one($shop_id)->how_many_deli_to_broadcast;

			$conds['current_distance'] = 1 ;

			$usr_data = $this->User->get_all_by($conds,$limit)->result();

			for ($i=0; $i <count($usr_data) ; $i++) { 
				
				$user_id = $usr_data[$i]->user_id;

				$message = get_msg('new_order_to_deliver');

				//// Start - Send Noti /////
				$data['message'] = $message;
				$data['flag'] = 'transaction';
				$data['trans_header_id'] = $id;

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
			}

		redirect( site_url('/admin/active_orders_dashboard/index/'.$shop_id) );	
	}
	

	/**
	* Update the existing one
	*/
	function edit( $id ) {

		// load user
		$this->data['transaction'] = $this->Transactionheader->get_one( $id );

		redirect(site_url('admin/transactions/'));
	}


	/**
	* View transaction Detail
	*/
	// function detail($id)
	// {
	// 	// breadcrumb urls
	// 	$this->data['action_title'] = get_msg( 'trans_detail' );

	// 	$detail = $this->Transactionheader->get_one( $id );
	// 	$this->data['transaction'] = $detail;
	// 	print_r($this->data); die;
	// 	$this->load_detail( $this->data );
	// }
	/**
	* View transaction Detail
	*/
	function detail($id)
	{
		$sess_array = array('from_active_order_detail' => 1);
		$this->session->set_userdata('from_active_order_detail', $sess_array);
		
		$selected_shop_id = $this->session->userdata('selected_shop_id');
        $shop_id = $selected_shop_id['shop_id'];
		$sess_array1 = array('allow_shop_id' => $shop_id);
		$this->session->set_userdata('allow_shop_id', $sess_array1);

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'ongoing_order_detail' );

		$detail = $this->Transactionheader->get_one( $id );

		if($detail->trans_status_id == "trans_sts29a4b0cd2fa6ae0449e47e9568320f3a"){
			$this->data['action_title'] = get_msg( 'new_order_detail' );
		}
		
		$this->data['transaction'] = $detail;
		//print_r($this->data); die;
		$this->load_detail( $this->data );
	}
/** Update the existing one
		*/
		function update() {
		
			
			$id = $this->input->post('trans_header_id');
			$status_id = $this->input->post('trans_status_id');
			$payment_id = '0'; //$this->get_data('payment_status_id');
			$delivery_boy_id = $this->get_data( 'delivery_boy_id' );
			$user_id = $this->Transactionheader->get_one( $id )->user_id;
			
			
			//start PPP @ 24/Aug/2020
	
			$existing_status_id = $this->Transactionheader->get_one( $id )->trans_status_id;
			$existing_payment_id = $this->Transactionheader->get_one( $id )->payment_status_id;
			$existing_deli_boy = $this->Transactionheader->get_one( $id )->delivery_boy_id;
			$trans_code = $this->Transactionheader->get_one( $id )->trans_code;

			$currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
			$currentDateTime->setTimezone(new DateTimeZone('Europe/London'));
			$trans_data['updated_date'] = $currentDateTime->format('Y-m-d H:i:s');
			$trans_data['trans_status_id'] = $status_id;
			$trans_data['delivery_boy_id'] = $delivery_boy_id;
			$this->Transactionheader->save($trans_data,$id);

			//echo($trans_code);die;
			//for transaction status
			if ($status_id != '0' ) {
				if ($status_id != "") {
					//check existing and form status
					if ($status_id != $existing_status_id) {
	
						$title = $this->Transactionstatus->get_one($status_id)->title;
						
						$message = "Order No. ".$trans_code. "\n".get_msg('order_status_changed') . " " . $title.".";
						//$message = "Customer a" . $title;
						//$data['title'] = "Order No. ".$trans_code;
						$data['message'] = $message;
						$data['flag'] = 'transaction';
						$data['trans_header_id'] = $id;
	
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
	
	
						if ( !$status ) $error_msg .= get_msg('fail_push_noti') . "<br/>";
	
	
						if( $delivery_boy_id != '0' ) {
	
							if( $delivery_boy_id != "" ){
	
								$title = $this->Transactionstatus->get_one($status_id)->title;
								$message = "Order No. ".$trans_code. "\n".get_msg('order_status_changed') . " " . $title.".";
								//$message = "delivery boy a " . $title;
								//$data['title'] = "Order No. ".$trans_code;
								$data['message'] = $message;
								$data['flag'] = 'transaction';
								$data['trans_header_id'] = $id;
			
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
								
								$status = send_android_deli_fcm( $device_ids, $data, $platform_names );				
								if ( !$status ) $error_msg .= get_msg('fail_push_all_devices') . "<br/>";
	
							}
	
						}
						//update status save at trans header
						
					}
				}
				
			}
	
			//for payment status
			if ($payment_id != '0') {
				if ($payment_id != "") {
					if ($payment_id != $existing_payment_id) {
	
					$title = $this->Paymentstatus->get_one($payment_id)->title;
					$message = "Your order payment status is " . $title.".";
	
					$data['message'] = $message;
					$data['flag'] = 'transaction';
					$data['trans_header_id'] = $id;
	
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
	
					if ( !$status ) $error_msg .= get_msg('fail_push_all_devices') . "<br/>";
	
					//update payment status save at trans header
	
					$trans_data['payment_status_id'] = $payment_id;
	
					$this->Transactionheader->save($trans_data,$id);
	
					}
				}
				
			}
	
			//for deli boy
			if( $delivery_boy_id != '0' ) {
				
				//if( $delivery_boy_id != "" ){
					if ($delivery_boy_id != $existing_deli_boy) {
						//die;
						$message = "You Have A New Order To Deliver.";
						$data['title'] = "Order No. ".$trans_code;
						$data['message'] = $message;
						$data['flag'] = 'transaction';
						$data['trans_header_id'] = $id;
	
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
						
						$status = send_android_deli_fcm( $device_ids, $data, $platform_names );
						
						if ( !$status ) $error_msg .= get_msg('fail_push_all_devices') . "<br/>";
	
						
					}	
				//}	
			}

			
			// load user
			$this->data['transaction'] = $this->Transactionheader->get_one( $id );
			redirect( $module_site_url.'/'.$shop_id);
			parent::status_edit($id,$status_id,$payment_id,$delivery_boy_id);
			
			
		}
	/**
	 * Saving Logic
	 * 1) upload image
	 * 2) save attribute
	 * 3) save image
	 * 4) check transaction status
	 *
	 * @param      boolean  $id  The user identifier
	 */
	function save( $id  = false, $status_id = 0, $payment_id = 0, $delivery_boy_id = 0 ) {
		// save Transaction

		
		$data['trans_status_id'] = $status_id;
		$data['payment_status_id'] = $payment_id;
		$data['delivery_boy_id'] = $delivery_boy_id;
		$data['updated_date'] = date("Y-m-d H:i:s");
		
		if ( ! $this->Transactionheader->save( $data, $id )) {
			// if there is an error in inserting user data,	
				
				// rollback the transaction
			$this->db->trans_rollback();

				// set error message
			$this->data['error'] = get_msg( 'err_model' );
				
			return;
			}
			// commit the transaction
			if ( ! $this->check_trans()) {
	        	
				// set flash error message
				$this->set_flash_msg( 'error', get_msg( 'err_model' ));
			} else {

				if ( $id ) {
				// if user id is not false, show success_add message
					
					$this->set_flash_msg( 'success', get_msg( 'success_trans_edit' ));
				}
			}


			redirect(site_url() . "/admin/active_orders_dashboard/detail/" . $id);
	}

	function filter_from_dashboard($status_id) {
		
		$this->session->set_userdata("trans_status_id", $status_id);

		redirect(site_url() . "/admin/active_orders_dashboard/search");

	}

	/**
	 * Delete the record
	 * 1) delete category
	 * 2) delete image from folder and table
	 * 3) check transactions
	 */
	function delete( $id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );

		// delete categories and images
		$enable_trigger = true; 
		
		if ( !$this->ps_delete->delete_transaction( $id, $enable_trigger )) {

			// set error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));

			// rollback
			$this->trans_rollback();

			// redirect to list view
			redirect( $this->module_site_url());
		}
			
		/**
		 * Check Transcation Status
		 */
		if ( !$this->check_trans()) {

			$this->set_flash_msg( 'error', get_msg( 'err_model' ));	
		} else {
        	
			$this->set_flash_msg( 'success', get_msg( 'success_trans_delete' ));
		}
		
		redirect( $this->module_site_url());
	}

    function get_all_new_orders() {

        // no publish filter
        $conds['no_publish_filter'] = 1;
        $selected_shop_id = $this->session->userdata('selected_shop_id');
        $shop_id = $selected_shop_id['shop_id'];

        $conds['shop_id'] = $shop_id;
        $conds['trans_status_id'] = 'trans_sts29a4b0cd2fa6ae0449e47e9568320f3a';
        // get rows count
        $old_count = $_SESSION['new_order_rows_count'];

        $rows_count = $this->Transactionheader->count_all_by( $conds );

        if ( $old_count < $rows_count) {
            echo 'true';
        }

    }
	/**
	 * Resume orders
	 */
	function resume_orders()
	{
		date_default_timezone_set('Europe/London');
		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];
		$data['accept_orders_date'] = date('Y-m-d H:i:s');
		// update accept orders date
		if ( ! $this->Mobile_setting->save( $data, 'mb1' )) {

			// if there is an error in inserting user data,	
	
				// rollback the transaction
				$this->db->trans_rollback();
	
				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return;
		}
		redirect( site_url() . '/admin/active_orders_dashboard/index/'.$shop_id);
	}
/**
	 * Pause orders
	 */
	function pause_orders()
	{
		date_default_timezone_set('Europe/London');
		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];
		// prepare duration
		if ( $this->has_data( 'duration' )) {
			$duration = $this->get_data('duration');
			$currentDate = date('Y-m-d H:i:s'); 
			if($duration == -1)
			{
				$data['accept_orders_date'] = date('Y-m-d 23:59:59');
			}
			else{
				$data['accept_orders_date'] = date('Y-m-d H:i:s', strtotime($currentDate . ' + ' . $duration . ' seconds'));
			}
			
		}
		// update accept orders date
		if ( ! $this->Mobile_setting->save( $data, 'mb1' )) {

			// if there is an error in inserting user data,	
	
				// rollback the transaction
				$this->db->trans_rollback();
	
				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return;
		}
		redirect( site_url() . '/admin/active_orders_dashboard/index/'.$shop_id);
	}
	/**
	 * Assign delivery boy to transaction header
	 */
	function assign_delivery_boy($trans_header_id){
		//shop id
		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];


		// prepare delivery boy id
		if ( $this->has_data( 'delivery_boy_id' )) {
			$data['delivery_boy_id'] = $this->get_data( 'delivery_boy_id' );
		}

		// update delivery boy id
		if ( ! $this->Transactionheader->save( $data, $trans_header_id )) {

			// if there is an error in inserting user data,	
	
				// rollback the transaction
				$this->db->trans_rollback();
	
				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return;
		}
		$trans_code = $this->Transactionheader->get_one( $trans_header_id )->trans_code;
		//// Start - Send Noti /////
		$message = "You Have A New Order To Deliver.";
		$data['title'] = "Order No. ".$trans_code;
		$data['message'] = $message;
		$data['flag'] = 'transaction';
		$data['trans_header_id'] = $trans_header_id;
		

		$devices = $this->Notitoken->get_all_device_in($this->get_data( 'delivery_boy_id' ))->result();

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

		$status = send_android_deli_fcm( $device_ids, $data, $platform_names );

		//// End - Send Noti /////
		
		redirect( site_url() . '/admin/active_orders_dashboard/index/'.$shop_id);
	}
	

}