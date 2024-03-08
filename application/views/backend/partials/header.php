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