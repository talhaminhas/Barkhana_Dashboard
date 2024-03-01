<style>
    .fc-left button, .fc-right button {
        padding: 10px;
        height: 40px;
        border: 1px solid rgba(0, 0, 255, 0.4);
        color: purple;
        font-weight: bold;
        background: rgba(0, 0, 255, 0.2);
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
    }
    .fc-left button:focus,
    .fc-right button:focus {
        outline: 0px  solid rgba(0, 0, 255, 0.4); 
        background: rgba(0, 0, 255, 0.4);
        box-shadow: none;
    }
    #calendar {
        border: 1px solid rgba(0, 0, 255, 0.4); 
        border-radius: 5px;

    }
    #calendar .fc-view {
        margin: -13px 13px 13px 13px;
        //border: 1px solid rgba(0, 0, 255, 0.4);
        border-radius: 5px;
        height: 65vh;
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none; 
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
    }

    #calendar .fc-view::-webkit-scrollbar {
        display: none; /* For Chrome, Safari, and Opera */
    }
    
    #calendar .fc-content-skeleton table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Style for table headers (days or weeks) */
    #calendar .fc-widget-header {
        background-color: rgba(0, 0, 255, 0.2); 
        color: #fff; /* Set the text color */
    }
/* Style for today's cell */
    #calendar .fc-today {
        background-color: rgba(0, 0, 255, 0.1); /* Highlight today's cell with a different background color */
    }

    body{
        margin-top: -50px;
    }

</style>
<?php
$num = 4;
$num_padded = sprintf("%02d", $num);
//echo $num_padded; // returns 04
?>
<div id="wrapper " class="" style="">
    <div class=" " >
        <div class="row animated fadeInDown" >

            <div class="col-lg-12" >
                <div class="ibox float-e-margins" >

                    <div class="ibox-content" style=" ">
                    <div class="" style="margin-bottom: 15px; display: flex; align-items: center;">

                        <a class="btn" style="padding-top: 15px; border-radius: 10px; background-color: <?php echo $this->config->item('pending_color'); ?>;" href="#"></a>
                        <span style="margin-left: 5px;"> Pending</span>
                        <a class="btn" style="margin-left: 20px; padding-top: 15px; border-radius: 10px; background-color: <?php echo $this->config->item('confirm_color'); ?>;" href="#"></a>
                        <span style="margin-left: 5px;"> Confirm</span>
                        <a class="btn" style="margin-left: 20px; padding-top: 15px; border-radius: 10px; background-color: <?php echo $this->config->item('cancel_color'); ?>;" href="#"></a> 
                        <span style="margin-left: 5px;"> Cancel</span>
                        <a class="btn" style="margin-left: 20px; padding-top: 15px; border-radius: 10px; background-color: <?php echo $this->config->item('complete_color'); ?>" href="#"></a> 
                        <span style="margin-left: 5px;"> Complete</span>
                        <a href='<?php echo $module_site_url .'/add';?>' class='btn btn-primary lrg-btn-size ml-auto'>
                            <span class='fa fa-plus'></span> 
                            Add Reservation
                        </a>

                    </div>

                        
                        <div id="calendar" style= ""></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php

function getDateTime($d, $t)
{
    $ds = explode("/", $d);
    $ts = explode(":", $t);

    switch ($ds[1]) {

        case 1:
            return $ds[2] . "-" . "01" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 2:
            return $ds[2] . "-" . "02" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 3:
            return $ds[2] . "-" . "03" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 4:
            return $ds[2] . "-" . "04" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 5:
            return $ds[2] . "-" . "05" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 6:
            return $ds[2] . "-" . "06" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 7:
            return $ds[2] . "-" . "07" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 8:
            return $ds[2] . "-" . "08" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        case 9:
            return $ds[2] . "-" . "09" . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
            break;

        default:
            return $ds[2] . "-" . $ds[1] . "-" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
    }
}

function getLabelColor($status)
{

    $ci = get_instance();
    switch ($status) {
        case 1:
            return $ci->config->item('pending_color');
            break;

        case 2:
            return $ci->config->item('cancel_color');
            break;

        case 3:
            return $ci->config->item('confirm_color');
            break;

        case 4:
            return $ci->config->item('complete_color');
            break;

        default:
            return $ci->config->item('pending_color');
    }
}


$reserve_array = array();
foreach ($reservations->result() as $resv) {
    $reserve_array[] = array(
        'id'    => $resv->id,
        'title' => 'Reserve ID #' . $resv->id,
        'start' => getDateTime($resv->resv_date, $resv->resv_time),
        'url'   => site_url('/admin/reservations') . "/edit/" . $resv->id,
        'color' => getLabelColor($resv->status_id),
    );
}

$reserves = json_encode($reserve_array);

?>

<script>
    $(document).ready(function() {

        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        /* initialize the external events
         -----------------------------------------------------------------*/


        $('#external-events div.external-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1111999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0 //  original position after the drag
            });

        });


        /* initialize the calendar
         -----------------------------------------------------------------*/
        
    });
    $(document).ready(function () {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear(); 

        $('#calendar').fullCalendar({
            timeFormat: 'H(:mm)',
            defaultView: 'agendaWeek',
            fixedWeekCount: false,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaList,agendaDay'  // Include 'agendaList' in the right section
            },
            editable: false,
            droppable: false,
            drop: function () {
                if ($('#drop-remove').is(':checked')) {
                    $(this).remove();
                }
            },
            events: <?php echo $reserves; ?>,
            timezone: 'local',
            now: date,
            eventRender: function (event, element) {
                element.find('.fc-time').css('font-weight', 'bold');
                element.find('.fc-time').css('font-size', '15px');
                element.find('.fc-title').css('font-weight', 'bold'); 
                element.find('.fc-content').css('background-color', '');
                element.find('.fc-time').text(event.start.format('HH:mm'));
                element.css('box-shadow','0px 2px 4px rgba(0, 0, 0, 0.5)');
            }
        });

});

</script>
