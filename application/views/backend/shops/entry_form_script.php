<style type="text/css">
    
    
    .red{ background: #ff0000; }
    .green{ background: #228B22; }
    .blue{ background: #0000ff; }
    .is_area { background: #e2e0e0; padding: 20px; display: none; margin-top: 20px; }
    .deli_fee_by_distance { background: #e2e0e0; padding: 20px; display: none; margin-top: 20px; }
    .fixed_delivery { background: #e2e0e0; padding: 20px; display: none; margin-top: 20px; }
    .free_delivery { background: #e2e0e0; padding: 20px; display: none; margin-top: 20px; }


    label{ margin-right: 15px; }

</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
    });
});

toggleCheckbox("deli_assign_settingLabel");

function jqvalidate() {

$('#shop-form').validate({
    rules:{
        currency_symbol:{
            blankCheck : "",
        },
        currency_short_form:{
            blankCheck : "",
        },
        email:{
            required: true,
            email: true,
        },
        sender_email:{
            required: true,
            email: true,
        },
        minimum_order_amount : {
            blankCheck : "",
            indexCheck : ""
        }
    
    },
    messages:{
        currency_symbol:{
            blankCheck : "<?php echo get_msg( 'err_curr_sym' ) ;?>",
        },
        currency_short_form:{
            blankCheck : "<?php echo get_msg( 'err_curr_short' ) ;?>",
        },
        email : {
            required: "<?php echo get_msg( 'err_contact_email_blank' ); ?>",
            email: "<?php echo get_msg( 'err_contact_email_invalid' ); ?>",
        },
        sender_email : {
            required: "<?php echo get_msg( 'err_sender_email_blank' ); ?>",
            email: "<?php echo get_msg( 'err_sender_email_invalid' ); ?>",
        },
        minimum_order_amount : {
            blankCheck : "<?php echo get_msg( 'err_min_order_amount' ) ;?>",
            indexCheck: "<?php echo get_msg('min_order_cannot_zero'); ?>"
        }
    
    }
});
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


$(document).ready(() => {
	$("#checkout_with_email").click(() => {
		$("#ways_for_email").removeClass("d-none");
        document.getElementById("one_page_checkout").checked = true;

	})
})

$(document).ready(() => {
	$("#checkout_with_whatsapp").click(() => {
		$("#ways_for_email").addClass("d-none");
	})
})

$(document).ready(() => {
	$("#deli_auto_assign").click(() => {
		$("#how_many_deli_to_broadcast_label").removeClass("d-none");
		$("#how_many_deli_to_broadcast_input").removeClass("d-none");
	})
})

$(document).ready(() => {
	$("#deli_manual_assign").click(() => {
		$("#how_many_deli_to_broadcast_label").addClass("d-none");
		$("#how_many_deli_to_broadcast_input").addClass("d-none");
	})
})


</script> 