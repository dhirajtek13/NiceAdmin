<div class="modal fade" id="userDataModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">Add New Ticket</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="userDataFrm" id="userDataFrm">
                <div class="modal-body">
                    <div class="frm-status"></div>
                    <div class="container">
                        <div class="row g-3">
                            <div class="col-md-12">
                                    <label for="userFirstName" class="form-label">Ticket Id <span class="required_mark">*</span></label>
                                    <input type="text" class="form-control" id="ticket_id" placeholder="Enter Ticket Id">
                            </div>
                            <div class="col-md-12">
                                    <label for="zira_link" class="form-label">Zira Link</label>
                                    <input type="text" class="form-control" id="zira_link" placeholder="Enter Zira Link">
                            </div>
                            <div class="col-md-6">
                                    <label for="type_id" class="form-label">Type</label>
                                    <?php print_r($ticket_types_row); ?>
                            </div>
                            <div class="col-md-6">
                                    <label for="c_status" class="form-label">C.Status</label>
                                    <?php print_r($c_status_types_row); ?>
                            </div>
                            <div class="col-md-6">
                                    <label for="assignee_id" class="form-label">Assignee</label>
                                    <?php print_r($assignees_row); ?>
                            </div>
                            <div class="col-md-6">
                                    <label for="assigned_date" class="form-label">Assigned Date</label>
                                    <input type="datetime-local" name="assigned_date" id="assigned_date" class="form-control">
                            </div>

                            <div class="col-md-6">
                                   <label for="plan_start_date" class="form-label">Plan Start Date</label>
                                    <input type="datetime-local" name="plan_start_date" id="plan_start_date" class="form-control" disabled>
                            </div>
                            <div class="col-md-6">
                                    <label for="plan_end_date" class="form-label">Plan End Date</label>
                                    <input type="datetime-local" name="plan_end_date" id="plan_end_date" class="form-control" disabled>
                            </div>
                             <div class="col-md-6">
                                   <label for="actual_start_date" class="form-label">Actual Start Date</label>
                                   <!-- <input class="datelocalformatted" type="date" my-date="" my-date-format="DD/MM/YYYY, hh:mm:ss" value="2015-08-09"> -->
                                    <input type="datetime-local" name="actual_start_date" id="actual_start_date" class="form-control" data-date-format="DD/MM/YYYY" data-date="">
                            </div>
                            <div class="col-md-6">
                                    <label for="actual_end_date" class="form-label">Actual End Date</label>
                                    <input type="datetime-local" name="actual_end_date" id="actual_end_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                    <label for="planned_hrs" class="form-label">Planned Hours</label>
                                    <input type="number" class="form-control" id="planned_hrs" placeholder="00.0" disabled>
                            </div>
                            <div class="col-md-6">
                                    <label for="actual_hrs" class="form-label">Actual Hours</label>
                                    <input type="number" class="form-control" id="actual_hrs" placeholder="00.0" disabled>
                                    <!-- <em>(as per log: <?php ?>)</em> -->
                            </div>
                            
                        </div>
                    </div>

        </div>
        <div class="modal-footer">
            <input type="hidden" id="editID" value="0">
                <input type="hidden" id="previousStatus" value="0">
                <input type="hidden" id="updatedStatus" value="0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="submitUserData()">Submit</button>
        </div>
        </form>
    </div>
</div>
</div>
<div class="modal fade" id="wbsDataModal" tabindex="-1" aria-labelledby="wbsAddEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <div class="modal-header">
                                <h1 class="modal-title fs-5" id="wbsModalLabel">Activity</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                <div>
                                        <h5><b>Add Activity:</b></h5>
                                        <form name="wbsDataFrm" id="wbsDataFrm">
                                                <div class="frm-status2"></div>
                                                <div class="container">
                                                        <div class="row g-3">

                                                                <!-- <div class="col-md-12">
                                                                <label for="userFirstName" class="form-label">Sr<span class="required_mark">*</span></label>
                                                                <input type="text" class="form-control" id="sr" placeholder="">
                                                        </div> -->
                                                                <div class="col-md-12">
                                                                        <label for="activity_name" class="form-label">Activity Name<span class="required_mark">*</span></label>
                                                                        <input type="text" class="form-control" id="activity_name" placeholder="Enter Activity Name">
                                                                </div>
                                                                <div class="col-md-6">
                                                                        <label for="act_planned_hrs" class="form-label">Planned Hours <span class="required_mark">*</span></label>
                                                                        <input type="number" class="form-control" id="act_planned_hrs" placeholder="00.0" 
                                                                        <?php echo ($_SESSION['user_id'] != 1 ) ? 'disabled': ''; ?>
                                                                        >
                                                                </div>
                                                                <div class="col-md-6">
                                                                        <label for="act_actual_hrs" class="form-label">Actual Hours</label>
                                                                        <input type="number" class="form-control" id="act_actual_hrs" placeholder="00.0" disabled="">
                                                                        <!-- <em>(as per log: )</em> -->
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="m-2 float-right">
                                                        <input type="hidden" id="parentID_wbshidden" value="0">
                                                        <input type="hidden" id="ticket_id_wbshidden" value="0">
                                                         <input type="hidden" id="assignee_id_wbshidden" value="0">
                                                         <input type="hidden" id="projectID_wbshidden" value="0">
                                                        <!-- <input type="hidden" id="previousStatus" value="0">
                                                        <input type="hidden" id="updatedStatus" value="0"> -->
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" onclick="submitWBSData()">Submit</button>
                                                </div>

                                        </form>
                                </div>
                                <div class="modal-footer"></div>
                                <div>
                                        <h5><b>Activity List:</b></h5>

                                        <table id="phptable" class="table display">
                                                <tr>
                                                        <td>Sr</td>
                                                        <td>Activity</td>
                                                </tr>
                                                <tr>
                                                        <td></td>
                                                        <td></td>
                                                </tr>
                                        </table>
                                </div>

                                <!-- <div class="modal-footer"></div> -->


                        </div>

                </div>
        </div>
</div>