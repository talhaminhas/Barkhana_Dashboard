        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.10/css/dataTables.checkboxes.css" />
        <script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.10/js/dataTables.checkboxes.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="<?php echo base_url( 'assets/plugins/bootstrap/js/bootstrap.bundle.min.js' ); ?>"></script>

        <!-- Morris.js charts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="<?php echo base_url( 'assets/plugins/morris/morris.min.js' ); ?>"></script>
        <!-- Sparkline -->
        <script src="<?php echo base_url( 'assets/plugins/sparkline/jquery.sparkline.min.js' ); ?>"></script>
        <!-- jvectormap -->
        <script src="<?php echo base_url( 'assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js' ); ?>"></script>
        <script src="<?php echo base_url( 'assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js' ); ?>"></script>
        <!-- jQuery Knob Chart -->
        <script src="<?php echo base_url( 'assets/plugins/knob/jquery.knob.js' ); ?>"></script>
        <!-- daterangepicker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
        <script src="<?php echo base_url( 'assets/plugins/daterangepicker/daterangepicker.js' ); ?>"></script>
        <!-- datepicker -->
        <script src="<?php echo base_url( 'assets/plugins/datepicker/bootstrap-datepicker.js' ); ?>"></script>
        <!-- color picker -->
        <script src="<?php echo base_url( 'assets/plugins/colorpicker/bootstrap-colorpicker.min.js' ); ?>"></script>
        <!-- Bootstrap WYSIHTML5 -->
       <script src="<?php echo base_url( 'assets/ckeditor4/ckeditor.js');?>"></script>
        
        <!-- AdminLTE App(This is sidebar and nav action) -->
        <script src="<?php echo base_url( 'assets/dist/js/adminlte.js' ); ?>"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo base_url( 'assets/dist/js/demo.js' ); ?>"></script>
        <!-- Select2 -->
        <link rel="stylesheet" href="<?php echo base_url('assets/select2/select2.min.css'); ?>">
        <script src="<?php echo base_url( 'assets/select2/select2.full.min.js' ); ?>"></script>
        
        <script>
            $(document).ready(function() {
                $('.select2').select2();                                    
            });
        </script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url( 'assets/plugins/jquery/jquery.dropdown.css' ); ?>">
        <script src="<?php echo base_url( 'assets/plugins/jquery/jquery.dropdown.js' ); ?>"></script>
     
        <!-- For Calendar -->
        <script src="<?php echo base_url('assets/fullcalendar/js/moment.min.js');?>"></script>
        <script src="<?php echo base_url('assets/fullcalendar/js/fullcalendar.min.js');?>"></script>
        <script src="<?php echo base_url('assets/fullcalendar/js/icheck.min.js');?>"></script>
        
        <script>
  
            $(function () {
              //Date range picker for offline paid
           
             $('#reservation').daterangepicker();

            })

        </script>
        
        <?php show_analytic(); ?>
        <script src="<?php echo base_url( 'assets/validator/jquery.validate.js' ); ?>"></script>
        <script type="text/javascript">
          
          // functions to run after jquery is loaded
          if ( typeof runAfterJQ == "function" ) runAfterJQ();

          <?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>
            
            // functions to run after jquery is loaded
            if ( typeof jqvalidate == "function" ) jqvalidate();

          <?php endif; ?>

                $('.page-sidebar-menu li').removeClass('active');

                // highlight submenu item
                $('li a[href="' + this.location.pathname + '"]').parent().addClass('active');

                // Highlight parent menu item.
                $('ul a[href="' + this.location.pathname + '"]').parents('li').addClass('active');

                

        </script>

    <script>
  
      $(function () {
          //Date range picker
        $('#reservation').daterangepicker()

        })

    </script>
    <script>
        //multi search select
        // $(document).ready(function() {
        //     $('.dropdown-sin-2').dropdown();
        // });
    </script>

   <!-- time picker -->
    <script src="/path/to/cdn/jquery.min.js"></script>
        
    <script src="/path/to/dist/qcTimepicker.min.js"></script>

    <script>
            $(document).ready(function() {
          $('.timepicker').qcTimepicker();
    });
    </script>


    <?php if ( isset( $load_gallery_js )) : ?>

      <?php $this->load->view( $template_path .'/components/gallery_script' ); ?> 

    <?php endif; ?>

  </div>
 <!-- ./ wrapper -->
</body>
</html>