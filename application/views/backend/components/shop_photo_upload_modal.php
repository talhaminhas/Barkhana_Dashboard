<div class="modal fade"  id="uploadImage">
		
	<div class="modal-dialog">
		
		<div class="modal-content">
		
			<div class="modal-header">
		
				<h4 class="modal-title"><?php echo get_msg('replace_photo_button')?></h4>

				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				
			</div>

			<?php
				$attributes = array('id' => 'upload-form','enctype' => 'multipart/form-data');
				echo form_open( $module_site_url ."/replace_cover_photo/". $img_type ."/". $img_parent_id, $attributes);
			?>

			<div class="modal-body">
				
				<div class="form-group">
					<label><?php echo get_msg('upload_photo')?></label>
					
					<input type="file" name="images1">
					
					<br/>
					
					<label><?php echo get_msg('photo_desc_label')?></label>
		
					<textarea style="border: 2px solid grey; border-radius: 10px; width: 100%; padding: 10px;" name="image_desc" rows="9"></textarea>
				</div>
			</div>

			<div class="modal-footer">
				<input type="submit" value="<?php echo get_msg('upload_button')?>" class="btn btn-success fixed-size-btn"/>
		
				<a type="button" style="color:white" class="btn fixed-size-btn btn-secondary" data-dismiss="modal"><?php echo get_msg('btn_cancel')?></a>
			</div>
			
			<?php echo form_close(); ?>

		</div>

	</div>
		
</div>