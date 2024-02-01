<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for News
 */
class PostCodeChecker extends API_Controller
{

	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'PostCodeChecker' );
	}

	/**
	 * Default Query for API
	 * @return [type] [description]
	 */
	function default_conds()
	{
		$conds = array();
        
		return $conds;
	}

    /**
     * Convert Object
     */
    function convert_object( &$obj )
    {
        // call parent convert object
        parent::convert_object( $obj );

        $this->ps_adapter->convert_postcode( $obj );

    }

}