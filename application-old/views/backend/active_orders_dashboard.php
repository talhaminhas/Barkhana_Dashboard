<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" class="table-responsive rounded-table animated fadeInRight " style="padding-top: 10px; margin-bottom 10px;">
	<input type="hidden" name="noti_time" id="noti_time" value="<?php echo $this->Backend_config->get_one('be1')->transaction_noti_sound_refresh_time ?>">
    <input type="hidden" name="page_time" id="page_time" value="<?php echo $this->Backend_config->get_one('be1')->transaction_page_refresh_time ?>">
  		
	<!-- New Orders  -->

	<legend class="text-center ml-3 mt-3 mb-5 font-weight-bold"><?php echo get_msg('new_orders')?></legend>
			
	<table class="table table-bordered m-0 table-striped text-center">
		<?php 
			$selected_shop_id = $this->session->userdata('selected_shop_id');
          	$shop_id = $selected_shop_id['shop_id'];
		?>
		<tr >
			<th class="align-middle "><?php echo get_msg('no'); ?></th>
			<th class="align-middle">Order Number</th>
			<th class="align-middle"><?php echo get_msg('total_amount_label') ?></th>
			<th class="align-middle">Time</th>
			<th class="align-middle"><span class="th-title">Customer Details</span></th>
			<th class="align-middle">Order Type</th>

			<?php if ( $this->ps_auth->has_access( EDIT )): ?>
				
				<th class="align-middle"><span class="th-title"><?php echo get_msg('accept_order_label')?></span></th>
			
			<?php endif; ?>


			<?php if ( $this->ps_auth->has_access( EDIT )): ?>
				
				<th class="align-middle"><span class="th-title"><?php echo get_msg('reject_order_label')?></span></th>
			
			<?php endif; ?>
			
			<?php if ( $this->ps_auth->has_access( EDIT )): ?>
				
				<th class="align-middle"><span class="th-title"><?php echo get_msg('order_detail_label')?></span></th>
			
			<?php endif; ?>

		</tr>

		<?php $count = 0; ?>

		<?php 
			$conds['shop_id'] = $shop_id;
          	$conds['trans_status_id'] = 'trans_sts29a4b0cd2fa6ae0449e47e9568320f3a';

          	$new_orders = $this->Transactionheader->get_all_by($conds)->result();

			if ( !empty( $new_orders ) && count( $new_orders ) > 0 ): ?>

				<?php foreach($new_orders as $new_order): ?>
					
					<tr>
						<td class="align-middle"><?php echo ++$count;?></td>
						<td class="align-middle"><?php echo $new_order->trans_code;?></td>
						<td class="align-middle">
							<?php
								$total_amount = $this->Shop->get_one($shop_id)->currency_symbol . number_format($new_order->total_item_amount, 2);
								echo $total_amount;
							?>
						</td>
						<td class="align-middle">
							<?php
								$added_date = new DateTime($new_order->added_date);
								echo $added_date->format('H:i'); 
							?>
						</td>
						<td class="align-middle">
							<?php echo $this->User->get_one($new_order->user_id)->user_name; ?>
							<br>
							<?php echo $this->User->get_one($new_order->user_id)->user_phone; ?>
						</td>
						<td class="align-middle">
							<?php
								$pick_at_shop = $new_order->pick_at_shop;
								if ($pick_at_shop == "0") {
									echo '<span class="order-delivery">Delivery</span>';
								} else {
									echo '<span class="order-collection">Collection</span>';
								}
							?>
						</td>

						<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					
							<td class="align-middle">
								<div class='d-flex align-items-center justify-content-center'>
									<a class="btn btn-sm btn-success fixed-size-btn" href="<?php echo $module_site_url . "/accept_order/" . $new_order->id;?>">
										<?php echo get_msg('btn_accept') ?>

									</a>
								</div>
							</td>
						
						<?php endif; ?>

						<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					
							<td class="align-middle">
								<div class='d-flex align-items-center justify-content-center'>
									<a class="btn btn-sm btn-danger fixed-size-btn" href="<?php echo $module_site_url . "/reject_order/" . $new_order->id;?>">
										<?php echo get_msg('btn_reject') ?>

									</a>
								</div>
							</td>
						
						<?php endif; ?>

						<?php if ( $this->ps_auth->has_access( EDIT )): ?>
							
							<td class="align-middle">
								<div class='d-flex align-items-center justify-content-center'>
									<a class="btn btn-sm btn-primary fixed-size-btn" href="<?php echo $module_site_url . "/detail/" . $new_order->id;?>">
										<?php echo get_msg('btn_detail') ?>

									</a>
								</div>
							</td>
						
						
						<?php endif; ?>

					</tr>

				<?php endforeach; ?>

			<?php else: ?>
					
				<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

			<?php endif; ?>		

	</table>

	<br>
	

	<!-- Ongoing Orders  -->

	<legend class="ml-3 mt-3 mb-5 font-weight-bold text-center"><?php echo get_msg('ongoing_orders')?></legend>
			
		<table class="table table-bordered m-0 table-striped text-center align-middle" >
			<?php 
				$selected_shop_id = $this->session->userdata('selected_shop_id');
	          	$shop_id = $selected_shop_id['shop_id'];
			?>
			<tr>
				<th class="align-middle"><?php echo get_msg('no'); ?></th>
				<th class="align-middle"><?php echo get_msg('trans_code'); ?></th>
				<th class="align-middle"><?php echo get_msg('total_amount_label') ?></th>
				<th class="align-middle">Time</th>
				<th class="align-middle"><span class="th-title">Customer Details</span></th>
				<th class="align-middle">Order Type</th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle"><span class="th-title">Delivery Boy</span></th>
				<?php endif; ?>
				<th class="align-middle"><?php echo get_msg('order_status_label'); ?></th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle"><span class="th-title"><?php echo get_msg('order_detail_label')?></span></th>
				<?php endif; ?>
			</tr>

			<?php $count = 0; ?>

			<?php 
				$conds1['shop_id'] = $shop_id;

	          	$ongoing_order = $this->Transactionheader->get_all_by($conds1)->result();


				if ( !empty( $ongoing_order ) && count( $ongoing_order ) > 0 ): ?>

					<?php foreach($ongoing_order as $ongoing): 

						$start_stage = $this->Transactionstatus->get_one($ongoing->trans_status_id)->start_stage;
						$final_stage = $this->Transactionstatus->get_one($ongoing->trans_status_id)->final_stage;
						$is_optional = $this->Transactionstatus->get_one($ongoing->trans_status_id)->is_optional;
						$title = $this->Transactionstatus->get_one($ongoing->trans_status_id)->title;

					?>

						<?php if( $start_stage == "0" && $final_stage == "0" && $is_optional == "0") : ?>
						
							<tr >
								<td class="align-middle"><?php echo ++$count; ?></td>
								<td class="align-middle"><?php echo $ongoing->trans_code; ?></td>
								<td class="align-middle">
									<?php
										$total_amount = $this->Shop->get_one($shop_id)->currency_symbol . number_format($ongoing->total_item_amount, 2);
										echo $total_amount;
									?>
								</td>
								<td class="align-middle">
									<?php
										$added_date = new DateTime($ongoing->added_date);
										echo $added_date->format('H:i'); 
									?>
								</td>
								
								<td class="align-middle">
										<?php echo $this->User->get_one($ongoing->user_id)->user_name; ?>
										<br>
										<?php echo $this->User->get_one($ongoing->user_id)->user_phone; ?>
									</td>
									<td class="align-middle">
										<?php
											$pick_at_shop = $ongoing->pick_at_shop;

											if ($pick_at_shop == "0") {
												echo '<span class="order-delivery">Delivery</span>';
											} else {
												echo '<span class="order-collection">Collection</span>';
											}
										?>
									</td>
									</td>
									<?php if ($this->ps_auth->has_access(EDIT)): ?>
										<td class="align-middle text-center">
									<?php if (
										($ongoing->delivery_boy_id == "" || $ongoing->delivery_boy_id == "0") &&
										$this->Shop->get_one($shop_id)->deli_manual_assign == 1 &&
										$ongoing->pick_at_shop == "0"
									): ?>
										<div class='d-flex align-items-center justify-content-center'>
											<a href='#' class='btn fixed-size-btn btn-warning btn-assign' data-toggle="modal" data-target="#assignDeliboyModal" id="<?php echo $ongoing->id; ?>">
												<span>Assign<br>Delivery Boy</span>
											</a>
										</div>
									<?php elseif ($ongoing->pick_at_shop == "1"): ?>
										-
									<?php else: ?>
										<?php echo $this->User->get_one($ongoing->delivery_boy_id)->user_name; ?>
										<br>
										<?php echo $this->User->get_one($ongoing->delivery_boy_id)->user_phone; ?>
									<?php endif; ?>
								</td>

								<?php endif; ?>

								<td class="align-middle text-center">
									<?php if ($this->Transactionstatus->get_one($ongoing->trans_status_id)->ordering == "2"): ?>
										<div class='d-flex align-items-center justify-content-center'>
											<a class="btn btn-secondary fixed-size-btn" href="<?php echo $module_site_url . "/order_ready/" . $ongoing->id; ?>">
												<span>Mark as<br>Ready</span>
											</a>
										</div>
								
									<?php elseif ($this->Transactionstatus->get_one($ongoing->trans_status_id)->ordering == "3" && $ongoing->pick_at_shop == "1"): ?>
										<div class='d-flex align-items-center justify-content-center'>
											<a class="btn btn-success fixed-size-btn" href="<?php echo $module_site_url . "/order_completed/" . $ongoing->id; ?>">
												<span>Mark as<br>Collected</span>
											</a>
										<div>
									<?php elseif ($this->Transactionstatus->get_one($ongoing->trans_status_id)->ordering == "3" && $ongoing->pick_at_shop == "0"): ?>
										<span>Waiting For Driver</span>
									<?php else: ?>
										<?php echo $title; ?>
									<?php endif; ?>
								</td>

								<?php if ($this->ps_auth->has_access(EDIT)): ?>
									<!-- Additional columns if needed -->
								<?php endif; ?>

								<?php if ($this->ps_auth->has_access(EDIT)): ?>
									<td class="align-middle text-center">
										<div class='d-flex align-items-center justify-content-center'>
											<a class="btn fixed-size-btn btn-primary" href="<?php echo $module_site_url . "/detail/" . $ongoing->id; ?>">
												<?php echo get_msg('btn_detail') ?>
											</a>
										</div>
									</td>
								<?php endif; ?>

								
							</tr>

						<?php endif; ?>	

					<?php endforeach; ?>

				<?php else: ?>
						
					<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

				<?php endif; ?>		

		</table>
		<legend class="ml-3 mt-3 mb-5 font-weight-bold text-center"></legend>
