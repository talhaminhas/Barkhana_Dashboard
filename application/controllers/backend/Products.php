<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Products Controller
 */
class Products extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'PRODUCTS' );
		///start allow module check
		$conds_mod['module_name'] = $this->router->fetch_class();
		$module_id = $this->Module->get_one_by($conds_mod)->module_id;
		$this->load->library('uploader');
		$this->load->library('csvimport');
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
		
		// no publish filter
		$conds['no_publish_filter'] = 1;

		// get rows count
		$this->data['rows_count'] = $this->Product->count_all_by( $conds );

		// get categories
		$this->data['products'] = $this->Product->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}
	function upload() {
		
		 
		if ( $this->is_POST()) {


		$file = $_FILES['file']['name'];
		
		$ext = substr(strrchr($file, '.'), 1);
		//print_r($ext); die;
		



        if(strtolower($ext) == "csv") {

        	 	$upload_data = $this->uploader->upload($_FILES);
				
				if (!isset($upload_data['error'])) {
					foreach ($upload_data as $upload) {
						
						$file_data = $this->upload->data();
		            	$file_path =  './uploads/'.$file_data['file_name'];
		            	if ($this->csvimport->get_array($file_path)) {

							//get data from imported csv file
			                $csv_array = $this->csvimport->get_array($file_path);

			                $i = 0; $s = 0; $f=0;
			                $fail_records = "";
			                foreach ($csv_array as $row) {
   

			                     if($row['subcat_name'] && 
								 $row['photo_name'] && 
								 $row['prd_name'] &&
								 $row['price'] 
								 ) {

									//Get Sub Category Id 
									$conds_subcat['name'] = trim($row['subcat_name']);
									$subcat_name =  trim($row['subcat_name']);


									// print_r($cat_name); die;
									//Get Image Info 

									$data_img = getimagesize(base_url() . "uploads/" . $row['photo_name']);

									//$data_icon = getimagesize(base_url() . "uploads/" . $row['icon_name']);

									
									if( $data_img !== false) {

										$data = getimagesize(base_url() . "uploads/" . $row['photo_name']);
										$img_width_cover = $data[0];
										$img_height_cover = $data[1];

									} 
									

									if( $data_img !== false ) {
										
										
										//Wallpaper must have subcategory
										if($subcat_name != "") {

											$subcat = $this->Subcategory->get_one_by($conds_subcat);
											$subcat_id = $subcat->id;
											$cat_id = $subcat->cat_id;
											
											if($subcat_id != "")
											{
												//print_r($row['ingredient']); die();
												$data = array(
													
													'name'     		=>	trim($row['prd_name']),
													'status'   		=>  trim($row['status']),
													'cat_id'       	=> 	$cat_id,
													'sub_cat_id'	=>	$subcat_id,
													'original_price'=>	trim($row['price']),
													'unit_price'	=>	trim($row['price']),
													'minimum_order'	=>	'1',
													'highlight_information'	=>	trim($row['highlight_info']),
													'is_featured'	=>	trim($row['is_featured']),
													'is_available'	=>	trim($row['is_available']),
													'status'		=>	trim($row['status']),
													'ingredient'	=>	trim($row['ingredients']),
													'maximum_order'	=>	'100',
													'nutrient'		=>	trim($row['prd_nutrients']),
												);

													$id = 0;
													
													if($this->Product->save($data, $id)) {

														
														$id = ( !$id )? $data['id']: $id ;
														$extrasArray = explode(',', trim($row['extras']));

														foreach ($extrasArray as $extra_name) {
															$conds_extra['name'] = trim($extra_name);
															$extra = $this->Additional->get_one_by($conds_extra);
															//print_r($extra); die;
															$extra_data = array(
													
																'product_id'     		=>	$id,
																'add_on_id'   		=>  $extra->id,
															);
															if($extra->id != '')
															{$this->Food_additional->save($extra_data);}
														}
																									
														$image = array(

															'img_parent_id' => $id,
															'img_type' 		=> "product",
															'img_desc' 		=> "",
															'img_path' 		=> trim($row['photo_name']),
															'img_width'     => $img_width_cover,
															'img_height'    => $img_height_cover
														);

														//cover photo path
														$path = "uploads/" . $row['photo_name'];
														$this->ps_image->create_thumbnail($path);
														
														if($this->Image->save($image)) {
														//both success

														$s++;	

														}

													} else {
														$f++;
														$fail_records .= " - " . $row['prd_name'] . ", " . get_msg('because_db_err') . "<br>";
													}

											}else{
												//subcategory name invalid
												$f++;
												$fail_records .= " - " . $row['prd_name'] .", Invalid Subcategory name. <br>";
											}
										} else {
											//sub Category Missing
											$f++;
											$fail_records .= " - " . $row['prd_name'] .", because of missing sub category name.<br>";
										}	



									} else {
										//image at uploads missing 
										$f++;
				                			$fail_records .= " - " . $row['prd_name'] . ", because of missing paroduct image.<br>";
									}

			                	} else {
			                		$f++;
			                		$fail_records .= " - " . $row['prd_name'] . ", because of incompelete data.<br>";
			                	}



			                	$i++;

			                }

			                $result_str = get_msg("total_subcat").": "  .$i. "<br>";
			                $result_str .= get_msg("success_subcat") .": ". $s . "<br>";
			                $result_str .= get_msg("fail_subcat") .": ". $f .  "<br>" . $fail_records;
			                
			                // print_r($result_str); die;

			                $this->data['message'] = $result_str;
			                $this->set_flash_msg( 'success', $result_str);

			            	redirect( $this->module_site_url());
							

			                //$this->session->set_flashdata('success', $result_str);

			                //$content['content'] = $this->load->view('items/import_items',$data,true);		
							//$this->load_template($content, false);

			            } else {

			            	//echo "Something wrong in your uploaded data.";
			                
			            	$this->set_flash_msg( 'error', get_msg( 'something_wrong_upload' ));

			            	redirect( $this->module_site_url());

			    //             $data['error'] = "Something wrong in your uploaded data.";
			    //             $this->session->set_flashdata('error', $data['error']);
			    //              $content['content'] = $this->load->view('items/import_items',$data,true);		
							// $this->load_template($content, false);
			            }


					}
				} else {
					// $data['error'] = $upload_data['error'];

					// $this->session->set_flashdata('error', $data['error']);
		   //          $content['content'] = $this->load->view('items/import_items',$data,true);		
					// $this->load_template($content, false);

					//print_r($upload_data['error']);

					$this->set_flash_msg( 'error', $upload_data['error']);

					redirect( $this->module_site_url());
				}

        } else {

        	//print_r('Please upload CSV file only.');

        	$this->set_flash_msg( 'error',  get_msg( 'pls_upload_csv' ));

			redirect( $this->module_site_url());

   //          $this->session->set_flashdata('error', 'Please upload CSV file only.');

   //          $content['content'] = $this->load->view('items/import_items',$data,true);		
			// $this->load_template($content, false);

        }



		} else {
			redirect( $this->module_site_url());
		}
		$this->set_flash_msg( 'error', 'as' );

	}
	/**
	 * Searches for the first match.
	 */
	function search() {
		

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'prd_search' );
		
		// condition with search term
		if($this->input->post('submit') != NULL ){

			$conds = array( 'searchterm' => $this->searchterm_handler( $this->input->post( 'searchterm' )));

			if($this->input->post('searchterm') != "") {
				$conds['searchterm'] = $this->input->post('searchterm');
				$this->data['searchterm'] = $this->input->post('searchterm');
				$this->session->set_userdata(array("searchterm" => $this->input->post('searchterm')));
			} else {
				
				$this->session->set_userdata(array("searchterm" => NULL));
			}
			
			if($this->input->post('is_available') == "is_available") {
				$conds['is_available'] = '1';
				$this->data['is_available'] = '1';
				$this->session->set_userdata(array("is_available" => '1'));
			} else {
				
				$this->session->set_userdata(array("is_available" => NULL));
			}

			if($this->input->post('is_featured') == "is_featured") {
				$conds['is_featured'] = '1';
				$this->data['is_featured'] = '1';
				$this->session->set_userdata(array("is_featured" => '1'));
			} else {
				
				$this->session->set_userdata(array("is_featured" => NULL));
			}

			if($this->input->post('is_discount') == "is_discount") {
				$conds['is_discount'] = '1';
				$this->data['is_discount'] = '1';
				$this->session->set_userdata(array("is_discount" => '1'));
			} else {
				
				$this->session->set_userdata(array("is_discount" => NULL));
			}


			if($this->input->post('cat_id') != ""  || $this->input->post('cat_id') != '0') {
				$conds['cat_id'] = $this->input->post('cat_id');
				$this->data['cat_id'] = $this->input->post('cat_id');
				$this->data['selected_cat_id'] = $this->input->post('cat_id');
				$this->session->set_userdata(array("cat_id" => $this->input->post('cat_id')));
				$this->session->set_userdata(array("selected_cat_id" => $this->input->post('cat_id')));
			} else {
				$this->session->set_userdata(array("cat_id" => NULL ));
			}

			if($this->input->post('sub_cat_id') != ""  || $this->input->post('sub_cat_id') != '0') {
				$conds['sub_cat_id'] = $this->input->post('sub_cat_id');
				$this->data['sub_cat_id'] = $this->input->post('sub_cat_id');
				$this->session->set_userdata(array("sub_cat_id" => $this->input->post('sub_cat_id')));
			} else {
				$this->session->set_userdata(array("sub_cat_id" => NULL ));
			}

			if($this->input->post('price_min') != "") {
				$conds['price_min'] = $this->input->post('price_min');
				$this->data['price_min'] = $this->input->post('price_min');
				$this->session->set_userdata(array("price_min" => $this->input->post('price_min')));
			} else {
				$this->session->set_userdata(array("price_min" => NULL ));
			}

			if($this->input->post('price_max') != "") {
				$conds['price_max'] = $this->input->post('price_max');
				$this->data['price_max'] = $this->input->post('price_max');
				$this->session->set_userdata(array("price_max" => $this->input->post('price_max')));
			} else {
				$this->session->set_userdata(array("price_max" => NULL ));
			}

			//Order By 

			$conds['no_publish_filter'] = '1';
			$conds['order_by'] = '1';

			if($this->input->post('order_by') == "name_asc") {
				
				$conds['order_by_field'] = "name";
				$conds['order_by_type'] = "asc";

				$this->data['order_by'] = $this->input->post('order_by');
				$this->session->set_userdata(array("order_by" => $this->input->post('order_by')));
			
			}  

			if($this->input->post('order_by') == "name_desc") {
				
				$conds['order_by_field'] = "name";
				$conds['order_by_type'] = "desc";

				$this->data['order_by'] = $this->input->post('order_by');
				$this->session->set_userdata(array("order_by" => $this->input->post('order_by')));

			
			} 

			if($this->input->post('order_by') == "price_asc") {
				
				$conds['order_by_field'] = "unit_price";
				$conds['order_by_type'] = "asc";

				$this->data['order_by'] = $this->input->post('order_by');
				$this->session->set_userdata(array("order_by" => $this->input->post('order_by')));
			
			} 

			if($this->input->post('order_by') == "price_desc") {
				
				$conds['order_by_field'] = "unit_price";
				$conds['order_by_type'] = "desc";

				$this->data['order_by'] = $this->input->post('order_by');
				$this->session->set_userdata(array("order_by" => $this->input->post('order_by')));
			
			}
			


		} else {
			//read from session value
			if($this->session->userdata('searchterm') != NULL){
				$conds['searchterm'] = $this->session->userdata('searchterm');
				$this->data['searchterm'] = $this->session->userdata('searchterm');
			}
			
			if($this->session->userdata('is_available') != NULL){
				$conds['is_available'] = $this->session->userdata('is_available');
				$this->data['is_available'] = $this->session->userdata('is_available');
			}

			if($this->session->userdata('is_featured') != NULL){
				$conds['is_featured'] = $this->session->userdata('is_featured');
				$this->data['is_featured'] = $this->session->userdata('is_featured');
			}

			if($this->session->userdata('is_discount') != NULL){
				$conds['is_discount'] = $this->session->userdata('is_discount');
				$this->data['is_discount'] = $this->session->userdata('is_discount');
			}

			if($this->session->userdata('cat_id') != NULL){
				$conds['cat_id'] = $this->session->userdata('cat_id');
				$this->data['cat_id'] = $this->session->userdata('cat_id');
				$this->data['selected_cat_id'] = $this->session->userdata('cat_id');
			}

			if($this->session->userdata('sub_cat_id') != NULL){
				$conds['sub_cat_id'] = $this->session->userdata('sub_cat_id');
				$this->data['sub_cat_id'] = $this->session->userdata('sub_cat_id');
				$this->data['selected_cat_id'] = $this->session->userdata('cat_id');
			}


			if($this->session->userdata('price_min') != NULL){
				$conds['price_min'] = $this->session->userdata('price_min');
				$this->data['price_min'] = $this->session->userdata('price_min');
			}			

			if($this->session->userdata('price_max') != NULL){
				$conds['price_max'] = $this->session->userdata('price_max');
				$this->data['price_max'] = $this->session->userdata('price_max');
			}


			//Order By
				$conds['no_publish_filter'] = 1;
				$conds['order_by'] = 1;

				if($this->session->userdata('order_by') != NULL){

					if($this->session->userdata('order_by') == "name_asc") {

						$conds['order_by_field'] = "name";
						$conds['order_by_type'] = "asc";

						$this->data['order_by'] = $this->session->userdata('order_by');

					}  

					if($this->session->userdata('order_by') == "name_desc") {
					
						$conds['order_by_field'] = "name";
						$conds['order_by_type'] = "desc";

						$this->data['order_by'] = $this->session->userdata('order_by');
					
					} 


					if($this->session->userdata('order_by')  == "price_asc") {
					
						$conds['order_by_field'] = "unit_price";
						$conds['order_by_type'] = "asc";

						$this->data['order_by'] = $this->session->userdata('order_by');
					
					} 

					if($this->session->userdata('order_by') == "price_desc") {

						$conds['order_by_field'] = "unit_price";
						$conds['order_by_type'] = "desc";

						$this->data['order_by'] = $this->session->userdata('order_by');
					
					}

				}  


		}

		if ($conds['order_by_field'] == "" ){
			$conds['order_by_field'] = "added_date";
			$conds['order_by_type'] = "desc";
		}


		// pagination
		$this->data['rows_count'] = $this->Product->count_all_by( $conds );

		// search data
		$this->data['products'] = $this->Product->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

		//$this->data['selected_cat_id'] = $this->input->post( 'cat_id' );

		// load add list
		parent::search();
	}

	/**
	 * Create new one
	 */
	function add() {

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'prd_add' );

		// call the core add logic
		parent::add();
	}

	
	/**
	 * Saving Logic
	 * 1) upload image
	 * 2) save category
	 * 3) save image
	 * 4) check transaction status
	 *
	 * @param      boolean  $id  The user identifier
	 */
	function save( $id = false ) {
		if((!isset($id ))|| (isset($id))) {
			// color count
			if($id) {
				$color_counter_total = $this->get_data( 'color_total' );
				if ($color_counter_total == "" || $color_counter_total== 0) {
					$color_counter_total = $this->get_data( 'color_total_existing' );
				}
				$edit_prd_id = $id;
			} else {
				$color_counter_total = $this->get_data( 'color_total' );
			}
			// specification count
			if($id) {
				$spec_counter_total = $this->get_data( 'spec_total' );
				if ($spec_counter_total == "" || $spec_counter_total== 0) {
					$spec_counter_total = $this->get_data( 'spec_total_existing' );
				}
				$edit_spec_id = $id;
			} else {
				$spec_counter_total = $this->get_data( 'spec_total' );
			}
			// start the transaction
			$this->db->trans_start();
			$logged_in_user = $this->ps_auth->get_user_info();
			
			/** 
			 * Insert Product Records 
			 */
			$data = array();

			// Product id
		   if ( $this->has_data( 'id' )) {
				$data['id'] = $this->get_data( 'id' );

			}

		   // Category id
		   if ( $this->has_data( 'cat_id' )) {
				$data['cat_id'] = $this->get_data( 'cat_id' );
			}

			// Sub Category id
		   if ( $this->has_data( 'sub_cat_id' )) {
				$data['sub_cat_id'] = $this->get_data( 'sub_cat_id' );
			}

			// Product Unit
		   if ( $this->has_data( 'product_unit' )) {
				$data['product_unit'] = $this->get_data( 'product_unit' );
			}

			// Discount Type id
		   	if ( $this->has_data( 'discount_type_id' )) {
				$data['discount_type_id'] = $this->get_data( 'discount_type_id' );
			}

			// prepare product name
			if ( $this->has_data( 'name' )) {
				$data['name'] = $this->get_data( 'name' );
			}

			// prepare product description
			if ( $this->has_data( 'description' )) {
				$data['description'] = $this->get_data( 'description' );
			}
			
			// prepare product original price
			if ( $this->has_data( 'original_price' )) {
				$data['original_price'] = $this->get_data( 'original_price' );
			}

			// Ordering
		   if ( $this->has_data( 'ordering' )) {
				$data['ordering'] = $this->get_data( 'ordering' );
			}

			// Ingredient
		   if ( $this->has_data( 'ingredient' )) {
				$data['ingredient'] = $this->get_data( 'ingredient' );
			}

			// Nutrient
		   if ( $this->has_data( 'nutrient' )) {
				$data['nutrient'] = $this->get_data( 'nutrient' );
			}

			//prepare product original price
			if ( $this->has_data( 'original_price' )) {
				$data['unit_price'] = $this->get_data( 'original_price' );
			}

			// prepare product unit price
			if ( $this->has_data( 'unit_price' )) {
				$data['unit_price'] = $this->get_data( 'unit_price' );
			}

			// prepare product minimum order
			if ( $this->has_data( 'minimum_order' )) {
				$data['minimum_order'] = $this->get_data( 'minimum_order' );
			}

			// prepare product maximum order
			if ( $this->has_data( 'maximum_order' )) {
				$data['maximum_order'] = $this->get_data( 'maximum_order' );
			}

			// prepare product search tag
			if ( $this->has_data( 'search_tag' )) {
				$data['search_tag'] = $this->get_data( 'search_tag' );
			}

			// prepare product highlight information
			if ( $this->has_data( 'highlight_information' )) {
				$data['highlight_information'] = $this->get_data( 'highlight_information' );
			}

			// prepare product product code
			if ( $this->has_data( 'code' )) {
				$data['code'] = $this->get_data( 'code' );
			}

			// if 'is featured' is checked,
			if ( $this->has_data( 'is_featured' )) {
				
				$data['is_featured'] = 1;
				if ($data['is_featured'] == 1) {

					if($this->get_data( 'is_featured_stage' ) == $this->has_data( 'is_featured' )) {
						$data['updated_date'] = date("Y-m-d H:i:s");
					} else {
						$data['featured_date'] = date("Y-m-d H:i:s");
						
					}
				}
			} else {
				
				$data['is_featured'] = 0;
			}
			

			// if 'is available' is checked,
			if ( $this->has_data( 'is_available' )) {
				$data['is_available'] = 1;
			} else {
				$data['is_available'] = 0;
			}

			// if 'status' is checked,
			if ( $this->has_data( 'status' )) {
				$data['status'] = 1;
			} else {
				$data['status'] = 0;
			}
			
			// set timezone
			$data['added_user_id'] = $logged_in_user->user_id;

			if($id == "") {
				//save
				$data['added_date'] = date("Y-m-d H:i:s");
			} else {
				//edit
				unset($data['added_date']);
				$data['updated_date'] = date("Y-m-d H:i:s");
				$data['updated_user_id'] = $logged_in_user->user_id;
			}

			//print_r($data);die;

			//save category
			if ( ! $this->Product->save( $data, $id )) {
			// if there is an error in inserting user data,	

				// rollback the transaction
				$this->db->trans_rollback();

				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return;
			}

			 /** 
			* Upload Image Records 
			*/
		
			if ( !$id ) {
			// if id is false, this is adding new record

				if ( ! $this->insert_images( $_FILES, 'product', $data['id'] )) {
				// if error in saving image

				}
			}

			//get inserted product id
			$id = ( !$id )? $data['id']: $id ;
			
			// prepare shop tag multiple select
			if ( $id ) {
				///start modify by MN
				if($this->get_data( 'addonselect' ) != "") {
					$data['prdselect'] = explode(",", $this->get_data( 'addonselect' ));
				} else {
					$data['prdselect'] = explode(",", $this->get_data( 'existing_addonselect' ));
				}

				if(!$this->ps_delete->delete_food_addon( $id )) {
					//loop
					for($i=0; $i<count($data['prdselect']);$i++) {
						if($data['prdselect'][$i] != "") {
							$select_data['add_on_id'] = $data['prdselect'][$i];
							$select_data['product_id'] = $id;
							$select_data['added_date'] = date("Y-m-d H:i:s");
							$select_data['added_user_id'] = $logged_in_user->user_id;

							$this->Food_additional->save($select_data);
						}
					}
				}
				///end

			}
			// prepare specification 
			if($spec_counter_total == false) {
				$spec_counter_total = 1;
				$spec_title = $this->get_data('prd_spec_title1');
				$spec_desc = $this->get_data('prd_spec_desc1');

				sleep(1);

				if($spec_title != "" || $spec_desc != "") {
						$spec_data['product_id'] = $id;
						$spec_data['name'] = $spec_title;
						$spec_data['description'] = $spec_desc;
						$spec_data['added_date'] = date("Y-m-d H:i:s");
						$spec_data['added_user_id'] = $logged_in_user->user_id;

						$this->Specification->save($spec_data);
				}
			} else {

				$this->ps_delete->delete_spec( $id );
				$spec_counter_total = $spec_counter_total;
				for($j=1; $j<=$spec_counter_total; $j++) {
					$spec_title = $this->get_data('prd_spec_title' . $j);
					$spec_desc = $this->get_data('prd_spec_desc' . $j);

					sleep(1);
					
					if( $spec_title != "" || $spec_desc != "" ) {
						$spec_data['product_id'] = $id;
						$spec_data['name'] = $spec_title;
						$spec_data['description'] = $spec_desc;
						$spec_data['added_date'] = date("Y-m-d H:i:s");
						$spec_data['added_user_id'] = $logged_in_user->user_id;

						$this->Specification->save($spec_data);
					}
				}
			}
			
			/** 
			 * Check Transactions 
			 */

			// commit the transaction
			if ( ! $this->check_trans()) {
	        	
				// set flash error message
				$this->set_flash_msg( 'error', get_msg( 'err_model' ));
			} else {

				if ( $id ) {
				// if user id is not false, show success_add message
					
					$this->set_flash_msg( 'success', get_msg( 'success_prd_edit' ));
				} else {
				// if user id is false, show success_edit message

					$this->set_flash_msg( 'success', get_msg( 'success_prd_add' ));
				}
			}
		}
		//get inserted product id
		$id = ( !$id )? $data['id']: $id ;

		///start deep link update item tb by MN
		$description = $data['description'];
		$name = $data['name'];
		$conds_img = array( 'img_type' => 'item', 'img_parent_id' => $id );
        $images = $this->Image->get_all_by( $conds_img )->result();
		$img = $this->ps_image->upload_url . $images[0]->img_path;
		$deep_link = deep_linking_shorten_url($description,$name,$img,$id);
		$item_data = array(
			'dynamic_link' => $deep_link
		);
        // print_r($item_data);die;
		$this->Product->save($item_data,$id);
		///End


		// Product Id Checking 
		if ( $this->has_data( 'gallery' )) {
		// if there is gallery, redirecti to gallery
			redirect( $this->module_site_url( 'gallery/' .$id ));
		}else if ( $this->has_data( 'attribute' )) {
			redirect( site_url( ) . '/admin/attributes/add/'.$id);
		}
		else {
		// redirect to list view
			redirect( $this->module_site_url() );
		}
	}

	function get_all_sub_categories( $cat_id )
    {
    	$conds['cat_id'] = $cat_id;
    	
    	$sub_categories = $this->Subcategory->get_all_by($conds);
		echo json_encode($sub_categories->result());
    }

    /**
	 * Show Gallery
	 *
	 * @param      <type>  $id     The identifier
	 */
	function gallery( $id ) {
		// breadcrumb urls
		$edit_product = get_msg('prd_edit');

		$this->data['action_title'] = array( 
			array( 'url' => 'edit/'. $id, 'label' => $edit_product ), 
			array( 'label' => get_msg( 'product_gallery' ))
		);
		
		$_SESSION['parent_id'] = $id;
		$_SESSION['type'] = 'product';
    	    	
    	$this->load_gallery();
    }


	/**
 	* Update the existing one
	*/
	function edit( $id ) 
	{

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'prd_edit' );

		// load user
		$this->data['product'] = $this->Product->get_one( $id );

		$conds['id'] = $id;

		$discount = $this->Product->get_one_by($conds);


		$this->data['is_discount'] = $discount->is_discount;

		$dis_conds['product_id'] = $id;

		$temp_dis = $this->ProductDiscount->get_one_by( $dis_conds );

		$discount_id = $temp_dis->discount_id;

		$conds_percent['id'] = $discount_id;

		$this->data['discount'] = $this->Discount->get_one_by( $conds_percent );

		// call the parent edit logic
		parent::edit( $id );

	}

	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input( $id = 0 ) 
	{
		
		$rule = 'required|callback_is_valid_name['. $id  .']';

		$this->form_validation->set_rules( 'name', get_msg( 'name' ), $rule);
		
		if ( $this->form_validation->run() == FALSE ) {
		// if there is an error in validating,

			return false;
		}

		return true;
	}

	/**
	 * Determines if valid name.
	 *
	 * @param      <type>   $name  The  name
	 * @param      integer  $id     The  identifier
	 *
	 * @return     boolean  True if valid name, False otherwise.
	 */
	function is_valid_name( $name, $id = 0 )
	{		
		 $conds['name'] = $name;

			if ( strtolower( $this->Product->get_one( $id )->name ) == rtrim(strtolower( $name ))) {
			// if the name is existing name for that user id,
				return true;
			} else if ( $this->Product->exists( ($conds ))) {
			// if the name is existed in the system,
				$this->form_validation->set_message('is_valid_name', get_msg( 'err_dup_name' ));
				return false;
			}
			return true;
	}


	/**
	 * Delete the record
	 * 1) delete product
	 * 2) delete image from folder and table
	 * 3) check transactions
	 */
	function delete( $id ) 
	{

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );

		// delete categories and images
		$enable_trigger = true; 
		
		// delete categories and images
		//if ( !$this->ps_delete->delete_product( $id, $enable_trigger )) {
		$type = "product";

		if ( !$this->ps_delete->delete_history( $id, $type, $enable_trigger )) {

			// set error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));

			// rollback
			$this->trans_rollback();

			// redirect to list view
			redirect( $this->module_site_url());
		}
		/**
		 * Check Transcation Status
		 */
		if ( !$this->check_trans()) {

			$this->set_flash_msg( 'error', get_msg( 'err_model' ));	
		} else {
        	
			$this->set_flash_msg( 'success', get_msg( 'success_prd_delete' ));
		}
		
		redirect( $this->module_site_url());
	}


	/**
	 * Check product name via ajax
	 *
	 * @param      boolean  $product_id  The cat identifier
	 */
	function ajx_exists( $id = false )
	{
		
		// get product name
		$name = $_REQUEST['name'];
		if ( $this->is_valid_name( $name, $id )) {
		// if the product name is valid,
			
			echo "true";
		} else {
		// if invalid product name,
			
			echo "false";
		}
	}

	/**
	 * Publish the record
	 *
	 * @param      integer  $prd_id  The product identifier
	 */
	function ajx_publish( $product_id = 0 )
	{
		// check access
		$this->check_access( PUBLISH );
		
		// prepare data
		$prd_data = array( 'status'=> 1 );
			
		// save data
		if ( $this->Product->save( $prd_data, $product_id )) {
			//Need to delete at history table because that wallpaper need to show again on app
			//$data_delete['product_id'] = $product_id;
			//$this->Product_delete->delete_by($data_delete);
			echo 'true';
		} else {
			echo 'false';
		}
	}
	
	/**
	 * Unpublish the records
	 *
	 * @param      integer  $prd_id  The category identifier
	 */
	function ajx_unpublish( $product_id = 0 )
	{
		// check access
		$this->check_access( PUBLISH );
		
		// prepare data
		$prd_data = array( 'status'=> 0 );
			
		// save data
		if ( $this->Product->save( $prd_data, $product_id )) {

			//Need to save at history table because that wallpaper no need to show on app
			//$data_delete['product_id'] = $product_id;
			//$this->Product_delete->save($data_delete);
			echo 'true';
		} else {
			echo 'false';
		}
	}


	/**
	 * To get all products for collection
	 *
	 */
	function get_all_products_for_collection($collection_id = '000') 
	{

		//Datatables Variables
		if ($collection_id=='000') {
 			// Datatables Variables
		    $draw = intval($this->input->get("draw"));
	        $start = intval($this->input->get("start"));
	        $length = intval($this->input->get("length"));

			//get all products
	        $products = $this->Product->get_all_by($conds);

	        $data = array();

	        foreach($products->result() as $r) {

	        	$unit_price = $r->unit_price;
	        	$unit_price = round($unit_price, 2) ;

	            $data[] = array(
	                $r->id,
	                $r->name,
	                $r->code,
	                $unit_price .$this->Shop->get_one('shop0b69bc5dbd68bbd57ea13dfc5488e20a')->currency_symbol
	            );
	        }

	          $output = array(
	               "draw" => $draw,
	                 "recordsTotal" => $products->num_rows(),
	                 "recordsFiltered" => $products->num_rows(),
	                 "data" => $data
	             );
	        echo json_encode($output);
	        exit();
	 	} else {
 		// Datatables Variables
          $draw = intval($this->input->get("draw"));
          $start = intval($this->input->get("start"));
          $length = intval($this->input->get("length"));

		    $conds1['collection_id'] = $collection_id;
    
		  $product_collection = $this->Productcollection->get_all_by($conds1)->result();
		  if(isset($product_collection)) {
			  $result = "";
			  foreach ($product_collection as $prd_collect) {
			  	$result .= "'".$prd_collect->product_id ."'" .",";
			  
			  }
			  $prd_ids_from_coll = rtrim($result,",");

			  $conds['prd_ids_from_coll'] = $prd_ids_from_coll;
				
	          $prd_dis = $this->Product->get_all_product_collection($conds);

	          $products_data = array();
	          foreach($prd_dis->result() as $prd) {

	          	$unit_price = $prd->unit_price;
	        	$unit_price = round($unit_price, 2) ;
	          
	          	$products_data[] = array(
	                     $prd->id,
	                     $prd->name,
	                     $prd->code,
	                     $unit_price .$this->Shop->get_one('shop0b69bc5dbd68bbd57ea13dfc5488e20a')->currency_symbol
	                 );
	         }
	          

	          $output = array(
	                "draw" => $draw,
	              "recordsTotal" => $prd_dis->num_rows(),
	              "recordsFiltered" => $prd_dis->num_rows(),
	              "data" => $products_data

	            );
	          echo json_encode($output);
	          exit();
	      	} else {
	      		  $products = $this->Product->get_all_by($conds);

		        $data = array();

		        foreach($products->result() as $r) {

		        	$unit_price = $r->unit_price;
	        		$unit_price = round($unit_price, 2) ;

		            $data[] = array(
		                $r->id,
		                $r->name,
		                $r->code,
		                $unit_price .$this->Shop->get_one('shop0b69bc5dbd68bbd57ea13dfc5488e20a')->currency_symbol
		            );
		        }

		          $output = array(
		               "draw" => $draw,
		                 "recordsTotal" => $products->num_rows(),
		                 "recordsFiltered" => $products->num_rows(),
		                 "data" => $data
		             );
		        echo json_encode($output);
		        exit();
	      	}
 		} 

	}

