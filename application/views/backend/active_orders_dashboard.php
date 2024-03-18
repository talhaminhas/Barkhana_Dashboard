<style>
	body{
		margin-top: -65px;
	}
	.tab {
		display: none;
	}

	.tab-button {
    border: 0px solid #f1f1f1;
    border-radius: 5px;
    background-color: #f1f1f1;
    display: inline-block;
    text-align: center;
    width: 130px;
    padding: 5px ;
	margin-right: 5px;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5); /* Add shadow effect */
}


.tab-number {
    font-size: 25px; 
	
}


.active {
    color: var(--main-text-color);
    border: 0px solid var(--main-color);
    border-radius: 5px;
    background-color: var(--main-color);
    box-shadow: none;
	outline: 2px solid var(--main-color)
}


	/* Style for tab content */
	.tab-content {
		display: none;
		padding: 15px 0px 0px 0px;
	}
      #ongoing-orders-table {
        border-collapse: collapse;
        width: 100%;
    }

    #ongoing-orders-table tr {
        border-bottom: none;
    }

    
  .rounded-corners{
  }
    .banner{
		//border: 3px solid rgba(255, 0, 0, 0.2);
		background-color: var(--main-color);
		color: red;
		border-radius: 5px;
		font-size: 15px;
		padding:10px;
		//box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
		margin-bottom: 4px;
	}
