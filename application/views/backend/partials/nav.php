  <!-- Navbar -->
  <nav class=" navbar navbar-expand  table-header align-middle fixed-top"  style="background-color: rgba(0, 0, 255, 0.3); padding-left:300px;">
    <?php $be_url = $this->config->item('be_url'); ?>
    
    <!-- Left navbar links -->
    <ul class="navbar-nav " style="width:200%; display: flex; align-items: center; ">
      
    <li class="nav-item icon-btn nav-btn" style="margin-left: 20px;" id="menuToggle">
        <a class="nav-link" data-widget="pushmenu" href="#">
            <i class="fa fa-bars" style="font-size: 20px;"></i>
        </a>
    </li>

      <!-- Brand Logo -->
      
        <span class="brand-text  " style="margin-left: 180px; width:100%; font-size: 27px; " >
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
      <ul class="navbar-nav" style="display: flex; align-items: center; justify-content: flex-end;">

        <!-- Digital Clock with Date -->
        <li class="clock">
            <span  id="digitalDate" style="color: black;"></span>
            <span  id="digitalTime" style="color: black;"></span>
        </li>

        <li class="nav-btn">
            <a href="<?php echo site_url($be_url . '/profile');?>" class="d-block">
                <?php $logged_in_user = $this->ps_auth->get_user_info(); ?>
                <?php if ($logged_in_user->user_profile_photo != "") { ?>
                    <img src="<?php echo img_url('' . $logged_in_user->user_profile_photo); ?>"
                        class="user-image" alt="User Image" style="width: 40px; height: 40px; border-radius: 5px;">
                <?php } else { ?>
                    <img src="<?php echo img_url('thumbnail/avatar.png'); ?>" class="user-image" alt="User Image">
                <?php } ?>
                <!--<span style="margin-left: 20px" class="hidden-xs"><?php echo $logged_in_user->user_name;?></span>-->
            </a>
        </li>

        <li class="messages-menu open icon-btn nav-btn" style="margin-left: 10px; margin-right:20px; ">
            <a href="<?php echo site_url('logout');?>" aria-expanded="true">
                <i class="fa nav-link fa-sign-out" style="font-size: 23px;"></i>
            </a>
        </li>
      </ul>
  </nav>
  <!-- /.navbar -->
  <style>
    .clock{
      margin-right: 10px; 
      font-size: 16px; 
      text-align: left;
      width:170px; 
      //background: rgba(0, 0, 255, 0.2);
      //box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
      padding: 5px 5px 0px 5px;
      border-radius: 5px;
    }
    .nav-btn {
    border-radius: 5px;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
}

  </style>
  <script>
function updateDigitalClock() {
    var now = new Date();
    var year = now.getFullYear();
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var month = monthNames[now.getMonth()];
    var day = now.getDate();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    var formattedDate = day + ' ' + month + ' ' + year;
    var formattedTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

    // Update the date and time elements
    document.getElementById('digitalDate').textContent = formattedDate + ' ';
    document.getElementById('digitalTime').textContent = formattedTime;

    // Update the clock every second
    setTimeout(updateDigitalClock, 1000);
}
  // Initial call to start the clock
  updateDigitalClock();

    function handleMenuToggleClick() {
      window.clearTimeout(refreshTimeout);
      refreshTimeout = window.setTimeout(function () {
        window.location.reload();
      }, 60000);
    }

    var menuToggleElement = document.getElementById('menuToggle');
    if (menuToggleElement) {
        menuToggleElement.addEventListener('click', handleMenuToggleClick);
    }
</script>