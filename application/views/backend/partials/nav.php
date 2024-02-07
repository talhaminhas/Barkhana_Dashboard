  <!-- Navbar -->
  <nav class=" navbar navbar-expand border-bottom table-header align-middle fixed-top"  style="padding-left:300px;">
    <?php $be_url = $this->config->item('be_url'); ?>
    
    <!-- Left navbar links -->
    <ul class="navbar-nav " style="width:200%; display: flex; align-items: center; ">
      
    <li class="nav-item icon-btn" style="margin-left: 20px;">
      <a class="nav-link" data-widget="pushmenu" href="#">
          <i class="fa fa-bars" style=" font-size: 20px; color: black;"></i>
      </a>
    </li>

      <!-- Brand Logo -->
      
        <span class="brand-text  " style="margin-left: 50px; width:100%; font-size: 27px; " >
          <?php 
              

            $selected_menu_child_name = $this->uri->segment(2); 

            $conds['module_name'] = $selected_menu_child_name;

            $selected_module_desc = $this->Module->get_one_by($conds)->module_lang_key;

            if($selected_module_desc != "") {
                echo get_msg($selected_module_desc); 
            } else {
                echo get_msg('dashboard_label');
            }
            
          
          ?></span>
      
      </ul>
      <ul class="navbar-nav " style=" display: flex; align-items: center; justify-content: flex-end;">


       <li >
        
            <a href="<?php echo site_url ( $be_url . '/profile');?>" class="d-block" >

              <?php $logged_in_user = $this->ps_auth->get_user_info(); 
                if( $logged_in_user->user_profile_photo  != "") {
              ?>
            
                <img src="<?php echo img_url('' . $logged_in_user->user_profile_photo); ?>
                " class="user-image" alt="User Image" style="width: 40px; height: 40px; border-radius: 5px;">

              <?php } else { ?>

                <img src="<?php echo img_url( 'thumbnail/avatar.png'); ?>" class="user-image" alt="User Image">

              <?php } ?>

              <!--<span style="margin-left: 20px" class="hidden-xs"><?php echo $logged_in_user->user_name;?></span>-->

            </a>
            
      </li>

      <li class="messages-menu open icon-btn"  style="margin-left: 10px; margin-right:20px; ">
        <a href="<?php echo site_url('logout');?>" aria-expanded="true">
          <i class="fa fa-sign-out" style="font-size: 23px; color: #000;"></i>
        </a>
        
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->