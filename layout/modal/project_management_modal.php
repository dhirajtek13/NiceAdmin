<div class="modal fade" id="userDataModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">Update Project Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="userDataFrm" id="userDataFrm">
                <div class="modal-body">
                    <div class="frm-status"></div>
                    <div class="container">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="project_name" type="text" class="form-control" id="project_name" placeholder="Project name *">
                                    <label for="floatingName">Project Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="region" type="text" class="form-control" id="region" placeholder="Region">
                                    <label for="floatingName">Region</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea placeholder="Description" name="description"  id="description"  class="form-control"  rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="start_date" type="datetime-local" class="form-control" id="start_date" placeholder="start date">
                                    <label for="floatingName">Start Date</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="end_date" type="datetime-local" class="form-control" id="end_date" placeholder="end date">
                                    <label for="floatingName">End Date</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="renewal_date" type="datetime-local" class="form-control" id="renewal_date" placeholder="renewal date">
                                    <label for="floatingName">Renewal Date</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="customer_name" type="text" class="form-control" id="customer_name" placeholder="customer name">
                                    <label for="floatingName">Customer Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="planned_billing" type="text" class="form-control" id="planned_billing" placeholder="planned date">
                                    <label for="floatingName">Planned Billing</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="actual_billing" type="text" class="form-control" id="actual_billing" placeholder="actual date">
                                    <label for="floatingName">Actual Billing</label>
                                </div>
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