<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reservations Controller
 */
class Reservations extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'RESERVATIONS' );
		$this->load->library('email',array(
			'mailtype'  => 'html',
			'newline'   => '\r\n'
		));
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
	 * List down the registered users
	 */
	function index() {
		
		// no delete flag
		$shop_id = "shop0b69bc5dbd68bbd57ea13dfc5488e20a";
		$conds['shop_id'] = $shop_id;
		// get rows count
		$this->data['rows_count'] = $this->Reservation->count_all_by( $conds );

		// get reservations
		$this->data['reservations'] = $this->Reservation->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		$this->load_template('reservations/calendarView', $this->data );
	}

	/**
 	* Update the existing one
	*/
	function edit( $id ) 
	{

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'resv_edit' );

		// load user
		$this->data['reservation'] = $this->Reservation->get_one( $id );

		// call the parent edit logic
		parent::edit( $id );

	}

	function add()
	{
		
		$this->data['action_title'] = 'Add Reservation';
		parent::add( );
	}
	
	function save( $id = false ) {

		$data = array();
		$shop_id = "shop0b69bc5dbd68bbd57ea13dfc5488e20a";
	    if ( $this->has_data( 'resv_date' )) {
			$data['resv_date'] = $this->get_data( 'resv_date' );

		}
		if ( $this->has_data( 'resv_time' )) {
			$data['resv_time'] = $this->get_data( 'resv_time' );

		}
		if ( $this->has_data( 'user_name' )) {
			$data['user_name'] = $this->get_data( 'user_name' );

		}
		if ( $this->has_data( 'user_phone_no' )) {
			$data['user_phone_no'] = $this->get_data( 'user_phone_no' );

		}
		if ( $this->has_data( 'user_email' )) {
			$data['user_email'] = $this->get_data( 'user_email' );

		}
		if ( $this->has_data( 'note' )) {
			$data['note'] = $this->get_data( 'note' );

		}
		if ( $this->has_data( 'no_of_people' )) {
			$data['no_of_people'] = $this->get_data( 'no_of_people' );

		}
		if ( $this->has_data( 'resv_status' )) {
			$data['status_id'] = $this->get_data( 'resv_status' );

		}
		if ( $this->has_data( 'resv_shop_id_hidden' ) && $this->get_data( 'resv_shop_id_hidden' ) != '')  {
			$data['shop_id'] = $this->get_data( 'resv_shop_id_hidden' );
		}
		else{
			$data['shop_id'] = $shop_id;
		}
		if ( $this->has_data( 'resv_user_id_hidden' )) {
			$data['user_id'] = $this->get_data( 'resv_user_id_hidden' );

		}
		if ( ! $this->Reservation->save( $data, $id )) {

				$this->db->trans_rollback();
				$this->data['error'] = get_msg( 'err_model' );
			}
			
		if ($this->input->server('REQUEST_METHOD')=='POST') {
			
			if(htmlentities( $this->input->post('resv_status_hidden')) != htmlentities( $this->input->post('resv_status'))) { 
			
				//get device token from user
				$reservation = $this->Reservation->get_one( $id );
				$user_id = $reservation->user_id;
				$title = $this->Reservation_status->get_one(htmlentities( $this->input->post('resv_status')))->title;
				$message = "Your Resrvation For ".$reservation->resv_date.", ".$reservation->resv_time." is " . $title.".";
				$data['title'] = "Resrvation For ".$reservation->resv_date.", ".$reservation->resv_time;
				$data['message'] = $message;
				$data['flag'] = 'reservation';
				$data['reservation_id'] = $id;
				$data['description'] = $message;

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

				if ( !$status ) $error_msg .= "Fail to push notification <br/>";
				///end noti

				$this->load->library( 'PS_Mail' );
			
				send_email_status_update_to_user(
				htmlentities( $this->input->post('resv_user_id_hidden')), 
				htmlentities( $this->input->post('resv_user_email_hidden')),
				htmlentities( $this->input->post('resv_user_name_hidden')),
				htmlentities( $this->input->post('resv_user_phone_hidden')), 
				htmlentities( $this->input->post('resv_shop_id_hidden')), 
				htmlentities( $this->input->post('resv_id_hidden')), 
				htmlentities( $this->input->post('resv_date_hidden')),
				htmlentities( $this->input->post('resv_time_hidden')), 
				htmlentities( $this->input->post('resv_note_hidden')), 
				$this->Reservation_status->get_one(htmlentities( $this->input->post('resv_status')))->title);
					
				redirect(site_url('/admin/reservations'));
				
			
			} else {
				redirect(site_url('/admin/reservations'));
			}
		}

	}

	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input( $id = 0 ) 
	{
		
		return true;
	}
	
	
}
?>