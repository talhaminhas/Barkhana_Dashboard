<div class="table-responsive animated fadeInRight">
	<table id="category-table" class="table m-0 ">
		<thead>
		<tr>
			<th class="table-header"><?php echo get_msg('no'); ?></th>
			<th class="table-header text-left"><?php echo get_msg('cat_name'); ?></th>
			
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
			</thead>
	
	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $categories ) && count( $categories->result()) > 0 ): ?>

		<?php foreach($categories->result() as $category): ?>
			
			<tr>
				<td class="table-cell align-middle"><?php echo ++$count;?></td>
				<td class="align-middle"><?php echo $category->name;?></td>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
					<td class="table-cell align-middle">
						<a href='<?php echo $module_site_url .'/edit/'. $category->id; ?>'>
							<span class='btn fixed-size-btn btn-warning'>Edit</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td class="table-cell align-middle">
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#categorymodal" id="<?php echo "$category->id";?>">
							<span class='btn fixed-size-btn btn-danger'>Delete</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>
					
					<td class="table-cell align-middle">
						<?php if ( @$category->status == 1): ?>
							<button class="btn fixed-size-btn btn-success unpublish" id='<?php echo $category->id;?>'>
							<?php echo get_msg('btn_yes'); ?></button>
						<?php else:?>
							<button class="btn fixed-size-btn btn-danger publish" id='<?php echo $category->id;?>'>
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

