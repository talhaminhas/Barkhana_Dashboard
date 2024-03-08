<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Send Booking Request Email to hotel
 * @param  [type] $booking_id [description]
 * @return [type]             [description]
 */
if ( !function_exists( 'send_transaction_order_emails' )) {

	function send_transaction_order_emails( $trans_header_id, $to_who = "", $subject = "" )
	{
		// get ci instance
		$CI =& get_instance();

		$sender_name = $CI->Backend_config->get_one('be1')->sender_name;
		
		$shop_obj = $CI->Shop->get_all()->result();

		$shop_id = $shop_obj[0]->id;

		$trans_header_obj = $CI->Transactionheader->get_one($trans_header_id);

		$shop_name = $CI->Shop->get_one($shop_id)->name;

		$shop_email = $CI->Shop->get_one($shop_id)->email;

		$trans_currency = $CI->Shop->get_one($shop_id)->currency_symbol;

		$user_email =  $CI->User->get_one($trans_header_obj->added_user_id)->user_email;

		$user_name =  $CI->User->get_one($trans_header_obj->added_user_id)->user_name;

		//bank info 
		$bank_account = $CI->Shop->get_one($shop_id)->bank_account;
		$bank_name = $CI->Shop->get_one($shop_id)->bank_name;
		$bank_code = $CI->Shop->get_one($shop_id)->bank_code;
		$branch_code = $CI->Shop->get_one($shop_id)->branch_code;
		$swift_code = $CI->Shop->get_one($shop_id)->swift_code;


		$bank_info  = get_msg('bank_acc_label') . $bank_account . " <br> " .
					get_msg('bank_name_label') . $bank_name . " <br> " .
					get_msg('bank_code_label') . $bank_code . " <br> " .
					get_msg('branch_code_label') . $branch_code . " <br> " .
		            get_msg('swift_code_label') . $swift_code . " <br><br> " ;

		//For Payment Method 
		$payment_info = "";
		if($trans_header_obj->payment_method == "COD") {
			$payment_info = get_msg('pay_met_cod');
		} else if($trans_header_obj->payment_method == "PAYPAL") {
			$payment_info = get_msg('pay_met_paypal');
		} else if($trans_header_obj->payment_method == "STRIPE") {
			$payment_info = get_msg('pay_met_stripe');
		} else if($trans_header_obj->payment_method == "BANK") {
			$payment_info = get_msg('pay_met_bank') . $bank_info;
		}


		$conds['transactions_header_id'] = $trans_header_obj->id;

		$trans_details_obj = $CI->Transactiondetail->get_all_by($conds)->result();
		$header_color = "#fc6f03";
		//For Transaction Detials
		for($i=0;$i<count($trans_details_obj);$i++) 
		{
			$price = number_format((float) $trans_details_obj[$i]->price, 2, '.', '');
			$item_price = $price;
				if($trans_details_obj[$i]->product_addon_id != "") {
					

					$att_name_info  = explode("#", $trans_details_obj[$i]->product_addon_name);
					
					$att_price_info = explode("#", $trans_details_obj[$i]->product_addon_price);
					
					$addon_content = "";
					$att_flag = 0;
					if( count($att_name_info) > 0 ) {

						//loop attribute info
						for($k = 0; $k < count($att_name_info); $k++) {
							
							if($att_name_info[$k] != "") {
								$att_flag = 1;
								$addon_price = number_format((float) $att_price_info[$k], 2, '.', '');
								$addon_content .= 
								"
								<div style='font-weight: bold; display: flex; justify-content: space-between;'>
								<span >{$att_name_info[$k]}:</span> 
								<span style = 'text-align: right;'> +£{$addon_price}</span>
								</div>
									
							";

							}
							$item_price += $addon_price;
						}
						
					} 
					$att_info_str = "<li style='
						background-color: #fabcb9;
						border: 0px solid #ddd;
						border-radius: 10px;
						padding: 15px;
						margin: 10px 0;
						list-style: none;
						'>
							<div style='font-size: 20px; font-weight: bold; color: {$header_color}; margin-bottom: 10px'> 
								Extras
							</div>
							{$addon_content}
						</li>";

				} 
				//$att_info_str = rtrim($att_info_str, ","); 
				$original_price = number_format((float) $trans_details_obj[$i]->original_price, 2, '.', '');
				
				$total_amount = number_format((float)$item_price * (int)$trans_details_obj[$i]->qty, 2, '.', '');
				$discount_amount = (float)$trans_details_obj[$i]->discount_amount;
				$price_info_str = "";
				$product_discount_info_str = "";
				$item_price = number_format($item_price, 2, '.', '');
				if( $discount_amount > 0)
				{
				
					$product_discount_info_str = 
					"
					<span style='color: red; text-align: right;' >
						<span style='font-size: 15px; text-decoration: line-through;' > £"
							.number_format($price + $discount_amount, 2, '.', '').
						"</span >
					£{$price}
					</span>";
					

				}
				else{
					$product_discount_info_str = 
					"<span style=' text-align: right;' >
					£{$price}
					</span>";
				}
				
				$order_items .= "<li style='
				background-color: #e6f7ff;
				border: 0px solid #ddd;
				border-radius: 10px;
				padding: 15px;
				margin: 10px 0;
				list-style: none;
				'>
				<div style='font-size: 22px; font-weight: bold; color: {$header_color}; margin-bottom: 10px; display: flex; justify-content: space-between;'>
				{$trans_details_obj[$i]->product_name}
				{$product_discount_info_str}
				</div>
					<ul style='padding: 0;'>{$att_info_str}</ul>
					<div style= 'display: flex; justify-content: space-between;'>
						<span >
							Quantity:
						</span> 
						<span style='text-align: right;'>
							{$trans_details_obj[$i]->qty}
						</span>
					</div>
					<div style= 'display: flex; justify-content: space-between;'>
						<span>
							Item Price:
						</span> 
						<span style='text-align: right;'>
							£{$item_price}
						</span>
					</div>
					<div style='display: flex; justify-content: space-between; font-size: 25px; font-weight: bold;'>
						<div>
							<span>Total:</span> 
						</div>
						<div style=' font-weight: bold; '>
							£{$total_amount} {$trans_details_obj[$i]->product_unit}
						</div>
					</div>
				</li>";

				$att_info_str = "";
				$sub_total_amt += $item_price * $trans_details_obj[$i]->qty;
				
				
		}


		

		$trans_status = $CI->Transactionstatus->get_one($trans_header_obj->trans_status_id)->title;

		
		//$total_amt = html_entity_decode($trans_currency).' ' . $total_amount ;

		$coupon_discount_amount = $trans_header_obj->coupon_discount_amount;
		$tax_amount = $trans_header_obj->tax_amount;
		$shipping_amount = $trans_header_obj->shipping_amount;
		$shipping_tax_amount = $trans_header_obj->shipping_amount * $trans_header_obj->shipping_tax_percent;

		$total_balance_amount = ($trans_header_obj->sub_total_amount + ($trans_header_obj->tax_amount + $trans_header_obj->shipping_amount + ($trans_header_obj->shipping_amount * $trans_header_obj->shipping_tax_percent)));  	
		//for msg label
		$hi = get_msg('hi_label');
    	$order_receive_info = get_msg('order_receive_info');
		$order_placed_info = get_msg('order_placed_info');
    	$trans_code = get_msg('trans_code');
    	$trans_status_label = get_msg('trans_status_label');
    	$memo_label = get_msg('memo_label');
    	$prd_detail_info = get_msg('prd_detail_info');
    	$sub_total = get_msg('sub_total');
    	$coupon_dis_amount = get_msg('coupon_dis_amount');
    	$overall_tax = get_msg('overall_tax');
    	$shipping_cost = get_msg('shipping_cost');
    	$shipping_tax = get_msg('shipping_tax');
    	$total_bal_amt = get_msg('total_bal_amt');
    	$best_regards = get_msg( 'best_regards_label' );
		$order_items = "<ul style='padding: 0;'>{$order_items}</ul>";
		//format all the amounts to be 2 decimal places.
		$sub_total_amt = number_format((float) $sub_total_amt, 2, '.', '');
		$coupon_discount_amount = number_format((float) $coupon_discount_amount, 2, '.', '');
		$shipping_amount = number_format((float) $shipping_amount, 2, '.', '');
		$total_balance_amount = number_format((float) $total_balance_amount, 2, '.', '');
		$coupon_discount_info = "";
		if($coupon_discount_amount != "0")
		{
			$coupon_discount_info = 
			"<div style='color: red; display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;'>
				<div>
					<span>{$coupon_dis_amount}:</span> 
				</div>
				<div style=' text-align: right;'>
					-£{$coupon_discount_amount}
				</div>
			</div>";
		}
		$email_content = 
		"<p>{$payment_info}</p>
		<div style='
			background-color: #fabcb9;
			padding: 15px;
			border-radius: 10px;
			margin: 10px 0;
		'>
			<p style='font-size: 22px; font-weight: bold; color: {$header_color};'><strong>{$prd_detail_info}:</strong></p>
			<div style='
				background-color: #fabcb9;
				border: 0px solid #ddd;
				border-radius: 10px;
				padding: 15px;
				margin: 10px 0;
			'>
				{$order_items}
				<div style='display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;'>
					<div>
						<span>{$sub_total}:</span> 
					</div>
					<div style=' text-align: right;'>
						£{$sub_total_amt}
					</div>
				</div>
				{$coupon_discount_info}
				<div style='display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;'>
					<div>
						<span>{$shipping_cost}:</span> 
					</div>
					<div style=' text-align: right;'>
						+£{$shipping_amount}
					</div>
				</div>
			</div>
			<div style='display: flex; justify-content: space-between; font-size: 25px; font-weight: bold; color: red;'>
					<div>
						<span>{$total_bal_amt}:</span> 
					</div>
					<div style=' text-align: right;'>
						£{$total_balance_amount}
					</div>
			</div>
		</div>
		<p>{$best_regards},<br>{$sender_name}</p>";

		//Shop or User
		if ($to_who == "shop") {
			$to = $shop_email;
			$title = $hi . ' ' . $shop_name;
			$content = <<<EOL
			<p>{$order_receive_info}</p>
			<p>{$trans_code}: {$trans_header_obj->trans_code}</p>
			{$email_content}
		EOL;

		
			$msg = generateHTMLContent($title, '', $content);
		} elseif ($to_who == "user") {
			$to = $user_email;
			$title = $hi . ' ' . $user_name;
			$content = <<<EOL
			<p>New Order is placed with the following information:</p>
			<p>{$trans_code}: {$trans_header_obj->trans_code}</p>
			<p>{$trans_status_label}: {$trans_status}</p>
			{$email_content}
		EOL;

		
			$msg = generateHTMLContent($title, '', $content);
		}
		
		
		// echo "---------";

		// send email from admin
		return $CI->ps_mail->send_from_admin( $to, $subject, $msg );
	}
}

