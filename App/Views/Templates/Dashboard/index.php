
<!-- Action Section -->
<div class="container text-center">
    <div class="row py-2 mt-2 justify-content-between">
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="barang_box card shadow rounded-5 border">
                <div class="card-body">
                    <div class="row mt-2">
                        <h3 class="fw-bold">Pending</h3>
                    </div>
                    <div class="row my-3">
                        <h4 class="display-5" id="pending-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="barang_box card shadow rounded-5 border">
                <div class="card-body">
                    <div class="row mt-2">
                        <h3 class="fw-bold">Ongoing</h3>
                    </div>
                    <div class="row my-3">
                        <h4 class="display-5" id="ongoing-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="barang_box card shadow rounded-5 border">
                <div class="card-body">
                    <div class="row mt-2">
                        <h3 class="fw-bold">Complete</h3>
                    </div>
                    <div class="row my-3">
                        <h4 class="display-5" id="complete-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="barang_box card shadow rounded-5 border">
                <div class="card-body">
                    <div class="row mt-2">
                        <h3 class="fw-bold">Paid</h3>
                    </div>
                    <div class="row my-3">
                        <h4 class="display-5" id="cancelled-count">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tables Section -->
<div class="container text-center">
    <div class="row py-1">
        <div class="col-sm-12 col-md-12 col-lg-6 py-1">
            <div class="card shadow rounded-4">
                <div class="card-body ">
                <div class="table-responsive">
                        <div class="d-flex justify-content-center">
                            <div>
                                <h4 class="fw-bold border-bottom">Recent Task</h4>
                            </div>
                        </div>
                        <!-- Spinner for Task Latest -->
                        <div id="task-spinner-dashboard" class="d-flex justify-content-center my-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                        <table id="table-task">
                            <!-- Dynamic content -->
                        </table>
                    </div>
                    <div class="d-flex justify-content-start">
                        <div class="mt-2 mb-2">
                            <a href="<?= $_ENV['HOME_URL'] ?>/task">
                                <button type="button" class="btn btn-primary btn-sm">View Tasks</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6 py-1">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="d-flex justify-content-center">
                            <div>
                                <h4 class="fw-bold border-bottom">Recent Customer</h4>
                            </div>
                        </div>
                        <!-- Spinner for Pelanggan Latest -->
                        <div id="pelanggan-spinner-dashboard" class="d-flex justify-content-center my-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                        <table id="table-cx">
                            <!-- Dynamic content -->
                        </table>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="mt-2 mb-2">
                            <a href="<?= $_ENV['HOME_URL'] ?>/customer">
                                <button type="button" class="btn btn-primary btn-sm">View Customers</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<?php if ($_SESSION['isAdmin'] === true) : ?>
    <div class="container text-center">
        <div class="row mt-3">
            <div class="col-sm-12 col-md-6 col-lg-3 py-1 mb-1">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="border-bottom pb-3">Quick Action</h5>
                        <div class="d-flex flex-column">
                        <a
                            class="user_button_customer btn p-2 my-2 w-100 h-100 fs-5 shadow text-light" 
                            href="<?= $_ENV['HOME_URL'] ?>/task/handler"
                        >Add Task</a>
                        <a
                            href="#"
                            class="user_button_customer btn p-2 my-2 w-100 h-100 fs-5 shadow text-light" 
                            data-bs-toggle="modal"
                            data-bs-target="#customer-modal"
                        >Add Customer</a>
                        <a
                            class="user_button_customer btn p-2 my-2 w-100 h-100 fs-5 shadow text-light" 
                            href="<?= $_ENV['HOME_URL'] ?>/barang"
                        >Inventory List</a>
                        <a
                            class="user_button_customer btn p-2 my-2 w-100 h-100 fs-5 shadow text-light" 
                            data-bs-toggle="modal"
                            data-bs-target="#user-modal"
                        >Add System User</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 py-1 mb-1">
                <div class="card shadow">
                    <div class="card-body">
                        <p class="text-primary">REVENUE TREND (Last 5 Days)</p>
                        <canvas id="chLine1" height="100" width="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 py-1 mb-1">
                <div class="card shadow">
                    <div class="card-body">
                        <p class="text-primary">REVENUE BY SERVICE TYPE (%)</p>
                        <canvas id="chDonut1"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 py-1 mb-1">
                <div class="card shadow">
                    <div class="card-body">
                        <p class="text-primary">CUSTOMER DISTRIBUTION BY PLATFORM (&)</p>
                        <canvas id="chDonut2"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ; ?>

<!-- Add Customer Modal -->
<div class="modal fade in" id="customer-modal" tabindex="-1" aria-labelledby="customer-modal-label">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="customer-modal-label">Add Customer</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-control" id="add-customer-form" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-name-add" class="form-label">Name: </label>
                                <input type="text" class="form-control" name="cxName" id="customer-name-add" placeholder="Enter customer name">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-email-add" class="form-label">Email: </label>
                                <input type="text" class="form-control" name="cxEmail" id="customer-email-add" placeholder="Enter email (optional)">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-phone-add" class="form-label">Telephone: </label>
                                <input type="text" class="form-control" name="cxPhone" id="customer-phone-add" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-platform-add" class="form-label">Platform: </label>
                                <select name="cxPlatform" class="form-select" id="customer-platform-add">
                                    <option selected disabled>Choose Customer Platform</option>
                                    <option value="Whatsapp">Whatsapp</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="user-remark-add" class="form-label">Customer Remark: </label>
                                <textarea class="form-control" name="cxRemark" id="user-remark-add" rows="10"
                                    value="" placeholder="Any remarks/detail here"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-2 mb-2">
                        <button type="button" class="btn btn-success" id="add-customer-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade in" id="user-modal" tabindex="-1" aria-labelledby="user-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-center w-100" id="user-modal-label">Add User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-control" id="add-user-form" autocomplete="off">
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                        <label for="user-name-add" class="form-label">Username: </label>
                        <input type="text" class="form-control" name="userName" id="user-name-add" placeholder="Enter username here">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-2">
                        <label for="user-phone-add" class="form-label">Telephone: </label>
                        <input type="text" class="form-control" name="userPhone" id="user-phone-add" placeholder="Enter phone number here">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="mb-2">
                        <label for="user-email-add" class="form-label">Email: </label>
                        <input type="email" class="form-control" name="userEmail" id="user-email-add" placeholder="Enter email here">
                    </div>
                </div>
                <div class="col-4">
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
                <div class="col-6">
                    <div class="mb-2">
                        <label for="user-password-add" class="form-label">Password: </label>
                        <input type="password" class="form-control" name="userPassword" id="user-password-add" placeholder="Enter password here (Min. 10 characters)">
                    </div>
                </div>
                <div class="col-6">
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

<script src="<?= $_ENV['HOME_URL'] ?>/js/dashboard/dashboard.js"></script>
<script>
    fetch(`${BASE_PATH}/dashboard/paparkanStatus`)
    .then(response => response.json())
    .then(data => {
        document.getElementById("pending-count").innerText = data.data.pending;
        document.getElementById("ongoing-count").innerText = data.data.ongoing;
        document.getElementById("complete-count").innerText = data.data.completed;
        document.getElementById("cancelled-count").innerText = data.data.paid;
    })
    .catch(error => console.error('Error:', error));
</script>