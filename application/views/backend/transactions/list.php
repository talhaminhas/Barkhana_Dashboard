<style>
    

</style>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" class="table-responsive animated fadeInRight">

<div class="card-body table-responsive p-0">
	
    <input type="hidden" name="noti_time" id="noti_time" value="<?php echo $this->Backend_config->get_one('be1')->transaction_noti_sound_refresh_time ?>">
    <input type="hidden" name="page_time" id="page_time" value="<?php echo $this->Backend_config->get_one('be1')->transaction_page_refresh_time ?>">
  	<table class="table  " id="completed-orders-table">
		<?php $count = $this->uri->segment(4) or $count = 0; ?>
		<thead>
			<tr>
				<th class="align-middle table-header" style="font-size: 20px" colspan = "9">Completed Orders</th>
			</tr>
			<tr>
				<th class="align-middle table-header"><?php echo get_msg('no'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('trans_code'); ?></th>
				<th class="align-middle table-header"><?php echo get_msg('total_amount_label') ?></th>
				<th class="align-middle table-header">Date & Time</th>
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
		<?php if ( !empty( $transactions ) && count( $transactions->result()) > 0 ): ?>
			
			<?php $count = 0; ?>
			<?php foreach($transactions->result() as $transaction): 
				$transaction_status = $this->Transactionstatus->get_one($transaction->trans_status_id);
				if($transaction_status->final_stage == "1"){?>
					
						<tr style="
						<?php echo $transaction_status->ordering == "0" ? 'background-color: rgba(255, 0, 0, 0.15);' : ''; ?>
						">
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
										$added_date = new DateTime($transaction->added_date);
										echo $added_date->format('d-m-Y H:i'); 
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


		<?php }endforeach; ?>
		<?php else: ?>

			<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

		<?php endif; ?>
		</table>
	</div>
</div>
<script>
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
// Delete Confirm Message Modal
$data = array(
    'title' => get_msg( 'delete_trans_label' ),
    'message' =>  get_msg( 'trans_yes_all_message' ),
    'no_only_btn' => get_msg( 'cat_no_only_label' )
);

$this->load->view( $template_path .'/components/report_delete_confirm_modal', $data );
?>