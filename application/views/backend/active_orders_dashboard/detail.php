<style>
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .fixed-size-btn {
    width: 100%; 
    height: 60px; 
	display: flex;
    align-items: center;
    justify-content: center;
  }

    .invoice-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .cust-info-cell {
        background-color: #f2f2f2;
        text-align: center;
    }

    .label-column {
        font-weight: bold;
    }
    .transaction-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .transaction-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .table-header {
        font-weight: bold;
        background-color: #f2f2f2;
        text-align: center;
    }

    .select{
        width:100%;
        height: 40px;
        text-align: center;
    }
    .order-collection{
        width: 100%; 
        height: 40px;
        font-weight: bold; 
        color: #fc3903; 
        border: 2px solid #fc3903; 
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .order-delivery{
        width: 100%; 
        height: 40px;
        font-weight: bold; 
        color: #9003fc;
        border: 2px solid #9003fc; 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .subtotal{
        font-weight: bold; 
        color: red;
        font-size: 25px;
    }
    .discount-label{
        font-weight: bold; 
        color: red;
    }
    .addon-container{
        border: 1px solid #000000; 
    }
    .height-fill{
        height: 100%;
    }
</style>
<script>
$('.btn-assign').click(function(){
		

		// get id and links
		var id = $(this).attr('id');
		var formLink = $('#assign-deliboy-form').attr('action');

		// modify link with id
		$('#assign-deliboy-form').attr( 'action', formLink + id );
		// Reset refresh timer to 0
		//resetRefreshTimer();
	});
</script>
<div class="invoice p-3 mb-3 shadow-sm rounded">
  	<!-- title row -->
  	<div class="row">
      <div class="col-12">
    <table class="table table-bordered">
        <tr>
            <td class="table-header" colspan="6">
                <h4><b>Order Detail</b></h4>
            </td>
        </tr>
        <tr>
            <td class="label-column text-center align-middle">Order Number</td>
            <td class="text-center align-middle"><?php echo $transaction->trans_code; ?></td>
            <td class="label-column text-center align-middle">Date</td>
            <td class="text-center align-middle"><?php echo date('Y-m-d', strtotime($transaction->added_date)); ?></td>
            <td class="label-column text-center align-middle">Time</td>
            <td class="text-center align-middle"><?php echo date('H:i', strtotime($transaction->added_date)); ?></td>
        </tr>
    </table>
</div>
    <!-- /.col -->
  	</div>
  <!-- info row -->
	<div class="row invoice-info">

    <div class="col-sm-4 invoice-col">
        <div style="height: 100%; padding-bottom: 15px;">
            <div class="table-responsive" style="height: 100%; ">
                <table class="table-bordered" style="height: 100%; width:100%;">
                    <tr>
                        <td class="cust-info-cell" colspan="2">
                            <b><?php echo get_msg('cust_info'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-column text-center align-middle"><?php echo get_msg('name_label'); ?></td>
                        <td class="align-middle text-center"><?php echo $transaction->contact_name; ?></td>
                    </tr>
                    <tr>
                        <td class="label-column text-center align-middle"><?php echo get_msg('email_label'); ?></td>
                        <td class="align-middle text-center"><?php echo $transaction->contact_email; ?></td>
                    </tr>
                    <tr>
                        <td class="label-column text-center align-middle"><?php echo get_msg('phone_label'); ?></td>
                        <td class="align-middle text-center"><?php echo $transaction->contact_phone; ?></td>
                    </tr>
                    <tr>
                        <td class="label-column text-center align-middle"><?php echo get_msg('address_label'); ?></td>
                        <td class="align-middle text-center"><?php echo $transaction->contact_address; ?></td>
                    </tr>
                </table>
            </div>
                <?php if ($transaction->user_id == '-1'): ?>
                    <span class="text-danger"><?php echo get_msg("deleted_user"); ?></span>
                <?php endif; ?>
            </div>
        </div>

		<!-- /.col -->
		<div class="col-sm-4 invoice-col">
            <div style="height: 100%; padding-bottom: 15px;">
                <table class="table table-bordered" style="height: 100%;">
                    <tr>
                        <td class="table-header" >
                            <?php echo get_msg('cust_loc'); ?>
                        </td>
                    </tr>
                    <tr style="width: 100%; height: 100%;">
                        <td class="label-column">
                            <div id="transaction_map" style="width: 100%; height: 100%"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
		<div class="col-sm-4 invoice-col">
		  	
				<?php
					$attributes = array('class' => 'form-inline');
						echo form_open('/admin/active_orders_dashboard/update', $attributes);
				
				?>
                    <?php if ($transaction->trans_status_id == 'trans_sts47fe98346e0f80d844d307981eaef7ec') { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order Status</th>
                                    <td><select  name="trans_status_id" id="trans_status_id" disabled>

                                            <option value="0"><?php echo get_msg('select_status'); ?></option>
                                            <?php
                                            $conds['is_optional'] = 0;
                                            $status = $this->Transactionstatus->get_all_by($conds);
                                            $add_status = false;
                                            foreach ($status->result() as $status)
                                            {
                                                if($add_status == false && $transaction->trans_status_id == $status->id)
                                                {   
                                                    $add_status = true;
                                                }
                                                if($add_status)
                                                {
                                                    echo "<option value='".$status->id."'";
                                                    if($transaction->trans_status_id == $status->id)
                                                    {
                                                        echo " selected ";
                                                    }
                                                    echo ">".$status->title."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo get_msg('payment_status_label'); ?>s:</th>
                                    <td><select  name="payment_status_id" id="payment_status_id" disabled>
                                            <option value="0"><?php echo get_msg('select_pay_status'); ?></option>
                                            <?php
                                            $status = $this->Paymentstatus->get_all();
                                            foreach ($status->result() as $status)
                                            {
                                                echo "<option value='".$status->id."'";
                                                if($transaction->payment_status_id == $status->id)
                                                {
                                                    echo " selected ";
                                                }
                                                echo ">".$status->title."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php if($transaction->pick_at_shop != 1) { ?>
                                    <tr>
                                        <th><?php echo get_msg('deliboy_label'); ?>:</th>
                                        <td><select  name="delivery_boy_id" id="delivery_boy_id" disabled>
                                                <option value="0"><?php echo get_msg('select_deli_boy'); ?></option>
                                                <?php
                                                $conds['role_id'] = 5;
                                                $conds['status']= 1;
                                                $deli_boys = $this->User->get_all_by($conds);
                                                foreach ($deli_boys->result() as $boy)
                                                {
                                                    echo "<option value='".$boy->user_id."'";
                                                    if($transaction->delivery_boy_id == $boy->user_id)
                                                    {
                                                        echo " selected ";
                                                    }
                                                    echo ">".$boy->user_name."</option>";
                                                }
                                                ?>
                                            </select>
                                            <?php if($transaction->delivery_boy_id == '-1'): ?>
    										<br/>
    										<span class="text-danger"><?php echo get_msg("deliboy_trans_deleted"); ?></span>
    										<?php endif; ?>
                                            </td>
                                    </tr>
                                <?php } ?>
                            </table>

                        </div>

                    <?php } else { ?>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                            <tr>
                                    <th class="text-center align-middle">Order Type</th>
                                    <td>
                                        <?php
                                            $pick_at_shop = $transaction->pick_at_shop;

                                            if ($pick_at_shop == "0") {
                                                echo '<span class="order-delivery">Delivery</span>';
                                            } else {
                                                echo '<span class="order-collection">Collection</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">Order Status</th>
                                    <td><select class="select" name="trans_status_id" id="trans_status_id">
                                            <!--<option value="0"><?php echo get_msg('select_status'); ?></option>-->
                                            <?php
                                            $conds['is_optional'] = 0;
                                            $status = $this->Transactionstatus->get_all_by($conds);
                                            $add_status = false;
                                            foreach ($status->result() as $status)
                                            {
                                                if($add_status == false && $transaction->trans_status_id == $status->id)
                                                {   
                                                    $add_status = true;
                                                }
                                                if($add_status && ($transaction->pick_at_shop != "1" || $status->ordering != "4"))
                                                {
                                                   
                                                    echo "<option class='option' value='".$status->id."'";
                                                    if($transaction->trans_status_id == $status->id)
                                                    {
                                                        echo " selected ";
                                                    }
                                                    echo ">".$status->title."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <!--<tr>
                                    <th><?php echo get_msg('payment_status_label'); ?>:</th>
                                    <td><select  name="payment_status_id" id="payment_status_id">
                                            <option value="0"><?php echo get_msg('select_pay_status'); ?></option>
                                            <?php
                                            $status = $this->Paymentstatus->get_all();
                                            foreach ($status->result() as $status)
                                            {
                                                echo "<option value='".$status->id."'";
                                                if($transaction->payment_status_id == $status->id)
                                                {
                                                    echo " selected ";
                                                }
                                                echo ">".$status->title."</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>-->
                                <?php if($transaction->pick_at_shop != 1) { ?>
                                    <tr>
                                        <th class="text-center align-middle"><?php echo get_msg('deliboy_label'); ?></th>
                                        <td><select class="select" name="delivery_boy_id" id="delivery_boy_id">
                                                <option value="0"><?php echo get_msg('select_deli_boy'); ?></option>
                                                <?php
                                                $conds['role_id'] = 5;
                                                $conds['status']= 1;
                                                $deli_boys = $this->User->get_all_by($conds);
                                                foreach ($deli_boys->result() as $boy)
                                                {
                                                    echo "<option value='".$boy->user_id."'";
                                                    if($transaction->delivery_boy_id == $boy->user_id)
                                                    {
                                                        echo " selected ";
                                                    }
                                                    echo ">".$boy->user_name."</option>";
                                                }
                                                ?>
                                            </select>
                                            <?php if($transaction->delivery_boy_id == '-1'): ?>
    										<br/>
    										<span class="text-danger"><?php echo get_msg("deliboy_trans_deleted"); ?></span>
    										<?php endif; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr >
                                    <td colspan="2">
                                        <input type="hidden" name="trans_header_id" value=<?php  echo $transaction->id;  ?>>
                                        <button type="submit" class="btn fixed-size-btn btn-primary <?php echo $langauge_class; ?>" ><?php echo get_msg('btn_update')?></button>
                                        <?php echo form_close(); ?>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>

                    <?php } ?>
		
		  		<!--<b><?php echo get_msg('account_label'); ?>:</b> <?php echo $transaction->sub_total_amount ." ". $transaction->currency_short_form; ?>-->		
		</div>	
	</div>

	<div class="row">
		<div class="col-12 table-responsive">
		  <table class="table table-bordered">
            <?php $count = 0; ?>
            <tr>
                <td class="cust-info-cell" colspan="6">
                    <b>Items Detail</b>
                </td>
            </tr>
			    <tr>
                    <th  class="text-center align-middle">No</th>
			      	<th  class="">Name</th>
                      <th class="text-center align-middle">Discount</th>
					<th class="text-center align-middle">Unit Price</th>
					<!-- <th><?php echo get_msg('Prd_dis_price'); ?></th> -->
					<th class="text-center align-middle"><?php echo get_msg('Prd_qty'); ?></th>
					
					<th class="text-center align-middle"><?php echo get_msg('Prd_amt'); ?></th>
			    </tr>
		    	<?php 
					$conds['transactions_header_id'] = $transaction->id;
					$all_detail =  $this->Transactiondetail->get_all_by( $conds );
					
					foreach($all_detail->result() as $transaction_detail):

				?>
				<tr>
                    <td class="text-center align-middle"><?php echo ++$count; ?></td>
					<td class="">
						<?php 

						
						$att_name_info  = explode("#", $transaction_detail->product_customized_name);
						$att_price_info = explode("#", $transaction_detail->product_customized_price);

						$addon_name_info  = explode("#", $transaction_detail->product_addon_name);
						$addon_price_info = explode("#", $transaction_detail->product_addon_price);


						$att_info_str = "";
						$att_flag = 0;
						if( $att_name_info[0] != '' ) {

							//loop attribute info
							for($k = 0; $k < count($att_name_info); $k++) {
								
								if($att_name_info[$k] != "") {
									$att_flag = 1;
									$att_info_str .= $att_name_info[$k] . ": ". $transaction->currency_symbol  . $att_price_info[$k] . ", ";

								}
							}


						} else {
							$att_info_str = "";
						}

						

						$att_info_str = rtrim($att_info_str, ","); 


						///addon

						$addon_info_str = "";
						$addon_flag = 0;
						if( $addon_name_info[0] != '' ) {

							//loop attribute info
							for ($k = 0; $k < count($addon_name_info); $k++) : 
                                if ($addon_name_info[$k] != "") :
                                    
                                    $addon_flag = 1;
                                    $addon_info_str .= '<tr><td class="text-center">' . $addon_name_info[$k] . '</td><td class="text-center">+' . $transaction->currency_symbol . number_format($addon_price_info[$k], 2) . '</tr></td> ';
                                  
                               endif; 
                            endfor; 
                            
						} 
                        else {
							$addon_info_str = "";
						}

						

						$addon_info_str = rtrim($addon_info_str, ","); 

						///end addon


						if( $att_flag == 1 || $addon_flag == 1 ) {

							echo '<table style="width:100%"><tr><th class=" text-center" >'.$transaction_detail->product_name .
                            '<th class="text-center">'.$transaction->currency_symbol . 
                            number_format($transaction_detail->price + $transaction_detail->discount_amount, 2) .'</th>'.
                            '</th></tr>' . $addon_info_str  .
                            '<tr><th class="table-header text-center">Total</th><th class="table-header text-center" >'.
                            $transaction->currency_symbol . number_format($transaction_detail->original_price, 2).'</th></tr></table>'; 

						} else {

							echo $transaction_detail->product_name .'<br>';

						}

						if ($transaction_detail->product_color_id != "") {

							echo "Color:";

							$color_value =  $this->Color->get_one($transaction_detail->product_color_id)->color_value . '}';
							

							} 

						?>

						<div style="background-color:<?php echo  $this->Color->get_one($transaction_detail->product_color_id)->color_value ; ?>; width: 20px; height: 20px; margin-top: -20px; margin-left: 50px;"> 
						</div>
						


					</td>
                    <?php
                    if($transaction_detail->discount_amount == 0)
					    echo('<td class="text-center align-middle">-</td>');
                    else
                        echo('<td class="text-center align-middle discount-label">-' .$transaction->currency_symbol . number_format($transaction_detail->discount_amount, 2) .  " (" .$transaction_detail->discount_percent . '% off)</td>');
                    ?>
					<td class="text-center align-middle "><?php 
                    if($transaction_detail->discount_amount != 0)
                        echo '<span class="discount-label" style="text-decoration: line-through;">'.$transaction->currency_symbol. 
                        number_format($transaction_detail->original_price, 2).'</span> ' ;
                    echo  $transaction->currency_symbol. 
                    number_format($transaction_detail->original_price - $transaction_detail->discount_amount, 2) ; ?></td>
					<td class="text-center align-middle"><?php echo $transaction_detail->qty ?></td>
                    
					<td class="text-center align-middle">
						<?php 

							echo $transaction->currency_symbol. number_format($transaction_detail->qty * ($transaction_detail->original_price - $transaction_detail->discount_amount), 2)  ; 
						?>
					</td>
				</tr>

					<?php endforeach; ?>
		  </table>
		</div>
	<!-- /.col -->
	</div>

	<div class="row">
        <!-- accepted payments column -->
       
        <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered">

              <tr>
                <th class="text-center">Coupon Discount</th>
                <?php if($transaction->coupon_discount_amount == 0)
                    echo('<td class="text-center">-</td>');
                else 
                    echo('<td class="text-center">-'.$transaction->currency_symbol. number_format($transaction->coupon_discount_amount, 2).'</td>');
                ?>
                <th class = "text-center align-middle" rowspan = "3">
                    <span class = "">Sub Total</span>
                </th>
                <th class = "text-center align-middle subtotal" rowspan = "3">
                	
                	<?php 

                	//balance_amount = total_item_amount - coupon_discont + (overall_tax + shipping_cost + shipping_tax (based on shipping cost)) 

                	echo  $transaction->currency_symbol.
                    number_format(($transaction->sub_total_amount + ($transaction->tax_amount + $transaction->shipping_amount + 
                    ($transaction->shipping_amount * $transaction->shipping_tax_percent))),2 );  
                	
                	?>
                </th>
              </tr>	

              <tr>
                <th class="text-center" style="width:50%">Item Sub total</th>
                <td class="text-center"><?php echo $transaction->currency_symbol. number_format($transaction->sub_total_amount, 2); ?></td>
              </tr>

              
              <tr>
                <th class="text-center">Delivery Cost</th>
                
                <?php if($transaction->shipping_amount == 0)
                    echo('<td class="text-center">-</td>');
                else 
                    echo('<td class="text-center">+'.$transaction->currency_symbol.number_format($transaction->shipping_amount, 2).'</td>');
                ?>
              </tr>
            </table>
          </div>
        <!-- /.col -->
        <div class="col-5">
    
        </div>
        <!-- /.col -->
    </div>
</div>

<script>
    <?php
        if (isset($transaction)) {
            $lat = $transaction->trans_lat;
            $lng = $transaction->trans_lng;
    ?>
            var trans_map = L.map('transaction_map').setView([<?php echo $lat;?>, <?php echo $lng;?>], 15);
    <?php
        } else {
    ?>
            var trans_map = L.map('transaction_map').setView([0, 0], 5);
    <?php
        }
    ?>

    const trans_attribution =
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
    const trans_tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    const trans_tiles = L.tileLayer(trans_tileUrl, { trans_attribution });
    trans_tiles.addTo(trans_map);
    <?php if(isset($transaction)) {?>
        var trans_marker = new L.Marker(new L.LatLng(<?php echo $lat;?>, <?php echo $lng;?>));
        trans_map.addLayer(trans_marker);
        // results = L.marker([<?php echo $lat;?>, <?php echo $lng;?>]).addTo(mymap);

    <?php } else { ?>
        var trans_marker = new L.Marker(new L.LatLng(0, 0));
        //mymap.addLayer(marker2);
    <?php } ?>
    var trans_searchControl = L.esri.Geocoding.geosearch().addTo(trans_map);
    var results = L.layerGroup().addTo(trans_map);

    trans_searchControl.on('results',function(data){
        results.clearLayers();

        for(var i= data.results.length -1; i>=0; i--) {
            trans_map.removeLayer(trans_marker);
            results.addLayer(L.marker(data.results[i].latlng));
            var trans_search_str = data.results[i].latlng.toString();
            var trans_search_res = trans_search_str.substring(trans_search_str.indexOf("(") + 1, trans_search_str.indexOf(")"));
            var trans_searchArr = new Array();
            trans_searchArr = trans_search_res.split(",");

            document.getElementById("lat").value = trans_searchArr[0].toString();
            document.getElementById("lng").value = trans_searchArr[1].toString(); 
            
        }
    })
    var popup = L.popup();

    function onMapClick(e) {

        var trans = e.latlng.toString();
        var trans_res = trans.substring(trans.indexOf("(") + 1, trans.indexOf(")"));
        trans_map.removeLayer(trans_marker);
        results.clearLayers();
        results.addLayer(L.marker(e.latlng));   

        var trans_tmpArr = new Array();
        trans_tmpArr = trans_res.split(",");

        document.getElementById("lat").value = trans_tmpArr[0].toString(); 
        document.getElementById("lng").value = trans_tmpArr[1].toString();
    }

    trans_map.on('click', onMapClick);
</script>