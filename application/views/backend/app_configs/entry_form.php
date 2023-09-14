<?php
	$attributes = array( 'id' => 'appconfig-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>


<div class="content animated fadeInRight">
	<div class="col-md-9">
			
		<div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><?php echo get_msg('app_config_info')?></h3>
          </div>

        <form role="form">
            <div class="card-body">
				<div class="col-md-8">
					
					<div class="form-group" style="padding-top: 30px;">
						<div class="form-check">

							<label>
							
								<?php echo form_checkbox( array(
									'name' => 'enable_comment',
									'id' => 'enable_comment',
									'value' => 'accept',
									'checked' => set_checkbox('enable_comment', 1, ( @$appsetting->enable_comment == 1 )? true: false ),
									'class' => 'form-check-input'
								));	?>

								<?php echo get_msg( 'enable_comment' ); ?>
							</label>
						</div>
					</div>

					<div class="form-group" style="padding-top: 30px;">
						<div class="form-check">

							<label>
							
								<?php echo form_checkbox( array(
									'name' => 'enable_review',
									'id' => 'enable_review',
									'value' => 'accept',
									'checked' => set_checkbox('enable_review', 1, ( @$appsetting->enable_review == 1 )? true: false ),
									'class' => 'form-check-input'
								));	?>

								<?php echo get_msg( 'enable_review' ); ?>
							</label>
						</div>
					</div>

				</div>
		
			</div>

			<div class="card-footer">
                <button type="submit" class="btn btn-sm btn-primary">
						<?php echo get_msg('btn_save')?>
				</button>

				<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
					<?php echo get_msg('btn_cancel')?>
				</a>
            </div>

		</div>
	</div>
</div>
	
<?php echo form_close(); ?>

