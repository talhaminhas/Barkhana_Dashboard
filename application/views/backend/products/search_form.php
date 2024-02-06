<?php
	$attributes = array('id' => 'search-form', 'enctype' => 'multipart/form-data');
	echo form_open( $module_site_url .'/search', $attributes);
?>

<div class='row my-3'>
	<div class="col-14">
		<div class='form-inline'>
			

		  	

	  		

		  <!-- 	<div class="form-group" style="padding-top: 3px;">
			
				<?php echo get_msg( 'from_price' ); ?>
					<?php echo form_input(array(
						'name' => 'price_min',
						'value' => set_value( 'price_min', $price_min ),
						'class' => 'form-control form-control-sm mr-3 ml-2',
						'placeholder' => get_msg( 'from_label' ),
						'pattern' => "^[0-9]*$",
						'type' => "number"
					)); ?>


				<?php echo get_msg( 'to_price' ); ?>
					<?php echo form_input(array(
						'name' => 'price_max',
						'value' => set_value('price_max', $price_max ),
						'class' => 'form-control form-control-sm mr-3 ml-2',
						'placeholder' => get_msg( 'to_label' ),
						'pattern' => "^[0-9]*$",
						'type' => "number"
					)); ?>

				
			</div> -->

		</div>
	</div>
</div>
<div class="" >	
	<!-- end form-inline -->
	<div class="row">
	<table class=" col-9" style="width:100%">
		<tr>
			<td class="table-cell align-middle" colspan="3">
					<?php echo form_input(array(
						'name' => 'searchterm',
						'value' => set_value( 'searchterm', $searchterm ),
						'class' => ' mr-3 std-field',
						'placeholder' => get_msg( 'btn_search' )
					)); ?>
			</td>
			<td class="table-cell align-middle" colspan="3">
					<?php
						$options=array();
						$options[0]=get_msg('Prd_search_cat');
						
						$categories = $this->Category->get_all( );
						foreach($categories->result() as $cat) {
							
								$options[$cat->id]=$cat->name;
						}
						
						echo form_dropdown(
							'cat_id',
							$options,
							set_value( 'cat_id', show_data( $cat_id ), false ),
							'class="std-field mr-3" id="cat_id"'
						);
					?> 

					<input type="hidden"  id="addonselect" name="addonselect">
			</td>
			<td class="table-cell align-middle" colspan="3">

				<?php
					if($selected_cat_id != "") {

						$options=array();
						$options[0]=get_msg('Prd_search_subcat');
						$conds['cat_id'] = $selected_cat_id;
						$sub_cat = $this->Subcategory->get_all_by($conds);
						foreach($sub_cat->result() as $subcat) {
							$options[$subcat->id]=$subcat->name;
						}
						echo form_dropdown(
							'sub_cat_id',
							$options,
							set_value( 'sub_cat_id', show_data( $sub_cat_id ), false ),
							'class="std-field mr-3" id="sub_cat_id"'
						);

					} else {

						$conds['cat_id'] = $selected_cat_id;
						$options=array();
						$options[0]=get_msg('Prd_search_subcat');

						echo form_dropdown(
							'sub_cat_id',
							$options,
							set_value( 'sub_cat_id', show_data( $sub_cat_id ), false ),
							'class="std-field mr-3" id="sub_cat_id"'
						);
					}
				?>
			</td>
			
	</tr>
	<tr>
		<!-- First three columns spanning two columns collectively -->
		<td class="table-cell align-middle" colspan="2" >
				<div class="form-check">
					<label class="form-check-label">
						<?php 
							echo form_checkbox(array(
								'name' => 'is_featured',
								'id' => 'is_featured',
								'value' => 'is_featured',
								'checked' => ($is_featured == 1) ? true : false,
								'class' => 'form-check-input'
							));	
						?>
						<?php echo get_msg('is_featured'); ?>
					</label>
				</div>
		</td>
		<!-- Remaining two columns individually -->
		<td class="table-cell align-middle" colspan="2">
				<div class="form-check">
					<label class="form-check-label">
						<?php 
							echo form_checkbox(array(
								'name' => 'is_available',
								'id' => 'is_available',
								'value' => 'is_available',
								'checked' => ($is_available == 1) ? true : false,
								'class' => 'form-check-input'
							));	
						?>
						<?php echo get_msg('is_available'); ?>
					</label>
				</div>
		</td>
		<td class="table-cell align-middle" colspan="2">
				<div class="form-check">
					<label class="form-check-label">
						<?php 
							echo form_checkbox(array(
								'name' => 'is_discount',
								'id' => 'is_discount',
								'value' => 'is_discount',
								'checked' => ($is_discount == 1) ? true : false,
								'class' => 'form-check-input'
							));	
						?>
						<?php echo get_msg('is_discount'); ?>
					</label>
				</div>
		</td>

		<td class="table-cell align-middle" colspan="3">
				<?php
					$options = array();
					$options[0] = get_msg('select_order');
					foreach ($this->Order->get_all()->result() as $ord) {
						$options[$ord->id] = $ord->name;
					}
					echo form_dropdown(
						'order_by',
						$options,
						set_value('order_by', show_data($order_by), false),
						'class="mr-3 ml-2 std-field" id="order_by"'
					);
				?>
			</td>
		
		</tr>

	</table>
	<table class="col-2 table-bordered">
		<tr>
			<td class="" colspan="3">
				<button type="submit" class="btn fixed-size-btn btn-success" value="submit" name="submit" style=" " >
					<?php echo get_msg('btn_search') ?>
				</button>	
			</td>
		</tr>
		<tr>
			<td class="table-cell align-middle" >
				<a href='<?php echo $module_site_url . '/index'; ?>' class="btn fixed-size-btn btn-primary" style="">
					<?php echo get_msg('btn_reset') ?>
				</a>
			</td>
		</tr>
		<tr>
			<td class="table-cell align-middle" >
				<a href='<?php echo $module_site_url .'/add';?>' class='btn fixed-size-btn btn-warning' >
					<i class='fa fa-plus'> </i> 
					<span> Add Food</span>
				</a>
				<?php echo form_close(); ?>
			</td>
		</tr>
	</table>
				</div>

	<div class="col-12">
	<hr>
	<div class="card card-info">
        <div class="card-body">

		<?php
		$attributes = array('enctype' => 'multipart/form-data');
		echo form_open( $module_site_url .'/upload', $attributes);
		?>

            <div class="row">
             	<div class="col-md-6">
            		<div class="form-group">
                   		
                   		<div class="form-group">
							
                   			<?php
                   				if( $message ) {
                   					echo "<br>";
                   					echo $message;
                   					echo "<br>";
                   				}
                   			 ?>

							<span style="font-size: 17px; color: red;">*</span>
							<label>
								<?php echo get_msg('select_csv_file');?> 
							</label>


							<br/>
							<input class="btn btn-sm" type="file" name="file" id="file" accept=".csv">
						</div>


              		</div>
                </div>
				<div class="col-md-6">
					<label>
						<?php echo get_msg('csv_upload_instruction_1');?> 
						<?php echo get_msg('product_csv_file_sample_link');?> 
						<?php echo get_msg('csv_upload_instruction_2');?> 
					</label>
				</div>
                <!-- col-md-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->

		<div class="card-footer" style="display: flex; justify-content: start; align-items: start;">
            <button type="submit" class="btn std-btn-size btn-success">
				<?php echo get_msg('btn_save')?>
			</button>

			<a href="<?php echo $module_site_url; ?>"class="btn std-btn-size btn-secondary" style="margin-left: 10px; display: flex; align-items: center; justify-content: center;">
				<?php echo get_msg('btn_cancel')?>
			</a>
        </div>

</div>

	

<script>
	
<?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>
	function jqvalidate() {
	
    $('.dropdown-sin-2').dropdown();
     
	$('#cat_id').on('change', function() {
		
			var catId = $(this).val();
			
			$.ajax({
				url: '<?php echo $module_site_url . '/get_all_sub_categories/';?>' + catId,
				method: 'GET',
				dataType: 'JSON',
				success:function(data){
					$('#sub_cat_id').html("");
					$.each(data, function(i, obj){
					    $('#sub_cat_id').append('<option value="'+ obj.id +'">' + obj.name + '</option>');
					});
					$('#name').val($('#name').val() + " ").blur();
				}
			});
		});
}
	<?php endif; ?>
</script>