function generateHTMLContent($title, $recipientName, $content) {
    return <<<EOL
    <html>
    <head>
        <style>
            /* Add your common CSS styles here */
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #ffffff;
                border: 1px solid #ddd;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .header {
                background-color: #3498db;
                color: #fff;
                text-align: center;
                padding: 10px;
            }
            .content {
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>{$title} {$recipientName}</h1>
            </div>
            <div >
                {$content}
            </div>
        </div>
    </body>
    </html>
    EOL;
}

if ( !function_exists( 'send_user_register_email' )) {

  function send_user_register_email( $user_id, $subject = "" )
  {
    // get ci instance
    $CI =& get_instance();
    
    $user_info_obj = $CI->User->get_one($user_id);

    $user_name  = $user_info_obj->user_name;
    $user_email = $user_info_obj->user_email;
    $code = $user_info_obj->code;
    

    $to = $user_email;

	$sender_name = $CI->Backend_config->get_one('be1')->sender_name;
    $hi = get_msg('hi_label');
    $new_user_acc = get_msg('new_user_acc');
    $verify_code = get_msg('verify_code_label');
    $best_regards = get_msg( 'best_regards_label' );

	$msg = "<!DOCTYPE html>
				<html>
				<head>
				<style>
						/* Add your CSS styles here */
						body {
							font-family: Arial, sans-serif;
						}
						.container {
							max-width: 600px;
							margin: 0 auto;
							padding: 20px;
							background-color: #f5f5f5;
						}
						.header {
							background-color: #3498db;
							color: #fff;
							text-align: center;
							padding: 10px;
						}
						.content {
							padding: 20px;
						}
						.reset-link {
							display: inline-block; /* Change to inline-block to make it as wide as its content */
							background-color: #3498db;
							color: #fff;
							text-decoration: none;
							padding: 10px 15px; /* Adjust padding to control button size */
							border-radius: 5px;
							margin-top: 20px;
						}
						
					</style>
				</head>
				<body>
					<div class='container'>
						<div class='header'>
							<h1>Hi, {$user_name} </h1>
						</div>
						<div class='content'>
							<p>{$new_user_acc}</p>
							<p class='reset-link'>
								Verification Code: <strong>{$code}</strong>
							</p>
							<p>{$best_regards},<br/>{$sender_name}.</p>
						</div>
					</div>
				</body>
				</html>";
    $msg1 = <<<EOL
<p>{$hi} {$user_name},</p>

<p>{$new_user_acc}</p>

<p>
{$verify_code} : {$code}<br/>
</p>


<p>
{$best_regards},<br/>
{$sender_name}
</p>
EOL;
    
    
    

    // send email from admin
    return $CI->ps_mail->send_from_admin( $to, $subject, $msg );
  }
}

