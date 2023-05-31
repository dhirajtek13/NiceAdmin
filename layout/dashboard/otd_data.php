<?php
$otd_dateSelected = date("Y-m-d");
$otd_allDaysColArr = x_week_range($otd_dateSelected);
?>

<div class="row m-2">
    <div class="col-3 ">
        <div class="form-floating">
            <input type="date" value="<?php echo $otd_allDaysColArr[0]; ?>" name="otd_start_date" id="otd_start_date" class="form-control" oninput="fetchOTDData()">
            <label for="floatingName">start date</label>
        </div>
    </div>
    <div class="col-3">
        <div class="form-floating">
            <input type="date" value="<?php echo $otd_allDaysColArr[6]; ?>" name="otd_end_date" id="otd_end_date" class="form-control" oninput="fetchOTDData()">
            <label for="floatingName">end date</label>
        </div>
    </div>
</div>
<div class="card-body mt-4">
    
    <table id="kpiTable1" class="display table table-striped " style="width:100%">
        <tr>
            <th>KPI Name</th>
            <th>Target</th>
            <th>Metrics</th>
            <th>% Achieved</th>
            <th>Status</th>
        </tr>
    </table>
</div>