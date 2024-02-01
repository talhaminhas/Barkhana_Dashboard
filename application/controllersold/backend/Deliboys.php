<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deliboys Controller
 */
class Deliboys extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'DELIBOYS' );
		///start allow module check
		$conds_mod['module_name'] = $this->router->fetch_class();
		$module_id = $this->Module->get_one_by($conds_mod)->module_id;
		
		$logged_in_user = $this->ps_auth->get_user_info();

		$user_id = $logged_in_user->user_id;
		if(empty($this->User->has_permission( $module_id,$user_id )) && $logged_in_user->user_is_sys_admin!=1){
			return redirect( site_url('/admin/') );
		}
		///end check
	}

	/**
	 * List down the deliboys
	 */
	function index() {
		// no publish filter
		
		$conds = array( 'role_id' => 5 , 'remove_status' => 0);

		// get rows count
		$this->data['rows_count'] = $this->User->count_deli_boy( $conds );

		// get deliboys
		$deliboys = $this->User->get_deli_boy( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		$this->data['deliboys'] = $deliboys;

		// load index logic
		parent::index();
	}

	/**
	 * Searches for the first match.
	 */
	function search() {
		
		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'cat_search' );
		// condition with search term
		if($this->input->post('submit') != NULL ){
		
			// condition with search term
			$conds = array( 'searchterm' => $this->searchterm_handler( $this->input->post( 'searchterm' )) );
			
			if($this->input->post('searchterm') != "") {
				$conds['searchterm'] = $this->input->post('searchterm');
				$this->data['searchterm'] = $this->input->post('searchterm');
				$this->session->set_userdata(array("searchterm" => $this->input->post('searchterm')));
			} else {
				
				$this->session->set_userdata(array("searchterm" => NULL));
			}

			if($this->input->post('deliboy_status') != "") {
				$conds['deliboy_status'] = $this->input->post('deliboy_status');
				$this->data['deliboy_status'] = $this->input->post('deliboy_status');
				$this->session->set_userdata(array("deliboy_status" => $this->input->post('deliboy_status')));
			} else {
				
				$this->session->set_userdata(array("deliboy_status" => NULL));
			}
		} else {
			//read from session value
			if($this->session->userdata('searchterm') != NULL){
				//echo "7";die;
				$conds['searchterm'] = $this->session->userdata('searchterm');
				$this->data['searchterm'] = $this->session->userdata('searchterm');
			}

			if($this->session->userdata('deliboy_status') != NULL){
				
				$this->data['deliboy_status'] = $this->session->userdata('deliboy_status');
				$conds['deliboy_status'] = $this->session->userdata('deliboy_status');

			} 
			
		}
		$conds['role_id'] = 5;
		$conds['remove_status'] = 0;

		// pagination
		$this->data['rows_count'] = $this->User->count_deli_boy( $conds );

		// search data

		$this->data['deliboys'] = $this->User->get_deli_boy( $conds, $this->pag['per_page'], $this->uri->segment( 4 ) );
		
		// load add list
		parent::search();
	}

	/**
	 * Create new one
	 */
	function add() {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'deliboy_add' );

		// call the core add logic
		parent::add();
	}


	/**
	 * Update the existing one
	 */
	function edit( $id ) {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'deliboy_edit' );

		// load user
		$deliboy = $this->User->get_one( $id );

		$this->data['deliboy'] = $deliboy;

		// call the parent edit logic
		parent::edit( $id );
	}

	/**
	 * Saving Logic
	 * 1) upload image
	 * 2) save wallpaper
	 * 3) save image
	 * 4) check transaction status
	 *
	 * @param      boolean  $id  The user identifier
	 */
	function save( $id = false ) {
		
		$logged_in_user = $this->ps_auth->get_user_info();
		// start the transaction
		$this->db->trans_start();
		
		/** 
		 * Insert Wallpaper Records 
		 */
		$data = array();

		// prepare user_name
		if ( $this->has_data( 'user_name' )) {
			$data['user_name'] = $this->get_data( 'user_name' );
		}

		// user_email
		if ( $this->has_data( 'user_email' )) {
			$data['user_email'] = $this->get_data( 'user_email' );
		}

		// save password if exists or not empty
		if ( $this->has_data( 'user_password' ) 
			&& !empty( $this->get_data( 'user_password' ))) {
			$data['user_password'] = md5( $this->get_data( 'user_password' ));
		}

		// if 'is published' is checked,
		if($id == "") {
			//save
			$data['added_date'] = date("Y-m-d H:i:s");
			$data['added_user_id'] = $logged_in_user->user_id;
			//when creating deli boy by super admin or shop admin, it must be auto approved
			$data['status'] = 1;
			$data['role_id'] = 5;
		} else {
			//edit
			unset($data['added_date']);
			date_default_timezone_set('Europe/London');
			$data['updated_date'] = date("Y-m-d H:i:s");
			$data['updated_user_id'] = $logged_in_user->user_id;
			$data['status'] = $this->get_data( 'deliboy_is_published' );
			

		}

		// save pending wallpaper
		if ( ! $this->User->save( $data, $id )) {
		// if there is an error in inserting user data,	

			// rollback the transaction
			$this->db->trans_rollback();

			// set error message
			$this->data['error'] = get_msg( 'err_model' );
			
			return;
		}
		//get inserted wallpaper id
		$id = ( !$id )? $data['user_id']: $id ;
		$user_name = $this->User->get_one($id)->user_name;
		
		//// Start - Send Noti /////
		if($data['status'] == 1) {
			//approve so change status to publish (1)
			$message = get_msg( 'approve_message_1' ) . $user_name . get_msg( 'approve_message_2' );
		} else {
			//reject so change status to reject (3)
			$message = get_msg( 'reject_message_1' ) . $user_name . get_msg( 'reject_message_2' );
		}
		
		$data['message'] = $message;
		$data['flag'] = 'approval';

		$devices = $this->Notitoken->get_all_device_in($id)->result();

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


		/** 
		 * Check Transactions 
		 */

		// commit the transaction
		if ( ! $this->check_trans()) {
        	
			// set flash error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {


			if ( !$status ) {
				$error_msg .= get_msg( 'noti_sent_fail' );
				$this->set_flash_msg( 'error', get_msg( 'noti_sent_fail' ) );
			}


			if ( $status ) {
				$this->set_flash_msg( 'success', get_msg( 'noti_sent_success' ) . $user_name );
			}

		}

		redirect( $this->module_site_url());
	}

	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input( $user_id = 0 ) {
		
		$email_rule = 'required|valid_email|callback_is_valid_email['. $user_id  .']';
		$rule = 'required';

		$this->form_validation->set_rules( 'user_email', get_msg( 'user_email' ), $email_rule);
		$this->form_validation->set_rules( 'user_name', get_msg( 'user_name' ), $rule );
		
		$user = $this->User->get_one( $user_id );
		
		if ( $user_id == 0 ) {
		// password is required if new user
			
			$this->form_validation->set_rules( 'user_password', get_msg( 'user_password' ), $rule );
			$this->form_validation->set_rules( 'conf_password', get_msg( 'conf_password' ), $rule .'|matches[user_password]' );
		}


		return true;
	}

	/**
	 * Determines if valid email.
	 *
	 * @param      <type>   $email  The user email
	 * @param      integer  $user_id     The user identifier
	 *
	 * @return     boolean  True if valid email, False otherwise.
	 */
	function is_valid_email( $email, $user_id = 0 )
	{		

		if ( strtolower( $this->User->get_one( $user_id )->user_email ) == strtolower( $email )) {
		// if the email is existing email for that user id,
			
			return true;
		} else if ( $this->User->exists( array( 'user_email' => $_REQUEST['user_email'] ))) {
		// if the email is existed in the system,

			$this->form_validation->set_message('is_valid_email', get_msg( 'err_dup_email' ));
			return false;
		}

		return true;
	}

	/**
	 * Ajax Exists
	 *
	 * @param      <type>  $user_id  The user identifier
	 */
	function ajx_exists( $user_id = null )
	{
		$user_email = $_REQUEST['user_email'];
		
		if ( $this->is_valid_email( $user_email, $user_id )) {
		// if the user email is valid,
			
			echo "true";
		} else {
		// if the user email is invalid,

			echo "false";
		}
	}

	/**
	 * Delete the user
	 */
	function delete( $user_id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );
		
		// delete categories and images
		if ( !$this->ps_delete->delete_user( $user_id )) {

			// set error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));

			// rollback
			//$this->trans_rollback();

			// redirect to list view
			redirect( $this->module_site_url());
		}
			
		/**
		 * Check Transcation Status
		 */
		if ( !$this->check_trans()) {

			$this->set_flash_msg( 'error', get_msg( 'err_model' ));	
		} else {
        	
			$this->set_flash_msg( 'success', get_msg( 'success_deliboy_delete' ));
		}
		
		redirect( $this->module_site_url());
	}

	/**
	 * Ban the user
	 *
	 * @param      integer  $user_id  The user identifier
	 */
	function ban( $user_id = 0 )
	{
		$this->check_access( BAN );
		
		$data = array( 'is_banned' => 1 );
			
		if ( $this->User->save( $data, $user_id )) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
	
	/**
	 * Unban the user
	 *
	 * @param      integer  $user_id  The user identifier
	 */
	function unban( $user_id = 0 )
	{
		$this->check_access( BAN );
		
		$data = array( 'is_banned' => 0 );
			
		if ( $this->User->save( $data, $user_id )) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
	
}