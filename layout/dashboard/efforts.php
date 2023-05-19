<?php
$efforts_dateSelected = date("Y-m-d");
$efforts_allDaysColArr = x_week_range($efforts_dateSelected);
// print_r($efforts_allDaysColArr); die();
?>
<div class="row m-0">
    <div class="col-6 ">
        <div class="form-floating">
            <input type="date" value="<?php echo $efforts_allDaysColArr[0]; ?>" name="efforts_start_date" id="efforts_start_date" class="form-control">
            <label for="floatingName">start date</label>
        </div>
    </div>
    <div class="col-6">
        <div class="form-floating">
            <input type="date" value="<?php echo $efforts_allDaysColArr[6]; ?>" name="efforts_end_date" id="efforts_end_date" class="form-control">
            <label for="floatingName">end date</label>
        </div>
    </div>
</div>

<div class="card-body mt-4">
    <span class="efforts_table_response">
        <table id="effortTable_id" class="display table table-striped " style="width:100%">
        </table>
    </span>
</div>