<div class='row '>
	
	<div class="card col-12 card-info">
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
						<?php echo get_msg('extras_csv_file_sample_link');?> 
						<?php echo get_msg('csv_upload_instruction_2');?> 
					</label>
				</div>
                <!-- col-md-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->

		<div class="card-footer" style="display: flex; justify-content: start; align-items: start;">
            <button type="submit"class="btn std-btn-size btn-success">
				<?php echo get_msg('btn_save')?>
			</button>

			<a href="<?php echo $module_site_url; ?>" class="btn std-btn-size btn-secondary" 
			style="margin-left: 10px; display: flex; align-items: center; justify-content: center;">
				<?php echo get_msg('btn_cancel')?>
			</a>
        </div>
	</div>
	<div class='col-9'>
	<!--<?php
		$attributes = array('class' => 'form-inline');
		echo form_open( $module_site_url .'/search', $attributes);
	?>
		
		<div class="form-group mr-3">

			<?php echo form_input(array(
				'name' => 'searchterm',
				'value' => set_value( 'searchterm' ),
				'class' => ' std-field',
				'placeholder' => get_msg( 'btn_search' )
			)); ?>

	  	</div>

	  	<div class="form-group" style="padding-right: 2px;">
		  	<button type="submit" class="btn std-btn-size btn-primary">
		  		<?php echo get_msg( 'btn_search' )?>
		  	</button>
	  	</div>

	  	<div class="form-group">
		  	<a href='<?php echo $module_site_url; ?>' class='btn std-btn-size btn-primary'>
				<?php echo get_msg( 'btn_reset' )?>
			</a>
	  	</div>

	<?php echo form_close(); ?>-->

	</div>	

	<div class='col-3'>
		<a href='<?php echo $module_site_url .'/add';?>' class='btn lrg-btn-size btn-primary pull-right'>
			<span class='fa fa-plus'></span> 
			<?php echo get_msg( 'food_additional_add' )?>
		</a>
	</div>
</div>