if ( !function_exists( 'send_contact_us_emails' )) {

  function send_contact_us_emails( $contact_id, $shop_id)
  {

		$CI =& get_instance();
		$shop = $CI->Shop->get_one('shop0b69bc5dbd68bbd57ea13dfc5488e20a');

		$shop_name = $shop->name;
		$sender_name = $CI->Backend_config->get_one('be1')->sender_name;

		$contact_info_obj = $CI->Contact->get_one($contact_id);
		$contact_name  = $contact_info_obj->name;
		$contact_email = $contact_info_obj->email;
		$contact_phone = $contact_info_obj->phone;
		$contact_msg   = $contact_info_obj->message;

		$to = $shop->email;
		$subject = 'New Message From '.$contact_name;
		$msg = "
			<p>Hi $shop_name,</p>
			<p>You have received a new message from <strong>$contact_name</strong>.</p>
			<table style='border-collapse: collapse;'>
				<tr>
					<td colspan='2' style='padding: 5px; text-align: center; border: 1px solid #000;'><strong>Message Details</strong></td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>From</td>
					<td style='padding: 5px; border: 1px solid #000;'>$contact_name</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Phone</td>
					<td style='padding: 5px; border: 1px solid #000;'>$contact_phone</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Email</td>
					<td style='padding: 5px; border: 1px solid #000;'>$contact_email</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Message</td>
					<td colspan='2' style='padding: 5px; border: 1px solid #000;'>$contact_msg</td>
				</tr>
			</table>

			<br>

			<p>Best Regards,<br/>
				<strong>$sender_name</strong><br/>
			</p>
		";

		
    // send email from admin
	    return $CI->ps_mail->send_from_admin( $to, $subject, $msg );
  }
}

