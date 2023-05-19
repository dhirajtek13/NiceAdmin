<?php
// $dateSelected = date("Y-m-d"); //today 
// if (isset($_GET['dateselected'])) {
//     $dateSelected = $_GET['dateselected'];
// }
$dateSelected = date("Y-m-d"); 
?>

<div class="m-3">
    Min. hrs considered: <?= $CONFIG_ALL['actual_hrs']['value1'] ?><br />
    <div class="form-floating">
        <input type="date" value="<?php echo $dateSelected; ?>" name="dateSelected" id="dateSelected" class="form-control w-25" oninput="reloadData()">
        <label for="floatingName">Select any day of the week</label>
    </div>
</div>
<div class="card-body mt-4">

    <span class="weekly_report_table_response">

    </span>
   
</div>