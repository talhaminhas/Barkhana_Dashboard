<script>

	<?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>

	function jqvalidate() {

		$('#area-form').validate({
			rules:{
				area_name:{
					blankCheck : "",
					minlength: 3,
					remote: "<?php echo $module_site_url .'/ajx_exists/'.@$area->id; ?>"
					
				},
				price:{
					blankCheck : "",
					indexCheck : ""
				}
				
			},
			messages:{
				area_name:{
					blankCheck : "<?php echo get_msg( 'err_shp_area_name' ) ;?>",
					minlength: "<?php echo get_msg( 'err_shp_area_len' ) ;?>",
					remote: "<?php echo get_msg( 'err_shp_area_exist' ) ;?>."
				},
				price:{
					blankCheck : "<?php echo get_msg( 'err_shp_price_blank' ) ;?>",
					indexCheck: "<?php echo get_msg('price_cannot_zero'); ?>"
 				}
				
			}

		});
			// custom validation
		jQuery.validator.addMethod("blankCheck",function( value, element ) {
			
			   if(value == "") {
			    	return false;
			   } else {
			   	 	return true;
			   }
		});

		jQuery.validator.addMethod("indexCheck",function( value, element ) {
			
			if(value == 0) {
				 return false;
			} else {
				 return true;
			};
			
	 	});

	}
	

	<?php endif; ?>

	$('input[name="price"]').keyup(function(e)
                                {
		  if (/[^\d.-]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    this.value = this.value.replace(/[^\d.-]/g, '');
		  }
		});
	
</script>