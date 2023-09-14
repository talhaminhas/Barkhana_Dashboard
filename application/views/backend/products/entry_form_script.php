<script>
	<?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>

	function jqvalidate() {

		$('#product-form').validate({
			rules:{
				name:{
					blankCheck : "",
					minlength: 3,
					remote: "<?php echo $module_site_url .'/ajx_exists/'.@$product->id; ?>"
				},
				cat_id: {
		       		indexCheck : ""
		      	},
				original_price: {
				blankCheck : "",
				indexCheck : ""

				},
                minimum_order: {
                    indexCheck : ""
                },
                maximum_order: {
                    indexCheck : ""
                }
			},
			messages:{
				name:{
					blankCheck : "<?php echo get_msg( 'err_Prd_name' ) ;?>",
					minlength: "<?php echo get_msg( 'err_Prd_len' ) ;?>",
					remote: "<?php echo get_msg( 'err_Prd_exist' ) ;?>."
				},
				cat_id:{
			       indexCheck: "<?php echo get_msg('f_item_cat_required'); ?>"
			    },
                minimum_order:{
                    indexCheck: "<?php echo get_msg('minimum_order_cannot_zero'); ?>"
                },
                maximum_order:{
                    indexCheck: "<?php echo get_msg('maximum_order_cannot_zero'); ?>"
                },
				original_price:{
					blankCheck : "<?php echo get_msg( 'err_price_blank' ) ;?>",
					indexCheck: "<?php echo get_msg('price_cannot_zero'); ?>"
				}
			},

			submitHandler: function(form) {
		        if ($("#product-form").valid()) {
		            form.submit();
		        }
		    }

		});
		
		jQuery.validator.addMethod("indexCheck",function( value, element ) {
			
			   if(value == 0) {
			    	return false;
			   } else {
			    	return true;
			   };
			   
		});
			jQuery.validator.addMethod("blankCheck",function( value, element ) {
			
			   if(value == "") {
			    	return false;
			   } else {
			   	 	return true;
			   }
		});

	}

	<?php endif; ?>
	function runAfterJQ() {


		$('.delete-img').click(function(e){
			e.preventDefault();

			// get id and image
			var id = $(this).attr('id');

			// do action
			var action = '<?php echo $module_site_url .'/delete_cover_photo/'; ?>' + id + '/<?php echo @$product->id; ?>';
			console.log( action );
			$('.btn-delete-image').attr('href', action);
			
		});


		$('#cat_id').on('change', function() {

				var value = $('li:selected', this).text().replace(/Value\s/, '');

				var catId = $(this).val();
				 
				$.ajax({
					url: '<?php echo $module_site_url . '/get_all_sub_categories/';?>' + catId,
					method: 'GET',
					dataType: 'JSON',
					success:function(data){
						$('#sub_cat_id').html("");
						$.each(data, function(i, obj){
						    $('#sub_cat_id').append('<option value="'+ obj.id +'">' + obj.name+ '</option>');
						});
						$('#name').val($('#name').val() + " ").blur();
						$('#sub_cat_id').trigger('change');
					}
				});
			});

		

      	// add specification
      	$(document).ready(function () {
     		//add new product
      		var edit_product_check = $('#edit_product').val();


     		if(edit_product_check == 0) {
     			//new product
     			var counter = 2;
     		} else {
     			//edit product
     			var counter =  parseInt($('#spec_total_existing').val())+2;
     		}

      		$('#spec_total_existing').val(counter);

      		$('#addspec').click(function () {
      			
      			var newTextBoxDiv = $(document.createElement('div'))
	     		.attr("class",'col-md-6',"id", 'TextBoxDiv' + counter);

	     		newTextBoxDiv.after().html(
	      		'<div class="form-group"><label>Title : '+counter+'</label><input class="form-control form-control-sm" type="text" name="prd_spec_title' + counter + 
	      		'" id="prd_spec_title' + counter + '" value="" ></div><div class="form-group"><label>Description : '+counter+'</label><input class="form-control form-control-sm" type="text" name="prd_spec_desc' + counter + 
	      		'" id="prd_spec_desc' + counter + '" value="" ></div>');
    
	      		newTextBoxDiv.appendTo("#spec_data1");
	      		counter++;
	      		
	      		$( "#CounterTextBoxDiv" ).remove();
				var newCounterTextBoxDiv = $(document.createElement('div'))
	     		.attr("id", 'CounterTextBoxDiv' + counter);

	     		newCounterTextBoxDiv.after().html(
	      		'<input type="hidden" name="spec_total" id="spec_total" value=" '+ counter +'" >');

	      		newCounterTextBoxDiv.appendTo("#spec_data1");

	      		
      		});
      	});

      	$('.dropdown-sin-2').dropdown();

      	$('input[name="original_price"]').keyup(function(e)
                                {
		  if (/[^\d.-]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    this.value = this.value.replace(/[^\d.-]/g, '');
		  }
		});

		$('input[name="ordering"]').keyup(function(e)
                                {
		  if (/[^\d.-]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    this.value = this.value.replace(/[^\d.-]/g, '');
		  }
		});

		$('input[name="minimum_order"]').keyup(function(e)
                                {
		  if (/[^\d.-]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    this.value = this.value.replace(/[^\d.-]/g, '');
		  }
		});
        
        $('input[name="original_price"]').keyup(function(e)
                                {
		  if (/[^\d.-]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    this.value = this.value.replace(/[^\d.-]/g, '');
		  }
		});
         
        $('input[name="maximum_order"]').keyup(function(e)
                                {
		  if (/[^\d.-]/g.test(this.value))
		  {
		    // Filter non-digits from input value.
		    this.value = this.value.replace(/[^\d.-]/g, '');
		  }
		});

        $('input[name="original_price"]').change(function(e) {

        	if(  $("#discount_percent").val() ) {
				var result = parseFloat($("#original_price").val()) - ( parseFloat($("#original_price").val()) * parseFloat($("#discount_percent").val()) );

				$("#discount_label").text( '( Unit Price : ' + result + ' )' );
				$("#unit_price").val(result);

			} else {
				$("#discount_label").text( '( Unit Price : ' + $("#original_price").val() + ' )' );
				$("#unit_price").val($("#original_price").val());
			}

		});
	
	}

</script>
<?php 
	// replace cover photo modal
	$data = array(
		'title' => get_msg('upload_photo'),
		'img_type' => 'product',
		'img_parent_id' => @$product->id
	);

	$this->load->view( $template_path .'/components/photo_upload_modal', $data );

	// delete cover photo modal
	$this->load->view( $template_path .'/components/delete_cover_photo_modal' ); 
?>