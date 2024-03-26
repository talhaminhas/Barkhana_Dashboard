<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
  	<?php echo $title; ?>
		<?php 
			if ( isset( $action_title )) {
				echo " | ";
				if ( is_string( $action_title )) echo $action_title;
				else if ( is_array( $action_title )) echo $action_title[count($action_title) - 1]['label'];
			} 
	?>
  </title>
  	<?php
  		$conds = array( 'img_type' => 'fav-icon', 'img_parent_id' => 'be1' );
		$images = $this->Image->get_all_by( $conds )->result();
	?>
  <link rel="icon" href="<?php echo img_url( $images[0]->img_path ); ?>">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo base_url('assets/backend/css/animate.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/backend/css/style.css'); ?>">
  	<?php
		$conds_login = array( 'img_type' => 'login-image', 'img_parent_id' => 'be1' );
		$login_img = $this->Image->get_all_by( $conds_login )->result();
		$img_url = $this->ps_image->upload_url . $login_img[0]->img_path;
	?>
	<style type="text/css">
	  	/*start login background image*/
		body#main {
		    background: url(<?php echo $img_url; ?>) no-repeat center center fixed;
		    -webkit-background-size: cover;
		    -moz-background-size: cover;
		    -o-background-size: cover;
		    background-size: cover;
	 	}
	 	/*end background image*/
  	</style>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
  	
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url('assets/dist/css/AdminLTE.min.css'); ?>">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/iCheck/flat/blue.css'); ?>">
	<!-- Morris chart -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/morris/morris.css'); ?>">
	<!-- jvectormap -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
	<!-- Color Picker -->
	<link href="<?php echo base_url('assets/plugins/colorpicker/bootstrap-colorpicker.min.css');?>" rel="stylesheet">
	<!-- Date Picker -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker-bs3.css'); ?>">
	<!-- bootstrap wysihtml5 - text editor -->
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>">
  	<!-- Google Font: Source Sans Pro -->
  	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

	<!-- ChartJS 1.0.1 -->
	<script src="<?php echo base_url( 'assets/plugins/chartjs-old/Chart.min.js' ); ?>"></script>
	<!-- FastClick -->
	<script src="<?php echo base_url( 'assets/plugins/fastclick/fastclick.js'); ?>"></script>
	<!-- jQuery -->
    <script src="<?php echo base_url( 'assets/plugins/jquery/jquery.min.js' ); ?>"></script>

	<script src="<?php echo base_url( 'assets/js/BrowserPrint-3.1.250.min.js' ); ?>"></script>
	<script src="<?php echo base_url( 'assets/js/BrowserPrint-Zebra-1.1.250.min.js' ); ?>"></script>

	<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Work+Sans" rel="stylesheet">
	
	<!-- gallery lightbox -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/gallery/gallery.css'); ?>">
    <script src="<?php echo base_url('assets/plugins/gallery/gallery.js');?>"></script>
	<!-- For Calendar -->
  
    <!--<link href="<?php echo base_url('assets/fullcalendar/css/fullcalendar.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/fullcalendar/css/fullcalendar.print.css');?>" rel='stylesheet' media='print'>
    <script src="<?= base_url('assets/fullcalendar/js/moment.min.js'); ?>"></script>
    <script src="<?= base_url('assets/fullcalendar/js/fullcalendar.min.js'); ?>"></script>-->
	<script src="<?= base_url('assets/fullcalendar/js/index.global.js'); ?>"></script>

	<!-- OpenStreet Map -->
	<!-- Load Leaflet from CDN -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
		integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
		crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
		integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
		crossorigin=""></script>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

	<!-- Load Esri Leaflet from CDN -->
	<script src="https://unpkg.com/esri-leaflet@2.5.3/dist/esri-leaflet.js"
		integrity="sha512-K0Vddb4QdnVOAuPJBHkgrua+/A9Moyv8AQEWi0xndQ+fqbRfAFd47z4A9u1AW/spLO0gEaiE1z98PK1gl5mC5Q=="
		crossorigin=""></script>

	<!-- Load Esri Leaflet Geocoder from CDN -->
	<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
		integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
		crossorigin="">

	<script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
		integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
		crossorigin=""></script>
		<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

	<!-- DataTables JavaScript -->
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
</head>
<?php
    date_default_timezone_set('Europe/London');
	//$this->set_flash_msg('error',"You don't have access to admin panel.");
	if( $this->config->item("is_demo") == 1 ) {

		?>

		<div style="background: #FF0000; height: 40px; text-align: center; padding-top: 5px; font-weight: bold; font-size: 20px; "> 
		<?php	
			echo get_msg('demo_dummy_refresh_hour');
		
		}

	?>

	</div>
