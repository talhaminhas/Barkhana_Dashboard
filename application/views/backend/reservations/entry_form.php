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
					<label> <span style="font-size: 17px; color: red;">*</span>
                   		<label>
							Reservation Date
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('resv_date_label')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo form_input(array(
							'type' => 'date',
							'name' => 'resv_date',
							'value' => set_value('resv_date', date('Y-m-d', strtotime(str_replace('/', '-', show_data(@$reservation->resv_date))) ?: time()), false),
							'class' => 'form-control form-control-sm',
							'style' => 'height:40px; min-width:250px;',
							'placeholder' => 'Reservation Date',
							'required' => 'required',
							'id' => 'resv_date'
						)); ?>

              		</div>

              		<div class="form-group">
					  	<label> <span style="font-size: 17px; color: red;">*</span>
                   		<label>
							Reservation Time
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('resv_time_label')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo form_input(array(
							'type' => 'time',
							'name' => 'resv_time',
							'value' => set_value('resv_time', date('H:i', strtotime(show_data(@$reservation->resv_time)) ?: time()), false),
							'class' => 'form-control form-control-sm',
							'style' => 'height:40px; min-width:250px;',
							'placeholder' => 'Reservation Time',
							'required' => 'required',
							'id' => 'resv_time'
						)); ?>

						
              		</div>

              		<div class="form-group">
					  <label> <span style="font-size: 17px; color: red;">*</span>
                   		<label>
							Customer Name
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('reserved_by')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>

						<?php echo form_input(array(
							'type' => 'text',  
							'name' => 'user_name',
							'value' => set_value('user_name', @$reservation->user_name, false),
							'class' => 'form-control form-control-sm',
							'style' => 'height:40px; min-width:250px;',
							'placeholder' => 'Customer Name',
							'id' => 'user_name',
							'required' => 'required',
							'maxlength' => '20'
						)); ?>
              		</div>

					  <div class="form-group">
					  <label> <span style="font-size: 17px; color: red;">*</span>
                   		<label>
							Customer Phone
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo form_input(array(
							'type' => 'number',  // Change input type to 'tel' for phone numbers
							'name' => 'user_phone_no',
							'value' => set_value('user_phone', @$reservation->user_phone_no, false),
							'class' => 'form-control form-control-sm',
							'style' => 'height:40px; min-width:250px;',
							'placeholder' => 'Customer Phone',
							'id' => 'user_phone_no',
							'pattern' => '[0-9]{10}',  // Restrict to exactly 10 numeric digits
							'required' => 'required',
						)); ?>
              		</div>

					  <div class="form-group">
                   		<label>
							Customer Email
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo form_input(array(
							'type' => 'email',  // Change input type to 'email'
							'name' => 'user_email',
							'value' => set_value('user_email', @$reservation->user_email, false),
							'class' => 'form-control form-control-sm',
							'style' => 'height:40px; max-width:250px;',
							'placeholder' => 'Customer Email',
							'id' => 'user_email',
							'title' => 'Please enter a valid email address',
						)); ?>

              		</div>

				</div>
				<div class="col-md-6">
              		<div class="form-group" style="">
                   		<label>
							Additional Notes
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('resv_note')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<textarea id="note" style="border: 2px solid grey; border-radius: 10px; width: 100%; padding: 10px;" 
						name="note" placeholder="Additional Notes" rows="9"><?php echo $reservation->note; ?></textarea>
						
              		</div>

              		<div class="form-group">
					  <label> <span style="font-size: 17px; color: red;">*</span>
              			<label>
							<?php echo get_msg('resv_status')?>
						</label>
						<br>
						<select name="resv_status" id="resv_status" 
						style="padding-left: 10px; padding-right: 10px; width: 250px; height: 40px; border: 2px solid #808080; border-radius: 20px;">
							<?php
							$status = $this->Reservation_status->get_all();
							foreach ($status->result() as $status) {
								echo "<option value='".$status->id."'";
								if($reservation->status_id == $status->id) {
									echo " selected ";
								}
								echo ">".$status->title."</option>";
							}
							?>
						</select>
			  		</div>
					  <div class="form-group">
					  <label> <span style="font-size: 17px; color: red;">*</span>
                   		<label>
							Number Of People (1-100)
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<br>
						<?php echo form_input(array(
							'type' => 'number',
							'name' => 'no_of_people',
							'value' => set_value('no_of_people', @$reservation->no_of_people, false),
							'class' => 'form-control form-control-sm',
							'style' => 'height:40px; min-width:250px;',
							'placeholder' => 'Number Of People',
							'id' => 'no_of_people',
							'min' => '1',  // Set the minimum allowed value
							'max' => '100',  // Set the maximum allowed value
							'required' => 'required',
							'title' => 'Please enter a number between 1 and 100',
						)); ?>


              		</div>
              	</div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->

		<div class="modal-footer">
			<button type="submit" class="btn std-btn-size btn-success" >
				<?php echo get_msg('btn_save')?>
			</button>
			
			<a href="<?php echo $module_site_url; ?>" class="btn std-btn-size btn-primary">
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