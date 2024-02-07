<?php
	$attributes = array( 'id' => 'additional-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>
	
<section class="content animated fadeInRight">
	<div class="card card-info">
	    <div class="card-header">
	        <h3 class="card-title"><?php echo get_msg('food_additional_info')?></h3>
	    </div>
        <!-- /.card-header -->
        <form role="form">
        <div class="card-body">
            <div class="row">
             	<div class="col-md-6">
             		
            		<div class="form-group">
                   		<label> <span style="font-size: 17px; color: red;">*</span>
							<?php echo get_msg('food_add_name')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('name_tooltips')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>

						<?php echo form_input( array(
							'name' => 'name',
							'value' => set_value( 'name', show_data( @$add->name ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'food_add_name' ),
							'id' => 'name'
						)); ?>
              		</div>
					  <div class="form-group">
                   		<label> <span style="font-size: 17px; color: red;">*</span>
							<?php echo get_msg('food_add_price')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('name_tooltips')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>

						<?php echo form_input( array(
							'name' => 'price',
							'value' => set_value( 'price', show_data( @$add->price ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'food_add_price' ),
							'id' => 'price'
						)); ?>
              		</div>
							<label class="form-unchecked-label" id="statusLabel">
							
								<?php echo form_checkbox( array(
									'name' => 'status',
									'id' => 'status',
									'value' => 'accept',
									'checked' => set_checkbox('status', 1, ( @$add->status == 1 )? true: false ),
									'class' => 'form-check-input',
									'onchange' => 'toggleCheckbox(this.id)',
									'style' => 'display:none'
								));	?>

								Published
							</label>
              		<!--<div class="form-group">
						<label><span style="font-size: 17px; color: red;">*</span>
							<?php echo get_msg('food_add_description')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('food_add_description')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						<textarea class="form-control" name="description" placeholder="<?php echo get_msg('food_add_description')?>" rows="5"><?php echo $add->description; ?></textarea>
					</div>-->

              		

              	</div>

              	<div class="col-md-6">
				  <?php if ( !isset( $add )): ?>

<div class="form-group">

	<label> <span style="font-size: 17px; color: red;">*</span>
		<?php echo get_msg('food_add_img')?>
		<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('cat_photo_tooltips')?>">
			<span class='glyphicon glyphicon-info-sign menu-icon'>
		</a>
	</label>

	<br/>

	<input class="btn btn-sm" type="file" name="cover" id="cover" accept="image/*">

</div>

<?php else: ?>




<?php
	$conds = array( 'img_type' => 'food-additional', 'img_parent_id' => $add->id );
	$images = $this->Image->get_all_by( $conds )->result();
?>
	
<?php if ( count($images) > 0 ): ?>
	
	<div class="row">

	<?php $i = 0; foreach ( $images as $img ) :?>

		<?php if ($i>0 && $i%3==0): ?>
				
		</div><div class='row'>
		
		<?php endif; ?>
			
		<div class="image-container" style="">

			<div class="thumbnail">

				<img class="img-rounded img-fluid" src="<?php echo $this->ps_image->upload_url . $img->img_path; ?>">

				<br/>
				
				<!--<p class="text-center">
					
					<a data-toggle="modal" data-target="#deletePhoto" class="delete-img" id="<?php echo $img->img_id; ?>"   
						image="<?php echo $img->img_path; ?>">
						<?php echo get_msg('remove_label'); ?>
					</a>
				</p>-->

			</div>

		</div>

	<?php $i++; endforeach; ?>

	</div>

<?php endif; ?>
<div class="btn fixed-size-btn btn-primary btn-upload " style="margin-top:10px;" data-toggle="modal" data-target="#uploadImage">
	Upload Image
</div>
<?php endif; ?>	
<!-- End Category cover photo -->
		
              	</div>
              	<!--  col-md-6  -->

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
    <!-- card info -->
</section>

<?php echo form_close(); ?>