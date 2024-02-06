
<table class="table m-0 table-bordered" >

	<tr>
		<th class="table-header"><?php echo get_msg('no')?></th>
		<th class="table-header"><?php echo get_msg('user_name')?></th>
		<th class="table-header"><?php echo get_msg('user_email')?></th>
		<th class="table-header"><?php echo get_msg('user_phone')?></th>

		<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
			<th class="table-header"><?php echo get_msg('btn_edit')?></th>

		<?php endif;?>


		<?php if ( $this->ps_auth->has_access( BAN )): ?>
					
			<th class="table-header"><?php echo get_msg('user_ban')?></th>

		<?php endif;?>

		<?php if ( $this->ps_auth->has_access( DEL )): ?>
				
		<th class="table-header"><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
	
		<?php endif; ?>

	</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $users ) && count( $users->result()) > 0 ): ?>
			
		<?php foreach($users->result() as $user): ?>
			
			<tr>
				<td class="table-cell align-middle"><?php echo ++$count;?></td>
				<td class="table-cell align-middle"><?php echo $user->user_name;?></td>
				<td class="table-cell align-middle"><?php echo $user->user_email;?></td>
				<td class="table-cell align-middle"><?php echo $user->user_phone;?></td>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
				
				<td class="table-cell align-middle">
					<a href='<?php echo $module_site_url .'/edit/'. $user->user_id; ?>'>
					<span class="btn btn-warning fixed-size-btn">Edit</span>
					</a>
				</td>
			
			
				<?php endif; ?>

				<?php if ( $this->ps_auth->has_access( BAN )):?>
						
					<td class="table-cell align-middle">
						<?php if ( @$user->is_banned == 0 ): ?>
							
							<button class="btn fixed-size-btn ban" userid='<?php echo @$user->user_id;?>'>
								Ban
							</button>
						
						<?php else: ?>
							
							<button class="btn fixed-size-btn btn-danger unban" userid='<?php echo @$user->user_id;?>'>
								Unban
							</button>
						
						<?php endif; ?>

					</td>

				<?php endif;?>

				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td class="table-cell align-middle">
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$user->user_id";?>">
							<span class="btn btn-danger fixed-size-btn">Delete</span>
						</a>
					</td>
				
				<?php endif; ?>

			</tr>
		
		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>