function get_all_products_for_discount($discount_id='000') 
 {
 	if ($discount_id=='000') {
 			// Datatables Variables
	        $draw = intval($this->input->get("draw"));
	        $start = intval($this->input->get("start"));
	        $length = intval($this->input->get("length"));


           	//// Start - Filter products inside other discount ////
			$conds2['discount_id'] = $discount_id;
			$product_discount_from_others = $this->ProductDiscount->get_all_not_in_discount($conds2)->result();

			$result_others = "";
			  foreach ($product_discount_from_others as $prd_dis) {
			  	$result_others .= "'".$prd_dis->product_id ."'" .",";
			  
			  }

			$prd_ids_from_dis_other = rtrim($result_others,",");

			$conds['prd_ids_from_dis_other'] = $prd_ids_from_dis_other;

			//// End - Filter products inside other discount ///
			$products = $this->Product->get_all_product($conds);	

          $products_data = array();
          
          foreach($products->result() as $prd) {

          	$conds['discount_id'] = $discount_id;
          	$conds['product_id']  = $prd->id;

          	$unit_price = $prd->unit_price;
	        $unit_price = round($unit_price, 2) ;
          
         	$products_data[] = array(
                     $prd->id,
                     $prd->name,
                     $prd->code,
                     $unit_price .$this->Shop->get_one('shop0b69bc5dbd68bbd57ea13dfc5488e20a')->currency_symbol
                 );
         }
          

          $output = array(
                "draw" => $draw,
              	"recordsTotal" => $products->num_rows(),
              	"recordsFiltered" => $products->num_rows(),
             	 "data" => $products_data

            );
          echo json_encode($output);
          exit();
 	} else {
 		// Datatables Variables
          $draw = intval($this->input->get("draw"));
          $start = intval($this->input->get("start"));
          $length = intval($this->input->get("length"));

		    $conds1['discount_id'] = $discount_id;
     
		  	$product_discount = $this->ProductDiscount->get_all_in_discount($conds1)->result();
		 	$result = "";
				foreach ($product_discount as $prd_dis) {
				  	$result .= "'".$prd_dis->product_id ."'" .",";
				  
				}
				$prd_ids_from_dis = rtrim($result,",");

				$conds['prd_ids_from_dis'] = $prd_ids_from_dis;

				 //// Start - Filter products inside other discount ////
			  $conds2['discount_id'] = $discount_id;
			  $product_discount_from_others = $this->ProductDiscount->get_all_not_in_discount($conds2)->result();

			  $result_others = "";
			  foreach ($product_discount_from_others as $prd_dis) {
			  	$result_others .= "'".$prd_dis->product_id ."'" .",";
			  
			  }

			  $prd_ids_from_dis_other = rtrim($result_others,",");

			  $conds['prd_ids_from_dis_other'] = $prd_ids_from_dis_other;

			  //// End - Filter products inside other discount ///	

	          $prd_dis = $this->Product->get_all_product($conds);

	           $products_data = array();
	          foreach($prd_dis->result() as $prd) {

	          	$unit_price = $prd->unit_price;
	        	$unit_price = round($unit_price, 2) ;
	          
	          	$products_data[] = array(
	                     $prd->id,
	                     $prd->name,
	                     $prd->code,
	                     $unit_price .$this->Shop->get_one('1')->currency_symbol 
	                 );
	         }
	          

	          $output = array(
	              "draw" => $draw,
	              "recordsTotal" => $prd_dis->num_rows(),
	              "recordsFiltered" => $prd_dis->num_rows(),
	              "data" => $products_data

	            );
	          echo json_encode($output);
	          exit();
 		}
   
 	}
 }