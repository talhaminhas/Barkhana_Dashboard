<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-bordered">
		<tr>
			<th class="table-header"><?php echo get_msg('no'); ?></th>
			<th class="table-header"><?php echo get_msg('subcat_name'); ?></th>
			<th class="table-header"><?php echo get_msg('cat_name'); ?></th>
			
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

	<?php if ( !empty( $subcategories ) && count( $subcategories->result()) > 0 ): ?>

		<?php foreach($subcategories->result() as $subcategory): ?>
			
			<tr>
				<td class="table-cell align-middle"><?php echo ++$count;?></td>
				<td class="table-cell align-middle"><?php echo $subcategory->name;?></td>
				<td class="table-cell align-middle"><?php echo $this->Category->get_one( $subcategory->cat_id )->name; ?></td>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
					<td class="table-cell align-middle">
						<a href='<?php echo $module_site_url .'/edit/'. $subcategory->id; ?>'>
						<span class='btn fixed-size-btn btn-warning'>Edit</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td class="table-cell align-middle">
						<a herf='#' class=' btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo $subcategory->id;?>">
							<span class='btn fixed-size-btn btn-danger'>Delete</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>
					
					<td class="table-cell align-middle">
						<?php 
						if ( $subcategory->status == 1): ?>
							<button class="btn fixed-size-btn btn-success unpublish" id='<?php echo $subcategory->id;?>'>
							<?php echo get_msg('btn_yes'); ?></button>
						<?php else:?>
							<button class="btn fixed-size-btn btn-danger publish" id='<?php echo $subcategory->id;?>'>
							<?php echo get_msg('btn_no'); ?></button><?php endif;?>
					</td>
				
				<?php endif; ?>

			</tr>

		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
