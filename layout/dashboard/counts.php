<?php
$counts_dateSelected = date("Y-m-d");
$counts_allDaysColArr = x_week_range($counts_dateSelected);
// print_r($counts_allDaysColArr); die();
?>
<div class="row m-0">
    <div class="col-6 ">
        <div class="form-floating">
            <input type="date" value="<?php echo $counts_allDaysColArr[0]; ?>" name="counts_start_date" id="counts_start_date" class="form-control">
            <label for="floatingName">start date</label>
        </div>
    </div>
    <div class="col-6">
        <div class="form-floating">
            <input type="date" value="<?php echo $counts_allDaysColArr[6]; ?>" name="counts_end_date" id="counts_end_date" class="form-control">
            <label for="floatingName">end date</label>
        </div>
    </div>
</div>

<div class="card-body mt-4">
    <span class="counts_table_response">
        <table id="countTable_id" class="display table table-striped " style="width:100%">
        </table>
    </span>
</div>