<div class="modal fade"  id="myModal">

	<div class="modal-dialog">
		
		<div class="modal-content">

			<div class="modal-header d-flex justify-content-center">

				<h4 class="modal-title"><?php echo $title; ?></h4>
			</div>

			<div class="modal-body text-center d-flex justify-content-center">
				<p><?php echo $message; ?></p>
			</div>

			<div class="modal-footer">

				<!-- <a class="btn btn-sm btn-primary btn-no" href='<?php echo $module_site_url ."/delete_all/";?>'>
				<?php echo get_msg( 'subcat_yes_all_label' ) ?></a> -->

				<!-- <a class="btn btn-sm btn-primary btn-no" href='<?php echo $module_site_url ."/delete/";?>'>
				<?php echo get_msg( 'subcat_no_only_label' ); ?></a> -->

				<a class="btn fixed-size-btn btn-danger btn-no" href='<?php echo $module_site_url ."/delete/";?>'>
					Confirm
				</a>

				<a href='#' class="btn fixed-size-btn btn-secondary" data-dismiss="modal">
				<?php echo get_msg( 'btn_cancel' )?></a>
			</div>

		</div><!-- /.modal-content -->

	</div><!-- /.modal-dialog -->

</div><!-- /.modal -->