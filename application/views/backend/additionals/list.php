


<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-bordered">
		<tr>
			<th class="table-header"><?php echo get_msg('no'); ?></th>
			<th class="sortable table-header"   data-column="Extras Name"><?php echo get_msg('food_add_name'); ?></th>
			<th class="table-header"><?php echo get_msg('food_add_price'); ?></th>
			
			<?php if ( $this->ps_auth->has_access( EDIT )): ?>
				
				<th class="table-header "><span class="th-title"><?php echo get_msg('btn_edit')?></span></th>
			
			<?php endif; ?>
			
			<?php if ( $this->ps_auth->has_access( DEL )): ?>
				
				<th class="table-header"><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
			
			<?php endif; ?>
			
			<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>
				
				<th class="table-header"><span class="th-title"><?php echo get_msg('btn_publish')?></span></th>
			
			<?php endif; ?>

		</tr>
		
	
	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $additionals ) && count( $additionals->result()) > 0 ): ?>

		<?php 
		$i = 1;
		foreach($additionals->result() as $add): ?>
			
			<tr>
				<td class="table-cell align-middle" ><?php echo  $i++;?></td>
				<td class="table-cell align-middle"><?php echo $add->name;?></td>
				<td class="table-cell align-middle"><?php echo '£'. number_format($add->price,2);?></td>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
					<td class="table-cell align-middle">
						<a href='<?php echo $module_site_url .'/edit/'. $add->id .'/' . $add->food_id; ?>'>
							<span class="btn fixed-size-btn btn-warning">Edit</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td class="table-cell align-middle">
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$add->id";?>">
							<span class="btn fixed-size-btn btn-danger">Delete</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>
					
					<td class="table-cell align-middle">
						<?php if ( @$add->status == 1): ?>
							<button class="btn fixed-size-btn btn-success unpublish" id='<?php echo $add->id;?>'>
							<?php echo get_msg('btn_yes'); ?></button>
						<?php else:?>
							<button class="btn fixed-size-btn btn-danger publish" id='<?php echo $add->id;?>'>
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

