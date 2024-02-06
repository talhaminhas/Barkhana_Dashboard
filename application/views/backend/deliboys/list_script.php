<script>
function runAfterJQ() {

	$(document).ready(function(){

		$(document).delegate('.ban','click',function(){
			var btn = $(this);
			var id = $(this).attr('userid');

			$.ajax({
				url: "<?php echo $module_site_url .'/ban/';?>"+id,
				method:'GET',
				success:function(msg){
					if(msg == 'true')
						btn.addClass('unban btn-danger')
							.removeClass('btn-primary-green ban')
							.html('Unban');
					else
						console.log( '<?php echo get_msg('sys_err_occur'); ?>' );
				}
			});
		});
		
		$(document).delegate('.unban','click',function(){
			var btn = $(this);
			var id = $(this).attr('userid');

			$.ajax({
				url: "<?php echo $module_site_url .'/unban/';?>"+id,
				method:'GET',
				success:function(msg){
					if(msg == 'true')
						btn.addClass('ban ')
							.removeClass('btn-danger unban')
							.html('Ban');
					else
						console.log( '<?php echo get_msg('sys_err_occur'); ?>' );
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
		'title' => get_msg( 'delete_deliboy_label' ),
		'message' =>  get_msg( 'deliboy_delete_confirm_message' ),
	);
	
	$this->load->view( $template_path .'/components/delete_confirm_modal', $data );
?>