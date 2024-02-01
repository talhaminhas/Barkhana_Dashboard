<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for shop table
 */
class Shop extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'rt_shops', 'id', 'shop' );
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{
		// about_id condition
		if ( isset( $conds['no_publish_filter'] )) {
			$this->db->where( 'status', $conds['no_publish_filter'] );
		}
	
		// about_id condition
		if ( isset( $conds['id'] )) {
			$this->db->where( 'id', $conds['id'] );
		}

		// checkout_with_whatsapp condition
		if ( isset( $conds['checkout_with_whatsapp'] )) {
			$this->db->where( 'checkout_with_whatsapp', $conds['checkout_with_whatsapp'] );
		}

		// checkout_with_email condition
		if ( isset( $conds['checkout_with_email'] )) {
			$this->db->where( 'checkout_with_email', $conds['checkout_with_email'] );
		}

		// transaction_on condition
		if ( isset( $conds['transaction_on'] )) {
			$this->db->where( 'transaction_on', $conds['transaction_on'] );
		}

		// transaction_off condition
		if ( isset( $conds['transaction_off'] )) {
			$this->db->where( 'transaction_off', $conds['transaction_off'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->group_start();
			$this->db->like( 'name', $conds['searchterm'] );
			$this->db->or_like( 'name', $conds['searchterm'] );
			$this->db->group_end();
		}
	}
	
}
?>