<body id="<?php echo strtolower( $module_name ); ?>" class = "sidebar-collapse" style=" padding-top: 120px; ">
<div class="wrapper" style="allign-middle">
	<style>
		/* Override form-control class */
		body{
			
		}
		:root {
            --main-color: #cbb2fc; 
			--main-border-color: #6f29f8;
			--main-text-color: #6f29f8;
			--main-field-border-color: #808080
        }
.form-control {
    height: 40px !important;
	border: 2px solid;
	border-color: grey;
	border-radius: 20px;
	font-weight:bold;
	color:grey;
	padding-left:10px;
}

/* Override form-control-sm class */
.form-control-sm {
    height: 40px !important;
	border: 2px solid;
	border-color: grey;
	border-radius: 20px;
	font-weight:bold;
	color:grey;
	padding-left:10px;
}
.table-header {
        font-weight: bold;
        //background-color: rgba(0, 0, 255, 0.2);
        text-align: center;
		//border-top: 2px solid #ddd;
		
    }
.icon-btn {
    width: 40px;
    height: 40px;
    font-weight: bold; 
    border: 1px solid var(--main-border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    color: var(--main-text-color);
}
.toggle.ios, .toggle-on.ios, .toggle-off.ios {
	margin-right: 20px;
	 border-radius: 20px;
	}
  .toggle.ios .toggle-handle { 
	
}
.btn-primary{
	background: var(--main-color);
	color: var(--main-text-color);
	font-weight: bold;
	border: 0px;
}
.btn-warning{
	color: var(--main-text-color);
	font-weight: bold;
}
.btn{
	font-weight: bold;
}

  .nav-link{
	color: var(--main-text-color);
  }
	.image-container{
		width: 100%; 
		height: 0; 
		padding-top: 250px; 
		position: relative;
		 overflow: hidden;
		 text-align:center;
	}
	.thumbnail{
		position: absolute; 
		top: 0; left: 0;
		 width: 100%; 
		 height: 100%;
		 color: var(--main-text-color);
	}
	.img-fluid{
		width: 97%; height: 250px; object-fit: cover;
	}
	.main-sidebar, .main-sidebar::before {
    width: 300px; /* default 250px */
	}

	body:not(.sidebar-mini-md) .content-wrapper,
	body:not(.sidebar-mini-md) .main-footer,
	body:not(.sidebar-mini-md) .main-header {
		margin-left: 300px; /* default 250px */
	}

	.sidebar-collapse .main-sidebar,
	.sidebar-collapse .main-sidebar::before {
		margin-left: -300px;
	}

	.sidebar-mini.sidebar-collapse .main-sidebar:hover,
	.sidebar-mini.sidebar-collapse .main-sidebar.sidebar-focused {
		width: 300px; /* default 250px */
	}

	.sidebar-collapse .wrapper,
	.sidebar-collapse .fixed-top {
		margin-left: -300px;
		transition: margin-left 0.3s ease-in-out;
	}

	.sidebar-open .wrapper,
	.sidebar-open .fixed-top {
		margin-left: 0;
		transition: margin-left 0.3s ease-in-out;
	}
	.table-cell {
        text-align: center;
		vertical-align: middle;
    }
	.fixed-size-btn {
    width: 100%; 
    height: 40px; 
	display: flex;
    align-items: center;
    justify-content: center;
	box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5); height: 40px;
  }
  	.std-btn-size{
		width: 100px; 
		height: 40px;
		display: flex; 
		align-items: center; 
		justify-content: center;
		box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5); height: 40px;
	}
	.lrg-btn-size{
		width: 164px; 
		height: 40px;
		display: flex; 
		align-items: center; 
		justify-content: center;
		box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5); height: 40px;
	}
	.xlrg-btn-size{
		width: 200px; 
		height: 40px;
		display: flex; 
		align-items: center; 
		justify-content: center;
		box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5); height: 40px;
	}
	.std-field{
        width:250px;
        height: 40px;
        border: 2px solid;
		border-color: grey;
		border-radius: 20px;
		font-weight:bold;
		color:grey;
		padding-left:10px;
    }
	.discount-label{
        font-weight: bold; 
        color: red;
    }
	.badge{
	width: 100%; 
    height: 40px;
	font-weight: bold; 
	color: #fc3903; 
	border: 2px solid #fc3903; 
	padding: 5px;
	display: flex;
    align-items: center;
    justify-content: center;
  }
  .order-collection{
	width: 100%; 
    height: 40px;
	font-weight: bold; 
	color: #fc3903; 
	border: 2px solid #fc3903; 
	padding: 5px;
	display: flex;
    align-items: center;
    justify-content: center;
	border-radius: 5px;
  }
  .order-delivery{
	width: 100%; 
    height: 40px;
	font-weight: bold; 
	color: #9003fc;
	border: 2px solid #9003fc; 
	display: flex;
    align-items: center;
    justify-content: center;
	border-radius: 5px;
  }
  /* Add this CSS to your stylesheet or in the head of your HTML document */
 .form-checked-label {
        font-weight:bold;
		color: white;
		background:#0275d8;
		border: 2px solid ;
		border-color: #0275d8;
		padding: 8px;
		border-radius: 20px; 
    }
	.form-unchecked-label {
		font-weight:bold;
		color:grey;
		border: 2px solid ;
		padding: 8px;
		border-radius: 20px;
	}
	 
	.dataTables_paginate {
        padding-bottom: 10px;
		margin-top: 10px;
        text-align: center;
		border-bottom: 1px solid;
    }
	.dataTables tr {
		border: 10px;
		background: red;
		text-align: center;
		vertical-align: middle;

	}
    .dataTables_paginate .paginate_button {
        padding: 5px 10px;
        margin: 0 2px;
        border: 1px solid #ddd;
        background-color: #ccc;
        color: #333;
        cursor: pointer;
        border-radius: 4px;
		border-color:green;
    }

	.dataTables_wrapper .dataTables_paginate .paginate_button.current {
		background-color: #ccc;
    }

   .dataTables_paginate .paginate_button:hover {
        background-color: #ddd;
    }
	thead {
		//background-color:  rgba(0, 0, 255, 0.2);
	}
	.invisible-input{
    border: none;
    color: grey;
    font-weight: bold;
	}
	.invisible-input:focus {
		outline: none; 
		border: 0px solid #ccc; 
	}
	.elevated-box {
	box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1);
	}

	</style>
	<script>
		
	function toggleCheckbox(idCheckbox) {
    var checkbox = document.getElementById(idCheckbox);
    var label = document.getElementById(idCheckbox + "Label");

    if (checkbox.checked) {
        label.classList.add('form-checked-label');
        label.classList.remove('form-unchecked-label');
    } else {
        label.classList.remove('form-checked-label');
        label.classList.add('form-unchecked-label');
    }
}
 
		</script>
		<script >
