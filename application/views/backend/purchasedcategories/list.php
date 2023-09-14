<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped">
		<tr>
			<th><?php echo get_msg('no'); ?></th>
			<th><?php echo get_msg('purchased_cat_name'); ?></th>
			<th><?php echo get_msg('purchased_cat_img'); ?></th>
			<th><span class="th-title"><?php echo get_msg('view'); ?></span></th>
		</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $purchasedcategories ) && count( $purchasedcategories ->result()) > 0 ): ?>

		<?php foreach($purchasedcategories ->result() as $purchasedcategory ): ?>
			
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $purchasedcategory->name; ?></td>	
				
				<?php 

				$default_photo = get_default_photo( $purchasedcategory->id, 'category' );

				if($default_photo->img_path != "") {
				 ?>		

				<td >
					<img class="img-rounded " width="128" height="128" src="<?php echo img_url( ''. $default_photo->img_path ); ?>"/>
				</td>

			<?php } else { ?>
				<td style="width: 128px; height: 128px; overflow: hidden;">
					<img class="img-fluid"  style="object-fit: cover; width: 100%; height: 100%;" src="<?php echo img_url( 'thumbnail/no_image.png'); ?>"/>
				</td>
			<?php } ?>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
					<td>
						<a href='<?php echo $module_site_url .'/edit/'. $purchasedcategory->id; ?>'>
							<i class='fa fa-eye'></i>
						</a>
					</td>
				
				<?php endif; ?>
				
				
				
			</tr>

		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

	</table>
</div>
