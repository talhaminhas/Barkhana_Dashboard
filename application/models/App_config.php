<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for App_config table
 */
class App_config extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'rt_app_configs', 'id', 'appset' );
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

		// enable_comment condition
		if ( isset( $conds['enable_comment'] )) {
			$this->db->where( 'enable_comment', $conds['enable_comment'] );
		}

		// enable_review condition
		if ( isset( $conds['enable_review'] )) {
			$this->db->where( 'enable_review', $conds['enable_review'] );
		}
	}
}