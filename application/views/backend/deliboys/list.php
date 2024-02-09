<?php $logged_in_user = $this->ps_auth->get_user_info(); ?>
<div class="table-responsive animated fadeInRight" style="padding: 10px 30px 10px 30px;">
	<table class="table m-0 table-bordered">
		<tr>
			<th class="table-header"><?php echo get_msg('no')?></th>
			<th class="table-header"><?php echo get_msg('user_name')?></th>
			<th class="table-header"><?php echo get_msg('user_email')?></th>
			<th class="table-header"><?php echo get_msg('user_status')?></th>
			
			<?php if ( $this->ps_auth->has_access( EDIT )): ?>
				
				<th class="table-header"><?php echo get_msg('btn_edit')?></th>
			
			<?php endif; ?>

			<?php if ( $this->ps_auth->has_access( BAN )): ?>
				
				<th class="table-header"><?php echo get_msg('user_ban')?></th>

			<?php endif;?>

			<?php if ( $this->ps_auth->has_access( DEL )): ?>
				
			<th class="table-header"><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
		
			<?php endif; ?>
		</tr>
		
		<?php $count = $this->uri->segment(4) or $count = 0; ?>

		<?php if ( !empty( $deliboys ) && count( $deliboys->result()) > 0 ): ?>

			<?php foreach($deliboys->result() as $deliboy): ?>
				
				<tr>
					<td class="table-cell align-middle"><?php echo ++$count;?></td>
					<td class="table-cell align-middle"><?php echo $deliboy->user_name;?></td>
					<td class="table-cell align-middle"><?php echo $deliboy->user_email;?></td>
					<td class="table-cell align-middle">
						<?php 
						if ($deliboy->status == '1') { ?>
			                <span class="badge " style="font-size:17px; color:green; border-color:green;">
			                  <?php echo "Active"; ?>
			                </span>
			            <?php  }else if($deliboy->status == '2'){ ?>
			            	<span class="badge " style="font-size:17px; color:red; border-color:red;">
			                  <?php echo "Pending"; ?>
			                </span>
			            <?php } else if($deliboy->status == '3') { ?>
			            	<span class="badge " style="font-size:17px; color:red; border-color:red;">
			                  <?php echo "Rejected"; ?>
			                </span>        
					 	
					 	<?php } ?>

					</td>

					<?php
						if ($logged_in_user->user_id == $deliboy->added_user_id || $logged_in_user->user_is_sys_admin == 1) {
						if ( $this->ps_auth->has_access( EDIT )):
					?>
				
					<td class="table-cell align-middle">
						<a href='<?php echo $module_site_url .'/edit/'. $deliboy->user_id; ?>'>
							<span class="btn btn-warning fixed-size-btn">Edit</span>
						</a>
					</td>
					
					<?php
						endif;
						} else {
					?>
					<td></td>
					<?php } ?>

					<?php if ( $this->ps_auth->has_access( BAN )):?>
					
						<td class="table-cell align-middle">
							<?php if ( @$deliboy->is_banned == 0 ): ?>
								
								<button class="btn fixed-size-btn ban" userid='<?php echo @$deliboy->user_id;?>'>
									Ban
								</button>
							
							<?php else: ?>
								
								<button class="btn fixed-size-btn btn-danger unban" userid='<?php echo @$deliboy->user_id;?>'>
									Unban
								</button>
							
							<?php endif; ?>

						</td>

					<?php endif;?>
					
					<?php 
						if ($logged_in_user->user_id == $deliboy->added_user_id || $logged_in_user->user_is_sys_admin == 1) {
						if ( $this->ps_auth->has_access( DEL )): 
					?>
					
					<td class="table-cell align-middle">
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$deliboy->user_id";?>">
							<span class="btn btn-danger fixed-size-btn">Delete</span>
						</a>
					</td>
				
					<?php 
						endif; 
						} else {
					?>
						<td></td>
					<?php } ?>
				</tr>

			<?php endforeach; ?>

		<?php else: ?>
				
			<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

		<?php endif; ?>

	</table>
</div>