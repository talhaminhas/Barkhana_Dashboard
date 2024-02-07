<?php

	$attributes = array( 'id' => 'discount-form', 'enctype' => 'multipart/form-data');
	echo form_open( '', $attributes);

?>
		
<section class="content animated fadeInRight">
	<div class="card card-info">
	    <div class="card-header">
	        <h3 class="card-title"><?php echo get_msg('dis_info')?></h3>
	    </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
             	<div class="col-md-6">
					<div class="form-group">
                   		<label> <span style="font-size: 17px; color: red;">*</span>
							<?php echo get_msg('dis_name')?>
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo get_msg('name_tooltips')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
							<?php echo form_input( array (
								'name' => 'name',
								'value' => set_value( 'name', show_data(@$discount->name),false),
								'class' => 'form-control form-control-sm',
								'placeholder' => get_msg('dis_name'),
								'id' => 'name'
							)); ?>
					</div>	
					
					<div class="form-group">
						<label><span style="font-size: 17px; color: red;">*</span>
							<?php echo get_msg('dis_percent') ?>
						</label>
									
						<?php echo form_input( array (
							'name' => 'percent',
							'value' => set_value( 'percent', show_data(@$discount->percent*100),false),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg('pls_dis_percent'),
							'id' => 'percent'
						)); ?>
					</div>
				</div>	

				<div class="col-md-6" style="margin-top: 20px;">

							<label class="form-unchecked-label" id="statusLabel">
							
								<?php echo form_checkbox( array(
									'name' => 'status',
									'id' => 'status',
									'value' => 'accept',
									'checked' => set_checkbox('status', 1, ( @$discount->status == 1 )? true: false ),
									'class' => 'form-check-input',
									'onchange' => 'toggleCheckbox(this.id)',
									'style' => 'display:none'
								));	?>
								
								Pulished
							</label>

					

				</div>	
			</div>	
			<hr>

			<div class="row" style="padding: 10px 20px 5px 10px;">
			   
			    <div class="col-md-12">
				  <table id="product-table" class="table table-bordered table-hover">
				     
				     <thead>
					      <tr>
					      	<th class="table-header"><input name="select_all" value="1" type="checkbox"></th>
					     	<th class="table-header"><?php echo get_msg('prd_name'); ?></th>
					     	<th class="table-header"><?php echo get_msg('prd_code'); ?></th>
			         	    <th class="table-header"><?php echo get_msg('product_price'); ?></th>
					      </tr>
					   </thead>
				     <tbody class="text-center">
				     </tbody>
			   	  </table>
			    </div>
			</div>
	
			<div class="modal-footer">		
				<button type="submit" class="btn std-btn-size btn-success" >
					<?php echo get_msg('btn_save')?> 
				</button>

				<a href="<?php echo $module_site_url; ?>" class="btn std-btn-size btn-secondary">
					<?php echo get_msg('btn_cancel')?>
				</a>

				<div id="divCheckbox" style="display: none;"> 
					<p><b><?php echo get_msg('selected_row_data')?>:</b></p>
					<pre id="example-console-rows"></pre>

					<p><b><?php echo get_msg('form_data_submit_server')?>:</b></p>
					<pre id="example-console-form"></pre>


					<input type="text" name="newchkval" id="newchkval" size="300">


				</div> 
			</div>



<?php echo form_close(); ?>

