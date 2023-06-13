<div class="modal fade" id="userDataModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">Update Leave Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="userDataFrm" id="userDataFrm">
                <div class="modal-body">
                    <div class="frm-status"></div>
                    <div class="container">
                        <div class="row g-3">

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input name="leave_desc" type="text" class="form-control" id="leave_desc">
                                    <label for="leave_desc">Leave Description</label>
                                </div>
                            </div>

                            <div class="form-floating col-6">
                                <select name="leave_type" id="leave_type" class="form-control" >
                                    <!-- <option value=''>None</option> -->
                                    <option value='1'>Sick</option>
                                    <option value='2'>Earned</option>
                                    <option value='3'>Emergency</option>
                                </select>
                                <label for="leave_type">Select Leave Type</label>
                            </div>
                            <div class="form-floating col-6">
                                <select name="day_type" id="day_type" class="form-control" >
                                    <option value='1'>Full Day</option>
                                    <option value='2'>Half Day</option>
                                </select>
                                <label for="day_type">Select One</label>
                            </div>
                            <div class="col-md-6">
                                    <label for="leave_start_date" class="form-label">Leave Start Date</label>
                                    <input type="date" name="leave_start_date" id="leave_start_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                    <label for="leave_end_date" class="form-label">Leave Start Date</label>
                                    <input type="date" name="leave_end_date" id="leave_end_date" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" id="editID" value="0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitUserData()">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>