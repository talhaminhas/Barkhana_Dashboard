<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PanaceaSoft Authentication
 */
class PS_Adapter {

	// codeigniter instance
	protected $CI;

	// login user
	protected $login_user_id;

	/**
	 * Constructor
	 */
	function __construct()
	{
		// get CI instance
		$this->CI =& get_instance();
	}

	/**
	 * Sets the login user.
	 */
	function set_login_user_id( $user_id )
	{
		$this->login_user_id = $user_id;
	}

	/**
	 * Sets the login user.
	 */
	function get_login_user_id()
	{
		return $this->login_user_id;
	}
	
	/**
	 * Gets the default photo.
	 *
	 * @param      <type>  $id     The identifier
	 * @param      <type>  $type   The type
	 */
	function get_default_photo( $id, $type )
	{
		$default_photo = "";

		// get all images
		$img = $this->CI->Image->get_all_by( array( 'img_parent_id' => $id, 'img_type' => $type ))->result();

		if ( count( $img ) > 0 ) {
		// if there are images for wallpaper,
			
			$default_photo = $img[0];
		} else {
		// if no image, return empty object

			$default_photo = $this->CI->Image->get_empty_object();
		}

		return $default_photo;
	}

	/**
	 * Gets the default photo.
	 *
	 * @param      <type>  $id     The identifier
	 * @param      <type>  $type   The type
	 */
	function get_default_photo_for_gallery( $id, $type )
	{
		$default_photo = "";
		$conds['img_parent_id'] = $id;
		$conds['img_type'] = $type;

		// get all images
		$img = $this->CI->Image->get_all_by($conds)->result();
		
		if ( count( $img ) == 1 ) {
			// if there are images for gallery,
			$default_photo = $img[0];
			
		} elseif ( count( $img ) > 1 ) {
			$conds['is_default'] = "1";
			$image = $this->CI->Image->get_all_by($conds)->result();
			// if there are images for gallery,
			if(count($image) != 0) {
				$default_photo = $image[0];
			} else {
				$default_photo = $img[0];
			}

		} else {
			// if no image, return empty object
			$default_photo = $this->CI->Image->get_empty_object();
		}

		return $default_photo;
	}


