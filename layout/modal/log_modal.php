<div class="modal fade" id="userDataModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">Add New Log</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="container">
                <form name="userDataFrm" id="userDataFrm">
                    <div class="modal-body">
                            <div class="frm-status"></div>

                            <div class="row">
                                <div class="col-sm">
                                    <div class="mb-12 ">
                                        <label for="ticket_id" class="form-label">Ticket Id</label>
                                        <?php echo $ticket_input_html ; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-4">
                                    <label for="dates" class="form-label">Dates</label>
                                    <input type="datetime-local" name="dates" id="dates" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label for="hrs" class="form-label">Hours</label>
                                    <input type="number" class="form-control" id="hrs" name="hrs" placeholder="00.0">
                                </div>
                                <div class="col-4">
                                    <label for="c_status" class="form-label">C.Status</label>
                                    <?php print_r($c_status_types_row); ?>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="form-group mt-2">
                                    <!-- <label for="what_is_done">What is Done</label> -->
                                    <textarea placeholder="What is Done...." name="what_is_done"  id="what_is_done"  class="form-control"  rows="3"></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <!-- <label for="what_is_pending">What is pending</label> -->
                                    <textarea placeholder="What is pending...." name="what_is_pending"  id="what_is_pending" class="form-control"  rows="3"></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <!-- <label for="what_support_required">What support is required</label> -->
                                    <textarea placeholder="What support is required...." name="what_support_required"  id="what_support_required" class="form-control" rows="3" ></textarea>
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
</div>