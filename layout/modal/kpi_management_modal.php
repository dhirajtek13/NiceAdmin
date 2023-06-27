<div class="modal fade" id="userDataModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">Update KPI Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="userDataFrm" id="userDataFrm">
                <div class="modal-body">
                    <div class="frm-status"></div>
                    <div class="container">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="kpi_name" type="text" class="form-control" id="kpi_name" placeholder="Type name">
                                    <label for="kpi_name">KPI Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="service_level" type="text" class="form-control" id="service_level" placeholder="Type name">
                                    <label for="service_level">KPI Service Level</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea placeholder="Description" name="description"  id="description"  class="form-control"  rows="20"></textarea>
                                    <label for="floatingName">Description</label>
                                </div>
                            </div>
                            <div class="form-floating col-6">
                                <select name="target_operator" id="target_operator" class="form-control" >
                                    <option value=''>None</option>
                                    <option value='<'><</option>
                                    <option value='>'>></option>
                                    <option value='<='><=</option>
                                    <option value='>='>>=</option>
                                    <option value='='>=</option>
                                </select>
                                <label for="target_operator">Select Operator</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="target_value" type="text" class="form-control" id="target_value" placeholder="Type name">
                                    <label for="target_value">Target Value</label>
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