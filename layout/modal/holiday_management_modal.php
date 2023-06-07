<div class="modal fade" id="userDataModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">Update Holiday Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="userDataFrm" id="userDataFrm">
                <div class="modal-body">
                    <div class="frm-status"></div>
                    <div class="container">
                        <div class="row g-3">

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input name="hol_name" type="text" class="form-control" id="hol_name" placeholder=" name">
                                    <label for="hol_name"> Name</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea placeholder="Description" name="hol_desc"  id="hol_desc"  class="form-control"  rows="20"></textarea>
                                    <label for="hol_desc">Description</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                   <label for="hol_start_date" class="form-label">Start Date</label>
                                    <input type="datetime-local" name="hol_start_date" id="hol_start_date" class="form-control" >
                            </div>
                            <div class="col-md-6">
                                    <label for="hol_end_date" class="form-label">End Date</label>
                                    <input type="datetime-local" name="hol_end_date" id="hol_end_date" class="form-control" >
                            </div>
                          
                            
                            <!-- <div class="col-md-12">
                                <div class="form-floating">
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                            </div> -->

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