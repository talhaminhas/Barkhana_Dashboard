
<?php
	$attributes = array( 'id' => 'subcategory-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);
?>

	<section class="content animated fadeInRight">
		<div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><?php echo get_msg('subcat_info')?></h3>
          </div>

			<form role="form">
        		<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label> <span style="font-size: 17px; color: red;">*</span>
									<?php echo get_msg('Prd_search_cat')?>
								</label>

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
										set_value( 'cat_id', show_data( @$subcategory->cat_id), false ),
										'class="form-control form-control-sm mr-3" id="cat_id" style="height:40px"'
									);
								?>

							</div>

							<div class="form-group">
								<label> <span style="font-size: 17px; color: red;">*</span>
									<?php echo get_msg('subcat_name')?>
								</label>

								<?php echo form_input( array(
									'name' => 'name',
									'value' => set_value( 'name', show_data( @$subcategory->name), false ),
									'class' => 'form-control form-control-sm',
									'placeholder' => get_msg('pls_sub_cat'),
									'style' => 'height:40px',
									'id' => 'name'
								)); ?>

							</div>
								<label class="form-unchecked-label" id="statusLabel">
								
									<?php echo form_checkbox( array(
										'name' => 'status',
										'id' => 'status',
										'value' => 'accept',
										'checked' => set_checkbox('status', 1, ( @$subcategory->status == 1 )? true: false ),
										'class' => 'form-check-input',
										'onchange' => 'toggleCheckbox(this.id)',
										'style' => 'display:none'
										
									));	?>

									Published
								</label>

						</div>

						<div class="col-md-6">
							<?php if ( !isset( $subcategory )): ?>

							<div class="form-group">
								
								<label><span style="font-size: 17px; color: red;">*</span>
									<?php echo get_msg('subcat_img')?> 
								</label>

								<br/>

								<input class="btn btn-sm" type="file" name="cover" id="cover" accept="image/*">

							</div>

							<?php else: ?>
								
								
								
								<?php
									$conds = array( 'img_type' => 'sub_category', 'img_parent_id' => $subcategory->id );
									
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
												<p style="position: absolute; bottom: 10px; left: 10px; width: 100%; margin: 0;">
													
													<a data-toggle="modal" data-target="#deletePhoto" class="delete-img" id="<?php echo $img->img_id; ?>"   
														image="<?php echo $img->img_path; ?>">
														<span class="btn btn-danger">Remove</span>
													</a>
												</p>

											</div>

										</div>

									<?php endforeach; ?>

									</div>
								
								<?php endif; ?>
								<div class="btn fixed-size-btn btn-primary btn-upload" style="margin-top:10px;" data-toggle="modal" data-target="#uploadImage">
									Upload Subcategory Image
								</div>
							<?php endif; ?>	


							<!--<?php if ( !isset( $subcategory )): ?>

								<div class="form-group">
									
									<label> <span style="font-size: 17px; color: red;">*</span>
										<?php echo get_msg('subcat_icon')?> 
									</label>

									<br/>

									<input class="btn btn-sm" type="file" name="icon" id="icon">
								</div>

							<?php else: ?>

								 <label> <span style="font-size: 17px; color: red;">*</span>
									<?php echo get_msg('subcat_icon')?>
								</label> 
								
								
								<div class="btn btn-sm btn-primary btn-upload pull-right" data-toggle="modal" data-target="#uploadIcon">
									<?php echo get_msg('btn_replace_icon')?>
								</div>
								
								<hr/>
								
								<?php

									$conds = array( 'img_type' => 'subcat_icon', 'img_parent_id' => $subcategory->id );
									
									$images = $this->Image->get_all_by( $conds )->result();
								?>
									
								<?php if ( count($images) > 0 ): ?>
									
									<div class="row">

									<?php $i = 0; foreach ( $images as $img ) :?>

										<?php if ($i>0 && $i%3==0): ?>
												
										</div><div class='row'>
										
										<?php endif; ?>
											
										<div class="col-md-4" style="height:100">

											<div class="thumbnail">

												<img src="<?php echo $this->ps_image->upload_url . $img->img_path; ?>">

												<br/>
												
												<p class="text-center">
													
													<a data-toggle="modal" data-target="#deleteIcon" class="delete-img" id="<?php echo $img->img_id; ?>"   
														image="<?php echo $img->img_path; ?>">
														<?php echo get_msg('remove_label'); ?>
													</a>
												</p>

											</div>

										</div>
									

									<?php endforeach; ?>

									</div>
								
								<?php endif; ?>

							<?php endif; ?>-->
						</div>
					
					</div>	

				</div>
			
				<div class="modal-footer">
			     	<button type="submit" class="btn std-btn-size btn-success">
						<?php echo get_msg('btn_save')?>
					</button>

					<a href="<?php echo $module_site_url; ?>" class="btn std-btn-size btn-secondary">
						<?php echo get_msg('btn_cancel')?>
					</a>
			    </div>

			</div>


<?php echo form_close(); ?>

</section>