if ( !function_exists( 'send_user_register_email_without_verify' )) {

  function send_user_register_email_without_verify( $user_id, $subject = "" )
  {
     // get ci instance
    $CI =& get_instance();
    
    $user_info_obj = $CI->User->get_one($user_id);

    $user_name  = $user_info_obj->user_name;
    $user_email = $user_info_obj->user_email;
    
    

    $to = $user_email;

	$sender_name = $CI->Backend_config->get_one('be1')->sender_name;
    $hi = get_msg('hi_label');
    $user_auto_approved = get_msg('user_auto_approved');
    
    $best_regards = get_msg( 'best_regards_label' );

    $msg = <<<EOL
<p>{$hi} {$user_name},</p>

<p>{$user_auto_approved}</p>

<p>
{$best_regards},<br/>
{$sender_name}
</p>
EOL;
    
    // send email from admin
    return $CI->ps_mail->send_from_admin( $to, $subject, $msg );
  }
}

if ( !function_exists( 'send_email_to_user' )) {
	function send_email_to_user($user_id, $user_email, $user_name, $user_phone, $shop_id, $resv_id, $resv_date, $resv_time, $note) 
	{
		$CI =& get_instance();
		
		$shop = $CI->Shop->get_one($shop_id);
		
		
		$sender_email = trim($shop->sender_email);
		$sender_name  = $shop->name;
		$sender_phone  = $shop->about_phone1;
		$sender_address = $shop->address1;

		$to = $user_email;
		$subject = 'Reservation Request Submitted';
		
		$msg = " 
		<p>Hi $user_name,</p>
		<p>Please take note your reservation request has been <strong style='color: red;'>Submitted</strong>.</p>
		<table style='border-collapse: collapse;  border: 2px solid #000;'>
			<tr>
				<td colspan='2' style='padding: 5px; text-align: center; border: 1px solid #000;'><strong>Reservation Details</strong></td>
			</tr>
			<tr>
				<td style='padding: 5px; border: 1px solid #000;'>Date</td>
				<td style='padding: 5px; border: 1px solid #000;'>$resv_date</td>
			</tr>
			<tr>
				<td style='padding: 5px; border: 1px solid #000;'>Time</td>
				<td style='padding: 5px; border: 1px solid #000;'>$resv_time</td>
			</tr>
			<tr>
				<td colspan='2' style='padding: 5px; text-align: center; border: 1px solid #000;'><strong>Customer Details</strong></td>
			</tr>
			<tr>
				<td style='padding: 5px; border: 1px solid #000;'>Name</td>
				<td style='padding: 5px; border: 1px solid #000;'>$user_name</td>
			</tr>
			<tr>
				<td style='padding: 5px; border: 1px solid #000;'>Email</td>
				<td style='padding: 5px; border: 1px solid #000;'>$user_email</td>
			</tr>
			<tr>
				<td style='padding: 5px; border: 1px solid #000;'>Phone No</td>
				<td style='padding: 5px; border: 1px solid #000;'>$user_phone</td>
			</tr>
		</table>
	
		<br>
	
		<p>Best Regards,<br/>
			<strong>$sender_name</strong><br/>
			Email: $sender_email<br/>
			Phone: $sender_phone<br/>
			Address: $sender_address<br/>
		</p>
	";
				
		// send email from admin
    	return $CI->ps_mail->send_from_admin( $to, $subject, $msg ); 
	}
}

