<script>

$(document).ready(function () {
    $('#food-table').DataTable({
        "pageLength": 20,
        "lengthChange": false,
        "columnDefs": [
            { "orderable": false, "targets": [5,6,7] }
        ],
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
});

function runAfterJQ() {
		
		// Publish Trigger
		$(document).delegate('.publish','click',function(){
			
			// get button and id
			var btn = $(this);
			var id = $(this).attr('id');

			// Ajax Call to publish
			$.ajax({
				url: "<?php echo $module_site_url .'/ajx_publish/'; ?>" + id,
				method: 'GET',
				success: function( msg ) {
					if ( msg == 'true' )
						btn.addClass('unpublish').addClass('btn-success')
							.removeClass('publish').removeClass('btn-danger')
							.html("<?php echo get_msg( 'btn_yes' ); ?>");
					else
						alert( "<?php echo get_msg( 'err_sys' ); ?>" );
				}
			});
		});
		
		// Unpublish Trigger
		$(document).delegate('.unpublish','click',function(){

			// get button and id
			var btn = $(this);
			var id = $(this).attr('id');

			// Ajax call to unpublish
			$.ajax({
				url: "<?php echo $module_site_url .'/ajx_unpublish/'; ?>" + id,
				method: 'GET',
				success: function( msg ){
					if ( msg == 'true' )
						btn.addClass('publish').addClass('btn-danger')
							.removeClass('unpublish').removeClass('btn-success')
							.html("<?php echo get_msg( 'btn_no' ); ?>");
					else
						alert( "<?php echo get_msg( 'err_sys' ); ?>" );
				}
			});
	});
}

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

</script>

<?php
	// Delete Confirm Message Modal
	$data = array(
		'title' => get_msg( 'delete_prd_label' ),
		'message' =>  get_msg( 'prd_yes_all_message' ),
		'no_only_btn' => get_msg( 'prd_no_only_label' )
	);
	
	$this->load->view( $template_path .'/components/delete_confirm_modal', $data );
?>