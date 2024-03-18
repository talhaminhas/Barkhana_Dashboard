<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Shop Controller
 */
class Shops extends BE_Controller
{

	/**
	 * set required variable and libraries
	 */
	function __construct()
	{

		parent::__construct(MODULE_CONTROL, 'SHOPS');
		///start allow module check
		$conds_mod['module_name'] = $this->router->fetch_class();
		$module_id = $this->Module->get_one_by($conds_mod)->module_id;

		$logged_in_user = $this->ps_auth->get_user_info();

		$user_id = $logged_in_user->user_id;
		if (empty($this->User->has_permission($module_id, $user_id)) && $logged_in_user->user_is_sys_admin != 1) {
			return redirect(site_url('/admin/'));
		}
		///end check
	}

	/**
	 * Home page for the shops controller
	 */
	function index($id = "shop0b69bc5dbd68bbd57ea13dfc5488e20a")
	{

		if ($this->is_POST()) {
			// if the method is post

			// server side validation
			if ($this->is_valid_input()) {

				// save user info
				$this->save($id);
			}
		}


		$logged_in_user = $this->ps_auth->get_user_info();

		$conds['status'] = 1;

		$this->data['shop'] = $this->Shop->get_one($id);

		$this->load_form($this->data);
	}



	function edit($shop_id = "", $current_tab = "")
	{

		$shops = $this->Shop->get_all()->result();
		$shop_id = $shops[0]->id;
		$this->data['shop'] = $this->Shop->get_one($shop_id);
		$this->data['current_tab'] = $this->uri->segment(5);

		$conds['shop_id'] = $shop_id;
		$schedules = $this->Schedule->get_all_by($conds)->result();

		for ($i = 0; $i < count($schedules); $i++) {
			//print_r($schedules[$i]->days_of_week);die;

			if ($schedules[$i]->days_of_week == get_msg('monday_label')) {
				$this->data['monday'] = $schedules[$i]->is_open;
				$this->data['from_monday'] = $schedules[$i]->open_hour;
				$this->data['to_monday'] = $schedules[$i]->close_hour;
			}

			if ($schedules[$i]->days_of_week == get_msg('tuesday_label')) {
				$this->data['tuesday'] = $schedules[$i]->is_open;
				$this->data['from_tuesday'] = $schedules[$i]->open_hour;
				$this->data['to_tuesday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('wednesday_label')) {
				$this->data['wednesday'] = $schedules[$i]->is_open;
				$this->data['from_wednesday'] = $schedules[$i]->open_hour;
				$this->data['to_wednesday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('thursday_label')) {
				$this->data['thursday'] = $schedules[$i]->is_open;
				$this->data['from_thursday'] = $schedules[$i]->open_hour;
				$this->data['to_thursday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('friday_label')) {
				$this->data['friday'] = $schedules[$i]->is_open;
				$this->data['from_friday'] = $schedules[$i]->open_hour;
				$this->data['to_friday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('saturday_label')) {
				$this->data['saturday'] = $schedules[$i]->is_open;
				$this->data['from_saturday'] = $schedules[$i]->open_hour;
				$this->data['to_saturday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('sunday_label')) {
				$this->data['sunday'] = $schedules[$i]->is_open;
				$this->data['from_sunday'] = $schedules[$i]->open_hour;
				$this->data['to_sunday'] = $schedules[$i]->close_hour;
			}
		}

		// call the parent edit logic
		parent::edit($shop_id);
	}

	/**
	 * Saving Logic
	 * 1) save about data
	 * 2) check transaction status
	 *
	 * @param      boolean  $id  The about identifier
	 */
	function save($id = false)
	{
		// start the transaction
		//$this->db->trans_start();


		// prepare data for save
		$data = array();
		$logged_in_user = $this->ps_auth->get_user_info();
		// prepare shop id
		if ($this->has_data('id')) {
			$data['id'] = $this->get_data('id');
		}

		// prepare shop name
		if ($this->has_data('name')) {
			$data['name'] = $this->get_data('name');
		}

		// prepare shop description
		if ($this->has_data('description')) {
			$data['description'] = $this->get_data('description');
		}

		// prepare shop email
		if ($this->has_data('email')) {
			$data['email'] = $this->get_data('email');
		}

		// prepare price level

		$price_level = $this->get_data('price_level');
		if ($price_level == 1) {
			$data['price_level'] = "Low";
		} else if ($price_level == 2) {
			$data['price_level'] = "Medium";
		} else {
			$data['price_level'] = "High";
		}

		// prepare highlighted info
		if ($this->has_data('highlighted_info')) {
			$data['highlighted_info'] = $this->get_data('highlighted_info');
		}

		// prepare shop lat
		if ($this->has_data('lat')) {
			$data['lat'] = $this->get_data('lat');
		}

		// prepare shop lng
		if ($this->has_data('lng')) {
			$data['lng'] = $this->get_data('lng');
		}

		// prepare shop address1
		if ($this->has_data('address1')) {
			$data['address1'] = $this->get_data('address1');
		}

		// prepare shop address2
		if ($this->has_data('address2')) {
			$data['address2'] = $this->get_data('address2');
		}

		// prepare shop address3
		if ($this->has_data('address3')) {
			$data['address3'] = $this->get_data('address3');
		}

		// prepare shop about_phone1
		if ($this->has_data('about_phone1')) {
			$data['about_phone1'] = $this->get_data('about_phone1');
		}

		// prepare shop about_phone2
		if ($this->has_data('about_phone2')) {
			$data['about_phone2'] = $this->get_data('about_phone2');
		}

		// prepare shop about_phone2
		if ($this->has_data('about_phone3')) {
			$data['about_phone3'] = $this->get_data('about_phone3');
		}

		// prepare shop about_website
		if ($this->has_data('about_website')) {
			$data['about_website'] = $this->get_data('about_website');
		}

		// prepare shop facebook
		if ($this->has_data('facebook')) {
			$data['facebook'] = $this->get_data('facebook');
		}

		// prepare shop google_plus
		if ($this->has_data('google_plus')) {
			$data['google_plus'] = $this->get_data('google_plus');
		}

		// prepare shop instagram
		if ($this->has_data('instagram')) {
			$data['instagram'] = $this->get_data('instagram');
		}

		// prepare shop youtube
		if ($this->has_data('youtube')) {
			$data['youtube'] = $this->get_data('youtube');
		}

		// prepare shop pinterest
		if ($this->has_data('pinterest')) {
			$data['pinterest'] = $this->get_data('pinterest');
		}

		// prepare shop twitter
		if ($this->has_data('twitter')) {
			$data['twitter'] = $this->get_data('twitter');
		}

		// prepare shop messenger
		if ($this->has_data('messenger')) {
			$data['messenger'] = $this->get_data('messenger');
		}

		if ($this->config->item("is_demo") !== 1) {


			// prepare shop global url
			if ($this->has_data('global_url')) {
				$data['global_url'] = $this->get_data('global_url');
			}
			// prepare shop global merchant id
			if ($this->has_data('global_merchantid')) {
				$data['global_merchantid'] = $this->get_data('global_merchantid');
			}
			// prepare shop global account
			if ($this->has_data('global_account')) {
				$data['global_account'] = $this->get_data('global_account');
			}
			// prepare shop global secretkey
			if ($this->has_data('global_secretkey')) {
				$data['global_secretkey'] = $this->get_data('global_secretkey');
			}

			// prepare shop stripe_publishable_key
			if ($this->has_data('stripe_publishable_key')) {
				$data['stripe_publishable_key'] = $this->get_data('stripe_publishable_key');
			}

			// prepare shop stripe_secret_key
			if ($this->has_data('stripe_secret_key')) {
				$data['stripe_secret_key'] = $this->get_data('stripe_secret_key');
			}

			// prepare shop bank_account
			if ($this->has_data('paypal_environment')) {
				$data['paypal_environment'] = $this->get_data('paypal_environment');
			}

			// prepare shop bank_name
			if ($this->has_data('paypal_merchant_id')) {
				$data['paypal_merchant_id'] = $this->get_data('paypal_merchant_id');
			}

			// prepare shop bank_code
			if ($this->has_data('paypal_public_key')) {
				$data['paypal_public_key'] = $this->get_data('paypal_public_key');
			}

			// prepare shop branch_code
			if ($this->has_data('paypal_private_key')) {
				$data['paypal_private_key'] = $this->get_data('paypal_private_key');
			}

			// prepare paypal_client_id
			if ($this->has_data('paypal_client_id')) {
				$data['paypal_client_id'] = $this->get_data('paypal_client_id');
			}

			// prepare paypal_secret_key
			if ($this->has_data('paypal_secret_key')) {
				$data['paypal_secret_key'] = $this->get_data('paypal_secret_key');
			}

			// prepare shop bank_account
			if ($this->has_data('bank_account')) {
				$data['bank_account'] = $this->get_data('bank_account');
			}

			// prepare shop bank_name
			if ($this->has_data('bank_name')) {
				$data['bank_name'] = $this->get_data('bank_name');
			}

			// prepare shop bank_code
			if ($this->has_data('bank_code')) {
				$data['bank_code'] = $this->get_data('bank_code');
			}

			// prepare shop branch_code
			if ($this->has_data('branch_code')) {
				$data['branch_code'] = $this->get_data('branch_code');
			}

			// prepare shop swift_code
			if ($this->has_data('swift_code')) {
				$data['swift_code'] = $this->get_data('swift_code');
			}

			// prepare shop cod_email
			if ($this->has_data('cod_email')) {
				$data['cod_email'] = $this->get_data('cod_email');
			}


			// prepare shop razor_key
			if ($this->has_data('razor_key')) {
				$data['razor_key'] = $this->get_data('razor_key');
			}

			// prepare shop currency_symbol
			if ($this->has_data('currency_symbol')) {
				$data['currency_symbol'] = $this->get_data('currency_symbol');
			}

			// prepare shop currency_short_form
			if ($this->has_data('currency_short_form')) {
				$data['currency_short_form'] = $this->get_data('currency_short_form');
			}

			// prepare shop sender_email
			if ($this->has_data('sender_email')) {
				$data['sender_email'] = $this->get_data('sender_email');
			}

			// prepare shop overall_tax_label
			if ($this->has_data('overall_tax_label')) {
				$data['overall_tax_label'] = $this->get_data('overall_tax_label');
				$data['overall_tax_value'] = $this->get_data('overall_tax_label') / 100;
			}

			// prepare shop shipping_tax_label
			if ($this->has_data('shipping_tax_label')) {
				$data['shipping_tax_label'] = $this->get_data('shipping_tax_label');
				$data['shipping_tax_value'] = $this->get_data('shipping_tax_label') / 100;
			}

			// prepare shop whapsapp_no
			if ($this->has_data('whapsapp_no')) {
				$data['whapsapp_no'] = $this->get_data('whapsapp_no');
			}

			// prepare shop refund_policy_label
			if ($this->has_data('refund_policy')) {
				$data['refund_policy'] = $this->get_data('refund_policy');
			}

			// if 'banktransfer_enabled' is checked,
			if ($this->has_data('banktransfer_enabled')) {
				$data['banktransfer_enabled'] = 1;
			} else {
				$data['banktransfer_enabled'] = 0;
			}

			// prepare shop terms_label
			if ($this->has_data('terms')) {
				$data['terms'] = $this->get_data('terms');
			}

			// prepare minimum_order_amount label
			if ($this->has_data('minimum_order_amount')) {

				$data['minimum_order_amount'] = $this->get_data('minimum_order_amount');
			}

			// prepare auto_accept_orders label
			if ($this->has_data('auto_accept_orders')) {
				$data['auto_accept_orders'] = 1;
			} else {
				$data['auto_accept_orders'] = 0;
			}
			// prepare pickup_message label
			if ($this->has_data('pickup_message')) {

				$data['pickup_message'] = $this->get_data('pickup_message');
			}

			// 	paystack_key
			if ($this->has_data('paystack_key')) {
				$data['paystack_key'] = $this->get_data('paystack_key');
			}

			// prepare flutter_wave_public_key
			if ($this->has_data('flutter_wave_publishable_key')) {
				$data['flutter_wave_publishable_key'] = $this->get_data('flutter_wave_publishable_key');
			}

			// prepare flutter_wave_encryption_key
			if ($this->has_data('flutter_wave_encryption_key')) {
				$data['flutter_wave_encryption_key'] = $this->get_data('flutter_wave_encryption_key');
			}
		}

		// 	deliver_distance
		if ($this->has_data('deliver_distance')) {
			$data['deliver_distance'] = $this->get_data('deliver_distance');
			// echo($this->get_data( 'deliver_distance' ));
			// die;
		}

		// prepare price level

		$deliver_unit = $this->get_data('deliver_unit');
		if ($deliver_unit == 1) {
			$data['deliver_unit'] = "Mile";
		} else {
			$data['deliver_unit'] = "Km";
		}

		// 	deli_charges
		if ($this->has_data('deli_charges')) {
			$data['deli_charges'] = $this->get_data('deli_charges');
		}

		// 	fixed_deli_fee
		if ($this->has_data('fixed_deli_fee')) {
			$data['fixed_deli_fee'] = $this->get_data('fixed_deli_fee');
		}

		// if 'is_area' is checked,
		if ($this->input->post('deliRadio') == 'is_area') {
			$data['is_area'] = 1;
			$data['deli_fee_by_distance'] = 0;
			$data['fixed_delivery'] = 0;
			$data['free_delivery'] = 0;
		}

		// if 'deli_fee_by_distance' is checked,
		if ($this->input->post('deliRadio') == 'deli_fee_by_distance') {
			$data['is_area'] = 0;
			$data['deli_fee_by_distance'] = 1;
			$data['fixed_delivery'] = 0;
			$data['free_delivery'] = 0;
		}

		// if 'fixed_delivery' is checked,	
		if ($this->input->post('deliRadio') == 'fixed_delivery') {
			$data['is_area'] = 0;
			$data['fixed_delivery'] = 1;
			$data['deli_fee_by_distance'] = 0;
			$data['free_delivery'] = 0;
		}

		// if 'free_delivery' is checked,	
		if ($this->input->post('deliRadio') == 'free_delivery') {
			$data['is_area'] = 0;
			$data['free_delivery'] = 1;
			$data['deli_fee_by_distance'] = 0;
			$data['fixed_delivery'] = 0;
		}

		// if 'deli_auto_assign' is checked,	
		if ($this->input->post('deli_assign_setting') == 'deli_auto_assign') {
			$data['deli_manual_assign'] = 0;
			$data['deli_auto_assign'] = 1;
		}

		// if 'deli_manual_assign' is checked,	
		if ($this->input->post('deli_assign_setting') == 'deli_manual_assign') {
			$data['deli_auto_assign'] = 0;
			$data['deli_manual_assign'] = 1;
		}

		// 	how_many_deli_to_broadcast
		if ($this->has_data('how_many_deli_to_broadcast')) {
			$data['how_many_deli_to_broadcast'] = $this->get_data('how_many_deli_to_broadcast');
		}



		// if 'status' is checked,
		if ($this->has_data('status')) {
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
		}


		// if 'stripe_enabled' is checked,
		if ($this->has_data('stripe_enabled')) {
			$data['stripe_enabled'] = 1;
		} else {
			$data['stripe_enabled'] = 0;
		}

		// if 'global_enabled' is checked,
		if ($this->has_data('global_enabled')) {
			$data['global_enabled'] = 1;
		} else {
			$data['global_enabled'] = 0;
		}

		// if 'paypal_enabled' is checked,
		if ($this->has_data('paypal_enabled')) {
			$data['paypal_enabled'] = 1;
		} else {
			$data['paypal_enabled'] = 0;
		}

		// if 'code_enabled' is checked,
		if ($this->has_data('cod_enabled')) {
			$data['cod_enabled'] = 1;
		} else {
			$data['cod_enabled'] = 0;
		}

		// if 'pickup_enabled' is checked,
		if ($this->has_data('pickup_enabled')) {
			$data['pickup_enabled'] = 1;
		} else {
			$data['pickup_enabled'] = 0;
		}

		// if 'razor_enabled' is checked,
		if ($this->has_data('razor_enabled')) {
			$data['razor_enabled'] = 1;
		} else {
			$data['razor_enabled'] = 0;
		}

		// if 'paystack_enabled' is checked,
		if ($this->has_data('paystack_enabled')) {
			$data['paystack_enabled'] = 1;
		} else {
			$data['paystack_enabled'] = 0;
		}

		// if 'flutter_wave_enabled' is checked,
		if ($this->has_data('flutter_wave_enabled')) {
			$data['flutter_wave_enabled'] = 1;
		} else {
			$data['flutter_wave_enabled'] = 0;
		}

		// // if 'checkout_with_whatsapp' is checked,
		// if ( $this->has_data( 'checkout_with_whatsapp' )) {
		// 	$data['checkout_with_whatsapp'] = 1;
		// } else {
		// 	$data['checkout_with_whatsapp'] = 0;
		// }

		// // if 'checkout_with_email' is checked,
		// if ( $this->has_data( 'checkout_with_email' )) {
		// 	$data['checkout_with_email'] = 1;
		// } else {
		// 	$data['checkout_with_email'] = 0;
		// }

		// if 'checkout_with_whatsapp' is checked,
		if ($this->input->post('checkout_setting') == 'checkout_with_whatsapp') {
			$data['checkout_with_whatsapp'] = 1;
			$data['checkout_with_email'] = 0;
			$data['one_page_checkout'] = 0;
			$data['multi_page_checkout'] = 0;
		}

		// if 'checkout_with_email' is checked,
		if ($this->input->post('checkout_setting') == 'checkout_with_email') {
			$data['checkout_with_whatsapp'] = 0;
			$data['checkout_with_email'] = 1;
		}

		// if 'checkout_with_email' is checked,
		if ($this->input->post('normal_checkout_setting') == 'one_page_checkout' && $this->input->post('checkout_setting') !== 'checkout_with_whatsapp') {
			$data['one_page_checkout'] = 1;
			$data['multi_page_checkout'] = 0;
		}

		// if 'checkout_with_email' is checked,
		if ($this->input->post('normal_checkout_setting') == 'multi_page_checkout' && $this->input->post('checkout_setting') !== 'checkout_with_whatsapp') {
			$data['one_page_checkout'] = 0;
			$data['multi_page_checkout'] = 1;
		}

		// print_r($data);die;
		// save about
		if (!$this->Shop->save($data, $id)) {

			// rollback the transaction
			$this->db->trans_rollback();

			// echo "string";die;

			// set error message
			$this->data['error'] = get_msg('err_model');

			return;
		}

		/** 
		 * Upload Image Records 
		 */
		if (!$id) {
			if (!$this->insert_images_icon_and_cover($_FILES, 'shop', $data['id'], "cover")) {
				// if error in saving image

				// commit the transaction
				$this->db->trans_rollback();

				return;
			}
			if (!$this->insert_images_icon_and_cover($_FILES, 'shop-icon', $data['id'], "icon")) {
				// if error in saving image

				// commit the transaction
				$this->db->trans_rollback();

				return;
			}
		}


		$id = (!$id) ? $data['id'] : $id;
		// prepare shop tag multiple select

		// open and close hour save at schedule table
		//clear existing data first

		// if 'monday' is checked,
		if ($this->has_data('monday')) {
			$data['monday'] = 1;
		} else {
			$data['monday'] = 0;
		}

		// if 'tuesday' is checked,
		if ($this->has_data('tuesday')) {
			$data['tuesday'] = 1;
		} else {
			$data['tuesday'] = 0;
		}

		// if 'wednesday' is checked,
		if ($this->has_data('wednesday')) {
			$data['wednesday'] = 1;
		} else {
			$data['wednesday'] = 0;
		}

		// if 'thursday' is checked,
		if ($this->has_data('thursday')) {
			$data['thursday'] = 1;
		} else {
			$data['thursday'] = 0;
		}

		// if 'friday' is checked,
		if ($this->has_data('friday')) {
			$data['friday'] = 1;
		} else {
			$data['friday'] = 0;
		}

		// if 'saturday' is checked,
		if ($this->has_data('saturday')) {
			$data['saturday'] = 1;
		} else {
			$data['saturday'] = 0;
		}

		// if 'sunday' is checked,
		if ($this->has_data('sunday')) {
			$data['sunday'] = 1;
		} else {
			$data['sunday'] = 0;
		}

		$this->ps_delete->delete_shop_schedule($id);

		// Monday
		if ($data['monday'] == '1') {
			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('monday_label');
			$schedule_data['open_hour'] = $this->get_data('from_monday');
			$schedule_data['close_hour'] = $this->get_data('to_monday');
			$schedule_data['is_open'] = '1';

			$this->Schedule->save($schedule_data);
		} else {

			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('monday_label');
			$schedule_data['open_hour'] = ' ';
			$schedule_data['close_hour'] = ' ';
			$schedule_data['is_open'] = '0';

			$this->Schedule->save($schedule_data);
		}

		// Tuesday

		if ($data['tuesday'] == '1') {
			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('tuesday_label');
			$schedule_data['open_hour'] = $this->get_data('from_tuesday');
			$schedule_data['close_hour'] = $this->get_data('to_tuesday');
			$schedule_data['is_open'] = '1';

			$this->Schedule->save($schedule_data);
		} else {

			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('tuesday_label');
			$schedule_data['open_hour'] = ' ';
			$schedule_data['close_hour'] = ' ';
			$schedule_data['is_open'] = '0';

			$this->Schedule->save($schedule_data);
		}

		// Wednesday

		if ($data['wednesday'] == '1') {
			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('wednesday_label');
			$schedule_data['open_hour'] = $this->get_data('from_wednesday');
			$schedule_data['close_hour'] = $this->get_data('to_wednesday');
			$schedule_data['is_open'] = '1';

			$this->Schedule->save($schedule_data);
		} else {

			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('wednesday_label');
			$schedule_data['open_hour'] = ' ';
			$schedule_data['close_hour'] = ' ';
			$schedule_data['is_open'] = '0';

			$this->Schedule->save($schedule_data);
		}

		// Thursday

		if ($data['thursday'] == '1') {
			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('thursday_label');
			$schedule_data['open_hour'] = $this->get_data('from_thursday');
			$schedule_data['close_hour'] = $this->get_data('to_thursday');
			$schedule_data['is_open'] = '1';

			$this->Schedule->save($schedule_data);
		} else {

			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('thursday_label');
			$schedule_data['open_hour'] = ' ';
			$schedule_data['close_hour'] = ' ';
			$schedule_data['is_open'] = '0';

			$this->Schedule->save($schedule_data);
		}

		// Friday

		if ($data['friday'] == '1') {
			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('friday_label');
			$schedule_data['open_hour'] = $this->get_data('from_friday');
			$schedule_data['close_hour'] = $this->get_data('to_friday');
			$schedule_data['is_open'] = '1';

			$this->Schedule->save($schedule_data);
		} else {

			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('friday_label');
			$schedule_data['open_hour'] = ' ';
			$schedule_data['close_hour'] = ' ';
			$schedule_data['is_open'] = '0';

			$this->Schedule->save($schedule_data);
		}

		// Saturday

		if ($data['saturday'] == '1') {
			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('saturday_label');
			$schedule_data['open_hour'] = $this->get_data('from_saturday');
			$schedule_data['close_hour'] = $this->get_data('to_saturday');
			$schedule_data['is_open'] = '1';

			$this->Schedule->save($schedule_data);
		} else {

			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('saturday_label');
			$schedule_data['open_hour'] = ' ';
			$schedule_data['close_hour'] = ' ';
			$schedule_data['is_open'] = '0';

			$this->Schedule->save($schedule_data);
		}

		// Sunday

		if ($data['sunday'] == '1') {
			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('sunday_label');
			$schedule_data['open_hour'] = $this->get_data('from_sunday');
			$schedule_data['close_hour'] = $this->get_data('to_sunday');
			$schedule_data['is_open'] = '1';

			$this->Schedule->save($schedule_data);
		} else {

			$schedule_data['shop_id'] = $id;
			$schedule_data['days_of_week'] =  get_msg('sunday_label');
			$schedule_data['open_hour'] = ' ';
			$schedule_data['close_hour'] = ' ';
			$schedule_data['is_open'] = '0';

			$this->Schedule->save($schedule_data);
		}

		// commit the transaction
		if (!$this->check_trans()) {

			// set flash error message
			$this->set_flash_msg('error', get_msg('err_model'));
		} else {

			if ($id) {
				// if user id is not false, show success_add message

				$this->set_flash_msg('success', get_msg('success_shop_edit'));
			} else {
				// if user id is false, show success_edit message

				$this->set_flash_msg('success', get_msg('success_shop_add'));
			}
		}
		// print_r( $this->get_data( 'current_tab'));die;

		$this->data['current_tab'] = $this->get_data('current_tab');
		redirect(site_url('/admin/shops/edit/' . $id . "/" . $this->get_data('current_tab')));
	}



	function exports()
	{
		// Load the DB utility class
		$this->load->dbutil();

		// Backup your entire database and assign it to a variable
		$export = $this->dbutil->backup();

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download('ps_news.zip', $export);
	}
	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input($id = 0)
	{

		return true;
	}
}
