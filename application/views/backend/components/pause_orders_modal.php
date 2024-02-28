<style>
    .hidden-radio {
        position: absolute;
        opacity: 0;
    }

    .form-check-label {
    width: 100%;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s; /* Add a smooth transition effect */
	}


	.form-check-input:checked + .form-check-label {
		background-color: #0275d8;
		color: #fff; /* Add text color when checked */
	}

    .modal-body {
        max-height: 400px;
        margin-right: 15px ;
        overflow-y: auto;
    }

	
</style>

<div class="modal fade" id="pauseOrdersModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title"><?php echo $title; ?></h4>
            </div>
            <?php
            $attributes = array('id' => 'pause-order-form', 'enctype' => 'multipart/form-data');
            echo form_open($module_site_url . '/pause_orders/', $attributes);
            ?>

            <div class="modal-body">
                <div class="">
                    <?php
                    /*$conds['role_id'] = 5;
                    $conds['status'] = 1;
                    $deli_boys = $this->User->get_all_by($conds);
                    foreach ($deli_boys->result() as $boy) {
                        echo '<div class="form-check mb-3">';
                        echo "<input type='radio' id='delivery_boy_id_$boy->user_id' name='delivery_boy_id' class='hidden-radio form-check-input' value='" . $boy->user_id . "'>";
                        echo "<label for='delivery_boy_id_$boy->user_id' class='form-check-label btn btn-outline-primary'>" . $boy->user_name . '</label>';
                        echo '</div>';
                    }*/
                    ?>
                    <div class="form-check mb-3">
                        <input type='radio' id='5' name='duration' class='hidden-radio form-check-input' value='5'>
                        <label for='5' class='form-check-label btn btn-outline-primary'> 5 Minutes</label>
                    </div>
                    <div class="form-check mb-3">
                    <input type='radio' id='15' name='duration' class='hidden-radio form-check-input' value='15'>
                        <label for='15' class='form-check-label btn btn-outline-primary'> 15 Minutes</label>
                    </div>
                    <div class="form-check mb-3">
                    <input type='radio' id='30' name='duration' class='hidden-radio form-check-input' value='30'>
                        <label for='30' class='form-check-label btn btn-outline-primary'> 30 Minutes</label>
                    </div>
                    <div class="form-check mb-3">
                    <input type='radio' id='60' name='duration' class='hidden-radio form-check-input' value='60'>
                        <label for='60' class='form-check-label btn btn-outline-primary'> 1 Hour</label>
                    </div>
                    <div class="form-check mb-3">
                    <input type='radio' id='0' name='duration' class='hidden-radio form-check-input' value='-1'>
                        <label for='0' class='form-check-label btn btn-outline-primary'> Rest Of The Day</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="submit" value="<?php echo get_msg('btn_yes') ?>" class="btn fixed-size-btn btn-success btn-yes" />
                <a href='#' class="btn fixed-size-btn btn-danger" data-dismiss="modal"><?php echo get_msg('btn_cancel') ?></a>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
