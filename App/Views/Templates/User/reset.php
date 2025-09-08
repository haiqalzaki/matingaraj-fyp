<div class="container">
    <div class="row pb-2 mt-3">
        <div class="col-12 col-sm-12 col-md-12 py-1">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-center mb-1">
                        <div class="p-2 mb-1">
                            <h4 class="fw-bold border-bottom">Password Reset</h4>
                        </div>
                    </div>
                     <div class="d-flex flex-row justify-content-center mb-1">
                        <p>Changing password for user:
                            <span class="text-success"><?= $data['userName'] ?></span>
                        </p>
                    </div> 
                    <div class="d-flex justify-content-center align-items-center">
                        <form class="form-control w-100 w-md-75 w-lg-50" id="reset-password-form" autocomplete="off">
                            <div class="mb-2">
                                <label for="user-password-new" class="form-label">New Password: </label>
                                <input type="password" class="form-control" name="userNewPassword" id="user-password-new" placeholder="(Min. 10 characters)">
                            </div>

                            <div class="mb-2">
                                <label for="user-password-confirm-new" class="form-label">Confirm New Password: </label>
                                <input type="password" class="form-control" id="user-password-confirm-new" placeholder="(Min. 10 characters)">
                            </div>
                    
                            <div class="mb-2">
                                <label for="user-recovery-key" class="form-label">Recovery Key: </label>
                                <input type="password" class="form-control" name="userKey" id="user-recovery-key" placeholder="Enter secret key here">
                            </div>

                            <div class="d-flex justify-content-center mt-3 mb-2">
                                <input type="hidden" name="userID" id="user-id-reset" value="<?= $data['userId'] ?>">
                                <button type="button" class="btn btn-success w-md-50 w-lg-25" id="reset-pswd-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/user/reset.js"></script>