<script>
			
	$(document).ready(function(){
		$('#reserve-form').validate({
			rules:{
				resv_date:{
					required: true
				}
			},
			messages:{
				resv_date:{
					required: "Please fill reservation date."
				}
			}
		});
		
	});
	
</script>