if ( !function_exists( 'send_email_status_update_to_user' )) {	
	function send_email_status_update_to_user($user_id, $user_email, $user_name, $user_phone, $shop_id, $resv_id, $resv_date, $resv_time, $note, $resv_status_title) 
	{
		// get ci instance  
    	$CI =& get_instance();
		
		$shop = $CI->Shop->get_one($shop_id);
		
		
		$sender_email = trim($shop->sender_email);
		$sender_name  = $shop->name;
		$sender_phone  = $shop->about_phone1;
		$sender_address = $shop->address1;
		
		$to = $user_email;
		$subject = 'Reservation '.$resv_status_title;
		
		$msg = "
			<p>Hi $user_name,</p>
			<p>Please take note, your reservation has been <strong style='color: red;'>$resv_status_title</strong>.</p>
			<table style='border-collapse: collapse; border: 2px solid #000;'>
				<tr>
					<td colspan='2' style='padding: 5px; text-align: center; border: 1px solid #000;'><strong>Reservation Details</strong></td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Date</td>
					<td style='padding: 5px; border: 1px solid #000;'>$resv_date</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Time</td>
					<td style='padding: 5px; border: 1px solid #000;'>$resv_time</td>
				</tr>
				<tr>
					<td colspan='2' style='padding: 5px; text-align: center; border: 1px solid #000;'><strong>Customer Details</strong></td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Name</td>
					<td style='padding: 5px; border: 1px solid #000;'>$user_name</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Email</td>
					<td style='padding: 5px; border: 1px solid #000;'>$user_email</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Phone No</td>
					<td style='padding: 5px; border: 1px solid #000;'>$user_phone</td>
				</tr>
			</table>

			<br>

			<p>Best Regards,<br/>
				<strong>$sender_name</strong><br/>
				Email: $sender_email<br/>
				Phone: $sender_phone<br/>
				Address: $sender_address<br/>
			</p>
		";

	// send email from admin
    	return $CI->ps_mail->send_from_admin( $to, $subject, $msg );
		
	}
}
	
if ( !function_exists( 'send_email_to_shop' )) {	
	function send_email_to_shop($user_id, $user_email, $user_name, $user_phone, $shop_id, $resv_id, $resv_date, $resv_time, $note) 
	{
		// get ci instance  
    	$CI =& get_instance();
    	
		$shop = $CI->Shop->get_one($shop_id);
		$shop_name = $shop->name;
		$sender_name = $CI->Backend_config->get_one('be1')->sender_name;
		$to = $shop->email;
		$subject = 'New Reservation Request';
		$msg = "
			<p>Hi $shop_name,</p>
			<p>You have a new reservation request.</p>
			<table style='border-collapse: collapse;  border: 2px solid #000;'>
				<tr>
					<td colspan='2' style='padding: 5px; text-align: center; border: 1px solid #000;'><strong>Reservation Details</strong></td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Date</td>
					<td style='padding: 5px; border: 1px solid #000;'>$resv_date</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Time</td>
					<td style='padding: 5px; border: 1px solid #000;'>$resv_time</td>
				</tr>
				<tr>
					<td colspan='2' style='padding: 5px; text-align: center; border: 1px solid #000;'><strong>Customer Details</strong></td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Name</td>
					<td style='padding: 5px; border: 1px solid #000;'>$user_name</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Email</td>
					<td style='padding: 5px; border: 1px solid #000;'>$user_email</td>
				</tr>
				<tr>
					<td style='padding: 5px; border: 1px solid #000;'>Phone No</td>
					<td style='padding: 5px; border: 1px solid #000;'>$user_phone</td>
				</tr>
			</table>

			<br>

			<p>Best Regards,<br/>
				<strong>$sender_name</strong><br/>
			</p>
		";
		
		// send email from admin
    	return $CI->ps_mail->send_from_admin( $to, $subject, $msg );
		
	}
}

