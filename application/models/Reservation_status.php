<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for reservation status table
 */
class Reservation_status extends PS_Model {
	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'rt_reservation_status', 'id', 'res_sts' );
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{
		
		// id condition
		if ( isset( $conds['id'] )) {
			$this->db->where( 'id', $conds['id'] );
		}

		// title condition
		if ( isset( $conds['title'] )) {
			$this->db->where( 'title', $conds['title'] );
		}

	}
}