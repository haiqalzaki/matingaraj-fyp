<!-- Action Section -->
<div class="container text-center">
    <div class="row py-2 mt-3 justify-content-between">
        <div class="col-sm-12 col-md-12 mb-3">
            <div class="barang_box card shadow rounded-4">
                <div class="card-body">
                    <div class="row mt-2">
                        <h3 class="fw-bold">Total Item</h3>
                    </div>
                    <div class="row my-3">
                        <h4 class="display-5" id="barang-available">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Table Section -->
<div class="container text-center">
    <div class="row pb-2">
        <div class="col py-1">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive" id="table-barang-main">
                        <div class="d-flex flex-row justify-content-center mb-1">
                            <div class="p-2 mb-1">
                                <h4 class="text-dark fw-bold border-bottom">Inventory List</h4>
                            </div>
                        </div>
                        <!-- Spinner for Barang -->
                        <div id="barang-spinner-table" class="d-flex justify-content-center my-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                        <table class="table align-middle" id="table-barang">
                            <!-- Barang Dynamic Content -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Chart and Add Barang Section -->
<div class="container text-center" id="barang-form-section">
    <div class="row py-2">
        <div class="col-sm-12 col-md-12 col-lg-8 py-2">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="pb-2 border-bottom">Add Barang</h3>

                    <form enctype="multipart/form-data" id="add-barang-form">
                        <div class="mb-3 text-start">
                            <label for="barang-name-add" class="form-label">Barang Name: </label>
                            <input type="text" class="form-control" name="barangName" id="barang-name-add" placeholder="Enter item name here">
                        </div>
                        <div class="mb-3 text-start">
                            <label for="barang-stock-add" class="form-label">Stock: </label>
                            <input type="number" step="1" class="form-control" name="barangStok"
                                id="barang-stock-add" placeholder="0">
                        </div>
                        <div class="mb-3 text-start">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <label for="barang-price-add" class="form-label">Cost Per Unit (RM):
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">RM</span>
                                        <input type="number" step="1" class="form-control" name="barangPrice"
                                            id="barang-price-add" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="barang-markup-add" class="form-label">Markup Per Unit (RM):
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">RM</span>
                                        <input type="number" step="1" class="form-control"
                                            name="barangMarkup" id="barang-markup-add" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <label for="barang-total-add" class="form-label">Gross Total Per Unit (RM):
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">RM</span>
                                        <input type="number" step="0.01" class="form-control"
                                            id="barang-total-add" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 text-start">
                            <label for="barang-pic-add" class="form-label">Barang Image (optional): </label>
                            <input class="form-control" type="file" name="barangImage" id="barang-pic-add">
                        </div>
                        <div class="mb-3 text-start">
                            <label for="barang-remark-add" class="form-label">Barang Remark: </label>
                            <textarea class="form-control" name="barangRemark" id="barang-remark-add"
                                rows="5" placeholder="Any remarks/description for item"></textarea>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-primary w-25 fs-6" id="add-barang-btn">Add
                                Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Charts Section -->
        <div class="col-sm-12 col-md-12 col-lg-4 py-2">
            <div class="card mb-2 shadow">
                <div class="card-body">
                    <p class="text-primary">TOP 5 MOST USED</p>
                    <canvas id="chDonut1"></canvas>
                </div>
            </div>
         </div>
    </div>
</div>
</div>
</div>

<!-- Edit Barang Modal -->
<div class="modal fade in" id="modify-barang-modal" tabindex="-1" aria-labelledby="barang-modal-edit-label">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="barang-edit-modal-label">Edit Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-control" id="modify-barang-form">
                    <div class="row justify-content-center mt-3 mb-4">
                        <div class="col-auto text-center">
                            <img src="<?= $_ENV['HOME_URL'] ?>/image/no-img.jpg" alt="Barang Image" width="150" height="150"
                                style="border: solid grey 1px; border-radius: 10%;" class="image-fluid"
                                id="barang-image-edit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="barang-name-edit" class="form-label">Barang Name: </label>
                                <input type="text" class="form-control" name="barangName" id="barang-name-edit">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <label for="barang-stock-edit" class="form-label">Stock: </label>
                                <input type="number" step="1" class="form-control" name="barangStock" id="barang-stock-edit">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-2">
                                <label for="barang-harga-edit" class="form-label">Cost Per Unit: </label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="1" class="form-control" name="barangPrice"
                                        id="barang-harga-edit" placeholder="(Default = 0.00)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-2">
                                <label for="barang-markup-edit" class="form-label">Markup: </label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="1" class="form-control" name="barangMarkup"
                                        id="barang-markup-edit" placeholder="(Default = 0.00)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-2">
                                <label for="barang-total-edit" class="form-label">Gross: </label>
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" step="1" class="form-control" id="barang-total-edit"
                                        readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="barang-remark-edit" class="form-label">Barang Remark: </label>
                                <textarea class="form-control" name="barangRemark" id="barang-remark-edit"
                                    rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <input type="hidden" name="barangID" id="barang-id-edit">
                        <button type="button" class="btn btn-danger" onclick="deleteBarang()">Delete</button>
                        <button type="button" class="btn btn-secondary" id="update-barang-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Barang Modal -->
<div class="modal modal-sm fade in" id="delete-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="delete-modal-title">Delete Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fs-5 mt-3" id="delete-modal-content">delete goes here..</p>
                <div class="modal-footer d-inline-flex justify-content-between">
                    <button class="btn btn-danger mt-2" id="delete-barang-btn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/barang/barang.js"></script>