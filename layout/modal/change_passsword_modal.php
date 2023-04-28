<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="changePasswordModalLabel">Change Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="userDataFrm2" id="userDataFrm2">
                <div class="modal-body">
                    <div class="frm2-status"></div>
                    <div class="container">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="current_password" class="form-label">Current Passsword <span class="required_mark">*</span></label>
                                <input type="password" class="form-control" id="current_password" placeholder="Enter Ticket Id">
                            </div>
                            <div class="col-md-12">
                                <label for="password" class="form-label">New Password <span class="required_mark">*</span></label>
                                <input type="password" class="form-control" id="password" placeholder="Enter Ticket Id">
                            </div>
                            <div class="col-md-12">
                                <label for="cpassword" class="form-label">Re-enter New Password <span class="required_mark">*</span></label>
                                <input type="password" class="form-control" id="cpassword" placeholder="Enter Ticket Id">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" id="editID2" value="0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitPasswordData()">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>