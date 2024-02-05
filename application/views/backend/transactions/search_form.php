<?php
	$attributes = array('id' => 'search-form', 'enctype' => 'multipart/form-data');
	echo form_open( $module_site_url .'/search', $attributes);
?>
<div class="col-sm-6 ">
		<table class="table  table-bordered ">
			<tr>
				<th class="align-middle table-header" style="font-size: 20px" colspan = "3">Search</th>
			</tr>
			<tr>
				<td class="align-middle table-cell" style="font-weight: bold; ">Order Number</td>
				<td class="align-middle table-cell">
					<div class="form-group">
						<?php echo form_input(array(
							'name'        => 'searchterm',
							'value'       => set_value('searchterm', $searchterm),
							'class'       => 'form-control form-control-sm',
							'placeholder' => 'Search Order Number',
							'style'       => 'height: 40px; line-height: 40px; float: left; margin-right: 20px;'
						)); ?>
					</div>
				</td>

				<td class="align-middle table-cell "  >
					<a href="<?php echo $module_site_url; ?>" class="btn fixed-size-btn btn-danger" style = "">
								<?php echo get_msg( 'btn_reset' ); ?>
					</a>
				</td>
			</tr>
			<tr>
				<td class="align-middle table-cell" style="font-weight: bold; ">Date</td>
				<td class="align-middle table-cell" >
					<div class="input-group" >
						<div class="input-group-prepend">
						<span class="input-group-text">
							<i class="fa fa-calendar" ></i>
						</span>
						</div>
						<?php echo form_input(array(
								'name' => 'date',
								'value' => set_value( 'date' , $date ),
								'class' => 'form-control',
								'placeholder' => 'Select Date Range',
								'id' => 'reservation',
								'size' => '20',
								'readonly' => 'readonly',
								'style'       => 'height: 40px; line-height: 40px; float: left;'
							)); ?>

					</div>
				</td>
				<td class="align-middle table-cell "  >
					<button type="submit" value="submit" name="submit" class="btn fixed-size-btn btn-success" style = "">
						<?php echo get_msg( 'btn_search' )?>
					</button>
				</td>
			</tr>
		</table>
	</div>
<?php echo form_close(); ?>