var selected_device;
var devices = [];
function setup()
{
	//Get the default device from the application as a first step. Discovery takes longer to complete.
	BrowserPrint.getDefaultDevice("printer", function(device)
			{
		
				//Add device to list of devices and to html select element
				selected_device = device;
				devices.push(device);
				var html_select = document.getElementById("selected_device");
				var option = document.createElement("option");
				option.text = device.name;
				html_select.add(option);
				
				//Discover any other devices available to the application
				BrowserPrint.getLocalDevices(function(device_list){
					for(var i = 0; i < device_list.length; i++)
					{
						//Add device to list of devices and to html select element
						var device = device_list[i];
						if(!selected_device || device.uid != selected_device.uid)
						{
							devices.push(device);
							var option = document.createElement("option");
							option.text = device.name;
							option.value = device.uid;
							html_select.add(option);
						}
					}
					
				}, function(){
                    alert("Error getting local devices")
                },"printer");
				
			}, function(error){
				alert("Error: Unable to connect to the printer");
			})
            //alert(printer);
}
var marginLeft = '        ';
var marginRight = '      ';
var lineLength = 34;
function printOrder(transaction) {

    //each line has 8 + 34 + 6 = 48 char

    var orderString = '';
    orderString += alignEdges('.','.','.');
    orderString += alignMiddle('Order Details');
    orderString += alignEdges('.','.','.');
    orderString += alignEdges('Order No:',' ',transaction.trans_code);
    orderString += alignEdges('Order Type:',' ',transaction.pick_at_shop === '0' ? 'Delivery' : 'Collection');
    orderString += alignEdges(transaction.pick_at_shop === '0' ? 'Delivery Date:' : 'Collection Date',' ', transaction.delivery_pickup_date);
    orderString += alignEdges(transaction.pick_at_shop === '0' ? 'Delivery Time:' : 'Collection Time',' ', transaction.delivery_pickup_time);
    orderString += alignEdges('.','.','.');
    orderString += alignMiddle('Customer Details');
    orderString += alignEdges('.','.','.');
    orderString += alignEdges('Name:',' ',transaction.contact_name);
    orderString += alignEdges('Phone:',' ',transaction.contact_phone);
    orderString += alignEdges('Address:',' ',transaction.contact_address.slice(0, 24));
    if(transaction.contact_address.length > 24)
        orderString += nextLine(9, transaction.contact_address.slice(24, transaction.contact_address.length));
        orderString += alignEdges('.','.','.');
    orderString += alignMiddle('Items Detail');
    orderString += alignEdges('.','.','.');
    var itemSubtotal = 0;
    var itemNumber = 0;
    <?php 
    $conds['transactions_header_id'] = $transaction->id;
    $all_detail =  $this->Transactiondetail->get_all_by( $conds );
    foreach($all_detail->result() as $transaction_detail){
        $addon_name_info  = explode("#", $transaction_detail->product_addon_name);
        $addon_price_info = explode("#", $transaction_detail->product_addon_price);
    ?>
        itemNumber += 1;
        var transactionDetail = <?= json_encode($transaction_detail) ?>;
        orderString += alignEdges(transactionDetail.product_name,' ', 
        (parseFloat(transactionDetail.price) + parseFloat(transactionDetail.discount_amount)).toFixed(2).toString());
        if(transactionDetail.discount_amount !== '0')
            orderString += alignEdges('Discount Amount:', ' ', '- ' + parseFloat(transactionDetail.discount_amount).toFixed(2).toString());
        var addonNames =  <?= json_encode($addon_name_info) ?>;
        var addonPrices = <?= json_encode($addon_price_info) ?>;
        var addonFlag = 0;
        if (addonNames[0] !== '') {
            
            for (var k = 0; k < addonNames.length; k++) {
                if (addonNames[k] !== "") {
                    addonFlag = 1;
                    orderString += alignEdges('  ' + addonNames[k], ' ', '+ ' + parseFloat(addonPrices[k]).toFixed(2).toString() + '  ');
                }
            }
        } else {
            addonInfoStr = "";
        }
        orderString += alignEdges('Quantity:',' ',transactionDetail.qty);
        var total =  parseFloat(transactionDetail.qty * (transactionDetail.original_price - transactionDetail.discount_amount));
        itemSubtotal += total; 
        orderString += alignEdges('Total:',' ',total.toFixed(2).toString());
        orderString += alignEdges('.','.','.');
    <?php 
    }
    ?>
    orderString += alignMiddle('Order Summary');
    orderString += alignEdges('.','.','.');
    orderString += alignEdges('Items Sub Total:',' ',itemSubtotal.toFixed(2).toString());
    if(transaction.coupon_discount_amount !== '0')
        orderString += alignEdges('Coupon Discount:', ' ', '- ' + parseFloat(transaction.coupon_discount_amount).toFixed(2).toString());
    if(transaction.shipping_amount !== '0')
        orderString += alignEdges('Delivery Cost:', ' ', '+ ' + parseFloat(transaction.shipping_amount).toFixed(2).toString());
        orderString += alignEdges('.','.','.');
    orderString += alignEdges('Sub Total:', ' ',
        (parseFloat(transaction.sub_total_amount) + parseFloat(transaction.shipping_amount)).toFixed(2).toString());
    orderString += alignEdges('.','.','.');
    if(transaction.memo !== '')
    {
        orderString += alignMiddle('Message From Customer');
        orderString += alignEdges('.','.','.');
        orderString += formatMessage(transaction.memo);
        orderString += alignEdges('.','.','.');
    }
    
    orderString += '\n\n\n';
    console.log(orderString);
    //selected_device.send(orderString, undefined, errorCallback);
}

