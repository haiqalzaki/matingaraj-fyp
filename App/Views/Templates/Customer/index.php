<!-- Main Customer Section -->
<div class="container">
    <div class="row pb-2 mt-3">
        <!-- Customer Table Column -->
        <div class="col-sm-12 col-md-12 col-lg-9 py-1">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between mb-1">
                        <div class="p-2 mb-1">
                            <h4 class="fw-bold border-bottom">Customer List</h4>
                        </div>
                        <div class="py-2 mb-1">
                            <input class="form-control w-100" id="search-control-customer" type="search" placeholder="Search">
                        </div>
                    </div>
                    <!-- Spinner for Pelanggan -->
                    <div id="pelanggan-spinner-table" class="d-flex justify-content-center my-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle fs-6" id="table-customer">
                            <!-- Customer Dynamic Content -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Task Nav Column -->
        <div class="col-sm 12 col-md-12 col-lg-3 py-1 text-center">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="border-bottom pb-2 fs-5">Customer Navigation</h4>
                    <a
                        class="user_button_customer btn p-2 my-2 w-100 h-100 shadow text-light" 
                        data-bs-toggle="modal" data-bs-target="#customer-modal"
                    >Add Customer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade in" id="customer-modal" tabindex="-1" aria-labelledby="customer-modal-label">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="customer-modal-label">Add Customer</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-control" id="add-customer-form">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-name-add" class="form-label">Name: </label>
                                <input type="text" class="form-control" name="cxName" id="customer-name-add" placeholder="Enter customer name">
                            </div>
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
                                <textarea class="form-control" name="cxRemark" id="user-remark-add" rows="5"
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

<!-- Edit Customer Modal -->
<div class="modal fade in" id="modify-customer-modal" tabindex="-1" aria-labelledby="customer-modal-edit-label">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="customer-edit-modal-label">Edit Customer</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-control" id="modify-customer-form">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-name-edit" class="form-label">Name: </label>
                                <input type="text" class="form-control" name="cxName" id="customer-name-edit">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-phone-edit" class="form-label">Telephone: </label>
                                <input type="text" class="form-control" name="cxPhone" id="customer-phone-edit">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-email-edit" class="form-label">Email: </label>
                                <input type="text" class="form-control" name="cxEmail" id="customer-email-edit">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="customer-platform-edit" class="form-label">Platform: </label>
                                <select name="cxPlatform" class="form-select" id="customer-platform-edit">
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
                                <label for="customer-remark-edit" class="form-label">Customer Remark: </label>
                                <textarea class="form-control" name="cxRemark" id="customer-remark-edit"
                                    rows="5" value=""></textarea>
                            </div>
                        </div>
                    </div>
                    <?php if ($_SESSION['isAdmin'] === true) : ?>
                        <div class="d-flex justify-content-between mt-2 mb-2">
                            <input type="hidden" name="cxID" id="customer-id-edit">
                            <button type="button" class="btn btn-danger" onclick="deleteCustomer()">Delete</button>
                            <button type="button" class="btn btn-success" id="update-customer-btn">Submit</button>
                        </div>
                    <?php else : ?>
                        <div class="d-flex justify-content-end mt-2 mb-2">
                            <input type="hidden" name="cxID" id="customer-id-edit">
                            <button type="button" class="btn btn-success" id="update-customer-btn">Submit</button>
                        </div>
                    <?php endif ; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Customer Modal -->
<div class="modal modal-sm fade in" id="delete-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="delete-modal-title">Delete Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form class="form-control" id="delete-customer-form">
                    <p class="fs-5 mt-3" id="delete-modal-content">delete goes here..</p>
            </div>
            <div class="modal-footer d-inline-flex justify-content-end">
                <input type="hidden" name="cxID" id="customer-id-delete">
                <button type="button" class="btn btn-danger w-25 mt-2" id="delete-customer-btn">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/customer/customer.js"></script>
