
 <?php 
    $dateSelected = date("Y-m-d"); 
 
    // $dateSelected =  $jsonObj->dateselected;
    // $projectSelected = $jsonObj->projectselected;
    // $actual_hrs = $jsonObj->actual_hrs;
    $weekdays = ['Sun', 'Mon', 'Tue', 'Wed','Thu','Fri', 'Sat'];
    //get current year, month and maxdays of it
    $allDaysColArr = x_week_range($dateSelected); //from customFunctions.php
 
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
                <table id="phptable" class="display table table-striped loading" style="width:100%">
                <tr>
                    <th>S.N</th>
                    <th>Employee Name</th>
                    <?php
                    foreach ($allDaysColArr as $index => $value) {
                        echo "<th class='no-sort'>$value (".$weekdays[$index].")</th>";
                    }
                    ?>
                </tr>
                <!-- <tr>
                    <tbody>
                        <td style="min-height:300px;"></td>
                    </tbody>
                </tr> -->
                </table>
              </span>
            </div>
</div>