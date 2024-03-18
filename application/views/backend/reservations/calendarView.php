<style>
    /* Custom styles for FullCalendar header buttons */
    .fc-header-toolbar {
        background-color: var(--main-color); 
        padding: 10px; 
    }
    :root {
    --fc-small-font-size: 11px;
    --fc-page-bg-color: var(--main-color);
    --fc-neutral-bg-color: rgba(208, 208, 208, 0.3);
    --fc-neutral-text-color: #808080;
    --fc-border-color: var(--main-color);

    --fc-button-text-color: white;
    --fc-button-bg-color: var(--main-text-color);
    --fc-button-border-color: var(--main-text-color);
    --fc-button-hover-bg-color: var(--main-color);
    --fc-button-hover-border-color: var(--main-text-color);
    --fc-button-active-bg-color: var(--main-color);
    --fc-button-active-border-color: var(--main-text-color);
    

    --fc-event-bg-color: green;
    --fc-event-border-color: green;
    --fc-event-text-color: green;
    --fc-event-selected-overlay-color: rgba(0, 0, 0, 0.25);

    --fc-more-link-bg-color: #d0d0d0;
    --fc-more-link-text-color: inherit;

    --fc-event-resizer-thickness: 8px;
    --fc-event-resizer-dot-total-width: 8px;
    --fc-event-resizer-dot-border-width: 1px;

    --fc-non-business-color: rgba(215, 215, 215, 0.3);
    --fc-bg-event-color: rgb(143, 223, 130);
    --fc-bg-event-opacity: 0.3;
    --fc-highlight-color: rgba(188, 232, 241, 0.3);
    --fc-today-bg-color: rgba(0, 0, 255, 0.08);
    --fc-now-indicator-color: red;

    --fc-daygrid-event-dot-width: 10px;
    --fc-list-event-dot-width: 10px;
    --fc-list-event-hover-bg-color: #f5f5f5;
    }
    .fc-scrollgrid-section {
        color: var(--main-text-color);
        font-weight: bold;
    }
    .fc-scrollgrid-section-header{
        background: var(--main-color);
        //color: var(--main-text-color);
    }
    .fc-scrollgrid-section-body{
        //background: green;
    }
    .rowgroup{
        //background: pink;
    }
    .fc-timegrid-slots{
    }
    .fc-timegrid-bg-harness{
        background: rgba(255, 0, 0, 0.1);
    }
    #calendar {
        border: 2px solid var(--main-border-color); 
        border-radius: 3px;
        height: 75vh;
        margin: 0 auto;
    }
    #calendar .fc-view {
        margin: -13px 13px 13px 13px;
        border-radius: 5px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);
    }
    body{
        margin-top: -50px;
        
    }
    .fc-daygrid-event-harness{
        
    }
    .fc-event-time{
        
        text-align:center;
        
    }
    .fc-event-title{
        width: 100%;
        text-align: center;
        border-radius: 3px;
    }
    .fc-event, .fc-event-start, .fc-event-end, .fc-event-past, .fc-daygrid-event, .fc-daygrid-dot-event, .regular-event{
        //background: rgba(255, 0, 0, 0.03);
    }
    .fc-timegrid-now-indicator-line{
        height: 2px;
        background: red;
    }
    .regular-event {
        //height:100px;
        content: ' ';
        color: #424242;
        font-weight: bold;
        //text-align: right;
        border: 2px solid var(--main-color);
        white-space: normal; /* or 'nowrap' based on your preference */
        //overflow: hidden;
        //text-overflow: ellipsis; /* Display ellipsis (...) for overflowed text */
    }

    .all-day-event, .fc-daygrid-event-harness{
        background: transparent;
        font-size: 15px;
        font-weight: bold;
        padding-bottom: 3px;
        //text-align: right;
    }