	/**
	 * Customize tag object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_category( &$obj )
	{
		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->id, 'category' );

		// set default icon 
		$obj->default_icon = $this->get_default_photo( $obj->id, 'category-icon' );
	}

	/**
	 * Customize tag object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_additional( &$obj )
	{
		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->id, 'food-additional' );

	}

	/**
	 * Customize tag object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_feed( &$obj )
	{
		// set default photo
		$obj->default_photo = $this->get_default_photo_for_gallery( $obj->id, 'feed' );

	}

	/**
	 * Customize tag object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_areas( &$obj )
	{
		$obj->currency_symbol = $this->CI->Shop->get_one( 'shop0b69bc5dbd68bbd57ea13dfc5488e20a' )->currency_symbol;

		$obj->currency_short_form = $this->CI->Shop->get_one( 'shop0b69bc5dbd68bbd57ea13dfc5488e20a' )->currency_short_form;

	}

	/**
	 * Customize sub category object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_sub_category( &$obj )
	{
		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->id, 'sub_category' );

		// set default icon 
		$obj->default_icon = $this->get_default_photo( $obj->id, 'subcat_icon' );
	}

	/**
	 * Customize reservation object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_reservation( &$obj )
	{
		// set status object
		if ( isset( $obj->status_id )) {
			$tmp_status = $this->CI->Reservation_status->get_one( $obj->status_id );

			$this->convert_user( $tmp_status );

			$obj->reservation_status = $tmp_status;
		}

		// set user object
		if ( isset( $obj->user_id )) {
			$tmp_user = $this->CI->User->get_one( $obj->user_id );

			$this->convert_user( $tmp_user );

			$obj->user = $tmp_user;
		}

	}

	function convert_product_shop_category_by_keyword (&$obj , $need_return = false) {
		
		foreach ($obj->categories as $category) {
			$categories = $this->convert_category( $category );
		}

		foreach ($obj->products as $product) {
			$products = $this->convert_product( $product );
		}
		
		foreach ($obj->subcategories as $subcategory) {
			$subcategories = $this->convert_sub_category( $subcategory );
		}

		if($need_return)
		{
			return $obj;
		} 
	}

	/**
	 * Customize product object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_product( &$obj , $need_return = false)
	{
		
		//Transaction Status 
		if(isset($obj->trans_status)) {

			if($obj->trans_status != "") {
				$obj->trans_status = $obj->trans_status;
			} else {
				$obj->trans_status = "";
			}

		} else {
			$obj->trans_status = "";
		}
		

		// set default photo
		$obj->default_photo = $this->get_default_photo_for_gallery( $obj->id, 'product' );

		// category object
		if ( isset( $obj->cat_id )) {
			$tmp_category = $this->CI->Category->get_one( $obj->cat_id );

			$this->convert_category( $tmp_category );

			$obj->category = $tmp_category;
		}

		// Sub Category Object
		if ( isset( $obj->sub_cat_id )) {
			$tmp_sub_category = $this->CI->Subcategory->get_one( $obj->sub_cat_id );

			$this->convert_sub_category( $tmp_sub_category );

			$obj->sub_category = $tmp_sub_category;
		}

		$conds['product_id'] = $obj->id;
		
		// Add On Object
		$food_addons = $this->CI->Food_additional->get_all_by( $conds )->result(); 
		foreach ($food_addons as $addon) {
			$tmp_result .= $addon->add_on_id .",";
			  
		}
		$add_on_id = rtrim($tmp_result,",");
		$addon_id = explode(",", $add_on_id);
		if (empty($addon_id[0])) {
			$addon_dummy = $this->CI->Additional->get_empty_object();
			$this->convert_additional( $addon_dummy );
			$tmp_add_on[] = $addon_dummy;
			$obj->addon = $tmp_add_on;
		} else {
			$tmp_addons = $this->CI->Additional->get_all_addon($addon_id)->result();
			foreach ($tmp_addons as $tmp_addon) {
				$this->CI->Subcategory->get_one( $tmp_addon->id );
				$this->convert_additional( $tmp_addon );
			}			
			$obj->addon = $tmp_addons;
		}

		// Colors Object 
		$color_count = $this->CI->Color->count_all_by( $conds );

		if ( $color_count > 0 ) {
			$tmp_colors = $this->CI->Color->get_all_by( $conds )->result();
			$obj->colors = $tmp_colors;
		} else {
			$color_dummy[] = $this->CI->Color->get_empty_object();
			$obj->colors = $color_dummy;
		}
		
		// Spec Object 
		$spec_count = $this->CI->Specification->count_all_by( $conds );

		if ( $spec_count > 0 ) {
			$tmp_spec = $this->CI->Specification->get_all_by( $conds )->result();
			$obj->specs = $tmp_spec;
		} else {
			$spec_dummy[] = $this->CI->Specification->get_empty_object();
			$obj->specs = $spec_dummy;
		}

		//Need to check for Like and Favourite
		$obj->is_liked = 0;
		$obj->is_favourited = 0;

		if($this->get_login_user_id() != "") {
			//Need to check for Fav
			$conds['product_id'] = $obj->id;
			$conds['user_id']    = $this->get_login_user_id();

			// checking for like product by user
			$like_id = $this->CI->Like->get_one_by($conds)->id;
			$obj->is_liked = 0;
			if($like_id != "") {
				$obj->is_liked = 1;
			} else {
				$obj->is_liked = 0;
			}

			$fav_id = $this->CI->Favourite->get_one_by($conds)->id;
			$obj->is_favourited = 0;
			if($fav_id != "") {
				$obj->is_favourited = 1;
			} else {
				$obj->is_favourited = 0;
			}

		} else if($obj->login_user_id_post != "") {

			$conds['product_id'] = $obj->id;
			$conds['user_id']    = $obj->login_user_id_post;

			// checking for like product by user
			$like_id = $this->CI->Like->get_one_by($conds)->id;
			$obj->is_liked = 0;
			if($like_id != "") {
				$obj->is_liked = 1;
			} else {
				$obj->is_liked = 0;
			}

			$fav_id = $this->CI->Favourite->get_one_by($conds)->id;
			$obj->is_favourited = 0;
			if($fav_id != "") {
				$obj->is_favourited = 1;
			} else {
				$obj->is_favourited = 0;
			}

		}

		
		unset($obj->login_user_id_post);

		$obj->is_liked = $obj->is_liked;
		$obj->is_favourited = $obj->is_favourited;

		// like count
	    $obj->like_count = $this->CI->Like->count_all_by(array("product_id" => $obj->id));

	    // fav count
		//$obj->favourite_count =  $this->CI->Favourite->count_all_by(array("product_id" => $obj->id));

	    // image count 
		$obj->image_count =  $this->CI->Image->count_all_by(array("img_parent_id" => $obj->id));

		// touch count
		//$obj->touch_count =  $this->CI->Touch->count_all_by(array("type_id" => $obj->id, "type_name" => "product"));

		// Comment count
		$obj->comment_header_count =  $this->CI->Commentheader->count_all_by(array("product_id" => $obj->id));

		$obj->currency_symbol = $this->CI->Shop->get_one( 'shop0b69bc5dbd68bbd57ea13dfc5488e20a' )->currency_symbol;

		$obj->currency_short_form = $this->CI->Shop->get_one( 'shop0b69bc5dbd68bbd57ea13dfc5488e20a' )->currency_short_form;

		//Discount Checking 
		$conds['product_id'] = $obj->id;
		$discount_id = $this->CI->ProductDiscount->get_one_by( $conds )->discount_id;

		if($discount_id != "") { 
			

			$discount_percent = $this->CI->Discount->get_one( $discount_id )->percent;

			$obj->discount_amount = $obj->original_price * $discount_percent;

			$obj->discount_percent = $discount_percent * 100;

			$obj->discount_value = $discount_percent;


		} else {


			$obj->discount_amount = 0;

			$obj->discount_percent = 0;

			$obj->discount_value = 0;

		}

		// Attribute Object
		// Get Header Object First
		$att_header_count = $this->CI->Prd_attribute->count_all_by( $conds );

		if ( $att_header_count > 0 ) {
			for($i = 0; $i < $att_header_count; $i++) {
				//Need to check for that header got details or not
				$tmp_header = $this->CI->Prd_attribute->get_all_by( $conds )->result();
				$att_conds['header_id'] = $tmp_header[$i]->id;
				$att_header_count_from_detail = $this->CI->Attributedetail->count_all_by( $att_conds );

				if( $att_header_count_from_detail > 0 ) {
					//if got details, need to put those details data at one header

					$tmp_detail = $this->CI->Attributedetail->get_all_by( $att_conds )->result();
					$obj->customized_header[$i] = $tmp_header[$i];
					$obj->customized_header[$i]->customized_detail = $tmp_detail;

				} else {
					$att_detail_dummy[] = $this->CI->Attributedetail->get_empty_object();

					$obj->customized_header[$i] = $tmp_header[$i];
					$obj->customized_header[$i]->customized_detail = $att_detail_dummy;
				}
			}

		} else {
			$header_dummy[] = $this->CI->Prd_attribute->get_empty_object();
			$obj->customized_header = $header_dummy;

			$att_detail_dummy[] = $this->CI->Attributedetail->get_empty_object();
			$obj->customized_header[0]->customized_detail = $att_detail_dummy;
		}
		
		//rating details 
		
		// $obj->like_count = $this->CI->Like->count_all_by(array("product_id" => $obj->id));

		
		$total_rating_count = 0;
		$total_rating_value = 0;

		$five_star_count = 0;
		$five_star_percent = 0;

		$four_star_count = 0;
		$four_star_percent = 0;

		$three_star_count = 0;
		$three_star_percent = 0;

		$two_star_count = 0;
		$two_star_percent = 0;

		$one_star_count = 0;
		$one_star_percent = 0;


		

		//Rating Total how much ratings for this product
		$conds_rating['product_id'] = $obj->id;
		$total_rating_count = $this->CI->Rate->count_all_by($conds_rating);
		$sum_rating_value = $this->CI->Rate->sum_all_by($conds_rating)->result()[0]->rating;

		//Rating Value such as 3.5, 4.3 and etc
		if($total_rating_count > 0) {
			$total_rating_value = number_format((float) ($sum_rating_value  / $total_rating_count), 1, '.', '');
		} else {
			$total_rating_value = 0;
		}

		//For 5 Stars rating

		$conds_five_star_rating['rating'] = 5;
		$conds_five_star_rating['product_id'] = $obj->id;
		$five_star_count = $this->CI->Rate->count_all_by($conds_five_star_rating);
		if($total_rating_count > 0) {
			$five_star_percent = number_format((float) ((100 / $total_rating_count) * $five_star_count), 1, '.', '');
		} else {
			$five_star_percent = 0;
		}

		//For 4 Stars rating
		$conds_four_star_rating['rating'] = 4;
		$conds_four_star_rating['product_id'] = $obj->id;
		$four_star_count = $this->CI->Rate->count_all_by($conds_four_star_rating);
		if($total_rating_count > 0) {
			$four_star_percent = number_format((float) ((100 / $total_rating_count) * $four_star_count), 1, '.', '');
		} else {
			$four_star_percent = 0;
		}


		//For 3 Stars rating
		$conds_three_star_rating['rating'] = 3;
		$conds_three_star_rating['product_id'] = $obj->id;
		$three_star_count = $this->CI->Rate->count_all_by($conds_three_star_rating);
		if($total_rating_count > 0) {
			$three_star_percent = number_format((float) ((100 / $total_rating_count) * $three_star_count), 1, '.', '');
		} else {
			$three_star_percent = 0;
		}


		//For 2 Stars rating
		$conds_two_star_rating['rating'] = 2;
		$conds_two_star_rating['product_id'] = $obj->id;
		$two_star_count = $this->CI->Rate->count_all_by($conds_two_star_rating);

		if($total_rating_count > 0) {
			$two_star_percent = number_format((float) ((100 / $total_rating_count) * $two_star_count), 1, '.', '');
		} else {
			$two_star_percent = 0;
		}

		//For 1 Stars rating
		$conds_one_star_rating['rating'] = 1;
		$conds_one_star_rating['product_id'] = $obj->id;
		$one_star_count = $this->CI->Rate->count_all_by($conds_one_star_rating);

		if($total_rating_count > 0) {
		$one_star_percent = number_format((float) ((100 / $total_rating_count) * $one_star_count), 1, '.', '');
		} else {
			$one_star_percent = 0;
		}


		$rating_std = new stdClass();
		$rating_std->five_star_count = $five_star_count; 
		$rating_std->five_star_percent = $five_star_percent;

		$rating_std->four_star_count = $four_star_count;
		$rating_std->four_star_percent = $four_star_percent;

		$rating_std->three_star_count = $three_star_count;
		$rating_std->three_star_percent = $three_star_percent;

		$rating_std->two_star_count = $two_star_count;
		$rating_std->two_star_percent = $two_star_percent;

		$rating_std->one_star_count = $one_star_count;
		$rating_std->one_star_percent = $one_star_percent;

		$rating_std->total_rating_count = $total_rating_count;
		$rating_std->total_rating_value = $total_rating_value;


		$obj->rating_details = $rating_std;

		if($need_return)
		{
			return $obj;
		} 

	}

	function convert_token(&$obj)
	{
		
	}

	function convert_food_additional( &$obj )
	{

	}

	function convert_transaction_status( &$obj )
	{

	}

	/**
	 * Customize wallpaper object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_image( &$obj )
	{

	}

	/**
	 * Customize tag object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_rating( &$obj )
	{
		// set user object
		if ( isset( $obj->user_id )) {
			$tmp_user = $this->CI->User->get_one( $obj->user_id );

			$this->convert_user( $tmp_user );

			$obj->user = $tmp_user;
		}
	}

	/**
	 * Customize tag object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_shop_rating( &$obj )
	{
		// set user object
		if ( isset( $obj->user_id )) {
			$tmp_user = $this->CI->User->get_one( $obj->user_id );

			$this->convert_user( $tmp_user );

			$obj->user = $tmp_user;
		}
	}

	/**
	 * Customize collection object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_collection( &$obj )
	{
		

		$conds['collection_id'] = $obj->id;

		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->id, 'collection' );

		$collection_id = $this->CI->get_collection_id();

		$count_product = 0;
		if($collection_id == "")
		{
			$count_product_collection = $this->CI->Api->get_one_by( array( 'api_constant' => "GET_ALL_COLLECTIONS" ) )->count;
		} else {
			$count_product = $this->CI->Productcollection->count_all_by( $conds );
		}


		if ( $count_product_collection > 0 ) {

			for($i = 0; $i < $count_product_collection ; $i++) {
				

				$tmp_collection = $this->CI->Productcollection->get_all_collections( $conds )->result();


				if(isset($tmp_collection[$i]->id)) {

					$prd_conds['id'] = $tmp_collection[$i]->id;
					$prd_conds['delete_flag'] = 0;

					$tmp_product = $this->CI->Product->get_one_by( $prd_conds );
					$obj->products[] = $this->convert_product($tmp_product, true);
				}

			}

		}

	}

	/**
	 * Customize noti object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_noti( &$obj )
	{
		
		
		if($this->get_login_user_id() != "") {
			$noti_user_data = array(
	        	"noti_id" => $obj->id,
	        	"user_id" => $this->get_login_user_id()
	    	);
			if ( !$this->CI->Notireaduser->exists( $noti_user_data )) {
				$obj->is_read = 0;
			} else {
				$obj->is_read = 1;
			}
		} 
		


		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->id, 'noti' );
	}

	/**
	 * Customize user object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_user( &$obj )
	{
		//user flag
		$conds['user_id'] = $obj->user_id;
		$role_id = $this->CI->User->get_one_by($conds)->role_id;
		$status = $this->CI->User->get_one_by($conds)->status;

		if ($role_id == 5 && $status == 1) {
			$obj->user_flag = "Approved";
		}elseif ($role_id == 5 && $status == 2) {
			$obj->user_flag = "Pending";
		}elseif ($role_id == 5 && $status == 3) {
			$obj->user_flag = "Rejected";
		}elseif ($role_id == 4) {
			$obj->user_flag = "Normal User";
		}else{
			$obj->user_flag = "Admin";
		}
		// area object
		if ( isset( $obj->user_area_id )) {
			$tmp_area = $this->CI->Shipping_area->get_one( $obj->user_area_id );

			$this->convert_areas( $tmp_area );

			$obj->user_area = $tmp_area;
		}
	}


	/**
	 * Customize about object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_about( &$obj )
	{
		$obj->privacypolicy = $this->CI->Privacy_policy->get_one('privacy1')->content;
		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->about_id, 'about' );

	}

	/**
	 * Checking for transaction
	 *
	 * 
	 */
	function transaction_checking( $trans_details = array())
	{
		$failed_records = array();

		$failed_price = array();

		$failed_available = array();

		$failed_delete = array();


		for($i=0; $i<count($trans_details); $i++) 
		{
			
			// Rule 1 : Need to check the product whether delete or not?
			$product_is_delete = $this->CI->Product_delete->get_one_by($trans_details[$i]['product_id'])->product_id;
			if($product_is_delete != "")
			{
				//delete_flag '1' is deleted
				$failed_delete[$i] = $trans_details[$i]['product_id'];

			} else {

				//Rule 2 : Need to check availability of the product
				$product_is_available = $this->CI->Product->get_one($trans_details[$i]['product_id'])->is_available;
				if($product_is_available == 0)
				{
					//is_available '0' is No More Stock
					$failed_available[$i] = $trans_details[$i]['product_id'];
				} else {

					// Rule 3 : Need to check the product price 
					$product_unit_price = $this->CI->Product->get_one($trans_details[$i]['product_id'])->unit_price;

					if($product_unit_price != $trans_details[$i]['price']) 
					{
						
						//Price not same
						$failed_price[$i] = $trans_details[$i]['product_id'];

					} else {
						//Product Price is same but attribute price is not same
						$att_additional_price = $this->CI->Attributedetail->get_one($trans_details[$i]['product_addon_id'])->additional_price;

						if($att_additional_price != $trans_details[$i]['product_attribute_price'])  {
							
							//att price not same
							$failed_price[$i] = $trans_details[$i]['product_id'];

						}

					}

				}

			}

		}
		
		$failed_records[] = array_unique($failed_price);
		$failed_records[] = array_unique($failed_available);
		$failed_records[] = array_unique($failed_delete);

		//print_r($failed_records); die;

		return $failed_records;
	}

