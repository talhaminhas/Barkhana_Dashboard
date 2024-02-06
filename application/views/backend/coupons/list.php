<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-bordered">
		<tr>
			<th class="table-header"><?php echo get_msg('no'); ?></th>
			<th class="table-header"><?php echo get_msg('coupon_name'); ?></th>
			<th class="table-header"><?php echo get_msg('coupon_code'); ?></th>
			<th class="table-header"><?php echo get_msg('coupon_amount'); ?></th>
			
			<?php if ( $this->ps_auth->has_access( EDIT )): ?>
				
				<th class="table-header"><span class="th-title"><?php echo get_msg('btn_edit')?></span></th>
			
			<?php endif; ?>
			
			<?php if ( $this->ps_auth->has_access( DEL )): ?>
				
				<th class="table-header"><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
			
			<?php endif; ?>
			
			<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>
				
				<th class="table-header"><span class="th-title"><?php echo get_msg('btn_publish')?></span></th>
			
			<?php endif; ?>

		</tr>
		
	
	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $coupons ) && count( $coupons->result()) > 0 ): ?>

		<?php foreach($coupons->result() as $coupon): ?>
			
			<tr>
				<td class="table-cell align-middle"><?php echo ++$count;?></td>
				<td class="table-cell align-middle"><?php echo $coupon->coupon_name;?></td>
				<td class="table-cell align-middle"><?php echo $coupon->coupon_code ?></td>
				<td class="table-cell align-middle"><?php echo number_format($coupon->coupon_amount, 2); ?></td>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
					<td class="table-cell align-middle">
						<a href='<?php echo $module_site_url .'/edit/'. $coupon->id; ?>'>
							<span class="btn btn-warning fixed-size-btn">Edit</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td class="table-cell align-middle">
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#couponmodal" id="<?php echo "$coupon->id";?>">
							<span class="btn btn-danger fixed-size-btn">Delete</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>
					
					<td class="table-cell align-middle">
						<?php if ( @$coupon->is_published == 1): ?>
							<button class="btn fixed-size-btn btn-success unpublish" id='<?php echo $coupon->id;?>'>
							<?php echo get_msg('btn_yes'); ?></button>
						<?php else:?>
							<button class="btn fixed-size-btn btn-danger publish" id='<?php echo $coupon->id;?>'>
							<?php echo get_msg('btn_no'); ?></button><?php endif;?>
					</td>
				
				<?php endif; ?>

			</tr>

		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
</div>

