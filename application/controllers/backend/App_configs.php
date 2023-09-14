<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * App_configs Controller
 */

class App_configs extends BE_Controller {
		/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'APP_CONFIGS' );
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
	 * Load About Entry Form
	 */

	function index( $id = "app_set1" ) {

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input()) {

				// save user info
				$this->save( $id );
			}
		}

		$this->data['action_title'] = get_msg('app_setting');
		
		//Get App Setting Object
		$this->data['appsetting'] = $this->App_config->get_one( $id );

		$this->load_form($this->data);

	}

	/**
	 * Update the existing one
	 */
	function edit( $id = "app_set1") {


		// load user
		$this->data['appsetting'] = $this->App_config->get_one( $id );

		// call the parent edit logic
		parent::edit( $id );
	}

	/**
	 * Saving Logic
	 * 1) save about data
	 * 2) check transaction status
	 *
	 * @param      boolean  $id  The about identifier
	 */
	function save( $id = false ) {

		// start the transaction
		$this->db->trans_start();
		
		// prepare data for save
		$data = array();

		// if 'enable_comment' is checked,
		if ( $this->has_data( 'enable_comment' )) {
			$data['enable_comment'] = 1;
		} else {
			$data['enable_comment'] = 0;
		}

		// if 'enable_review' is checked,
		if ( $this->has_data( 'enable_review' )) {
			$data['enable_review'] = 1;
		} else {
			$data['enable_review'] = 0;
		}

		// save backend config
		if ( ! $this->App_config->save( $data, $id )) {
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
				
				$this->set_flash_msg( 'success', get_msg( 'success_app_config_edit' ));
			} else {
			// if user id is false, show success_edit message

				$this->set_flash_msg( 'success', get_msg( 'success_app_config_add' ));
			}
		}

		
		redirect( site_url('/admin/app_configs') );

	}


	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input( $id = 0 ) {
 		return true;
	}
}