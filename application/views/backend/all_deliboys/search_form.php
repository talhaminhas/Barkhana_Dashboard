<div class='row my-3' style="padding: 10px 30px 10px 30px;">

	<div class='col-md-12'>
			<?php
				$attributes = array('class' => 'form-inline');
				echo form_open( $module_site_url .'/search', $attributes);
				?>
		<div class="form-group">
			<?php echo form_input(array(
			'name' => 'searchterm',
			'value' => set_value( 'searchterm' ),
			'class' => 'form-control form-control-sm',
			'placeholder' => get_msg( 'btn_search' ),
			'style' => 'float: left; margin-right: 20px;'
			)); ?>
		</div>

		<div class="form-group mr-3">
				<div class="input-group">
			    <div class="input-group-prepend">
			      <span class="input-group-text">
			        <i class="fa fa-calendar"></i>
			      </span>
			    </div>
				<?php echo form_input(array(
						'name' => 'date',
						'value' => set_value( 'date' ),
						'class' => 'form-control',
						'placeholder' => '',
						'id' => 'reservation',
						'size' => '40',
						'readonly' => 'readonly',
						'style' => 'float: left; margin-right: 20px;'
					)); ?>

				</div>
		</div>

		<div class="form-group">
		  	<button type="submit" class="btn btn-sm btn-primary" name="submit" value="submit">
		  		<?php echo get_msg( 'btn_search' )?>
		  	</button>
	  	</div>

  		<div class="form-group ml-3">
		  	<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
				<?php echo get_msg( 'btn_reset' ); ?>
			</a>
		</div>
	</div>	


	  	<?php echo form_close(); ?>	
</div>