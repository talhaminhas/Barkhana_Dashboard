<?php $this->load->view( $template_path .'/partials/nav' ); ?>

 <div class="container-fluid" style="">
  <div class="content-wrapper">
   <div class="row">
   	<div class="col-12 col-md-3 sidebar teamps-sidebar-collapse">
   		 <?php $this->load->view( $template_path .'/partials/sidebar' ); ?>
   	</div>
    
    <div class="col-12 col-md-12 main teamps-sidebar-push" >
      
    	<?php $this->load->view( $template_path .'/'. $view, $data ); ?>
    </div>
   </div>
  </div>
 </div>