	/*
	 * Customize category object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_tag( &$obj )
	{
		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->id, 'category' );

		// set default icon 
		$obj->default_icon = $this->get_default_photo( $obj->id, 'category-icon' );

	}

	/*
	 * Customize shop object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_shop( &$obj )
	{
		// set default photo
		$obj->default_photo = $this->get_default_photo( $obj->id, 'shop' );

		// set default photo
		$obj->default_icon = $this->get_default_photo( $obj->id, 'shop-icon' );
		// restaurant branch object
				// shop schedule obj
		$shop_sch = new stdClass();
		$schedules = $this->CI->Schedule->get_all_by($conds_rating)->result();

		for ($i=0; $i <count($schedules) ; $i++) { 
			//print_r($schedules[$i]->days_of_week);die;

			if ($schedules[$i]->days_of_week == get_msg('monday_label')) {
				if ($schedules[$i]->is_open == '1') {
					$shop_sch->is_monday_open = '1';
					$shop_sch->monday_open_hour = $schedules[$i]->open_hour;
					$shop_sch->monday_close_hour = $schedules[$i]->close_hour;
				} else {
					$shop_sch->is_monday_open = '0';
					$shop_sch->monday_open_hour = '00:00';
					$shop_sch->monday_close_hour = '00:00';
				}
				
			}

			if ($schedules[$i]->days_of_week == get_msg('tuesday_label')) {
				if ($schedules[$i]->is_open == '1') {
					$shop_sch->is_tuesday_open = '1';
					$shop_sch->tuesday_open_hour = $schedules[$i]->open_hour;
					$shop_sch->tuesday_close_hour = $schedules[$i]->close_hour;
				} else {
					$shop_sch->is_tuesday_open = '0';
					$shop_sch->tuesday_open_hour = '00:00';
					$shop_sch->tuesday_close_hour = '00:00';
				}
			}

			if ($schedules[$i]->days_of_week == get_msg('wednesday_label')) {
				if ($schedules[$i]->is_open == '1') {
					$shop_sch->is_wednesday_open = '1';
					$shop_sch->wednesday_open_hour = $schedules[$i]->open_hour;
					$shop_sch->wednesday_close_hour = $schedules[$i]->close_hour;
				} else {
					$shop_sch->is_wednesday_open = '0';
					$shop_sch->wednesday_open_hour = '00:00';
					$shop_sch->wednesday_close_hour = '00:00';
				}
			}

			if ($schedules[$i]->days_of_week == get_msg('thursday_label')) {
				if ($schedules[$i]->is_open == '1') {
					$shop_sch->is_thursday_open = '1';
					$shop_sch->thursday_open_hour = $schedules[$i]->open_hour;
					$shop_sch->thursday_close_hour = $schedules[$i]->close_hour;
				} else {
					$shop_sch->is_thursday_open = '0';
					$shop_sch->thursday_open_hour = '00:00';
					$shop_sch->thursday_close_hour = '00:00';
				}
			}

			if ($schedules[$i]->days_of_week == get_msg('friday_label')) {
				if ($schedules[$i]->is_open == '1') {
					$shop_sch->is_friday_open = '1';
					$shop_sch->friday_open_hour = $schedules[$i]->open_hour;
					$shop_sch->friday_close_hour = $schedules[$i]->close_hour;
				} else {
					$shop_sch->is_friday_open = '0';
					$shop_sch->friday_open_hour = '00:00';
					$shop_sch->friday_close_hour = '00:00';
				}
			}

			if ($schedules[$i]->days_of_week == get_msg('saturday_label')) {
				if ($schedules[$i]->is_open == '1') {
					$shop_sch->is_saturday_open = '1';
					$shop_sch->saturday_open_hour = $schedules[$i]->open_hour;
					$shop_sch->saturday_close_hour = $schedules[$i]->close_hour;
				} else {
					$shop_sch->is_saturday_open = '0';
					$shop_sch->saturday_open_hour = '00:00';
					$shop_sch->saturday_close_hour = '00:00';
				}
			}

			if ($schedules[$i]->days_of_week == get_msg('sunday_label')) {
				if ($schedules[$i]->is_open == '1') {
					$shop_sch->is_sunday_open = '1';
					$shop_sch->sunday_open_hour = $schedules[$i]->open_hour;
					$shop_sch->sunday_close_hour = $schedules[$i]->close_hour;
				} else {
					$shop_sch->is_sunday_open = '0';
					$shop_sch->sunday_open_hour = '00:00';
					$shop_sch->sunday_close_hour = '00:00';
				}
			}
			$obj->shop_schedules = $shop_sch;

		}
		if ( isset( $obj->id )) {
			$conds['shop_id'] = $obj->id;
			$tmp_res_branch = $this->CI->Restaurant_branch->get_all_by( $conds )->result();
			
			$this->convert_branch( $tmp_res_branch );

			$obj->restaurant_branch = $tmp_res_branch;
		}

		//rating details 
		
		// $obj->like_count = $this->CI->Like->count_all_by(array("product_id" => $obj->id));

		
		$total_rating_count = 0;
		$total_rating_value = 0;

		$five_star_count = 0;
		$five_star_percent = 0;

		$four_star_count = 0;
		$four_star_percent = 0;

		$three_star_count = 0;
		$three_star_percent = 0;

		$two_star_count = 0;
		$two_star_percent = 0;

		$one_star_count = 0;
		$one_star_percent = 0;

		//Rating Total how much ratings for this product
		$conds_rating['shop_id'] = $obj->id;
		$total_rating_count = $this->CI->Shop_rate->count_all_by($conds_rating);
		$sum_rating_value = $this->CI->Shop_rate->sum_all_by($conds_rating)->result()[0]->rating;

		//Rating Value such as 3.5, 4.3 and etc
		if($total_rating_count > 0) {
			$total_rating_value = number_format((float) ($sum_rating_value  / $total_rating_count), 1, '.', '');
		} else {
			$total_rating_value = 0;
		}

		//For 5 Stars rating

		$conds_five_star_rating['rating'] = 5;
		$conds_five_star_rating['shop_id'] = $obj->id;
		$five_star_count = $this->CI->Shop_rate->count_all_by($conds_five_star_rating);
		if($total_rating_count > 0) {
			$five_star_percent = number_format((float) ((100 / $total_rating_count) * $five_star_count), 1, '.', '');
		} else {
			$five_star_percent = 0;
		}

		//For 4 Stars rating
		$conds_four_star_rating['rating'] = 4;
		$conds_four_star_rating['shop_id'] = $obj->id;
		$four_star_count = $this->CI->Shop_rate->count_all_by($conds_four_star_rating);
		if($total_rating_count > 0) {
			$four_star_percent = number_format((float) ((100 / $total_rating_count) * $four_star_count), 1, '.', '');
		} else {
			$four_star_percent = 0;
		}


		//For 3 Stars rating
		$conds_three_star_rating['rating'] = 3;
		$conds_three_star_rating['shop_id'] = $obj->id;
		$three_star_count = $this->CI->Shop_rate->count_all_by($conds_three_star_rating);
		if($total_rating_count > 0) {
			$three_star_percent = number_format((float) ((100 / $total_rating_count) * $three_star_count), 1, '.', '');
		} else {
			$three_star_percent = 0;
		}


		//For 2 Stars rating
		$conds_two_star_rating['rating'] = 2;
		$conds_two_star_rating['shop_id'] = $obj->id;
		$two_star_count = $this->CI->Shop_rate->count_all_by($conds_two_star_rating);

		if($total_rating_count > 0) {
			$two_star_percent = number_format((float) ((100 / $total_rating_count) * $two_star_count), 1, '.', '');
		} else {
			$two_star_percent = 0;
		}

		//For 1 Stars rating
		$conds_one_star_rating['rating'] = 1;
		$conds_one_star_rating['shop_id'] = $obj->id;
		$one_star_count = $this->CI->Shop_rate->count_all_by($conds_one_star_rating);

		if($total_rating_count > 0) {
		$one_star_percent = number_format((float) ((100 / $total_rating_count) * $one_star_count), 1, '.', '');
		} else {
			$one_star_percent = 0;
		}


		$rating_std = new stdClass();
		$rating_std->five_star_count = $five_star_count; 
		$rating_std->five_star_percent = $five_star_percent;

		$rating_std->four_star_count = $four_star_count;
		$rating_std->four_star_percent = $four_star_percent;

		$rating_std->three_star_count = $three_star_count;
		$rating_std->three_star_percent = $three_star_percent;

		$rating_std->two_star_count = $two_star_count;
		$rating_std->two_star_percent = $two_star_percent;

		$rating_std->one_star_count = $one_star_count;
		$rating_std->one_star_percent = $one_star_percent;

		$rating_std->total_rating_count = $total_rating_count;
		$rating_std->total_rating_value = $total_rating_value;


		$obj->rating_details = $rating_std;

	}

	function convert_branch( &$obj )
	{

	}

	function convert_delivery_status( &$obj )
	{
		$obj->shop_id = $this->CI->Shop->get_one( 'shop0b69bc5dbd68bbd57ea13dfc5488e20a' )->id;

		// shop object
		if ( isset( $obj->shop_id )) {
			$tmp_shop = $this->CI->Shop->get_one( $obj->shop_id );

			$this->convert_shop( $tmp_shop );

			$obj->shop = $tmp_shop;
		}
	}

	/*
	 * Customize Transaction Header object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_transaction_header( &$obj )
	{

		if ( isset( $obj->shop_id )) {
			$tmp_shop = $this->CI->Shop->get_one( $obj->shop_id );

			$this->convert_shop( $tmp_shop );

			$obj->shop = $tmp_shop;
		}

		$temp_obj_id = $obj->id;

		$obj_id = explode('_', $temp_obj_id);

		//refund status and rating status will return at transaction header and detail objects only

		if (($obj_id[0] == "trans" && $obj_id[1] == "hdr") || ($obj_id[0] == "trans" && $obj_id[1] == "det")) {
			//refund status
			$transaction_header_id = $obj->id ;
			//print_r($transaction_header_id);die;
			$trans_status_id = $this->CI->Transactionheader->get_one( $transaction_header_id )->trans_status_id;
			$payment_method = $this->CI->Transactionheader->get_one( $transaction_header_id )->payment_method;
			$refund_status = $this->CI->Transactionstatus->get_one( $trans_status_id )->is_refundable;
			$conds['transactions_header_id'] = $transaction_header_id;
			$payment_status = $this->CI->Transaction_payment->get_one_by( $conds )->payment_status;
			//print_r($payment_status);die;

			if ($payment_status == "refunded") {
				$refund_status = 2; // 2 for refunded
			}

			if ( $payment_method == "COD" || $payment_method == "Pick Up" || $payment_method == "Razor" || $payment_method == "BANK" || $payment_method == "Paystack" ) {
				$refund_status = 0; // 0 for not refundable (due to payment method or transation status stage)
			}

			$obj->refund_status = $refund_status; // 1 for refundable

			//rating_status
			$delivery_boy_id = $this->CI->Transactionheader->get_one( $transaction_header_id )->delivery_boy_id;
			$final_stage = $this->CI->Transactionstatus->get_one( $trans_status_id )->final_stage;


			if ( $final_stage == '0'  || ( $delivery_boy_id == "" || $delivery_boy_id == '0' )  ) {
				//deli boy not yet assigned and not final stage
				//not able to rate
				$obj->rating_status = 0;

			} else {
				//deli boy already assigned and reached final stage

				$conds_deli['to_user_id'] = $delivery_boy_id;
				$conds_deli['transactions_header_id'] = $transaction_header_id;
				$deli_rating_id = $this->CI->Deliboy_Rate->get_one_by( $conds_deli )->id;
				if ($deli_rating_id != "") {
					//already rated
					$obj->rating_status = 0;
				} else {
					$obj->rating_status = 1;
				}

			}
		} 
		

		// delivery boy object
		if ( isset($obj->delivery_boy_id)) {
			$tmp_deliboy = $this->CI->User->get_one( $obj->delivery_boy_id );

			$this->convert_user( $tmp_deliboy );

			$obj->delivery_boy = $tmp_deliboy;
		}		
	}

	function convert_postcode( &$obj )
	{
		
	}


	/*
	 * Customize Transaction Detail object
	 *
	 * @param      <type>  $obj    The object
	 */
	function convert_transaction_detail( &$obj )
	{

        //refund status
        $transaction_header_id = $this->CI->Transactiondetail->get_one( $obj->id )->transactions_header_id;
        //print_r($transaction_header_id);die;
        $trans_status_id = $this->CI->Transactionheader->get_one( $transaction_header_id )->trans_status_id;
        $payment_method = $this->CI->Transactionheader->get_one( $transaction_header_id )->payment_method;
        $refund_status = $this->CI->Transactionstatus->get_one( $trans_status_id )->is_refundable;
        $conds['transactions_header_id'] = $transaction_header_id;
        $payment_status = $this->CI->Transaction_payment->get_one_by( $conds )->payment_status;
        //print_r($payment_status);die;

        if ($payment_status == "refunded") {
        	$refund_status = 2; // 2 for refunded
        }

        if ( $payment_method == "COD" || $payment_method == "Pick Up" || $payment_method == "Razor" || $payment_method == "BANK" || $payment_method == "Paystack" ) {
        	$refund_status = 0; // 0 for not refundable (due to payment method or transation status stage)
        }

        $obj->refund_status = $refund_status; // 1 for refundabl

        //rating_status
        $delivery_boy_id = $this->CI->Transactionheader->get_one( $transaction_header_id )->delivery_boy_id;
        $final_stage = $this->CI->Transactionstatus->get_one( $trans_status_id )->final_stage;


        if ( $final_stage == '0'  || ( $delivery_boy_id == "" || $delivery_boy_id == '0' )  ) {
            //deli boy not yet assigned and not final stage
            //not able to rate
            $obj->rating_status = 0;

        } else {
            //deli boy already assigned and reached final stage

            $conds_deli['to_user_id'] = $delivery_boy_id;
            $conds_deli['transactions_header_id'] = $transaction_header_id;
            $deli_rating_id = $this->CI->Deliboy_Rate->get_one_by( $conds_deli )->id;
            if ($deli_rating_id != "") {
                //already rated
                $obj->rating_status = 0;
            } else {
                $obj->rating_status = 1;
            }

        }

		// shop object
		 if ( isset( $obj->shop_id )) {
		 	$tmp_shop = $this->CI->Shop->get_one( $obj->shop_id );

		 	$this->convert_shop( $tmp_shop );

		 	$obj->shop = $tmp_shop;
		 }
		
	}

