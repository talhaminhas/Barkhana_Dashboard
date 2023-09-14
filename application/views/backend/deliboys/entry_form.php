<?php
	$attributes = array( 'id' => 'deliboy-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
	$logged_in_user = $this->ps_auth->get_user_info();
	if (isset($deliboy)) {
		if($logged_in_user->user_id == $deliboy->added_user_id){
		    $readonly = "";
		} else {
		    $readonly = "readonly";
		}
	}
?>

<div class="card card-info  animated fadeInRight">
	<div class="card-header">
        <h3 class="card-title"><?php echo get_msg('deliboy_info')?></h3>
    </div>

	<div id="perm_err" class="alert alert-danger fade in" style="display: none">
		<label for="permissions[]" class="error"></label>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	</div>
        	<!-- /.card-header -->	

	        <div class="card-body">
	            <div class="row">
	             	<div class="col-md-6">
	            		<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('user_name')?></label>

							<?php echo form_input(array(
								'name' => 'user_name',
								'value' => set_value( 'user_name', show_data( @$deliboy->user_name ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('user_name'),
								'id' => 'name',
								$readonly => $readonly
							)); ?>

						</div>

						<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('user_email')?></label>

							<?php echo form_input(array(
								'name' => 'user_email',
								'value' => set_value( 'user_email', show_data( @$deliboy->user_email ), false ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('user_email'),
								'id' => 'user_email',
								$readonly => $readonly
							)); ?>

						</div>

	                </div>

	                <div class="col-md-6">

						<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('user_password')?></label>

							<?php echo form_input(array(
								'type' => 'password',
								'name' => 'user_password',
								'value' => set_value( 'user_password' ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('user_password'),
								'id' => 'user_password',
								$readonly => $readonly
							)); ?>
						</div>
									
						<div class="form-group">
							<label> <span style="font-size: 17px; color: red;">*</span>
								<?php echo get_msg('conf_password')?></label>
							
							<?php echo form_input(array(
								'type' => 'password',
								'name' => 'conf_password',
								'value' => set_value( 'conf_password' ),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('conf_password'),
								'id' => 'conf_password',
								$readonly => $readonly
							)); ?>
						</div>

	                </div>
	                <!--  col-md-6  -->

	            </div>
	            <?php 
	            	$logged_in_user = $this->ps_auth->get_user_info();

	            	if ( $logged_in_user->user_is_sys_admin == 1 && isset($deliboy)): 
	            ?>
	            <hr>
	            <div class="form-group" style="background-color: #edbbbb; padding: 20px;">
					<label>
						<strong><?php echo get_msg('select_status')?></strong>
					</label>

					<select id="deliboy_is_published" name="deliboy_is_published" class="form-control">
					   <?php if ($deliboy->status == 1) { ?> 
					   		<option value="0"><?php echo get_msg('select_label'); ?></option>
					   		<option value="1" selected><?php echo get_msg('approved_label')?></option>
					   		<option value="3"><?php echo get_msg('reject_label')?></option>
					   <?php }else if ($deliboy->status == 2) { ?>
					   		<option value="0" selected><?php echo get_msg('select_label'); ?></option>
					   		<option value="1"><?php echo get_msg('approve_label')?></option>
					   		<option value="3"><?php echo get_msg('reject_label')?></option>
					   <?php }else{  ?>
					  		<option value="0"><?php echo get_msg('select_label'); ?></option>
					   		<option value="1"><?php echo get_msg('approve_label')?></option>
					   		<option value="3" selected><?php echo get_msg('rejected_label')?></option>
					   <?php } ?>
					</select>
				</div>	
				<?php endif; ?>	
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
    </div>
    <!-- card info -->
</div>
				

	
	

<?php echo form_close(); ?>