function formatMessage(message) {
    formatedMessage = '';
    for (let i = 0; i < message.length; i += lineLength) {
        let line = message.substring(i, i + lineLength);
        // Trim leading and trailing spaces from the line
        line = line.trim();
        // Add spaces to the end of the line if its length is less than lineLength
        while (line.length < lineLength) {
            line += ' ';
        }
        // Add '-' where a word is broken
        if (i + lineLength < message.length && message[i + lineLength] !== ' ') {
            const lastSpaceIndex = line.lastIndexOf(' ');
            if (lastSpaceIndex !== -1) {
                line = line.substring(0, lastSpaceIndex) + '-' + line.substring(lastSpaceIndex + 1);
            }
        }
        formatedMessage += marginLeft + line + marginRight;
    }
    return formatedMessage;
}
function alignEdges(string1, character, string2) {
    const remainingLength = lineLength - (string1.length + string2.length + 1);

    // If remaining length is negative or zero, return empty string
    if (remainingLength <= 0) {
        return "";
    }

    // Repeat the character for the remaining length
    const repeatedCharacter = character.repeat(remainingLength);

    // Combine strings with the repeated character
    const combinedString = marginLeft + string1 + character + repeatedCharacter + string2 + marginRight;

    return combinedString;
}
function alignMiddle(string) {

    if (string.length % 2 !== 0) {
        string += ' ';
    }
    const remainingLength = lineLength - string.length;

    // If remaining length is negative or zero, return empty string
    if (remainingLength <= 0) {
        return "";
    }

    // Calculate spaces needed on both sides
    const spacesOnEachSide = Math.floor(remainingLength / 2);

    // Construct the combined string with equal spaces on both sides
    const combinedString = marginLeft + " ".repeat(spacesOnEachSide) + string + " ".repeat(spacesOnEachSide) + marginRight;

    return combinedString.slice(0, 48);
}

function nextLine(spacesInFront, str ) {
    
    const spacesToAddEnd = Math.max(0, lineLength - str.length - spacesInFront);
    const spacesToAddStart = Math.max(0, spacesInFront);
    
    const paddedString = marginLeft + ' '.repeat(spacesToAddStart) + str + ' '.repeat(spacesToAddEnd) + marginRight;
    
    return paddedString;
}
var errorCallback = function(errorMessage){
	alert("Error: " + errorMessage);
}

window.onload = setup;
</script>