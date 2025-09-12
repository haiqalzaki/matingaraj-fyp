<!-- Action Section -->
<div class="container text-center">
    <div class="card shadow mt-3">
        <div class="card-body">
            <div class="row  justify-content-center align-items-center g-0">
                <div class="col-12 col-sm-2 col-md-3">
                    <button class="user_button1 btn btn-primary p-3 my-2 w-75 h-100 shadow" 
                    data-bs-toggle="modal" data-bs-target="#user-modal">Add System User</button>
                </div>
                <!-- <div class="col-12 col-sm-2 col-md-3">
                    <button class="user_button2 btn btn-success p-3 my-2 w-75 h-100 shadow">Change Password</button>
                </div> -->
                <!-- <div class="col-12 col-sm-2 col-md-3">
                    <button class="user_button3 btn btn-secondary p-3 my-2 w-75 h-100 shadow">Get Secret Key</button>
                </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container">
    <div class="row pb-2 mt-3">
        <!-- Admin Column -->
        <div class="col-12 col-sm-12 col-md-6 py-1">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="d-flex flex-row justify-content-center mb-1">
                            <div class="p-2 mb-1">
                                <h4 class="fw-bold border-bottom">Admin List</h4>
                            </div>  
                        </div>
                        <!-- Spinner for Admin -->
                        <div id="admin-spinner" class="d-flex justify-content-center my-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                        <table class="table align-middle fs-6" id="table-admin">
                            <!-- Admin Dynamic Content -->
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Worker Column -->
        <div class="col-12 col-sm-12 col-md-6 py-1">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="d-flex flex-row justify-content-center mb-1">
                            <div class="p-2 mb-1">
                                <h4 class="fw-bold border-bottom">Worker List</h4>
                            </div>  
                        </div>
                        <div id="worker-spinner" class="d-flex justify-content-center my-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                        <table class="table align-middle fs-6" id="table-user">
                            <!-- Worker Dynamic Content -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Not Yet Section -->
<div class="container text-center">
    <div class="row pb-2 mt-3">
        <div class="col-12">
            
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade in p-0" id="user-modal" tabindex="-1" aria-labelledby="user-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-center w-100" id="user-modal-label">Add User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-control" id="add-user-form" autocomplete="off">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-2">
                        <label for="user-name-add" class="form-label">Username: </label>
                        <input type="text" class="form-control" name="userName" id="user-name-add" placeholder="Enter username here">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-2">
                        <label for="user-phone-add" class="form-label">Telephone: </label>
                        <input type="text" class="form-control" name="userPhone" id="user-phone-add" placeholder="Enter phone number here">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                        <label for="user-email-add" class="form-label">Email: </label>
                        <input type="email" class="form-control" name="userEmail" id="user-email-add" placeholder="Enter email here">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                    <label for="user-role-add" class="form-label">Platform: </label>
                        <select name="userRole" class="form-select" id="user-role-add">
                            <option selected disabled>User Role</option>
                            <option value="1">Admin</option>
                            <option value="0">Worker</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                        <label for="user-password-add" class="form-label">Password: </label>
                        <input type="password" class="form-control" name="userPassword" id="user-password-add" placeholder="Enter password here (Min. 10 characters)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                        <label for="user-password-confirm-add" class="form-label">Confirm Password: </label>
                        <input type="password" class="form-control" id="user-password-confirm-add" placeholder="Confirm password here (Min. 10 characters)">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3 mb-2">
                <button type="button" class="btn btn-success w-50" id="add-user-btn">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade in p-0" id="delete-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100" id="delete-modal-title">Delete Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <form class="form-control" id="delete-user-form">
                <p class="fs-5 mt-3" id="delete-modal-content">delete goes here..</p>
            </div>
            <div class="modal-footer d-inline-flex justify-content-between">
                <input type="hidden" name="userID" id="user-id-delete">
                <button class="btn btn-primary w-25 mt-2" id="delete-user-cancel">Cancel</button>
                <button class="btn btn-danger w-25 mt-2" id="delete-user-btn">Confirm</button>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Modify User Modal -->
<div class="modal fade in p-0" id="modify-user-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100">Modify User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-control" id="modify-user-form">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-2">
                        <label for="user-name-edit" class="form-label">Username: </label>
                        <input type="text" class="form-control" name="userName" id="user-name-edit">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-2">
                        <label for="user-phone-edit" class="form-label">Telephone: </label>
                        <input type="text" class="form-control" name="userPhone" id="user-phone-edit">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                        <label for="user-email-edit" class="form-label">Email: </label>
                        <input type="email" class="form-control" name="userEmail" id="user-email-edit">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                    <label for="user-role-edit" class="form-label">Platform: </label>
                        <select name="userRole" class="form-select" id="user-role-edit">
                            <option selected disabled>User Role</option>
                            <option value="1">Admin</option>
                            <option value="0">Worker</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3 mb-2">
                <input type="hidden" name="userID" id="user-id-edit">
                <button type="button" class="btn btn-primary" id="reset-pswd-btn">Reset Password</button>
                <button type="button" class="btn btn-success" id="update-user-btn">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/user/user.js"></script>