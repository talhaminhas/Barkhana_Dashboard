<?php
	$attributes = array( 'id' => 'reserve-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>
	
<section class="content animated fadeInRight">
	<div class="card card-info">
	    <div class="card-header">
	        <h3 class="card-title"><?php echo get_msg('resv_info')?></h3>
	    </div>
        <!-- /.card-header -->
        <form role="form">
        <div class="card-body">
            <div class="row">

             	<div class="col-md-6">
            		<div class="form-group">
                   		<label>
							<?php echo get_msg('resv_date_label')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('resv_date_label')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo @$reservation->resv_date;?>
						
              		</div>

              		<div class="form-group">
                   		<label>
							<?php echo get_msg('resv_time_label')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('resv_time_label')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo @$reservation->resv_time;?>
						
              		</div>

              		<div class="form-group">
                   		<label>
							<?php echo get_msg('reserved_by')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('reserved_by')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo @$reservation->user_name . "(" . $reservation->user_email . ")";?>
						
              		</div>

              		<div class="form-group">
                   		<label>
							<?php echo get_msg('resv_note')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('resv_note')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo @$reservation->note;?>
						
              		</div>

              		<div class="form-group">
              			<label>
							<?php echo get_msg('resv_status')?>
						</label><br>
						<select  name="resv_status" id="resv_status">
						<?php
							$status = $this->Reservation_status->get_all();
							foreach ($status->result() as $status) 
							{
								echo "<option value='".$status->id."'";
									if($reservation->status_id == $status->id) 
									{
										echo " selected ";
									}
										echo ">".$status->title."</option>";
							}
							
						?>
						</select>

			  		</div>
              	</div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->

		<div class="card-footer">
            <hr/>
				
			<button type="submit" class="btn btn-sm btn-primary">
				<?php echo get_msg('btn_save')?>
			</button>
			
			<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
				<?php echo get_msg('btn_cancel')?>
			</a>
			<input type="hidden" id="resv_status_hidden" name="resv_status_hidden" value="<?php echo $reservation->status_id; ?>">
			<input type="hidden" id="resv_date_hidden" name="resv_date_hidden" value="<?php echo $reservation->resv_date; ?>">
			<input type="hidden" id="resv_time_hidden" name="resv_time_hidden" value="<?php echo $reservation->resv_time; ?>">
			<input type="hidden" id="resv_id_hidden" name="resv_id_hidden" value="<?php echo $reservation->id; ?>">
			
			<input type="hidden" id="resv_user_id_hidden" name="resv_user_id_hidden" value="<?php echo $reservation->user_id; ?>">
			<input type="hidden" id="resv_user_email_hidden" name="resv_user_email_hidden" value="<?php echo $reservation->user_email; ?>">
			<input type="hidden" id="resv_user_name_hidden" name="resv_user_name_hidden" value="<?php echo $reservation->user_name; ?>">
			<input type="hidden" id="resv_user_phone_hidden" name="resv_user_phone_hidden" value="<?php echo $reservation->user_phone_no; ?>">
			<input type="hidden" id="resv_shop_id_hidden" name="resv_shop_id_hidden" value="<?php echo $reservation->shop_id; ?>">
			<input type="hidden" id="resv_note_hidden" name="resv_note_hidden" value="<?php echo $reservation->note; ?>">
        </div>
       
    </div>
    <!-- card info -->
</section>
				

	
	

<?php echo form_close(); ?>