</style>
<?php
$num = 4;
$num_padded = sprintf("%02d", $num);
        $shops = $this->Shop->get_all()->result();
		$shop_id = $shops[0]->id;

		$conds['shop_id'] = $shop_id;
		$schedules = $this->Schedule->get_all_by($conds)->result();

		for ($i = 0; $i < count($schedules); $i++) {
			//echo($schedules[$i]->is_open);die;

			if ($schedules[$i]->days_of_week == get_msg('monday_label')) {
				$this->data['monday'] = $schedules[$i]->is_open;
				$this->data['from_monday'] = $schedules[$i]->open_hour;
				$this->data['to_monday'] = $schedules[$i]->close_hour;
			}

			if ($schedules[$i]->days_of_week == get_msg('tuesday_label')) {
				$this->data['tuesday'] = $schedules[$i]->is_open;
				$this->data['from_tuesday'] = $schedules[$i]->open_hour;
				$this->data['to_tuesday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('wednesday_label')) {
				$this->data['wednesday'] = $schedules[$i]->is_open;
				$this->data['from_wednesday'] = $schedules[$i]->open_hour;
				$this->data['to_wednesday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('thursday_label')) {
				$this->data['thursday'] = $schedules[$i]->is_open;
				$this->data['from_thursday'] = $schedules[$i]->open_hour;
				$this->data['to_thursday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('friday_label')) {
				$this->data['friday'] = $schedules[$i]->is_open;
				$this->data['from_friday'] = $schedules[$i]->open_hour;
				$this->data['to_friday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('saturday_label')) {
				$this->data['saturday'] = $schedules[$i]->is_open;
				$this->data['from_saturday'] = $schedules[$i]->open_hour;
				$this->data['to_saturday'] = $schedules[$i]->close_hour;
			}
			if ($schedules[$i]->days_of_week == get_msg('sunday_label')) {
				$this->data['sunday'] = $schedules[$i]->is_open;
				$this->data['from_sunday'] = $schedules[$i]->open_hour;
				$this->data['to_sunday'] = $schedules[$i]->close_hour;
			}
		}
?>
<div id="wrapper " class="" style="">
    <div class="" style=" margin-bottom: 15px; display: flex; align-items: center;">
        <a class="btn" style="box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5); padding-top: 15px; border-radius: 15px; background-color: <?php echo $this->config->item('pending_color'); ?>;" href="#"></a>
        <span style="margin-left: 5px;"> Pending</span>
        <a class="btn" style="box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);margin-left: 20px; padding-top: 15px; border-radius: 15px; background-color: <?php echo $this->config->item('confirm_color'); ?>;" href="#"></a>
        <span style="margin-left: 5px;"> Confirmed</span>
        <a class="btn" style="box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);margin-left: 20px; padding-top: 15px; border-radius: 15px; background-color: <?php echo $this->config->item('cancel_color'); ?>;" href="#"></a> 
        <span style="margin-left: 5px;"> Canceled</span>
        <a class="btn" style="box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);margin-left: 20px; padding-top: 15px; border-radius: 15px; background-color: <?php echo $this->config->item('complete_color'); ?>" href="#"></a> 
        <span style="margin-left: 5px;"> Completed</span>
        <a class="btn" style="box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);margin-left: 20px; padding-top: 15px; border-radius: 15px; background-color: <?php echo $this->config->item('rejected_color'); ?>" href="#"></a> 
        <span style="margin-left: 5px;"> Rejected</span>
        <a href='<?php echo $module_site_url .'/add';?>' class='btn btn-primary lrg-btn-size ml-auto'>
            <span class='fa fa-plus' style="margin-right: 5px;"></span> 
            Add Reservation
        </a>
    </div>
    
</div>
<div id="calendar" style= "height: 200px"></div>
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
            return $ds[2] . "" . $ds[1] . "" . $ds[0] . " " . $ts[0] . ":" . $ts[1];
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

        case 5:
            return $ci->config->item('rejected_color');
            break;
        default:
            return $ci->config->item('pending_color');
    }
}


    $reserve_array = array();

    foreach ($reservations->result() as $resv) {
        $resvTime = DateTime::createFromFormat('H:i', $resv->resv_time);
        $resvTime->modify('+1 hours');
        $endTime = $resvTime->format('H:i');

        $reserve_array[] = array(
            'id'    => $resv->id,
            'title' => ''.$resv->no_of_people . '-People ' . $resv->user_name . ' ' . $resv->user_phone_no,
            'start' => getDateTime($resv->resv_date, $resv->resv_time),
            //'end' => getDateTime($resv->resv_date, $endTime),
            'url'   => site_url('/admin/reservations') . "/edit/" . $resv->id,
            'color' => getLabelColor($resv->status_id),
            'day'   => date('Y-m-d', strtotime($resv->resv_date)), 
            'textColor'  => 'white',
            //'constraint' => 'businessHours',
        );
    }

    // Calculate total reservations for each day
    $totalReservationsPerDay = array();

    foreach ($reserve_array as $event) {
        $day = $event['day'];
        
        if (!isset($totalReservationsPerDay[$day])) {
            $totalReservationsPerDay[$day] = 1;
        } else {
            $totalReservationsPerDay[$day]++;
        }
    }

    // Append total reservations information to each event
    foreach ($reserve_array as &$event) {
        $day = $event['day'];
        $totalReservations = isset($totalReservationsPerDay[$day]) ? $totalReservationsPerDay[$day] : 0;
    }

    // Now $reserve_array has the modified 'title' property with total reservations for each day
    foreach ($totalReservationsPerDay as $day => $totalReservations) {
        $s = $totalReservations == 1 ? ' Reservation' : ' Reservations';
        $allDayEvent = array(
            'title' => $totalReservations.$s,
            'start' => $day,
            'allDay' => true,
            'color' => 'transparent',
            'textColor'  => 'red',
            
        );
    
        $reserve_array[] = $allDayEvent;
    }
    /*$reserve_array[] = array(
            'groupId'=> 'rejected',
            'display'=> 'background',
            'start'=> '2024-03-11T10:00:00',
            'end'=> '2024-03-13T16:00:00',
    );*/
    $reserves = json_encode($reserve_array);

    ?>

<script>
   
   document.addEventListener('DOMContentLoaded', function() {
    var reserves = <?php echo $reserves; ?>;
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var calendarEl = document.getElementById('calendar');

    var scrollDate = new Date();
    //scrollDate.setHours(scrollDate.getHours() - 2);
    var hours = ('0' + scrollDate.getHours()).slice(-2);
    var minutes = ('0' + scrollDate.getMinutes()).slice(-2);
    var seconds = ('0' + scrollDate.getSeconds()).slice(-2);
    var scrollTimeString = hours + ':' + minutes + ':' + seconds;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'listWeek',
        fixedWeekCount: false,
        editable: false,
        droppable: false,
        selectable: true,
        events: reserves,
        now: date,
        nowIndicator: true, 
        slotEventOverlap: false,
        scrollTime: scrollTimeString,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            listWeek: "Week's List",
            // Add or modify button text as needed
        },
        drop: function () {
            if ($('#drop-remove').is(':checked')) {
                $(this).remove();
            }
        },
        eventTimeFormat: { // Define the time format
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        businessHours: getBusinessHours(),
        eventClassNames: function(arg) {
            // Check if the event is an all-day event
            return arg.event.allDay ? 'all-day-event' : 'regular-event';
        },
    });

    function getBusinessHours() {
        var businessHours = [];

        // Define different timings for each day of the week
        if (<?php echo json_encode($this->data['monday']); ?> == 1) {
            businessHours.push({
                daysOfWeek: [1],
                startTime: <?php echo json_encode($this->data['from_monday']); ?>,
                endTime: <?php echo json_encode($this->data['to_monday']); ?>,
            });
        }
        if (<?php echo json_encode($this->data['tuesday']); ?> == 1) {
            businessHours.push({
                daysOfWeek: [2],
                startTime: <?php echo json_encode($this->data['from_tuesday']); ?>,
                endTime: <?php echo json_encode($this->data['to_tuesday']); ?>,
            });
        }
        if (<?php echo json_encode($this->data['wednesday']); ?> == 1) {
            businessHours.push({
                daysOfWeek: [3],
                startTime: <?php echo json_encode($this->data['from_wednesday']); ?>,
                endTime: <?php echo json_encode($this->data['to_wednesday']); ?>,
            });
        }
        if (<?php echo json_encode($this->data['thursday']); ?> == 1) {
            businessHours.push({
                daysOfWeek: [4],
                startTime: <?php echo json_encode($this->data['from_thursday']); ?>,
                endTime: <?php echo json_encode($this->data['to_thursday']); ?>,
            });
        }
        if (<?php echo json_encode($this->data['friday']); ?> == 1) {
            businessHours.push({
                daysOfWeek: [5],
                startTime: <?php echo json_encode($this->data['from_friday']); ?>,
                endTime: <?php echo json_encode($this->data['to_friday']); ?>,
            });
        }
        if (<?php echo json_encode($this->data['saturday']); ?> == 1) {
            businessHours.push({
                daysOfWeek: [6],
                startTime: <?php echo json_encode($this->data['from_saturday']); ?>,
                endTime: <?php echo json_encode($this->data['to_saturday']); ?>,
            });
        }
        if (<?php echo json_encode($this->data['sunday']); ?> == 1) {
            businessHours.push({
                daysOfWeek: [0],
                startTime: <?php echo json_encode($this->data['from_sunday']); ?>,
                endTime: <?php echo json_encode($this->data['to_sunday']); ?>,
            });
        }

        return businessHours;
    }

    calendar.render();
});

 
</script>
