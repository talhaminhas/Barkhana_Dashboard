<script type="text/javascript">
    // Initial call i make so user do not wait 2 seconds for messages to show
    function refresh() {
        $.ajax({
            method: 'GET',
            dataType: 'JSON',
            url:  '<?php echo $module_site_url . '/get_all_activetask/';?>',
            cache: false,
            success:function(msg){

                if ( msg == true) {
                    var audio = new Audio("<?php echo base_url('assets/backend/audio.mp3'); ?>");
                    audio.play();

                    //setTimeout("location.reload(true);", 3000);

                }


            }
        });

    }
    //autorefresh sound every 2 seconds
    /*var noti_time = document.getElementById("noti_time").value ;
    setInterval(function () {
        //refresh();
    }, noti_time);

    //autorefresh page every 10 seconds
    var page_time = document.getElementById("page_time").value ;
    window.setTimeout(function () {
        window.location.reload();
    }, page_time);*/

    $(document).ready(function () {
  	$('#completed-orders-table').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [5, 6, 7] } 
            ],
			"pageLength": 15,
        	"lengthChange": false,
			"drawCallback": function (settings) {
				var api = this.api();
				var pageInfo = api.page.info();

				if (pageInfo.pages <= 1) {
					$(this).closest('.dataTables_wrapper').find('.dataTables_paginate').hide();
				} else {
					$(this).closest('.dataTables_wrapper').find('.dataTables_paginate').show();
				}
        	}
        });
	})
</script>