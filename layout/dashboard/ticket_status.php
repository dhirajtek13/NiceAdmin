<?php
$ticket_status_dateSelected = date("Y-m-d");
$ticket_status_allDaysColArr = x_week_range($ticket_status_dateSelected);
// print_r($ticket_status_allDaysColArr); die();
?>
<div class="row m-2">
    <div class="col-6 ">
        <div class="form-floating">
            <input type="date" value="<?php echo $ticket_status_allDaysColArr[0]; ?>" name="ticket_status_start_date" id="ticket_status_start_date" class="form-control">
            <label for="floatingName">start date</label>
        </div>
    </div>
    <div class="col-6">
        <div class="form-floating">
            <input type="date" value="<?php echo $ticket_status_allDaysColArr[6]; ?>" name="ticket_status_end_date" id="ticket_status_end_date" class="form-control">
            <label for="floatingName">end date</label>
        </div>
    </div>
</div>