// send refund email to user

if ( !function_exists( 'send_refund_order_email' )) {

    function send_refund_order_email( $transaction_header_id, $to_who = "", $subject = "", $amount )
    {
        // get ci instance
        $CI =& get_instance();

        $sender_name = $CI->Backend_config->get_one('be1')->sender_name;

        $trans_header_obj = $CI->Transactionheader->get_one($transaction_header_id);
        $user_email =  $CI->User->get_one($trans_header_obj->added_user_id)->user_email;

        if ($user_email == "") {
            $user_email = $trans_header_obj->billing_email;
        }

        $user_name =  $CI->User->get_one($trans_header_obj->added_user_id)->user_name;

        $conds['transactions_header_id'] = $trans_header_obj->id;

        $trans_details_obj = $CI->Transactiondetail->get_all_by($conds)->result();

        //For Transaction Detials
        for($i=0;$i<count($trans_details_obj);$i++)
        {
            if($trans_details_obj[$i]->product_attribute_id != "") {


                $att_name_info  = explode("#", $trans_details_obj[$i]->product_attribute_name);

                $att_price_info = explode("#", $trans_details_obj[$i]->product_attribute_price);

                $att_info_str = "";
                $att_flag = 0;
                if( count($att_name_info[0]) > 0 ) {

                    //loop attribute info
                    for($k = 0; $k < count($att_name_info); $k++) {

                        if($att_name_info[$k] != "") {
                            $att_flag = 1;
                            $att_info_str .= $att_name_info[$k] . " : " . $att_price_info[$k] . "(". $trans_currency ."),";

                        }
                    }


                } else {
                    $att_info_str = "";
                }

                $att_info_str = rtrim($att_info_str, ",");




                $order_items .= $i + 1 .". " . $trans_details_obj[$i]->product_name .
                    " (". get_msg('price_label') .   $trans_details_obj[$i]->original_price  . html_entity_decode($trans_currency) .
                    "," . get_msg('qty_label') ." : " . $trans_details_obj[$i]->qty . ",". get_msg('unit_label') ." : " . $trans_details_obj[$i]->product_measurement .' ' . $trans_details_obj[$i]->product_unit . ") {". $att_info_str ."}<br>";





            } else {

                $order_items .= $i + 1 .". " . $trans_details_obj[$i]->product_name .
                    " (". get_msg('price_label') .   $trans_details_obj[$i]->original_price  . html_entity_decode($trans_currency) .
                    "," . get_msg('qty_label') ." : " . $trans_details_obj[$i]->qty . ",". get_msg('unit_label') ." : " . $trans_details_obj[$i]->product_measurement .' ' . $trans_details_obj[$i]->product_unit . ") <br>";

            }



        }


        //for msg label
        $hi = get_msg('hi_label');
        $order_refund_info = get_msg('order_refund_info');
        $trans_code = get_msg('trans_code');
        $refunded_amount = get_msg('refunded_amount');
        $prd_detail_info = get_msg('prd_detail_info');
        $best_regards = get_msg( 'best_regards_label' );

        $to = $user_email;

        $msg = <<<EOL
<p>{$hi} {$user_name},</p>

<p>{$order_refund_info}</p>

<p>
{$trans_code} : {$trans_header_obj->trans_code}<br/>
</p>

<p>
{$refunded_amount} : {$amount}<br/>
</p>

<p>{$prd_detail_info} :</p>
{$order_items}            

<p>
{$best_regards},<br/>
{$sender_name}
</p>
EOL;

        // send email from admin
        return $CI->ps_mail->send_from_admin( $to, $subject, $msg );
    }
}