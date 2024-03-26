<?php
require_once( APPPATH .'libraries/REST_Controller.php' );
require_once('vendor/autoload.php');

/**
 * REST API for News
 */
class TokenFunc extends API_Controller
{
	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct('User');
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

	function token_post()
	{
        // if(!$this->post('user_email') || !$this->post('user_password'))
		// {
        //     $user_info = $this->User->get_one_by( array( "user_email" => 'guestbarkhana@itretail.co.uk'));
        // }   
        // else 
        // {
            $user_info=null;
            if((!$this->post( 'user_email' )=='guestbarkhana@itretail.co.uk' && !$this->post( 'user_password' )=='Barkhana1234*') &&            
            ( !$this->User->exists( array( 'user_email' => $this->post( 'user_email' ), 'user_password' => $this->post( 'user_password' ))))) {                

                $this->error_response( get_msg( 'err_user_not_exist' ), 400);
            }
            if($this->post( 'user_email' )=='guestbarkhana@itretail.co.uk' && $this->post( 'user_password' )=='Barkhana1234*')
            {
                $user_info='guestbarkhana@itretail.co.uk';
            }
            else 
            {
                $email = $this->post( 'user_email' );
                $conds['user_email'] = $email;
                $is_banned = $this->User->get_one_by($conds)->is_banned;
                $code = $this->User->get_one_by($conds)->code;
                $status = $this->User->get_one_by($conds)->status;
    
                if ( $code != "" && $code != " "){
                    $this->error_response( get_msg( 'need_to_verify' ), 400);
                }else if ( $is_banned == '1' ) {
                    $this->error_response( get_msg( 'err_user_banned' ), 400);
                }else if ( $status == '0') {
                    $this->error_response( get_msg( 'not_yet_activate' ), 400);
                }else {
                    $user_info = $this->User->get_one_by( array( "user_email" => $this->post( 'user_email' )));
                }    
                
            }
                
        //}
		if($user_info->user_email!=null || $user_info=='guestbarkhana@itretail.co.uk')
        {
            $email=$user_info->user_email==null?$user_info:$user_info->user_email;
			$token = $this->jwtlib->generateToken($email);//JWT::encode($payload, $key, 'HS256');
        }
        else 
        {
            $this->error_response( get_msg( 'err_user_not_exist' ), 400);
        }
        $data=$token;
        $this->custom_response($data);
	}


    function refresh_token_post()
    {
        $timezone = new DateTimeZone('Europe/London');
        $expired= $this->jwtlib->isTokenExpired($this->post( 'user_token' ));
        $token= array();
        if($expired['isExpired'])
        {
            $email='guestbarkhana@itretail.co.uk';
			$token = $this->jwtlib->generateToken($email);
            //$response["token"]="";
            //$response["expiry"]="";
            $token["expired"]="true";
        }
        else
        {
            // $expDateTime=new DateTime();
            // $expDateTime->setTimestamp($expired['data']->exp);
            // $expDateTime->setTimezone($timezone);

				
            // $response["token"]=$this->post( 'user_token' );
            // $response["expiry"]=$expDateTime->format('m/d/Y H:i:s');
            if(!is_null($expired['data']->sub))
            {
                $email=$expired['data']->sub;
                $token = $this->jwtlib->generateToken($email);
                $token["expired"]="false";
            }
            else 
            {
                $token["token"]="";
                $token["expiry"]="";
                $token["expired"]="true";
                
            }
        }
        $this->custom_response($token);

    }





	/**
	 * Convert Object
	 */
	function convert_object( &$obj )
	{
	}


}