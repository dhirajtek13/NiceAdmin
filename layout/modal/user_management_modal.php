<div class="modal fade" id="userDataModal" tabindex="-1" aria-labelledby="userAddEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userModalLabel">Update User Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="userDataFrm" id="userDataFrm">
                <div class="modal-body">
                    <div class="frm-status"></div>
                    <div class="container">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="username" type="text" class="form-control" id="username" placeholder="Your Name">
                                    <label for="floatingName">Username</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="email" type="email" class="form-control" id="email" placeholder="Your Email">
                                    <label for="floatingEmail">Your Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="fname" type="text" class="form-control" id="fname" placeholder="First Name">
                                    <label for="floatingfname">First Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="lname" type="text" class="form-control" id="lname" placeholder="Last Name">
                                    <label for="floatinglname">Last Name</label>
                                </div>
                            </div>
                            
                            <!-- <div class="col-md-12">
                                <div class="form-floating">
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                            </div> -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input name="employee_id" type="text" class="form-control" id="employee_id" placeholder="Your Name">
                                    <label for="floatingName">Employee Id</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input name="designation" type="text" class="form-control" id="designation" placeholder="Your Name">
                                    <label for="floatingName">Designation</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <?php echo $user_type_row; ?>

                                    <label for="floatingSelect">User Type</label>
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