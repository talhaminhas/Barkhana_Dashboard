<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for Users
 */
class Users extends API_Controller
{

	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'User' );
	}	

	/**
	 * Convert Object
	 */
	function convert_object( &$obj )
	{
		// call parent convert object
		parent::convert_object( $obj );

		// convert customize category object
		$this->ps_adapter->convert_user( $obj );
	}
	
	/**
	 * Users Registration
	 */
	function add_post()
	{
		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'user_name',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'user_email',
	        	'rules' => 'required|valid_email|callback_email_check'
	        ),
	        array(
	        	'field' => 'user_password',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
        $email_verified_enabled = $this->Backend_config->get_one('be1')->email_verification_enabled;

        $code = generate_random_string(5);
       
      	$is_delivery_boy = $this->post('is_delivery_boy');

      	if ($is_delivery_boy == 1) {
      		// deli boy

      		if ($email_verified_enabled != 1) {

	      		$user_data = array(
	        	"user_name" => $this->post('user_name'), 
	        	"user_email" => $this->post('user_email'),
	        	"user_phone" => $this->post('user_phone'), 
	        	'user_password' => md5($this->post('user_password')),
	        	"device_token" => $this->post('device_token'),
	        	"code" =>'',
	        	"role_id" => 5,
	        	"verify_types" => 1,
	        	"status" => 2, //Need to verified status
	        	"added_date" =>  date("Y-m-d H:i:s")
	        	);

	        }else{

	        	$user_data = array(
	        	"user_name" => $this->post('user_name'), 
	        	"user_email" => $this->post('user_email'),
	        	"user_phone" => $this->post('user_phone'), 
	        	'user_password' => md5($this->post('user_password')),
	        	"device_token" => $this->post('device_token'),
	        	"code" =>  $code,
	        	"role_id" => 5,
	        	"verify_types" => 1,
	        	"status" => 2, //Need to verified status
	        	"added_date" =>  date("Y-m-d H:i:s")
	        	);

	        }

      	}	
      	else{
      		// user

      		if($email_verified_enabled != 1) {
      			// no need to verify
      			$user_data = array(
	        	"user_name" => $this->post('user_name'), 
	        	"user_email" => $this->post('user_email'),
	        	"user_phone" => $this->post('user_phone'), 
	        	'user_password' => md5($this->post('user_password')),
	        	"device_token" => $this->post('device_token'),
	        	"code" =>  "",
	        	"role_id" => 4,
	        	"verify_types" => 1,
	        	"status" => 1, //Need to verified status
	        	"added_date" =>  date("Y-m-d H:i:s")
	        	);	


      		} 
      		else {

      			$user_data = array(
	        	"user_name" => $this->post('user_name'), 
	        	"user_email" => $this->post('user_email'),
	        	"user_phone" => $this->post('user_phone'), 
	        	'user_password' => md5($this->post('user_password')),
	        	"device_token" => $this->post('device_token'),
	        	"code" =>  $code,
	        	"role_id" => 4,
	        	"verify_types" => 1,
	        	"status" => 2, //Need to verified status
	        	"added_date" =>  date("Y-m-d H:i:s")
	        	);	
	        	$conds['status'] = 2;

      		}

      	}
        $conds['user_email'] = $user_data['user_email'];
       	$user_infos = $this->User->get_one_user_email($conds)->result();

       	if (empty($user_infos)) {

       		// not exist yet

       		if ( !$this->User->save($user_data)) {

        	$this->error_response( get_msg( 'err_user_register' ), 500);
	        } else {

	        	$noti_token = array(
					"device_id" => $user_data['device_token']
				);
		        
				$noti_count = $this->Notitoken->count_all_by($noti_token);
        		
				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(
							"user_id" => $user_data['user_id'],
	        				"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_data['user_id'],
							"device_id" => $this->post( 'device_token' ),
	        				"platform_name" => $this->post('platform_name')
							
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_data['user_id'],
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );

				}

	        	$subject = get_msg('new_user_register');
				

	        	if($email_verified_enabled != 1) {
	        		if ( !send_user_register_email_without_verify( $user_data['user_id'], $subject )) {

					$this->error_response( get_msg( 'user_register_success_but_email_not_send' ), 500);
				
					} 
	        	} else {
	        		if ( !send_user_register_email( $user_data['user_id'], $subject )) {

					$this->error_response( get_msg( 'user_register_success_but_email_not_send' ), 500);
				
				} 
	        	}
	        }
       	}else{
       		$status = $user_infos[0]->status;
       		
       		if ($status == 2) {
        	// already exist inside db

       		$user_id = $user_infos[0]->user_id;
       		$role_id = $user_infos[0]->role_id;
       		$subject = get_msg('new_user_register');

	       		if($email_verified_enabled != 1) {
	        		if ( !send_user_register_email_without_verify( $user_id, $subject )) {

					$this->error_response( get_msg( 'user_register_success_but_email_not_send' ), 500);
				
					} 
	        	} else {
	        		if ( !send_user_register_email( $user_id, $subject )) {

					$this->error_response( get_msg( 'user_register_success_but_email_not_send' ), 500);
				
					} 
	        	}

				if ($is_delivery_boy == 1) {
					//deli boy
					$user_data = array(
						
						"role_id" => 5
					);

				}else{
					//user
					if ($role_id == 4) {
						$user_data = array(
						
						"role_id" => 4
						);
					}else{
						$user_data = array(
						
						"role_id" => 5
						);
					}
					
				}	

				$this->User->save( $user_data, $user_id );

				if ( !send_user_register_email( $user_id, $subject )) {

						$this->error_response( get_msg( 'user_register_success_but_email_not_send' ), 500);
					
					} 


       			$this->custom_response($this->User->get_one($user_id));

	        }else if ($status == 0) {
	        	// already exist inside db

       		$user_id = $user_infos[0]->user_id;
       		$role_id = $user_infos[0]->role_id;
       		$subject = get_msg('new_user_register');

	       		 

				if ($is_delivery_boy == 1) {
					//deli boy (normal user and deli boy can be deli boy)
					$user_data = array(
						
						"role_id" => 5,
						"status"  => 2,
						"code"	  => $code
					);

				}else{
					//user (user can't be deli boy, but deli boy can be user)
					if ($role_id == 4) {
						$user_data = array(
						
						"role_id" => 4,
						"status"  => 2,
						"code"	  => $code
						);
					}else{
						$user_data = array(
						
						"role_id" => 5,
						"status"  => 2,
						"code"	  => $code
						);
					}
					
				}	

				$this->User->save( $user_data, $user_id );

				if ( !send_user_register_email( $user_id, $subject )) {

						$this->error_response( get_msg( 'user_register_success_but_email_not_send' ), 500);
					
					}


       			$this->custom_response($this->User->get_one($user_id));
	        }else{
	        	// already exist inside db but rejected

	       		$user_id = $user_infos[0]->user_id;
	       		$role_id = $user_infos[0]->role_id;
	       		$subject = get_msg('new_user_register');

				if ($is_delivery_boy == 1) {
					//deli boy
					$user_data = array(
						
						"role_id" => 5
					);

				}else{
					//user
					if ($role_id == 4) {
						$user_data = array(
						
						"role_id" => 4
						);
					}else{
						$user_data = array(
						
						"role_id" => 5
						);
					}
					
				}	

				$this->User->save( $user_data, $user_id );


	       		$this->custom_response($this->User->get_one($user_id));

	        }
	        

       	}


        $this->custom_response($this->User->get_one($user_data["user_id"]));

	}

	/**
	 * Users Registration with Facebook
	 */
	function facebook_register_post()
	{
		$rules = array(
	        array(
	        	'field' => 'facebook_id',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

		$is_delivery_boy = $this->post('is_delivery_boy');

        //Need to check facebook_id is aleady exist or not?
        if ( !$this->User->exists( array( 'facebook_id' => $this->post( 'facebook_id' ) ))) {

        	$rules = array(
		        array(
		        	'field' => 'user_name',
		        	'rules' => 'required'
		        )
	        );

            //User not yet exist 
        	$fb_id = $this->post( 'profile_img_id' );
			$url = "https://graph.facebook.com/$fb_id/picture?width=350&height=500";

			// for uploads 

		  	$data = file_get_contents($url);
		  	$dir = "uploads/";
			$img = md5(time()).'.jpg';
		  	$ch = curl_init($url);
			$fp = fopen( 'uploads/'. $img, 'wb' );
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);


			//for thumbnail
			$dir = "uploads/thumbnail/";
			$ch = curl_init($url);
			$fp = fopen( 'uploads/thumbnail/'. $img, 'wb' );
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);

			if ($is_delivery_boy == 1) {
				// deli boy
				$user_data = array(
	        	"user_name" 	=> $this->post('user_name'), 
	        	'user_email'    => $this->post('user_email'), 
	        	"facebook_id" 	=> $this->post('facebook_id'),
	        	"user_profile_photo" => $img,
	        	"device_token" => $this->post('device_token'),
	        	"verify_types" => 2,
	        	"role_id" => 5,
	        	"status" 	=> 2, 
		        "code"    => ' ',
		        "added_date" =>  date("Y-m-d H:i:s")
        		);

			}else{
				// user
				$user_data = array(
	        	"user_name" 	=> $this->post('user_name'), 
	        	'user_email'    => $this->post('user_email'), 
	        	"facebook_id" 	=> $this->post('facebook_id'),
	        	"user_profile_photo" => $img,
	        	"device_token" => $this->post('device_token'),
	        	"verify_types" => 2,
	        	"role_id" => 4,
	        	"status" 	=> 1, 
		        "code"    => ' ',
		        "added_date" =>  date("Y-m-d H:i:s")
        		);

			}
        	//print_r($user_email);die;

        	//if (!empty($user_email)) {
        		//email exists
        		$cond_user_existed['user_email'] = $user_data['user_email'];
        		$cond_user_existed['phone_id'] = "";
				$user_infos = $this->User->get_email_phone($cond_user_existed)->result();
				$user_id = $user_infos[0]->user_id;
        		
        	//} 
			
        	if ( $user_id != "") {
				//user email alerady exist

				//for user name and user email
			$user_name = $this->post('user_name');
			$user_email = $this->post('user_email');

			$role_id = $this->User->get_one($user_id)->role_id;
			$status = $this->User->get_one($user_id)->status;
			if ($role_id == 5) {
				//user
				$user_data = array(
	        	"role_id" => 5,
	        	"status" 	=> $status 
    			);
			}

			if ($user_name == "" && $user_email == "") {
				$user_data = array(
				"user_name" => $user_infos[0]->user_name,
				"user_email" => $user_infos[0]->user_email,	
				"device_token"  => $user_data['device_token'],
				"facebook_id" 	=> $user_data['facebook_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	'user_profile_photo' => $user_data['user_profile_photo'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}else if ($user_name == "") {
				$user_data = array(
				"user_name" => $user_infos[0]->user_name,
				"user_email"    => $user_email,
				"device_token"  => $user_data['device_token'],
				"facebook_id" 	=> $user_data['facebook_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	'user_profile_photo' => $user_data['user_profile_photo'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}else if ($user_email == "") {
				$user_data = array(
				"user_name"    => $user_name,
				"user_email" => $user_infos[0]->user_email,
				"device_token"  => $user_data['device_token'], 
				"facebook_id" 	=> $user_data['facebook_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	'user_profile_photo' => $user_data['user_profile_photo'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}else{
				$user_data = array(
				"user_name"    => $user_name,
				"user_email"    => $user_email,
				"device_token"  => $user_data['device_token'],
				"facebook_id" 	=> $user_data['facebook_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	'user_profile_photo' => $user_data['user_profile_photo'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}

				$this->User->save($user_data,$user_id);

				$noti_token = array(
					"device_id" => $this->post( 'device_token' )
				);

				$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
						$noti_id = $this->Notitoken->get_one_by($noti_token);
						$push_noti_token_id = $noti_id->id;
						$noti_data = array(

							"user_id" => $user_id,
							"platform_name" => $this->post('platform_name')
							
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					} else {
						$noti_data = array(

							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
							
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					}
				}else{
					$this->Notitoken->delete_by($noti_token);
						$noti_data = array(

							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
							
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}
				
			} else {
				//user email not exist
				//user update
				if ( !$this->User->save($user_data)) {
        			$this->error_response( get_msg( 'err_user_register' ), 500);
        		}

        		// noti update
        		
        		$noti_token = array(
    				"device_id" => $this->post( 'device_token' )
    			);

        		$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(
							"user_id" => $user_data['user_id'],
							"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(

							"user_id" => $user_data['user_id'],
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
							
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_data['user_id'],
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')							
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}
        		$this->custom_response($this->User->get_one($user_data['user_id']));

			}

        	$this->custom_response($this->User->get_one($user_infos[0]->user_id));

        } else {

        	//User already exist in DB no need to check email
        	$conds['facebook_id'] = $this->post( 'facebook_id' );
        	$conds1['facebook_id'] = $this->post( 'facebook_id' );
        	$user_profile_data = $this->User->get_one_by($conds);
        	$user_profile_photo = $user_profile_data->user_profile_photo;
        	
        	//Delete existing image 
        	@unlink('./uploads/'.$user_profile_photo);
        	@unlink('./uploads/thumbnail/'.$user_profile_photo);
			
			//Download again
			$fb_id = $this->post( 'profile_img_id' );
			$url = "https://graph.facebook.com/$fb_id/picture?width=350&height=500";

			// for uploads

		  	$data = file_get_contents($url);
		  	$dir = "uploads/";
			$img = md5(time()).'.jpg';
		  	$ch = curl_init($url);
			$fp = fopen( 'uploads/'. $img, 'wb' );
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);

			// for thumbnail 

			$dir = "uploads/thumbnail/";
			$ch = curl_init($url);
			$fp = fopen( 'uploads/thumbnail/'. $img, 'wb' );
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);

			
			

			$conds['facebook_id'] = $this->post( 'facebook_id' );
			$user_datas = $this->User->get_one_by($conds);
			$user_id = $user_datas->user_id;
			$role_id = $user_datas->role_id;
			$status = $user_datas->status;

			if ($role_id == 4) {
				//user
				if ($is_delivery_boy == 1) {
					//user can be deli boy
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_email'    => $this->post('user_email'),
					'user_profile_photo' => $img,
					'device_token'  => $this->post('device_token'),
					'role_id' => 5,
					'status'  => 2,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				}else{
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_email'    => $this->post('user_email'),
					'user_profile_photo' => $img,
					'device_token'  => $this->post('device_token'),
					'role_id' => 4,
					'status' => 1,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				}
			}else{
				//deli boy
				
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_email'    => $this->post('user_email'),
					'user_profile_photo' => $img,
					'device_token'  => $this->post('device_token'),
					'status' => 2,
					'role_id' => 5,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				
			}

			//for user name and user email
			$user_name = $this->post('user_name');
			$user_email = $this->post('user_email');

			if ($user_name == "" && $user_email == "") {
				$user_data = array(
				'device_token'  => $this->post('device_token'),
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'], 
				);
			}else if ($user_name == "") {
				$user_data = array(
				'user_email'    => $user_data['user_email'],
				'device_token'  => $user_data['device_token'],
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'], 
				);
			}else if ($user_email == "") {
				$user_data = array(
				'user_name'    => $user_data['user_name'],
				'device_token'  => $user_data['device_token'], 
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'],
				);
			}else{
				$user_data = array(
				'user_name'    => $user_data['user_name'],
				'user_email'    => $user_data['user_email'],
				'device_token'  => $user_data['device_token'],
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'], 
				);
			}	

			if ( $user_datas->is_banned == 1 ) {

				$this->error_response( get_msg( 'err_user_banned' ), 400);
			} else {

				if ( !$this->User->save($user_data,$user_id)) {
        			$this->error_response( get_msg( 'err_user_register' ), 500);
        		}

				$noti_token = array(
					"device_id" => $this->post( 'device_token' )
				);
				
				$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
						$noti_id = $this->Notitoken->get_one_by($noti_token);
						$push_noti_token_id = $noti_id->id;
						$noti_data = array(
							"user_id" => $user_id,
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					} else {
						$noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					}
				}else{
					$this->Notitoken->delete_by($noti_token);
						$noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}
			}

        	$this->custom_response($this->User->get_one($user_datas->user_id));

        }

	}
	/**
	 * Users Registration with Google
	*/
	function google_register_post()
	{
		$rules = array(
	        array(
	        	'field' => 'google_id',
	        	'rules' => 'required'
	        )
        );

        $is_delivery_boy = $this->post('is_delivery_boy');

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        //Need to check google_id is aleady exist or not?
        if ( !$this->User->exists( 
        	array( 
        		'google_id' => $this->post( 'google_id' ) 
        		))) {

        	$rules = array(
				array(
		        	'field' => 'user_name',
		        	'rules' => 'required'
		        )
			);
        
            //User not yet exist 
        	$gg_id = $this->post( 'google_id' ) ;
			$url = $this->post('profile_photo_url');

		  	if ($url !="") {

		  		// for upload

				$data = file_get_contents($url);
			  	$dir = "uploads/";
				$img = md5(time()).'.jpg';
			  	$ch = curl_init($url);
				$fp = fopen( 'uploads/'. $img, 'wb' );
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);

				// for thumbnail

				$dir = "uploads/thumbnail/";
			  	$ch = curl_init($url);
				$fp = fopen( 'uploads/thumbnail/'. $img, 'wb' );
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);

				if ($is_delivery_boy == 1) {
					//deli boy
					$user_data = array(
		        	"user_name" 	=> $this->post('user_name'), 
		        	'user_email'    => $this->post('user_email'), 
		        	"google_id" 	=> $this->post('google_id'),
		        	"user_profile_photo" => $img,
		        	"device_token" => $this->post('device_token'),
		        	"verify_types" => 3,
		        	"role_id" => 5,
		        	"status" 	=> 2, 
			        "code"   => ' ',
			        "added_date" =>  date("Y-m-d H:i:s")
	        		);

				}else{
					//user
					$user_data = array(
		        	"user_name" 	=> $this->post('user_name'), 
		        	'user_email'    => $this->post('user_email'), 
		        	"google_id" 	=> $this->post('google_id'),
		        	"user_profile_photo" => $img,
		        	"device_token" => $this->post('device_token'),
		        	"verify_types" => 3,
		        	"role_id" => 4,
		        	"status" 	=> 1, 
			        "code"   => ' ',
			        "added_date" =>  date("Y-m-d H:i:s")
	        	);


				}


				
			} else{

				if($is_delivery_boy == 1){

					//deli boy
					$user_data = array(
		        	"user_name" 	=> $this->post('user_name'), 
		        	'user_email'    => $this->post('user_email'), 
		        	"google_id" 	=> $this->post('google_id'),
		        	"device_token" => $this->post('device_token'),
		        	"verify_types" => 3,
		        	"role_id" => 5,
		        	"status" 	=> 2, 
			        "code"   => ' ',
			        "added_date" =>  date("Y-m-d H:i:s")
        			);

				}else{

					//user
					$user_data = array(
		        	"user_name" 	=> $this->post('user_name'), 
		        	'user_email'    => $this->post('user_email'), 
		        	"google_id" 	=> $this->post('google_id'),
		        	"device_token" => $this->post('device_token'),
		        	"verify_types" => 3,
		        	"role_id" => 4,
		        	"status" 	=> 1, 
			        "code"   => ' ',
			        "added_date" =>  date("Y-m-d H:i:s")
        			);

				}

					
			}

        	$cond_user_existed['user_email'] = $user_data['user_email'];
        	$cond_user_existed['phone_id'] = "";
			$user_infos = $this->User->get_email_phone($cond_user_existed)->result();
			$user_id = $user_infos[0]->user_id;

			//print_r($user_id);die;

			//for email blank

			if ( $user_id != "" ) {
				$role_id = $this->User->get_one($user_id)->role_id;
				$status = $this->User->get_one($user_id)->status;
				if ($role_id == 5) {
					//user
					$user_data = array(
		        	"role_id" => 5,
		        	"status" 	=> $status 
        			);
				}
				//user email alerady exist
				//for user name and user email
				$user_name = $this->post('user_name');
				$user_email = $this->post('user_email');

				if ($user_name == "" && $user_email == "") {
					$user_data = array(
					"user_name" => $user_infos[0]->user_name,
					"user_email" => $user_infos[0]->user_email,	
					"device_token"  => $user_data['device_token'],
					"google_id" 	=> $user_data['google_id'],
		        	"verify_types" => $user_data['verify_types'],
		        	'user_profile_photo' => $user_data['user_profile_photo'],
		        	"role_id" => $user_data['role_id'],
		        	"status" 	=> $user_data['status'] 
					);
				}else if ($user_name == "") {
					$user_data = array(
					"user_name" => $user_infos[0]->user_name,
					"user_email"    => $user_email,
					"device_token"  => $user_data['device_token'],
					"google_id" 	=> $user_data['google_id'],
		        	"verify_types" => $user_data['verify_types'],
		        	'user_profile_photo' => $user_data['user_profile_photo'],
		        	"role_id" => $user_data['role_id'],
		        	"status" 	=> $user_data['status']
					);
				}else if ($user_email == "") {
					$user_data = array(
					"user_name"    => $user_name,
					"user_email" => $user_infos[0]->user_email,
					"device_token"  => $user_data['device_token'], 
					"google_id" 	=> $user_data['google_id'],
		        	"verify_types" => $user_data['verify_types'],
		        	'user_profile_photo' => $user_data['user_profile_photo'],
		        	"role_id" => $user_data['role_id'],
		        	"status" 	=> $user_data['status']
					);
				}else{
					$user_data = array(
					"user_name"    => $user_name,
					"user_email"    => $user_email,
					"device_token"  => $user_data['device_token'],
					"google_id" 	=> $user_data['google_id'],
		        	"verify_types" => $user_data['verify_types'],
		        	'user_profile_photo' => $user_data['user_profile_photo'],
		        	"role_id" => $user_data['role_id'],
		        	"status" 	=> $user_data['status']
					);
				}


    			$this->User->save($user_data,$user_id);

				$noti_token = array(
    				"device_id" => $this->post( 'device_token' )
    			);

    			$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(

							"user_id" => $user_id,
	        				"platform_name" => $this->post('platform_name')

							
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
	        				"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_id,
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}
				
			} else {
				//user email not exist
				if ( !$this->User->save($user_data)) {
        		$this->error_response( get_msg( 'err_user_register' ), 500);
        		}

        		$noti_token = array(
    				"device_id" => $this->post( 'device_token' )
    			);

        		$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(
							"user_id" => $user_data['user_id'],
	        				"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_data['user_id'],
							"device_id" => $this->post( 'device_token' ),
	        				"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_data['user_id'],
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}

        		$this->custom_response($this->User->get_one($user_data['user_id']));

			}
			//print_r($user_data);die;

        	$this->custom_response($this->User->get_one($user_infos[0]->user_id));


        } else {

        	//User already exist in DB
        	$conds['google_id'] = $this->post( 'google_id' );
        	$user_profile_data = $this->User->get_one_by($conds);
        	$user_profile_photo = $user_profile_data->user_profile_photo;

        	//Delete existing image 
        	@unlink('./uploads/'.$user_profile_photo);
			@unlink('./uploads/thumbnail/'.$user_profile_photo);
			//Download again
			$fb_id = $this->post( 'google_id' ) ;
			$url = $this->post('profile_photo_url');

			$conds['google_id'] = $this->post( 'google_id' );
			$user_datas = $this->User->get_one_by($conds);
			$user_id = $user_datas->user_id;
			$role_id = $user_datas->role_id;
			$status = $user_datas->status;

			if($url != "") {

		  		// for upload

			  	$data = file_get_contents($url);
			  	$dir = "uploads/";
				$img = md5(time()).'.jpg';
			  	$ch = curl_init($url);
				$fp = fopen( 'uploads/'. $img, 'wb' );
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);

				// for thumbnail
				
				$dir = "uploads/thumbnail/";
			  	$ch = curl_init($url);
				$fp = fopen( 'uploads/thumbnail/'. $img, 'wb' );
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);

				if ($role_id == 4) {
					//user can be deli boy
					if ($is_delivery_boy == 1) {
						$user_data = array(
						'user_name'    	=> $this->post('user_name'), 
						'user_email'    => $this->post('user_email'),
						'device_token'  => $this->post('device_token'),
						'user_profile_photo' => $img,
						'role_id' => 5,
						'status'  => 2,
						"added_date" =>  date("Y-m-d H:i:s")
						);
					}else{
						$user_data = array(
						'user_name'    	=> $this->post('user_name'), 
						'user_email'    => $this->post('user_email'),
						'device_token'  => $this->post('device_token'),
						'user_profile_photo' => $img,
						'role_id' => 4,
						'status' => 1,
						"added_date" =>  date("Y-m-d H:i:s")
						);
					}
				}else{
					// deli boy (can't be user)
					if ($status == 0) {
						$user_data = array(
						'user_name'    	=> $this->post('user_name'), 
						'user_email'    => $this->post('user_email'),
						'user_profile_photo' => $img,
						'device_token'  => $this->post('device_token'),
						'status' => 2,
						'role_id' => 5,
						"added_date" =>  date("Y-m-d H:i:s")
						);
					}


				}
			} else {

				if ($role_id == 4) {
					//user can be deli boy
					if ($is_delivery_boy == 1) {
						$user_data = array(
						'user_name'    	=> $this->post('user_name'), 
						'user_email'    => $this->post('user_email'),
						'device_token'  => $this->post('device_token'),
						'role_id' => 5,
						'status'  => 2,
						"added_date" =>  date("Y-m-d H:i:s")
						);
					}else{
						$user_data = array(
						'user_name'    	=> $this->post('user_name'), 
						'user_email'    => $this->post('user_email'),
						'device_token'  => $this->post('device_token'),
						'role_id' => 4,
						'status' => 1,
						"added_date" =>  date("Y-m-d H:i:s")
						);
					}
				}else{
					// deli boy (can't be user)
					
						$user_data = array(
						'user_name'    	=> $this->post('user_name'), 
						'user_email'    => $this->post('user_email'),
						'device_token'  => $this->post('device_token'),
						'role_id' => 5,
						'status' => 1,
						"added_date" =>  date("Y-m-d H:i:s")
						);
					
				}
			}


			//for user name and user email
			$user_name = $this->post('user_name');
			$user_email = $this->post('user_email');

			if ($user_name == "" && $user_email == "") {
				$user_data = array(
				'device_token'  => $this->post('device_token'),
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'], 
				);
			}else if ($user_name == "") {
				$user_data = array(
				'user_email'    => $user_data['user_email'],
				'device_token'  => $user_data['device_token'], 
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'],
				);

			}else if ($user_email == "") {
				$user_data = array(
				'user_name'    => $user_data['user_name'],
				'device_token'  => $user_data['device_token'], 
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'],
				);
			}else{
				$user_data = array(
				'user_name'    => $user_data['user_name'],
				'user_email'    => $user_data['user_email'],
				'device_token'  => $user_data['device_token'], 
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_profile_photo' => $user_data['user_profile_photo'],
				);
			}	
			
			
			if ( $user_datas->is_banned == 1 ) {

				$this->error_response( get_msg( 'err_user_banned' ), 400);
			} else {

				if ( !$this->User->save($user_data,$user_id)) {
	        		$this->error_response( get_msg( 'err_user_register' ), 500);
	        	}

				$noti_token = array(
					"device_id" => $this->post( 'device_token' )
				);

				$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
						$noti_id = $this->Notitoken->get_one_by($noti_token);
						$push_noti_token_id = $noti_id->id;
						$noti_data = array(
							"user_id" => $user_id,
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					} else {
						$noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					}
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_id,
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}

			}

        	$this->custom_response($this->User->get_one($user_datas->user_id));

        }


	}


	/**
	 * Email Checking
	 *
	 * @param      <type>  $email     The identifier
	 *
	 * @return     <type>  ( description_of_the_return_value )
	 */
	function email_check( $email)
    {
        if ( $this->User->exists( array( 'user_email' => $email, 'status' => 1 ))) {
        
            $this->form_validation->set_message('email_check', get_msg('email_already_existed'));
            return false;

        } 

        return true;
    }

    /**
	 * Users Login
	 */
	function login_post()
	{
		// validation rules for user register
		$rules = array(
			
	        array(
	        	'field' => 'user_email',
	        	'rules' => 'required|valid_email'
	        ),
	        array(
	        	'field' => 'user_password',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        if ( !$this->User->exists( array( 'user_email' => $this->post( 'user_email' ), 'user_password' => $this->post( 'user_password' )))) {
            $this->error_response( get_msg( 'err_user_not_exist' ), 400);
        }
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
		        $user_id = $user_info->user_id;
		        $data = array(
					
					'device_token' => $this->post('device_token')
				);
				$this->User->save($data,$user_id);

        		$noti_token = array(
					"device_id" => $this->post( 'device_token' )
				);

				$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(

							"user_id" => $user_id,
	        				"platform_name" => $this->post('platform_name')
							
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
	        				"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_id,
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}

		        $this->custom_response($this->User->get_one_by(array("user_email" => $this->post('user_email'))));

	        }
	        
	        
        
	}

	/**
	* User Reset Password
	*/
	function reset_post()
	{
		// validation rules for user register
		$rules = array(
	        array(
	        	'field' => 'user_email',
	        	'rules' => 'required|valid_email'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $user_info = $this->User->get_one_by( array( "user_email" => $this->post( 'user_email' )));

        if ( isset( $user_info->is_empty_object )) {
        // if user info is empty,
        	
        	$this->error_response( get_msg( 'err_user_not_exist' ), 400);
        }

        // generate code
        $code = md5(time().'teamps');

        // insert to reset
        $data = array(
			'user_id' => $user_info->user_id,
			'code' => $code
		);

		if ( !$this->ResetCode->save( $data )) {
		// if error in inserting,

			$this->error_response( get_msg( 'err_model' ), 500);
		}

		// Send email with reset code
		$to = $user_info->user_email;
	    $sender_name = $this->Backend_config->get_one('be1')->sender_name;
	    $subject = get_msg( 'pwd_reset_label' );
	    $hi = get_msg( 'hi_label' );
		$msg = "<p>".$hi.",". $user_info->user_name ."</p>".
					"<p>".get_msg( 'pwd_reset_link' )."<br/>".
					"<a href='". site_url( $this->config->item( 'reset_url') .'/'. $code ) ."'>".get_msg( 'reset_link_label' )."</a></p>".
					"<p>".get_msg( 'best_regards_label' ).",<br/>". $sender_name ."</p>";

		// send email from admin
		if ( ! $this->ps_mail->send_from_admin( $to, $subject, $msg ) ) {

			$this->error_response( get_msg( 'err_email_not_send' ), 500);
		}
		
		$this->success_response( get_msg( 'success_email_sent' ));
	}

	/**
	* User Profile Update
	*/

	function profile_update_post()
	{

		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'user_name',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'user_email',
	        	'rules' => 'required|valid_email'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
        $user_id = $this->post('user_id');
        // user email checking
        $user_email = $this->User->get_one($user_id)->user_email;
        if ($user_email == $this->post('user_email')) {
        	$email = $this->post('user_email');
        } else {
        	$conds['user_email'] = $this->post('user_email');
        	$conds['status'] = 1;
       		$user_infos = $this->User->get_one_user_email($conds)->result();
        	if (empty($user_infos)) {
        		$email = $this->post( 'user_email' );
        	} else {
        		
		    	$this->error_response( get_msg( 'err_user_email_exist' ), 409);
		    }
		 
        }

        // user phone checking
        $user_phone = $this->User->get_one($user_id)->user_phone;
        if ($user_phone == $this->post('user_phone')) {
        	$phone = $this->post('user_phone');
        } else {
        	$conds['user_phone'] = $this->post('user_phone');
        	$conds['status'] = 1;
       		$user_infos = $this->User->get_one_user_phone($conds)->result();
        	if (empty($user_infos)) {
        		$phone = $this->post( 'user_phone' );
        	} else {
        		
		    	$this->error_response( get_msg( 'err_user_phone_exist' ), 409);
		    }
		 
        }


        $user_lat = $this->post('user_lat');
        $user_lng = $this->post('user_lng');
        $user_area_id = $this->post('user_area_id');
		$user_user_postcode=$this->post('user_postcode');
		$user_user_city=$this->post('user_city');
		$user_user_country=$this->post('user_country');
		

        if ($user_lat != "" || $user_lng != "") {
        	//lat and lng not blank
        	if ($user_area_id != "") {
        		//user_area_id is not blank
        		$conds['id'] = $user_area_id;
        		$id = $this->Shipping_area->exists($conds);
        		if (!$id) {
        			//invalid
        			$this->error_response( get_msg( 'area_invlaid' ), 400);
        		} else{
        			//valid
        			$user_data = array(
		        	"user_name"     		=> $this->post('user_name'), 
		        	"user_email"    		=> $email, 
		        	"user_phone"    		=> $phone,
		        	"user_about_me" 		=> $this->post('user_about_me'),
		        	"user_address" 		   	=> $this->post('user_address'),
		        	"user_area_id" 		    => $user_area_id,
		        	"user_lat" 		    	=> $user_lat,
		        	"user_lng" 		    	=> $user_lng,
		        	"added_date" 			=> date("Y-m-d H:i:s"),
					"user_postcode"			=> $user_user_postcode,
					"user_city"				=> $user_user_city,
					"user_country"			=> $user_user_country
		            );
		        }
        	} else {
        		//user_area_id is blank
	    		$user_data = array(
	        	"user_name"     		=> $this->post('user_name'), 
	        	"user_email"    		=> $email, 
	        	"user_phone"    		=> $this->post('user_phone'),
	        	"user_about_me" 		=> $this->post('user_about_me'),
	        	"user_address" 		   	=> $this->post('user_address'),
	        	"user_lat" 		    	=> $user_lat,
	        	"user_lng" 		    	=> $user_lng,
	        	"user_area_id" 		    => $user_area_id,
	        	"added_date" 			=>  date("Y-m-d H:i:s"),
				"user_postcode"			=> $user_user_postcode,
				"user_city"		=> $user_user_city,
				"user_country"		=> $user_user_country
	            );
	        }    
        } else {
        	//lat and lng not blank
        	if ($user_area_id != "") {
        		//user_area_id is not blank
        		$conds['id'] = $user_area_id;
        		$id = $this->Shipping_area->exists($conds);
        		if (!$id) {
        			//invalid
        			$this->error_response( get_msg( 'area_invlaid' ), 400);
        		} else{
        			//valid
        			$user_data = array(
		        	"user_name"     		=> $this->post('user_name'), 
		        	"user_email"    		=> $email, 
		        	"user_phone"    		=> $this->post('user_phone'),
		        	"user_about_me" 		=> $this->post('user_about_me'),
		        	"user_address" 		   	=> $this->post('user_address'),
		        	"user_area_id" 		    => $user_area_id,
		        	"added_date" 			=>  date("Y-m-d H:i:s"),
					"user_postcode"			=> $user_user_postcode,
					"user_city"				=> $user_user_city,
					"user_country"			=> $user_user_country
						
		            );
		        }
        	} else {
                $user_data = array(
                    "user_name"     		=> $this->post('user_name'),
                    "user_email"    		=> $email,
                    "user_phone"    		=> $this->post('user_phone'),
                    "user_about_me" 		=> $this->post('user_about_me'),
                    "user_address" 		   	=> $this->post('user_address'),
                    "added_date" 			=>  date("Y-m-d H:i:s"),
					"user_postcode"			=> $user_user_postcode,
					"user_city"				=> $user_user_city,
					"user_country"			=> $user_user_country

                );
            }
        }

        if ( !$this->User->save($user_data, $this->post('user_id'))) {

        	$this->error_response( get_msg( 'err_user_update' ), 500);
        }

    	$this->custom_response($this->User->get_one($user_id));

	    // $this->success_response( get_msg( 'success_profile_update' ));

	}

	/**
	* User Profile Update
	*/
	function password_update_post()
	{

		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'user_id',
	        	'rules' => 'required|callback_id_check[User]'
	        ),
	        array(
	        	'field' => 'user_password',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $user_data = array(
        	"user_password"     => md5($this->post('user_password'))
        );

        if ( !$this->User->save($user_data, $this->post('user_id'))) {
        	$this->error_response( get_msg( 'err_user_password_update' ), 500); 
        }

        $this->success_response( get_msg( 'success_profile_update' ), 201);

	}

	/**
	* User Verified Code
	*/
	function verify_post()
	{

		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'user_id',
	        	'rules' => 'required|callback_id_check[User]'
	        ),
	        array(
	        	'field' => 'code',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $conds['user_id'] = $this->post('user_id');
        $role_id = $this->User->get_one_by($conds)->role_id;

    	$user_verify_data = array(
    	"code"     => $this->post('code'),
    	"user_id"  => $this->post('user_id'),
    	"status"   => 2		
        );

        $user_data = $this->User->get_one_user($user_verify_data)->result();

        foreach ($user_data as $user) {
        	$user_id = $user->user_id;
        	$code = $user->code;
        }

        if($user_id  == $this->post('user_id')) {

        	if ($role_id == 4) {
        		$user_data = array(
	        	"code"    => " ",
	        	"status"  => 1
        		);
        	}else{
        		$user_data = array(
	        	"code"    => " ",
	        	"status"  => 2
        		);
        	}
        	$this->User->save($user_data,$user_id);
        	$this->custom_response($this->User->get_one($user_id));

        } else {

        	$this->error_response( get_msg( 'invalid_code' ), 400); 

        }
       

	}

	/**
	 * Users Request Code
	 */
	function request_code_post()
	{
		// validation rules for user register
		$rules = array(
	        array(
	        	'field' => 'user_email',
	        	'rules' => 'required'
	        )

        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        	if (!$this->User->user_exists( array( 'user_email' => $this->post( 'user_email' ), 'status' => 2 ))) {

        		$this->error_response( get_msg( 'err_user_not_exist' ), 400);

        	} else {
        		
        		$email = $this->post( 'user_email' );
		        $conds['user_email'] = $email;
		        $conds['status'] = 2;

		        $user_data = $this->User->user_exists($conds)->result();

		       	foreach ($user_data as $user) {
		       		$user_id = $user->user_id;
		       		$code = $user->code;
		       	}

		        if($code == " " ) {

		        	$resend_code = generate_random_string(5);
		        	$user_data_code = array(
			        	"code"    => $resend_code
		        	);
		        	$this->User->save($user_data_code,$user_id);

		        } 

	        
		        $user_data['user_id'] = $user_id;
		        //print_r($user_data);die;

        		$subject = get_msg('verify_code_sent');

	        	if ( !send_user_register_email( $user_data['user_id'], $subject )) {

					$this->error_response( get_msg( 'user_register_success_but_email_not_send' ), 500);
				
				}
					
				$this->success_response( get_msg( 'success_email_sent' ));

				
        	}

       
    }

   /**
	 * Users Registration with Phone
	*/
	function phone_register_post()
	{

		$rules = array(
	        array(
	        	'field' => 'phone_id',
	        	'rules' => 'required'
	        )
        );

        $is_delivery_boy = $this->post('is_delivery_boy');

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        //Need to check phone_id is aleady exist or not?
        if ( !$this->User->exists( 
        	//new
        	array( 
        		'phone_id' => $this->post( 'phone_id' ) 
        		))) {

        	$rules = array(
	        	array(
		        	'field' => 'user_name',
		        	'rules' => 'required'
		        )
	        );

        	if ($is_delivery_boy == 1) {
        		//deli boy
        		$user_data = array(
	        	"user_name" 	=> $this->post('user_name'), 
	        	'user_phone'    => $this->post('user_phone'), 
	        	"phone_id" 	   => $this->post('phone_id'),
	        	"device_token" => $this->post('device_token'),
	        	"verify_types" => 4,
	        	"role_id" => 5,
	        	"status" => 2,
	        	"added_date" =>  date("Y-m-d H:i:s")
        		);

        	}else{
        		$user_data = array(
	        	"user_name" 	=> $this->post('user_name'), 
	        	'user_phone'    => $this->post('user_phone'), 
	        	"phone_id" 	   => $this->post('phone_id'),
	        	"device_token" => $this->post('device_token'),
	        	"verify_types" => 4,
	        	"role_id" => 4,
	        	"status" => 1,
	        	"added_date" =>  date("Y-m-d H:i:s")
        		);
        	}

        	
			

        	$conds_phone['user_phone'] = $user_data['user_phone'];
			$user_infos = $this->User->get_one_user_phone($conds_phone)->result();
			$user_id = $user_infos[0]->user_id;

			if ( $user_id != "") {
				//user email alerady exist

				//for user name and user email
			$user_name = $this->post('user_name');
			$user_phone = $this->post('user_phone');

			$role_id = $this->User->get_one($user_id)->role_id;
			$status = $this->User->get_one($user_id)->status;
			if ($role_id == 5) {
				//user
				$user_data = array(
	        	"role_id" => 5,
	        	"status" 	=> $status 
    			);
			}

			if ($user_name == "" && $user_phone == "") {
				$user_data = array(
				"user_name" => $user_infos[0]->user_name,
				"user_phone" => $user_infos[0]->user_phone,	
				"device_token"  => $user_data['device_token'],
				"phone_id" 	=> $user_data['phone_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status']
				);
			}else if ($user_name == "") {
				$user_data = array(
				"user_name" => $user_infos[0]->user_name,
				"user_phone"    => $user_phone,
				"device_token"  => $user_data['device_token'],
				"phone_id" 	=> $user_data['phone_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status']
				);
			}else if ($user_phone == "") {
				$user_data = array(
				"user_name"    => $user_name,
				"user_phone" => $user_infos[0]->user_phone,
				"device_token"  => $user_data['device_token'], 
				"phone_id" 	=> $user_data['phone_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status']
				);
			}else{
				$user_data = array(
				"user_name"    => $user_name,
				"user_phone"    => $user_phone,
				"device_token"  => $user_data['device_token'],
				"phone_id" 	=> $user_data['phone_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status']
				);
			}

    			$this->User->save($user_data,$user_id);

				$noti_token = array(
    				"device_id" => $this->post( 'device_token' )
    			);

	        	$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(
							"user_id" => $user_id,
	        				"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
	        				"platform_name" => $this->post('platform_name')	
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_id,
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}
				
				
			} else {
				//user phone not exist

				if ( !$this->User->save($user_data)) {
        		$this->error_response( get_msg( 'err_user_register' ), 500);
        		}
				$noti_token = array(
    				"device_id" => $this->post( 'device_token' )
    			);

		        $noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(

							"user_id" => $user_data['user_id'],
	        				"platform_name" => $this->post('platform_name')

							
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_data['user_id'],
							"device_id" => $this->post( 'device_token' ),
	        				"platform_name" => $this->post('platform_name')
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_data['user_id'],
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}

        		$this->custom_response($this->User->get_one($user_data['user_id']));

			}

        	$this->custom_response($this->User->get_one($user_infos[0]->user_id));

        } else {
        	//update
        	//User already exist in DB
			$conds['phone_id'] = $this->post( 'phone_id' );
			$user_datas = $this->User->get_one_by($conds);
			$user_id = $user_datas->user_id;
			$role_id = $user_datas->role_id;
			$status = $user_datas->status;

			if ($role_id == 4) {
				//user can be deli boy
				if ($is_delivery_boy == 1) {
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_phone'    => $this->post('user_phone'),
					'device_token'  => $this->post('device_token'),
					'role_id' => 5,
					'status'  => 2,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				}else{
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_phone'    => $this->post('user_phone'),
					'device_token'  => $this->post('device_token'),
					'role_id' => 4,
					'status' => 1,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				}
			}else{
				//deli boy can't be user
				
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_phone'    => $this->post('user_phone'),
					'device_token'  => $this->post('device_token'),
					'status' => 2,
					'role_id' => 5,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				
				
			}

			//for user name and user email
			$user_name = $this->post('user_name');
			$user_phone = $this->post('user_phone');

			if ($user_name == "" && $user_phone == "") {
				$user_data = array(
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'device_token'  => $this->post('device_token'), 
				);
			}else if ($user_name == "") {
				$user_data = array(
				'user_phone'    => $user_data['user_phone'],
				'device_token'  => $user_data['device_token'],
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'], 
				);
			}else if ($user_phone == "") {
				$user_data = array(
				'user_name'    => $user_data['user_name'],
				'device_token'  => $user_data['device_token'],
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'], 
				);
			}else{
				$user_data = array(
				'user_name'    => $user_data['user_name'],
				'user_phone'    => $user_data['user_phone'],
				'device_token'  => $user_data['device_token'], 
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				);
			}

			if ( $user_datas->is_banned == 1 ) {

				$this->error_response( get_msg( 'err_user_banned' ), 400);
				
			} else {

				if ( !$this->User->save($user_data,$user_id)) {
	        		$this->error_response( get_msg( 'err_user_register' ), 500);
	        	}

				$noti_token = array(
					"device_id" => $this->post( 'device_token' )
				);

				$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
						$noti_id = $this->Notitoken->get_one_by($noti_token);
						$push_noti_token_id = $noti_id->id;
						$noti_data = array(

							"user_id" => $user_id,
							"platform_name" => $this->post('platform_name')

							
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					} else {
						$noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					}
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_id,
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				} 
			}

        	$this->custom_response($this->User->get_one($user_datas->user_id));

        }

	}

	/**
	 * Users Registration with Apple
	*/

	function apple_register_post()
	{
		$rules = array(
	        array(
	        	'field' => 'apple_id',
	        	'rules' => 'required'
	        )
        );

        $is_delivery_boy = $this->post('is_delivery_boy');


		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        //Need to check apple_id is aleady exist or not?
        if ( !$this->User->exists( 
        	array( 
        		'apple_id' => $this->post( 'apple_id' ) 
        		))) {

        	$rules = array(
		        array(
		        	'field' => 'user_name',
		        	'rules' => 'required'
		        )
        	);
        
          	if ($is_delivery_boy == 1) {
				//deli boy
				$user_data = array(
	        	"user_name" 	=> $this->post('user_name'), 
	        	'user_email'    => $this->post('user_email'), 
	        	"apple_id" 	=> $this->post('apple_id'),
	        	"device_token" => $this->post('device_token'),
	        	"verify_types" => 5,
	        	"status" 	=> 2, 
		        "code"   => ' ',
		        "role_id" => 5,
		        "added_date" =>  date("Y-m-d H:i:s")
    			);
			}else{
				//user
				$user_data = array(
	        	"user_name" 	=> $this->post('user_name'), 
	        	'user_email'    => $this->post('user_email'), 
	        	"apple_id" 	=> $this->post('apple_id'),
	        	"device_token" => $this->post('device_token'),
	        	"verify_types" => 5,
	        	"status" 	=> 1, 
		        "code"   => ' ',
		        "role_id" => 4,
		        "added_date" =>  date("Y-m-d H:i:s")
    			);
			}
			

        	$cond_user_existed['user_email'] = $user_data['user_email'];
        	$cond_user_existed['phone_id'] = "";
			$user_infos = $this->User->get_email_phone($cond_user_existed)->result();
			$user_id = $user_infos[0]->user_id;

			if ( $user_id != "") {
				//user email alerady exist

				//for user name and user email
			$user_name = $this->post('user_name');
			$user_email = $this->post('user_email');

			$role_id = $this->User->get_one($user_id)->role_id;
			$status = $this->User->get_one($user_id)->status;
			if ($role_id == 5) {
				//user
				$user_data = array(
	        	"role_id" => 5,
	        	"status" 	=> $status 
    			);
			}

			if ($user_name == "" && $user_email == "") {
				$user_data = array(
				"user_name" => $user_infos[0]->user_name,
				"user_email" => $user_infos[0]->user_email,	
				"device_token"  => $user_data['device_token'],
				"apple_id" 	=> $user_data['apple_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}else if ($user_name == "") {
				$user_data = array(
				"user_name" => $user_infos[0]->user_name,
				"user_email"    => $user_email,
				"device_token"  => $user_data['device_token'],
				"apple_id" 	=> $user_data['apple_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}else if ($user_email == "") {
				$user_data = array(
				"user_name"    => $user_name,
				"user_email" =>  $user_infos[0]->user_email,
				"device_token"  => $user_data['device_token'], 
				"apple_id" 	=> $user_data['apple_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}else{
				$user_data = array(
				"user_name"    => $user_name,
				"user_email"    => $user_email,
				"device_token"  => $user_data['device_token'],
				"apple_id" 	=> $user_data['apple_id'],
	        	"verify_types" => $user_data['verify_types'],
	        	"role_id" => $user_data['role_id'],
	        	"status" 	=> $user_data['status']
				);
			}

				$this->User->save($user_data,$user_id);

				$noti_token = array(
    				"device_id" => $this->post( 'device_token' )
    			);    	        

	        	$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(

							"user_id" => $user_id,
							"platform_name" => $this->post('platform_name')
							
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')	
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_id,
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				} 
			} else {
				//user email not exist

				if ( !$this->User->save($user_data)) {
        		$this->error_response( get_msg( 'err_user_register' ), 500);
        		}

				$noti_token = array(
    				"device_id" => $this->post( 'device_token' )
    			);    	        

	        	$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
	        			$noti_id = $this->Notitoken->get_one_by($noti_token);
	        			$push_noti_token_id = $noti_id->id;
	        			$noti_data = array(

							"user_id" => $user_id,
							"platform_name" => $this->post('platform_name')
							
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        } else {
			            $noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')	
						);
			        	$this->Notitoken->save( $noti_data, $push_noti_token_id );
			        }
				}else{
					$this->Notitoken->delete_by($noti_token);
					$noti_data = array(
						"user_id" => $user_id,
						"device_id" => $this->post( 'device_token' ),
						"platform_name" => $this->post('platform_name')
					);
					$this->Notitoken->save( $noti_data, $push_noti_token_id );
				}

        		$this->custom_response($this->User->get_one($user_data['user_id']));

			}

        	$this->custom_response($this->User->get_one($user_infos[0]->user_id));


        } else {

        	//User already exist in DB
			$conds['apple_id'] = $this->post( 'apple_id' );
			$user_datas = $this->User->get_one_by($conds);
			$user_id = $user_datas->user_id;
			$role_id = $user_datas->role_id;
			$status = $user_datas->status;

			if ($role_id == 4) {
				// user can be deli boy
				if ($is_delivery_boy == 1) {
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_email'    => $this->post('user_email'),
					'device_token'  => $this->post('device_token'),
					'role_id' => 5,
					'status'  => 2,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				}else{
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_email'    => $this->post('user_email'),
					'device_token'  => $this->post('device_token'),
					'role_id' => 4,
					'status' => 1,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				}
			}else{
				//deli boy can't be user
					$user_data = array(
					'user_name'    	=> $this->post('user_name'), 
					'user_email'    => $this->post('user_email'),
					'device_token'  => $this->post('device_token'),
					'role_id' => 5,
					'status' => 2,
					"added_date" =>  date("Y-m-d H:i:s")
					);
				
			}

			//for user name and user email
			$user_name = $this->post('user_name');
			$user_email = $this->post('user_email');

			if ($user_name == "" && $user_email == "") {
				$user_data = array(
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status']	,
				'device_token'  => $this->post('device_token'),
				);
			}else if ($user_name == "") {
				$user_data = array(
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],
				'user_email'    => $user_data['user_email'],
				'device_token'  => $user_data['device_token'], 
				);
			}else if ($user_email == "") {
				$user_data = array(
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],	
				'user_name'    => $user_data['user_name'],
				'device_token'  => $user_data['device_token'],
				);
			}else{
				$user_data = array(
				"role_id" => $user_data['role_id'],
				"status" 	=> $user_data['status'],	
				'user_name'    => $user_data['user_name'],
				'user_email'    => $user_data['user_email'],
				'device_token'  => $user_data['device_token'],
				);
			}	
			
			if ( $user_datas->is_banned == 1 ) {

				$this->error_response( get_msg( 'err_user_banned' ), 400);
			} else {

				if ( !$this->User->save($user_data,$user_id)) {
	        		$this->error_response( get_msg( 'err_user_register' ), 500);
	        	}

			
				$noti_token = array(
					"device_id" => $this->post( 'device_token' )
				);

				$noti_count = $this->Notitoken->count_all_by($noti_token);

				if ($noti_count == 1) {
					if ( $this->Notitoken->exists( $noti_token )) {
						$noti_id = $this->Notitoken->get_one_by($noti_token);
						$push_noti_token_id = $noti_id->id;
						$noti_data = array(

							"user_id" => $user_id,
							"platform_name" => $this->post('platform_name')
							
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					} else {
						$noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
					}
				}else{
					$this->Notitoken->delete_by($noti_token);
						$noti_data = array(
							"user_id" => $user_id,
							"device_id" => $this->post( 'device_token' ),
							"platform_name" => $this->post('platform_name')
						);
						$this->Notitoken->save( $noti_data, $push_noti_token_id );
				} 
			}

        	$this->custom_response($this->User->get_one($user_datas->user_id));

        }
	}

	/**
	 * Users Logout
	 */
	function logout_post()
	{
		// validation rules for user register
		$rules = array(
			
	        array(
	        	'field' => 'user_id',
	        	'rules' => 'required|callback_id_check[User]'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
       
       $conds['user_id'] = $this->post('user_id');
       $this->Notitoken->delete_by($conds);

       $this->success_response( get_msg( 'success_logout' ));

	}

	/**
	 * Delivery Boy Location Update
	 */
	function deliboy_location_update_post(){
		$rules = array(
	        array(
	        	'field' => 'user_id',
	        	'rules' => 'required|callback_id_check[User]'
			),
			array(
	        	'field' => 'lat',
	        	'rules' => 'required'
	        ),
			array(
	        	'field' => 'lng',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
		if ( !$this->is_valid( $rules )) exit;

		$lat = $this->post('lat');
		$lng = $this->post('lng');
        $location = location_check($lat,$lng);

		$user_id = $this->post('user_id');
		$data['user_lat'] = $lat;
		$data['user_lng'] = $lng;

		if($this->User->get_one($user_id)->role_id == 5){
			if( !$this->User->save($data, $user_id) ) {
				// rollback the user
				$this->db->trans_rollback();
				$this->error_response( get_msg( 'err_model' ), 500);
			}else{
				$this->success_response( get_msg( 'success_location_update' ), 200);
			}
		}else{
			$this->error_response( get_msg( 'not_deliboy' ), 503);
		}
		
	}

	
    /**
	* Trigger to delete user related data when user is deleted
	* delete user related data
	*/
	function user_delete_post( ) {

		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'user_id',
	        	'rules' => 'required'
	        )
	    );   
	    
	    // exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $id = $this->post('user_id');

        $conds['user_id'] = $id;

        // check user id

        $user_data = $this->User->get_one_by($conds);

        //print_r($user_data);die;


        if ( $user_data->user_id == "" ) {

        	$this->error_response( get_msg( 'invalid_user_id' ), 400);

        }elseif ( $user_data->role_id != 4) {
        	$this->error_response( get_msg( 'not_user' ), 400);
        } else {
        	$conds_user['user_id'] = $id;
			$conds_added_user['added_user_id'] = $id;

			//delete User
			if ( !$this->User->delete_by( $conds_user )) {

				return false;
			}

			// delete Code
			if ( !$this->Code->delete_by( $conds_added_user )) {

				return false;
			}

			// delete ResetCode
			if ( !$this->ResetCode->delete_by( $conds_user )) {

				return false;
			}

			// delete Commentheader
			if ( !$this->Commentheader->delete_by( $conds_user )) {

				return false;
			}

			// delete Commentdetail
			if ( !$this->Commentdetail->delete_by( $conds_user )) {

				return false;
			}

			// delete Favourite
			if ( !$this->Favourite->delete_by( $conds_user )) {

				return false;
			}

			// delete Like
			if ( !$this->Like->delete_by( $conds_user )) {

				return false;
			}

			// delete Transaction_payment
			if ( !$this->Transaction_payment->delete_by( $conds_added_user )) {

				return false;
			}

			// delete noti token
			if ( !$this->Notitoken->delete_by( $conds_user )) {

				return false;
			}

			// delete noti user
			if ( !$this->Notireaduser->delete_by( $conds_user )) {

				return false;
			}

			// delete Touch
			if ( !$this->Touch->delete_by( $conds_user )) {

				return false;
			}

			// update user id at Transactionheader
			$trans_header_data = $this->Transactionheader->get_all_by($conds_user)->result();
			//print_r($trans_header_data);die;
			for ($i=0; $i <count($trans_header_data) ; $i++) { 
				$trans_header_update['user_id'] = "-1";
				$trans_header_id = $trans_header_data[$i]->id;
				$this->Transactionheader->save($trans_header_update,$trans_header_id);
			}

			// update user id at Transactiondetail
			$trans_detail_data = $this->Transactiondetail->get_all_by($conds_added_user)->result();
			//print_r($trans_detail_data);die;

			for ($i=0; $i <count($trans_detail_data) ; $i++) { 
				$trans_detail_update['added_user_id'] = "-1";
				$trans_detail_id = $trans_detail_data[$i]->id;
				$this->Transactiondetail->save($trans_detail_update,$trans_detail_id);
			}

			// update user id at Transactioncount
			$trans_count_data = $this->Transactioncount->get_all_by($conds_user)->result();

			for ($i=0; $i <count($trans_count_data) ; $i++) { 
				$trans_count_update['user_id'] = "-1";
				$trans_count_id = $trans_count_data[$i]->id;
				$this->Transactioncount->save($trans_count_update,$trans_count_id);
			}
	
			$this->success_response( get_msg( 'success_user_delete' ), 200);
       }
	}
	
	/**
	* Trigger to delete delivery boy related data when user is deleted
	* delete deliboy related data
	*/
	function deliboy_delete_post( ) {

		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'user_id',
	        	'rules' => 'required'
	        )
	    );   
	    
	    // exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $id = $this->post('user_id');

        $conds['user_id'] = $id;

        // check user id

        $user_data = $this->User->get_one_by($conds);

        //print_r($user_data);die;


        if ( $user_data->user_id == "" ) {

        	$this->error_response( get_msg( 'invalid_user_id' ), 400);

        }elseif ( $user_data->role_id != 5) {
        	$this->error_response( get_msg( 'not_deli_boy' ), 400);
        } else {
        	$conds_user['user_id'] = $id;
			$conds_to_user['to_user_id'] = $id;
			$conds_deliboy['delivery_boy_id'] = $id;

			//delete User
			if ( !$this->User->delete_by( $conds_user )) {

				return false;
			}

			// delete ResetCode
			if ( !$this->ResetCode->delete_by( $conds_user )) {

				return false;
			}

			// delete deliboy rating
			if ( !$this->Deliboy_Rate->delete_by( $conds_to_user )) {

				return false;
			}

			// delete new order assign
			if ( !$this->New_order_assign->delete_by( $conds_deliboy )) {

				return false;
			}

			// delete noti token
			if ( !$this->Notitoken->delete_by( $conds_user )) {

				return false;
			}

			// delete noti user
			if ( !$this->Notireaduser->delete_by( $conds_user )) {

				return false;
			}

			// update delivery boy id at order route
			$trans_data = $this->Transactionheader->get_all_by($conds_deliboy)->result();
			//print_r($trans_data);die;
			for ($i=0; $i <count($trans_data) ; $i++) { 
				$trans_header_update_deli['delivery_boy_id'] = "-1";
				$trans_header_id = $trans_data[$i]->id;
				$this->Transactionheader->save($trans_header_update_deli,$trans_header_id);
			}

            $this->success_response( get_msg( 'success_deliboy_delete' ), 200);		
        }
	}

}