</style>
<?php
		date_default_timezone_set('Europe/London');
		//new orders
		$app_config = $this->Mobile_setting->get_one('mb1');
		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];
		$conds['shop_id'] = $shop_id;
		$conds['trans_status_id'] = 'trans_sts29a4b0cd2fa6ae0449e47e9568320f3a';
		$new_orders = $this->Transactionheader->get_all_by($conds)->result();
		
		//ongoing orders
		$conds1['shop_id'] = $shop_id;
		$this->db->order_by('delivery_pickup_time', 'ASC');
		$ongoing_orders = $this->Transactionheader->get_all_by($conds1)->result();
		$upcoming_orders = [];
		$preparing_orders = [];
		if (!empty($ongoing_orders) && count($ongoing_orders) > 0):
			
			date_default_timezone_set('Europe/London');
			$timezone = new DateTimeZone('Europe/London');
			$currentTimestamp = new DateTime('now', $timezone);
			$currentDateTime = strtotime($currentTimestamp->format('Y-m-d H:i:s'));

			foreach ($ongoing_orders as $ongoing_order):
				$start_stage = $this->Transactionstatus->get_one($ongoing_order->trans_status_id)->start_stage;
				$final_stage = $this->Transactionstatus->get_one($ongoing_order->trans_status_id)->final_stage;
				$is_optional = $this->Transactionstatus->get_one($ongoing_order->trans_status_id)->is_optional;
				$orderTimestamp = strtotime($ongoing_order->delivery_pickup_date.' '.$ongoing_order->delivery_pickup_time);
					
				if( $start_stage == "0" && $final_stage == "0" && $is_optional == "0") {
					
					if ($orderTimestamp <= ($currentDateTime + $app_config->default_order_time*60)) {
						
						$preparing_orders[] = $ongoing_order;
					} 
					else {
						$upcoming_orders[] = $ongoing_order;
					}
				}
			endforeach;
		endif;
		//completed orders(today)
		$conds['shop_id'] = $shop_id;
		$conds['trans_status_id'] = 'trans_sts159cbfb84410ebea91919234532885ec';
		$this->db->where('DATE(updated_date)', 'CURDATE()', FALSE);
		$completed_orders = $this->Transactionheader->get_all_by($conds)->result();

		//rejected orders(today)
		$conds['shop_id'] = $shop_id;
		$conds['trans_status_id'] = 'trans_stsef071eefcc46df677fe52e7afe414199';
		$this->db->where('DATE(updated_date)', 'CURDATE()', FALSE);
		$rejected_orders = $this->Transactionheader->get_all_by($conds)->result();

		//shop open or close ?
		$schedule_conds['shop_id'] = $shop_id;
		$schedule_conds['days_of_week'] = date('l');
		$schedule = $this->Schedule->get_one_by($schedule_conds);
    	$currentTime = date('H:i:s');
    	$is_open = $currentTime >= $schedule->open_hour && $currentTime <= $schedule->close_hour;
	
		//accepting order ?
		$accept_orders_date = strtotime($app_config->accept_orders_date);
		$current_date = strtotime(date('Y-m-d H:i:s'));
		$is_accepting_orders = $current_date >= $accept_orders_date;
		?>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" class="table-responsive rounded-table animated fadeInRight " 
	style=" margin-bottom: 10px;">
	<input type="hidden" name="noti_time" id="noti_time" value="<?php echo $this->Backend_config->get_one('be1')->transaction_noti_sound_refresh_time ?>">
    <input type="hidden" name="page_time" id="page_time" value="<?php echo $this->Backend_config->get_one('be1')->transaction_page_refresh_time ?>">




	<div id="tabContainer" class="text-center" style="">
		<div style="margin-top: 15px; display: flex; align-items: center; justify-content: space-between;">

		<!-- Tab buttons -->
		<b style="">
			<?php if (!empty($new_orders) && count($new_orders) > 0){ ?>
				<div id="tab1-button" class="tab-button" onclick="openTab('tab1')">
					<span class="tab-number"><?= count($new_orders) ?></span><br> New Orders
				</div>
			<?php } ?>
			<div id="tab2-button" class="tab-button" onclick="openTab('tab2')">
				<span class="tab-number"><?= count($preparing_orders) ?></span><br> Preparing Now
			</div>
			<div id="tab3-button" class="tab-button" onclick="openTab('tab3')">
				<span class="tab-number"><?= count($upcoming_orders) ?></span><br> Upcoming Orders
			</div>
			
			<div style="color: green;" id="tab4-button" class="tab-button" onclick="openTab('tab4')">
				<span class="tab-number"><?= count($completed_orders) ?></span><br> Completed Orders
			</div>
			<?php if (!empty($rejected_orders) && count($rejected_orders) > 0){ ?>
				<div  style="color: red;" id="tab5-button" class="tab-button" onclick="openTab('tab5')">
					<span class="tab-number" ><?= count($rejected_orders) ?></span><br> Rejected Orders
				</div>
			<?php } ?>
		</b>

		<div>
		<?php if ($is_open == true): ?>
			<?php if ($is_accepting_orders == false): ?>
				<div class="banner align-middle" style="color: red;">
				<span class="table-header align-middle">Closed For New Orders till </span>
				<span class="table-header align-middle" style=""><?= date('H:i:s', strtotime($app_config->accept_orders_date)); ?></span>
				</div>
				<div>
					<a style="height: 40px;" class="btn btn-success fixed-size-btn btn-assign" href="<?php echo $module_site_url . "/resume_orders/";?>">
						<span>Resume Orders</span>
					</a>
				</div>
			<?php else: ?>
				<!--<div class="banner align-middle" style="color: green; background-color: rgba(0, 255, 0, 0.2);">
					<span class="table-header align-middle">Accepting New Orders</span>
				</div>-->
				<div style="">
					<a href='#' style="" class='btn xlrg-btn-size btn-danger btn-assign' data-toggle="modal" data-target="#pauseOrdersModal" id="<?php echo $ongoing->id; ?>">
						<span>Pause Orders</span>
					</a>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="banner text-center" style="">
				<span class="table-header">Closed For The Day</span>
			</div>
			<div >
			</div>
		<?php endif; ?>
		</div>
	</div>

	


    <!-- Tab content -->
    <div id="tab1" class="tab-content" style="">
        
	<!-- New Orders  -->
	<?php if ( !empty( $new_orders ) && count( $new_orders ) > 0 ): ?>
	<div class="table-responsive" style="height: 100%; ">	
		<table id="new-orders-table" class="table  m-0 text-center align-middle " style=" width:100%">
			
			<thead>
			<tr>
				<th class="align-middle  table-header" style="font-size: 20px" colspan = "10">New Orders</th>
			</tr>
			
			<tr >
				<th class="align-middle  table-header"><?php echo get_msg('no'); ?></th>
				<th class="align-middle table-header">Order Number</th>
				<th class="align-middle table-header"><?php echo get_msg('total_amount_label') ?></th>
				<th class="align-middle table-header">Pickup Time</th>
				<th class="align-middle table-header"><span class="th-title">Customer Details</span></th>
				<th class="align-middle table-header">Order Type</th>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					
					<th class="align-middle table-header"><span class="th-title"><?php echo get_msg('accept_order_label')?></span></th>
				
				<?php endif; ?>


				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					
					<th class="align-middle table-header"><span class="th-title"><?php echo get_msg('reject_order_label')?></span></th>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					
					<th class="align-middle table-header"><span class="th-title"><?php echo get_msg('order_detail_label')?></span></th>
				
				<?php endif; ?>

			</tr>
				</thead>
			<?php $count = 0; ?>

			

					<?php foreach($new_orders as $new_order): ?>
						
						<tr>
							<td class="align-middle"><?php echo ++$count;?></td>
							<td class="align-middle"><?php echo $new_order->trans_code;?></td>
							<td class="align-middle">
								<?php
									$total_amount = $this->Shop->get_one($shop_id)->currency_symbol . number_format($new_order->balance_amount, 2);
									echo $total_amount;
								?>
							</td>
							<td class="align-middle">
								<?php
									$added_date = new DateTime($new_order->delivery_pickup_time);
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
					

		</table>
	</div>
	<legend class="ml-3 mt-3 mb-5 font-weight-bold text-center"></legend>
	<?php else: ?>
	<h2 class= "text-center " style = "padding-top: 150px;">Nothing To Show</h2>
	<?php endif; ?>	
	
    </div>
    <div id="tab2" class="tab-content">
        	<!-- Ongoing Orders  -->
	<?php 
		if ( !empty( $preparing_orders ) && count( $preparing_orders ) > 0 ): 
		?>
	<div class="table-responsive" style="height: 100%; ">
		<table id="ongoing-orders-table" class="table  m-0  text-center align-middle" style="width:100%;">
			
			<thead>
			<tr>
				<th class="align-middle table-header" style="font-size: 20px" colspan = "10">Preparing Now</th>
			</tr>
			<tr>
				<th class="align-middle table-header"><?php echo get_msg('no'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('trans_code'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('total_amount_label') ?></th>
				<th class="align-middle table-header">Pickup Time</th>
				<th class="align-middle table-header"><span class="th-title">Customer Details</span></th>
				<th class="align-middle table-header">Order Type</th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title">Delivery Boy</span></th>
				<?php endif; ?>
				<th class="align-middle table-header"><?php echo get_msg('order_status_label'); ?></th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title"><?php echo get_msg('order_detail_label')?></span></th>
				<?php endif; ?>
				<th class="align-middle table-header">Time To Prepare</th>
			</tr>
				</thead>
			<?php $count = 0; ?>

					<?php foreach($preparing_orders as $index => $ongoing): 
		
						
						$title = $this->Transactionstatus->get_one($ongoing->trans_status_id)->title;

					?>
						<?php $timeDifference = strtotime($ongoing_order->delivery_pickup_date.' '.$ongoing->delivery_pickup_time) - $currentDateTime; ?>
									
						
						
							<tr id="tableRow<?= $index ?>" style="
								<?php 

									if($this->Transactionstatus->get_one($ongoing->trans_status_id)->ordering == "2")
									{
										/*if ($timeDifference <= 300 && $timeDifference > 0) {
											echo 'background-color: rgba(255, 255, 0, 0.2);';
										} else*/if ($timeDifference <= 0) {
											echo 'background-color: rgba(255, 0, 0, 0.2);';
										} 
									}

									?>
							">
								<td class="align-middle"><?php echo ++$count; ?></td>
								<td class="align-middle"><?php echo $ongoing->trans_code; ?></td>
								<td class="align-middle">
									<?php
										$total_amount = $this->Shop->get_one($shop_id)->currency_symbol . number_format($ongoing->balance_amount, 2);
										echo $total_amount;
									?>
								</td>
								<td class="align-middle">
									<?php
										$added_date = new DateTime($ongoing->delivery_pickup_time);
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
												<span>Assign Delivery Boy</span>
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
												<span>Mark as Ready</span>
											</a>
										</div>
								
									<?php elseif ($this->Transactionstatus->get_one($ongoing->trans_status_id)->ordering == "3" && $ongoing->pick_at_shop == "1"): ?>
										<div class='d-flex align-items-center justify-content-center'>
											<a class="btn btn-success fixed-size-btn" href="<?php echo $module_site_url . "/order_completed/" . $ongoing->id; ?>">
												<span>Mark as Collected</span>
											</a>
										<div>
									<?php elseif ($this->Transactionstatus->get_one($ongoing->trans_status_id)->ordering == "3" && $ongoing->pick_at_shop == "0"): ?>
										<span style="font-weight: bold; color: <?= $this->Transactionstatus->get_one($ongoing->trans_status_id)->color_value ?>;">
											Waiting For Driver
										</span>
									<?php else: ?>
										<span style="font-weight: bold; color: <?= $this->Transactionstatus->get_one($ongoing->trans_status_id)->color_value ?>;">
											<?php echo $title; ?>
										</span>
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
								<?php
									$orderingStatus = $this->Transactionstatus->get_one($ongoing->trans_status_id)->ordering;
									if ($orderingStatus == "2") { 

									?>
									<td class="align-middle text-center">
										<div class='d-flex align-items-center justify-content-center'>
										<script>
												var currentTimestamp<?= $index ?> = Math.floor(new Date() / 1000);
												var completionTimestamp<?= $index ?> = <?= $timeDifference ?>;
												var completionTime<?= $index ?> = currentTimestamp<?= $index ?> + completionTimestamp<?= $index ?>;
												var totalDuration<?= $index ?> = <?= $app_config->default_order_time*60?>;
												//alert(<?= $timeDifference ?>);

												var countdown<?= $index ?> = setInterval(function () {
													var now = Math.floor(new Date() / 1000);
													var timeLeft = completionTime<?= $index ?> - now;
													var tableRow = document.getElementById("tableRow<?= $index ?>");
													var timerElement = document.getElementById("timer<?= $index ?>");
													var progressBarElement = document.getElementById("progressBar<?= $index ?>");
													
													if (timeLeft <= 0) {
														clearInterval(countdown<?= $index ?>);
														timerElement.innerHTML = "<b style='color: red;'>Timer Expired</b>";
														progressBarElement.style.height = "0px"
														//alert('1');
													} else {
														var minutes = Math.floor(timeLeft / 60);
														var seconds = timeLeft % 60;
														timerElement.innerHTML = "<b>" + minutes + "m " + seconds + "s";
														var progress = ((totalDuration<?= $index ?> - timeLeft) / totalDuration<?= $index ?>) * 100;
														progress = Math.min(progress, 100);
														progressBarElement.style.width = progress + "%";
														var hue = 120 - (progress * 1.2);
														progressBarElement.style.backgroundColor = "hsl(" + hue + ", 100%, 50%)";
														setTimeout(updateTimer<?= $index ?>, 1000);
													}
												}, 0); // Set interval to 0 to update immediately

												function updateTimer<?= $index ?>() {
													var now = Math.floor(new Date() / 1000);
													var timeLeft = completionTime<?= $index ?> - now;
													var tableRow = document.getElementById("tableRow<?= $index ?>");
													var timerElement = document.getElementById("timer<?= $index ?>");
													var progressBarElement = document.getElementById("progressBar<?= $index ?>");
													
													if (timeLeft <= 0) {
														//clearInterval(countdown<?= $index ?>);
														timerElement.innerHTML = "<b style='color: red;'>Timer Expired</b>";
														tableRow.style.backgroundColor = "rgba(255, 0, 0, 0.2)";
														progressBarElement.style.height = "0px";;
														//alert(tableRow.style.backgroundColor);
													} else {
														var minutes = Math.floor(timeLeft / 60);
														var seconds = timeLeft % 60;
														timerElement.innerHTML = "<b>" + minutes + "m " + seconds + "s";
														var progress = ((totalDuration<?= $index ?> - timeLeft) / totalDuration<?= $index ?>) * 100;
														progress = Math.min(progress, 100);
														var hue = 120 - (progress * 1.2);
														progressBarElement.style.backgroundColor = "hsl(" + hue + ", 100%, 50%)";
														setTimeout(updateTimer<?= $index ?>, 1000);
													}
												}

											</script>

											<span id="timer<?= $index ?>"></span>
											
										</div>
									</td>
									<?php } else { ?>
									<td class="align-middle text-center">-</td>
									<?php } ?>

							</tr >
							
							<?php if ($orderingStatus == "2") {?>
								<tr class="" data-exclude="true" >
									<td style="padding: 0px;  " colspan="10">
										<div class="" style="  margin-top: 0px; width: calc(100%);">
											<div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar<?= $index ?>" 
												style=" height: 5px; width: 0%;">
											</div>
										</div>
									</td>
								</tr>
							<?php } ?>
					<?php endforeach; ?>	

		</table>
	</div>
	<legend class="ml-3 mt-3 mb-5 font-weight-bold text-center"></legend>
	<?php else: ?>
	<h2 class= "text-center " style = "padding-top: 150px;">Nothing To Show</h2>
	<?php endif; ?>
    </div>
    <div id="tab3" class="tab-content">
        	<!-- Do Not Cook  -->

	<?php 
		if ( !empty( $upcoming_orders ) && count( $upcoming_orders ) > 0 ): ?>
		<!--<label onclick="toggleTable()" class="align-middle table-header" style="font-size: 20px" colspan = "9">Upcoming Orders</label>-->
	<div class="table-responsive" style="height: 100%;">
		<table id="upcoming-orders-table" class="table  m-0  text-center align-middle" style="width: 100%;">
			
			<thead>

			<tr >
				<th class="align-middle table-header" style="font-size: 20px" colspan = "9">Upcoming Orders</th>
			</tr>
			<tr >
				<th class="align-middle table-header"><?php echo get_msg('no'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('trans_code'); ?></th>
				<th class="align-middle table-header">Total Amount</th>
				<th class="align-middle table-header">Pickup Time</th>
				<th class="align-middle table-header"><span class="th-title">Customer Details</span></th>
				<th class="align-middle table-header">Order Type</th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title">Delivery Boy</span></th>
				<?php endif; ?>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title"><?php echo get_msg('order_detail_label')?></span></th>
				<?php endif; ?>
			</tr>
				</thead>
			<?php $count = 0; ?>

			

					<?php foreach($upcoming_orders as $ongoing): 

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
										$total_amount = $this->Shop->get_one($shop_id)->currency_symbol . number_format($ongoing->balance_amount, 2);
										echo $total_amount;
									?>
								</td>
								<td class="align-middle">
									<?php
										$added_date = new DateTime($ongoing->delivery_pickup_time);
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
												<span>Assign Delivery Boy</span>
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

					

		</table>
	</div>
	<legend class="ml-3 mt-3 mb-5 font-weight-bold text-center"></legend>
	<?php else: ?>
	<h2 class= "text-center " style = "padding-top: 150px;">Nothing To Show</h2>
	<?php endif; ?>	
</div>	
    </div>
	<div id="tab4" class="tab-content" style="">
	<?php if ( !empty( $completed_orders ) && count( $completed_orders ) > 0 ): ?>
		<!--<label onclick="toggleTable()" class="align-middle table-header" style="font-size: 20px" colspan = "9">Upcoming Orders</label>-->
		<div class="table-responsive" style="height: 100%;">
		<table class="table m-0  text-center align-middle" style = "width:100%;" id="completed-orders-table">
		<?php $count = $this->uri->segment(4) or $count = 0; ?>
		<thead>
			<tr>
				<th class="align-middle table-header" style="font-size: 20px" colspan = "10">Completed Orders</th>
			</tr>
			<tr>
				<th class="align-middle table-header"><?php echo get_msg('no'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('trans_code'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('total_amount_label') ?></th>
				<th class="align-middle table-header">Time</th>
				<th class="align-middle table-header"><span class="th-title">Customer Details</span></th>
				<th class="align-middle table-header">Order Type</th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title">Delivery Boy</span></th>
				<?php endif; ?>
				<th class="align-middle table-header">Delete Order</th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title"><?php echo get_msg('order_detail_label')?></span></th>
				<?php endif; ?>
			</tr>
		</thead>
			<?php $count = 0; ?>
			<?php foreach($completed_orders as $transaction): ?>
					
						<tr style="">
								<td class="align-middle table-cell"><?php echo ++$count; ?></td>
								<td class="align-middle table-cell"><?php echo $transaction->trans_code; ?></td>
								<td class="align-middle table-cell">
									<?php
										$total_amount = '£' . number_format($transaction->balance_amount, 2);
										echo $total_amount;
									?>
								</td>
								<td class="align-middle table-cell">
									<?php
										$added_date = new DateTime($transaction->updated_date);
										echo $added_date->format('H:i'); 
									?>
								</td>
								
								<td class="align-middle table-cell">
										<?php echo $this->User->get_one($transaction->user_id)->user_name; ?>
										<br>
										<?php echo $this->User->get_one($transaction->user_id)->user_phone; ?>
									</td>
									<td class="align-middle table-cell">
										<?php
											$pick_at_shop = $transaction->pick_at_shop;

											if ($pick_at_shop == "0") {
												echo '<span class="order-delivery">Delivery</span>';
											} else {
												echo '<span class="order-collection">Collection</span>';
											}
										?>
									</td>
									</td>
									<?php if ($this->ps_auth->has_access(EDIT)): ?>
										<td class="align-middle  table-cell">
									<?php if (
										($transaction->delivery_boy_id == "" || $transaction->delivery_boy_id == "0") 
										//&& $this->Shop->get_one($shop_id)->deli_manual_assign == 1 
										//&& $transaction->pick_at_shop == "0"
									): ?>
										-
									<?php elseif ($transaction->pick_at_shop == "1"): ?>
										-
									<?php else: ?>
										<?php echo $this->User->get_one($transaction->delivery_boy_id)->user_name; ?>
										<br>
										<?php echo $this->User->get_one($transaction->delivery_boy_id)->user_phone; ?>
									<?php endif; ?>
								</td>

								<?php endif; ?>
								<td>
							<a herf='#' class='btn-delete btn fixed-size-btn btn-danger' data-toggle="modal" data-target="#reportsmodal" id="<?php echo "$transaction->id";?>">
								<span style="color: white;">Delete</span>
							</a>
							<!--<button class=" btn fixed-size-btn btn-danger" data-toggle="modal" data-target="#reportsmodal" id="<?php echo $transaction->id; ?>">
								Delete
							</button>-->
						</td>
						<td>
							<a class=" btn fixed-size-btn btn-primary" href="<?php echo $module_site_url . "/detail/" . $transaction->id;?>">
								<?php echo get_msg('detail_label');  ?>
							</a>
						</td>
					</tr>


		<?php endforeach; ?>
		</table>
	</div>
	<legend class="ml-3 mt-3 mb-5 font-weight-bold text-center"></legend>
	<?php else: ?>
	<h2 class= "text-center " style = "padding-top: 150px;">Nothing To Show</h2>
	<?php endif; ?>	
    </div>
</div>
</div>	
    </div>
	<div id="tab5" class="tab-content">
	<?php if ( !empty( $rejected_orders ) && count( $rejected_orders ) > 0 ): ?>
		<!--<label onclick="toggleTable()" class="align-middle table-header" style="font-size: 20px" colspan = "9">Upcoming Orders</label>-->
		<div class="table-responsive" style="height: 100%;">
		<table class="table m-0  text-center align-middle" style = "width:100%;" id="rejected-orders-table">
		<?php $count = $this->uri->segment(4) or $count = 0; ?>
		<thead>
			<tr>
				<th class="align-middle table-header" style="font-size: 20px" colspan = "10">Rejected Orders</th>
			</tr>
			<tr>
				<th class="align-middle table-header"><?php echo get_msg('no'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('trans_code'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('total_amount_label') ?></th>
				<th class="align-middle table-header">Time</th>
				<th class="align-middle table-header"><span class="th-title">Customer Details</span></th>
				<th class="align-middle table-header">Order Type</th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title">Delivery Boy</span></th>
				<?php endif; ?>
				<th class="align-middle table-header">Delete Order</th>
				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
					<th class="align-middle table-header"><span class="th-title"><?php echo get_msg('order_detail_label')?></span></th>
				<?php endif; ?>
			</tr>
		</thead>
			<?php $count = 0; ?>
			<?php foreach($rejected_orders as $transaction): ?>
					
						<tr style="background-color: rgba(255, 0, 0, 0.2);">
								<td class="align-middle table-cell"><?php echo ++$count; ?></td>
								<td class="align-middle table-cell"><?php echo $transaction->trans_code; ?></td>
								<td class="align-middle table-cell">
									<?php
										$total_amount = '£' . number_format($transaction->balance_amount, 2);
										echo $total_amount;
									?>
								</td>
								<td class="align-middle table-cell">
									<?php
										$added_date = new DateTime($transaction->updated_date);
										echo $added_date->format('H:i'); 
									?>
								</td>
								
								<td class="align-middle table-cell">
										<?php echo $this->User->get_one($transaction->user_id)->user_name; ?>
										<br>
										<?php echo $this->User->get_one($transaction->user_id)->user_phone; ?>
									</td>
									<td class="align-middle table-cell">
										<?php
											$pick_at_shop = $transaction->pick_at_shop;

											if ($pick_at_shop == "0") {
												echo '<span class="order-delivery">Delivery</span>';
											} else {
												echo '<span class="order-collection">Collection</span>';
											}
										?>
									</td>
									</td>
									<?php if ($this->ps_auth->has_access(EDIT)): ?>
										<td class="align-middle  table-cell">
									<?php if (
										($transaction->delivery_boy_id == "" || $transaction->delivery_boy_id == "0") 
										//&& $this->Shop->get_one($shop_id)->deli_manual_assign == 1 
										//&& $transaction->pick_at_shop == "0"
									): ?>
										-
									<?php elseif ($transaction->pick_at_shop == "1"): ?>
										-
									<?php else: ?>
										<?php echo $this->User->get_one($transaction->delivery_boy_id)->user_name; ?>
										<br>
										<?php echo $this->User->get_one($transaction->delivery_boy_id)->user_phone; ?>
									<?php endif; ?>
								</td>

								<?php endif; ?>
								<td>
							<a herf='#' class='btn-delete btn fixed-size-btn btn-danger' data-toggle="modal" data-target="#reportsmodal" id="<?php echo "$transaction->id";?>">
								<span style="color: white;">Delete</span>
							</a>
							<!--<button class=" btn fixed-size-btn btn-danger" data-toggle="modal" data-target="#reportsmodal" id="<?php echo $transaction->id; ?>">
								Delete
							</button>-->
						</td>
						<td>
							<a class=" btn fixed-size-btn btn-primary" href="<?php echo $module_site_url . "/detail/" . $transaction->id;?>">
								<?php echo get_msg('detail_label');  ?>
							</a>
						</td>
					</tr>


		<?php endforeach; ?>
		</table>
	</div>
	<legend class="ml-3 mt-3 mb-5 font-weight-bold text-center"></legend>
	<?php else: ?>
	<h2 class= "text-center " style = "padding-top: 150px;">Nothing To Show</h2>
	<?php endif; ?>	
    </div>
</div>
	


<script>

	function toggleTable() {
            var table = document.getElementById("upcoming-orders-table");
            table.style.display = (table.style.display === "none" || table.style.display === "") ? "table" : "none";
        }

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

    var page_time = document.getElementById("page_time").value ;
    var refreshTimeout = window.setTimeout(function () {
        window.location.reload();
    }, page_time);


	$('.btn-assign').click(function(){
		

		// get id and links
		var id = $(this).attr('id');
		var formLink = $('#assign-deliboy-form').attr('action');

		// modify link with id
		$('#assign-deliboy-form').attr( 'action', formLink + id );
		window.clearTimeout(refreshTimeout);
		refreshTimeout = window.setTimeout(function () {
			window.location.reload();
		}, 60000);
	});
    $(document).ready(function () {
  	$('#new-orders-table').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [3, 6, 7, 8] } 
            ],
			"lengthChange": false,
			"searching": false, // Disable search bar
			"paging": false, // Disable pagination
			"info": false, // Disable table information display
			"drawCallback": function (settings) {
				var api = this.api();
				var pageInfo = api.page.info();

				if (pageInfo.pages <= 1) {
					$(this).closest('.dataTables_wrapper').find('.dataTables_paginate').hide();
				} else {
					$(this).closest('.dataTables_wrapper').find('.dataTables_paginate').show();
				}
        	}
        });
	});
	$(document).ready(function () {
    $('#ongoing-orders-table').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [3, 8, 9] } 
        ],
        "lengthChange": false,
        "searching": false, // Disable search bar
        "paging": false, // Disable pagination
        "info": false, // Disable table information display
        "drawCallback": function (settings) {
            var api = this.api();
            var pageInfo = api.page.info();

            if (pageInfo.pages <= 1) {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').hide();
            } else {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').show();
            }
        }
    });
});
	$(document).ready(function () {
    $('#upcoming-orders-table').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [3, 6, 7] } 
        ],
        "pageLength": 15,
        "lengthChange": false,
        "searching": false, // Disable search bar
        "paging": false, // Disable pagination
        "info": false, // Disable table information display
        "drawCallback": function (settings) {
            var api = this.api();
            var pageInfo = api.page.info();

            if (pageInfo.pages <= 1) {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').hide();
            } else {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').show();
            }
        }
    });
});