</div>	
<script>
	    // Initial call i make so user do not wait 2 seconds for messages to show
		function refresh() {
        $.ajax({
            method: 'GET',
            dataType: 'JSON',
            url:  '<?php echo $module_site_url . '/get_all_new_orders/';?>',
            cache: false,
            success:function(msg){

                if ( msg == true) {
                    var audio = new Audio("<?php echo base_url('assets/backend/audio.mp3'); ?>");
                    audio.play();

                    //setTimeout("location.reload(true);", 3000);

                }


            }
        });

    }
    //autorefresh sound every 2 seconds
    /*var noti_time = document.getElementById("noti_time").value ;
    setInterval(function () {
        refresh();
    }, noti_time);*/

    function resetRefreshTimer() {
    clearTimeout(refreshTimer);
    
    var page_time = document.getElementById("page_time").value;
    
    refreshTimer = window.setTimeout(function () {
        window.location.reload();
    }, page_time);
}
    /*var page_time = document.getElementById("page_time").value ;
    window.setTimeout(function () {
        window.location.reload();
    }, page_time);*/


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
<style>
  .fixed-size-btn {
    width: 100%; 
    height: 60px; 
	display: flex;
    align-items: center;
    justify-content: center;
	border-radius: 5px;
  }
  .rounded-corners{
	border-radius: 5px; 
  }
  .order-collection{
	width: 100%; 
    height: 60px;
	font-weight: bold; 
	color: #fc3903; 
	border: 2px solid #fc3903; 
	border-radius: 5px; 
	padding: 5px;
	display: flex;
    align-items: center;
    justify-content: center;
	border-radius: 5px;
  }
  .order-delivery{
	width: 100%; 
    height: 60px;
	font-weight: bold; 
	color: #9003fc;
	border: 2px solid #9003fc; 
	border-radius: 5px; 
	display: flex;
    align-items: center;
    justify-content: center;
	border-radius: 5px;
  }
</style>
<?php
	// Assign Delivery Boy Modal
	$data = array(
		'title' => get_msg( 'assign_deliboy' )
	);
	
	$this->load->view( $template_path .'/components/assign_deliboy_modal', $data );
?>