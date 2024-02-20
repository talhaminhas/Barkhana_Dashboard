<script>
function runAfterJQ() {
	$(document).ready(function () {
  	$('#contact-table').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [3, 4, 5] } 
            ],
			"pageLength": 15,
        	"lengthChange": false,
			"drawCallback": function (settings) {
				var api = this.api();
				var pageInfo = api.page.info();

				if (pageInfo.pages <= 1) {
					$(this).closest('.dataTables_wrapper').find('.dataTables_paginate').hide();
				} else {
					$(this).closest('.dataTables_wrapper').find('.dataTables_paginate').show();
				}
        	}
        });
	})
	$(document).ready(function(){
		
		// Delete Trigger
		$('.btn-delete').click(function(){

			// get id and links
			var id = $(this).attr('id');
			var btnYes = $('.btn-yes').attr('href');
			var btnNo = $('.btn-no').attr('href');

			// modify link with id
			$('.btn-yes').attr( 'href', btnYes + id );
			$('.btn-no').attr( 'href', btnNo + id );
		});

	});
}
</script>

<?php
	// Delete Confirm Message Modal
	$data = array(
		'title' => get_msg( 'delete_contact_label' ),
		'message' => get_msg( 'contact_delete_confirm_message' ),
		'no_only_btn' => get_msg( 'btn_yes' )
	);
	
	$this->load->view( $template_path .'/components/delete_confirm_modal', $data );
?>