$(document).ready(function () {
    $('#completed-orders-table').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [7, 8] } 
        ],
        "lengthChange": false, // Disable pagination
        "info": false, // Disable table information display
		"searching": false,
        "drawCallback": function (settings) {
            var api = this.api();
            var pageInfo = api.page.info();

            if (pageInfo.pages <= 1) {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').hide();
            } else {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').show();
            }
        }
    });
});
$(document).ready(function () {
    $('#rejected-orders-table').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [7, 8] } 
        ],
        "lengthChange": false, // Disable pagination
		"searching": false,
        "info": false, // Disable table information display
        "drawCallback": function (settings) {
            var api = this.api();
            var pageInfo = api.page.info();

            if (pageInfo.pages <= 1) {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').hide();
            } else {
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate').show();
            }
        }
    });
});
// Function to open a tab and store the selected tab in localStorage
function openTab(tabName) {
    // Hide all tabs and reset button styles
    var tabs = document.getElementsByClassName('tab-content');
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].style.display = 'none';
    }
 
	var selectedButton;
    var buttons = document.getElementsByClassName('tab-button');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('active');
		
    }
	
    // Show the selected tab and set the button style to active
    var selectedTab = document.getElementById(tabName);
	var selectedButton = document.getElementById(tabName + '-button');
    selectedTab.style.display = 'block';
	selectedButton.classList.add('active');

    localStorage.setItem('selectedTab', tabName);
}

