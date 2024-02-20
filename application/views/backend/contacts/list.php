<div class="table-responsive animated fadeInRight">
	<table id="contact-table" class="table m-0 ">
	<thead>	
		<tr>
			<th class="table-header"><?php echo get_msg('no')?></th>
			<th class="table-header"><?php echo get_msg('contact_name')?></th>
			<th class="table-header"><?php echo get_msg('contact_email')?></th>
			<th class="table-header"><?php echo get_msg('contact_phone')?></th>
			<?php if ( $this->ps_auth->has_access( DEL )): ?>
				<th class="table-header"><?php echo get_msg('btn_delete')?></th>
			<?php endif; ?>
			<th class="table-header"><?php echo get_msg('lbl_view')?></th>
		</tr>
			</thead>
	<?php $count = $this->uri->segment(4) or $count = 0; ?>
	<?php if ( !empty( $contacts ) && count( $contacts->result()) > 0 ): ?>
		<?php foreach($contacts->result() as $contact): ?>
			<tr>
				<td class="table-cell align-middle"><?php echo ++$count;?></td>
				<td class="table-cell align-middle"><?php echo $contact->name;?></td>
				<td class="table-cell align-middle"><?php echo $contact->email;?></td>
				<td class="table-cell align-middle"><?php echo $contact->phone;?></td>
				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					<td class="table-cell align-middle">
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo $contact->id;?>">
							<span class="btn btn-danger fixed-size-btn">Delete</span>
						</a>
					</td>
				<?php endif; ?>
				<td class="table-cell align-middle">
					<a href='<?php echo $module_site_url .'/detail/'.$contact->id;?>'>
						<span class="btn btn-primary fixed-size-btn">View</span>
					</a>
				</td>
			</tr>

		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
</div>