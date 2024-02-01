<?php
	$attributes = array( 'id' => 'paymentstatus-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>
	
<section class="content animated fadeInRight">
	<div class="col-md-6">
		<div class="card card-info">
		    <div class="card-header">
		        <h3 class="card-title"><?php echo get_msg('pay_status_info')?></h3>
		    </div>
	        <!-- /.card-header -->
	        <div class="card-body">
	            <div class="row">
	             	<div class="col-md-12">
	            		
	              		<div class="form-group">
	                   		<label>
								<?php echo get_msg('deli_status_title')?>
								<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('deli_status_title')?>">
									<span class='glyphicon glyphicon-info-sign menu-icon'>
								</a>
							</label>

							<?php echo form_input( array(
								'name' => 'title',
								'value' => set_value( 'title', show_data( @$pay_status->title ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg( 'deli_status_title' ),
								'id' => 'title'
							)); ?>
	              		</div>
	              	</div>        		
	            </div>
	            <!-- /.row -->
	        </div>
	        <!-- /.card-body -->

			<div class="card-footer">
	            <button type="submit" class="btn btn-sm btn-primary">
					<?php echo get_msg('btn_save')?>
				</button>

				<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
					<?php echo get_msg('btn_cancel')?>
				</a>
	        </div>
	       
	    </div>
	    <!-- card info -->
	</div>
</section>
				
<?php echo form_close(); ?>