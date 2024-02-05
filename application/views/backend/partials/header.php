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
  
    <link href="<?php echo base_url('assets/fullcalendar/css/fullcalendar.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/fullcalendar/css/fullcalendar.print.css');?>" rel='stylesheet' media='print'>
	
	<!-- OpenStreet Map -->
	<!-- Load Leaflet from CDN -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
		integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
		crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
		integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
		crossorigin=""></script>

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
</head>
<?php
	if( $this->config->item("is_demo") == 1 ) {

		?>

		<div style="background: #FF0000; height: 40px; text-align: center; padding-top: 5px; font-weight: bold; font-size: 20px; "> 
		<?php	
			echo get_msg('demo_dummy_refresh_hour');
		
		}

	?>

	</div>
<body id="<?php echo strtolower( $module_name ); ?>" class = "sidebar-collapse">
<div class="wrapper">
	<style>
.table-header {
        font-weight: bold;
        background-color: #f2f2f2;
        text-align: center;
    }
	.icon-btn{
	width: 40px;
	height: 40px;
	font-weight: bold; 
	border: 1px solid ; 
	display: flex;
    align-items: center;
    justify-content: center;
	border-radius: 5px;
  }
	.navbar.fixed-top {
		box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
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
    height: 60px; 
	display: flex;
    align-items: center;
    justify-content: center;
  }
  	.std-btn-size{
		width: 100px; 
		height: 60px;
		display: flex; 
		align-items: center; 
		justify-content: center;
	}
	.lrg-btn-size{
		width: 150px; 
		height: 60px;
		display: flex; 
		align-items: center; 
		justify-content: center;
	}
	.std-field{
        width:250px;
        height: 40px;
        
    }
	</style>