<script>
	
function runAfterJQ() {

	$(document).ready(function(){
		$(document).delegate('.publish','click',function(){

			
		var sortOrder = 'asc';
		// Toggle sorting order between ascending and descending
		sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';

		// Get the column name from the header's data attribute
		var columnName = $(this).data('column');
		

		
		var $table = $('.table');
		var $rows = $table.find('tbody > tr').get();

		$rows.sort(function(a, b) {
			var keyA = $(a).find('.' + columnName).text().toUpperCase();
			var keyB = $(b).find('.' + columnName).text().toUpperCase();

			// Compare the values based on sortOrder (ascending or descending)
			if (sortOrder === 'asc') {
			return keyA.localeCompare(keyB);
			} else {
			return keyB.localeCompare(keyA);
			}
		});

		// Reorder the rows in the table
		$.each($rows, function(index, row) {
			$table.children('tbody').append(row);
		});
		
			});
		
		
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
					if ( msg == true ){
						btn.addClass('unpublish').addClass('btn-success')
							.removeClass('publish').removeClass('btn-danger')
							.html("<?php echo get_msg( 'btn_yes' ); ?>");
					} else {
						alert( "<?php echo get_msg( 'err_sys' ); ?>" );
					}
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
					if ( msg == true )
						btn.addClass('publish').addClass('btn-danger')
							.removeClass('unpublish').removeClass('btn-success')
							.html("<?php echo get_msg( 'btn_no' ); ?>");
					else
						alert( "<?php echo get_msg( 'err_sys' ); ?>" );
				}
			});
		});
		
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
		'title' => get_msg( 'delete_additional_label' ),
		'message' =>  get_msg( 'additional_delete_confirm_message' ),
		'no_only_btn' => get_msg( 'additional_no_only_label' )
	);
	
	$this->load->view( $template_path .'/components/delete_confirm_modal', $data );
?>