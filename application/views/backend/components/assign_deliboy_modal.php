<style>
    .hidden-radio {
        position: absolute;
        opacity: 0;
    }
	.form-check-label {
		width:100%;
		height: 40px; 
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 5px;
		font-weight: bold; 
    }
	.form-check {
        display: flex;
		align-items: center;
		
	}
	.modal-body {
        max-height: 400px;
        margin: auto; 
		overflow-y: auto;
    }
	

</style>
<div class="modal fade"  id="assignDeliboyModal">

	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-header d-flex justify-content-center">
				<h4 class="modal-title"><?php echo $title; ?></h4>
			</div>
			<?php
				$attributes = array('id' => 'assign-deliboy-form','enctype' => 'multipart/form-data');
				echo form_open( $module_site_url . '/assign_delivery_boy/', $attributes);
			?>
			
			<div class="modal-body">
    <div class="">

        <?php
        $conds['role_id'] = 5;
        $conds['status'] = 1;
        $deli_boys = $this->User->get_all_by($conds);
        foreach ($deli_boys->result() as $boy) {
            echo '<div class="form-check mb-3">';
            echo "<input type='radio' id='delivery_boy_id_$boy->user_id' name='delivery_boy_id' class='form-check-input mr-2 ' value='" . $boy->user_id . "'>";
            echo "<label for='delivery_boy_id_$boy->user_id' class='form-check-label btn btn-outline-primary'>" . $boy->user_name . '</label>';
            echo '</div>';
        }
        ?>
    </div>
</div>




				<div class="modal-footer">

					<input type="submit" value="<?php echo get_msg('btn_yes') ?>" class="btn fixed-size-btn btn-success btn-yes"/>

					<a href='#' class="btn fixed-size-btn btn-danger" data-dismiss="modal"><?php echo get_msg('btn_cancel')?></a>

				</div>
			
				<?php echo form_close(); ?>

		</div>

	</div>

</div>