// Function to retrieve and open the previously selected tab on page load
window.onload = function() {
    var selectedTab = localStorage.getItem('selectedTab');
    if (selectedTab) {
        openTab(selectedTab);
    }
    
    
}
    // Delete Trigger
    $('.btn-delete').click(function(){

		// get id and links
		var id = $(this).attr('id');
		var btnYes = $('.btn-yes').attr('href');
		var btnNo = $('.btn-no').attr('href');

		// modify link with id
		$('.btn-yes').attr( 'href', btnYes + id );
		$('.btn-no').attr( 'href', btnNo + id );
		});

</script>

<?php
	// Assign Delivery Boy Modal
	$data = array(
		'title' => get_msg( 'assign_deliboy' )
	);
	
	$this->load->view( $template_path .'/components/assign_deliboy_modal', $data );
	// Pause Orders Modal
	$data = array(
		'title' => 'Pause Duration'
	);
	
	$this->load->view( $template_path .'/components/pause_orders_modal', $data );
// Delete Confirm Message Modal
$data = array(
    'title' => get_msg( 'delete_trans_label' ),
    'message' =>  get_msg( 'trans_yes_all_message' ),
    'no_only_btn' => get_msg( 'cat_no_only_label' )
);

$this->load->view( $template_path .'/components/report_delete_confirm_modal', $data );
?>