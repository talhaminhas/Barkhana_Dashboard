<div class="table-responsive animated fadeInRight">
	<table class="table m-0 " id="food-table" style="">
		<?php

			$shop_obj = $this->Shop->get_all()->result();

			$shop_id = $shop_obj[0]->id;

			$currency_symbol = $this->Shop->get_one( $shop_id )->currency_symbol;
		?>
		<thead>
		<tr>
			<th class="table-header"><?php echo get_msg('no'); ?></th>
			<th class="table-header text-left"><?php echo get_msg('product_name'); ?></th>
			<th class="table-header"><?php echo get_msg('cat_name'); ?></th>
			<th class="table-header"><?php echo get_msg('subcat_name'); ?></th>
			<th class="table-header"><?php echo get_msg('unit_price') ; ?></th>
			
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

	<?php if ( !empty( $products ) && count( $products->result()) > 0 ): ?>

		<?php foreach($products->result() as $product): ?>
			
			<tr>
				<td class="table-cell align-middle"><?php echo ++$count;?></td>
				<?php if($product->is_featured == 1 ) { ?>
				<td class="table-cell text-left align-middle"><span class="fa fa-diamond" style="color:red;"></span>&nbsp;<?php echo $product->name;?></td>
				<?php } else { ?>
				<td class="table-cell text-left align-middle">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $product->name;?></td>
				<?php } ?>
				<td class="table-cell align-middle"><?php echo $this->Category->get_one( $product->cat_id )->name; ?></td>
				<td class="table-cell align-middle"><?php echo $this->Subcategory->get_one( $product->sub_cat_id )->name; ?></td>
				

				 <td class="table-cell align-middle"><?php  

					if ($product->is_discount == 1) {
						echo '<span class="discount-label" style="text-decoration: line-through;">' . $currency_symbol .
							number_format($product->original_price, 2) . '</span> ';
					} 
						$unit_price = $product->unit_price;
						$unit_price = $this->Shop->get_one($shop_id)->currency_symbol . number_format($unit_price, 2);
						echo $unit_price;
					


				 ?></td>

				<?php if ( $this->ps_auth->has_access( EDIT )): ?>
			
					<td class="table-cell align-middle">
						<a href='<?php echo $module_site_url .'/edit/'. $product->id; ?>'>
							<span class="btn fixed-size-btn btn-warning">Edit</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td class="table-cell align-middle">
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$product->id";?>">
							<span class="btn fixed-size-btn btn-danger">Delete</span>
						</a>
					</td>
				
				<?php endif; ?>
				
				<?php if ( $this->ps_auth->has_access( PUBLISH )): ?>
					
					<td class="table-cell align-middle">
						<?php if ( @$product->status== 1): ?>
							<button class="btn fixed-size-btn btn-success unpublish" id='<?php echo $product->id;?>'>
							<?php echo get_msg('btn_yes'); ?></button>
						<?php else:?>
							<button class="btn fixed-size-btn btn-danger publish" id='<?php echo $product->id;?>'>
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

<style>
	table.dataTable thead .sorting:after,
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_asc_disabled:after,
table.dataTable thead .sorting_asc_disabled:before,
table.dataTable thead .sorting_desc:after,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting_desc_disabled:after,
table.dataTable thead .sorting_desc_disabled:before {
  bottom: .5em;
}
	</style>