    /**
     * Customize tag object
     *
     * @param      <type>  $obj    The object
     */
    function convert_rating_user( &$obj )
    {
        // set user object

        if ( is_array( $obj )) {

            for ($i=0; $i < count($obj) ; $i++) {
                if ( isset( $obj[$i]->from_user_id )) {

                    $tmp_from_user_id = $this->CI->User->get_one( $obj[$i]->from_user_id );
                    //print_r($tmp_from_user_id);die;
                    $this->convert_user( $tmp_from_user_id );
                    //print_r($a);die;
                    $obj[$i]->from_user = $tmp_from_user_id;
                }


                // set user object
                if ( isset( $obj[$i]->to_user_id )) {
                    $tmp_to_user_id = $this->CI->User->get_one( $obj[$i]->to_user_id );

                    $this->convert_user( $tmp_to_user_id );

                    $obj[$i]->to_user = $tmp_to_user_id;
                }

				// set user transaction header obj
				if ( isset( $obj[$i]->transactions_header_id )) {
					$tmp_trans_header_id = $this->CI->Transactionheader->get_one( $obj[$i]->transactions_header_id );

					$this->convert_transaction_header( $tmp_trans_header_id );

					$obj[$i]->transactions_header = $tmp_trans_header_id;
				}
            }


        }else {

            if ( isset( $obj->from_user_id )) {

                $tmp_from_user_id = $this->CI->User->get_one( $obj->from_user_id );
                //print_r($tmp_from_user_id);die;
                $this->convert_user( $tmp_from_user_id );
                //print_r($a);die;
                $obj->from_user = $tmp_from_user_id;
            }


            // set user object
            if ( isset( $obj->to_user_id )) {
                $tmp_to_user_id = $this->CI->User->get_one( $obj->to_user_id );

                $this->convert_user( $tmp_to_user_id );

                $obj->to_user = $tmp_to_user_id;
            }

			// set user transaction header obj
			if ( isset( $obj->transactions_header_id )) {
				$tmp_trans_header_id = $this->CI->Transactionheader->get_one( $obj->transactions_header_id );

				$this->convert_transaction_header( $tmp_trans_header_id );

				$obj->transactions_header = $tmp_trans_header_id;
			}


        }
    }

	/**
	 * Customize comment header
	 */
	function convert_comment_header(&$obj)
	{

		// call parent convert object
		$obj->comment_reply_count =  $this->CI->Commentdetail->count_all_by(array("header_id" => $obj->id));

		if (isset($obj->user_id)) {
			$tmp_user = $this->CI->User->get_one($obj->user_id);

			$this->convert_user($tmp_user);

			$obj->user = $tmp_user;
		}
	}

	/**
	 * Customize comment detail
	 */
	function convert_comment_detail(&$obj)
	{
		if (isset($obj->user_id)) {
			$tmp_user = $this->CI->User->get_one($obj->user_id);

			$this->convert_user($tmp_user);

			$obj->user = $tmp_user;
		}
	}

}