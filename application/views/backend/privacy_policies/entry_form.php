<?php
	$attributes = array( 'id' => 'policy-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>
	
<section class="animated fadeInRight">
	<div class="content">
	<div class="card card-info">
	    <div class="card-header">
	        <h3 class="card-title"><?php echo get_msg('policy_info')?></h3>
	    </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
             	<div class="col-md-12">
            		<div class="form-group">
                   		<label>
							<?php echo get_msg('content_label')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('key_label')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>

						
						<?php echo form_textarea( array(
							'name' => 'content',
							'value' => set_value( 'content', show_data( @$privacy->content ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'content' ),
							'rows' => '10',
							'id' => 'content',
						)); ?>
              		</div>
                </div>
                <!-- col-md-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->

		<div class="modal-footer">
            <button type="submit" class="btn std-btn-size btn-success">
				<?php echo get_msg('btn_save')?>
			</button>

			<a href="<?php echo $module_site_url; ?>" class="btn std-btn-size btn-secondary">
				<?php echo get_msg('btn_cancel')?>
			</a>
        </div>
       
    </div>
</div>
    <!-- card info -->
</section>
				
<?php echo form_close(); ?>