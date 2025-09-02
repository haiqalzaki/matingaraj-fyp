<div class="content full-width container-fluid">
    <div class="row ms-1 me-2">
        <!-- Task Form -->
        <div class="container mt-4">
            <div class="row">
                <!-- Form Column -->
                <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                    <form id="taskFormUpdate" autocomplete="off">
                        <div class="card p-3">
                            <div class="d-flex flex-column card-body">
                                <div class="row pb-3">
                                    <h4>Task Section</h4>
                                </div>
                                <div class="row pb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="motor">Motorcyle Type: </label>
                                        <select class="form-select" name="motor" id="motor">
                                            <option value="Yamaha 125Z" <?= ($data['task']['detail']['motor'] == "Yamaha 125Z") ? 'selected' : '' ?>>Yamaha 125Z</option>
                                            <option value="Yamaha LC135" <?= ($data['task']['detail']['motor'] == "Yamaha LC135") ? 'selected' : '' ?>>Yamaha LC135</option>
                                            <option value="Yamaha Y15" <?= ($data['task']['detail']['motor'] == "Yamaha Y15") ? 'selected' : '' ?>>Yamaha Y15</option>
                                            <option value="Yamaha Y16" <?= ($data['task']['detail']['motor'] == "Yamaha Y16") ? 'selected' : '' ?>>Yamaha Y16</option>
                                            <option value="Honda EX5" <?= ($data['task']['detail']['motor'] == "Honda EX5") ? 'selected' : '' ?>>Honda EX5</option>
                                            <option value="Honda RS150" <?= ($data['task']['detail']['motor'] == "Honda RS150") ? 'selected' : '' ?>>Honda RS150</option>
                                            <option value="Honda Wave 115" <?= ($data['task']['detail']['motor'] == "Honda Wave 115") ? 'selected' : '' ?>>Honda Wave 115</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="plate">Motorcyle Plate: </label>
                                        <input class="form-control" type="text" name="plate" id="plate"
                                            value="<?= $data['task']['detail']['plate'] ?>">
                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="servis">Service Type: </label>
                                        <select class="form-select" name="servis" id="servis">
                                            <option value="" disabled>Select service type</option>
                                            <option value="Major" <?= ($data['task']['detail']['servis'] == "Major") ? 'selected' : '' ?>>Major</option>
                                            <option value="Minor" <?= ($data['task']['detail']['servis'] == "Minor") ? 'selected' : '' ?>>Minor</option>
                                            <option value="Modify" <?= ($data['task']['detail']['servis'] == "Modify") ? 'selected' : '' ?>>Modify</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="status">Status: </label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="" disabled>Select status</option>
                                            <option value="Pending" <?= ($data['task']['detail']['status'] == "Pending") ? 'selected' : '' ?>>Pending</option>
                                            <option value="Ongoing" <?= ($data['task']['detail']['status'] == "Ongoing") ? 'selected' : '' ?>>Ongoing</option>
                                            <option value="Completed" <?= ($data['task']['detail']['status'] == "Completed") ? 'selected' : '' ?>>Completed</option>
                                        </select>
                                    </div>
                                </div>  
                                <div class="row pb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label for="task-markup-add" class="form-label">Task Markup: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">RM</span>
                                            <input type="number" step="0.01" class="form-control" name="markup" 
                                                id="task-markup-add" value="<?= $data['task']['detail']['markup_total'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label for="task-harga-total" class="form-label">Task Total: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">RM</span>
                                            <input type="number" step="0.01" class="form-control"
                                                id="task-harga-add" value="<?= $data['task']['finance']['harga_total'] ?>" readonly disabled>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row pb-3">
                                    <div class="col-sm-12 col-md-12">
                                        <label class="form-label" for="status-pembayaran">Payment Status: </label>
                                        <select class="form-select" name="isPaid" id="status-pembayaran">
                                            <option value="0" <?= ($data['task']['finance']['status_pembayaran'] == "0") ? 'selected' : '' ?>>Not Paid</option>
                                            <option value="1" <?= ($data['task']['finance']['status_pembayaran'] == "1") ? 'selected' : '' ?>>Paid</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="row pb-3">
                                    <div class="col">
                                        <label for="task-remark-add" class="form-label">Task Remark: </label>
                                        <textarea class="form-control" name="remark" id="task-remark-add" 
                                            rows="5"><?= $data['task']['detail']['remark'] ?></textarea>
                                    </div>          
                                </div>
                                <div class="row pb-3">
                                    <div class="col">
                                        <label class="form-label mb-3" for="status">List of Items: </label>
                                        <div class="table-responsive">
                                            <table class="table text-center fs-6" id="myBarang">
                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-3 ps-1">
                                    <div class="col d-flex justify-content-between">
                                        <input type="hidden" name="taskID" id="task-id" value="<?= $data['task']['detail']['task_id'] ?>">
                                        <input type="hidden" name="financeID" id="finance-id" value="<?= $data['task']['finance']['finance_id'] ?>">
                                        <!-- Download PDF button -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= $_ENV['HOME_URL'] ?>/pdf/quotation/<?= $data['task']['detail']['task_id'] ?>" class="btn btn-success">Get Quotation</a>
                                            <a href="<?= $_ENV['HOME_URL'] ?>/pdf/receipt/<?= $data['task']['detail']['task_id'] ?>" class="btn btn-secondary">Get Receipt</a>
                                        </div>
					                    <span class="text-success fw-bold d-none p-1" id="success-flasher">Succesfully updated!</span>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <?php if ($_SESSION['isAdmin'] === true) : ?>
                                                <button type="button" class="btn btn-danger" onclick="deleteTask()">Delete</button>
                                                <button type="button" class="btn btn-primary" id="update-task-btn">Update</button>
                                            <?php else : ?>
                                                <button type="button" class="btn btn-primary" id="update-task-btn">Update</button>
                                            <?php endif ; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Details Column -->
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <!-- Barang List Table Column -->
                    <div class="row px-2 ">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex my-2 justify-content-between">
                                    <div class="refresh_table_button" id="refreshTable" style="cursor: pointer;">
                                        <i class="bi bi-arrow-clockwise align-middle mt-1 mx-2 px-2 bg-light border rounded-5 text-dark"></i>
                                    </div>
                                    <h4 class="w-100 text-center">Inventory List</h4>
                                    <div>
                                        <div class="d-flex justify-content-end" role="search">
                                            <input class="form-control w-75" id="search-control-brg" type="search" placeholder="Search">
                                        </div> 
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable">
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- Customer Details Column -->
                    <div class="row px-2 mt-2 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h3 class="w-100 text-center pb-3 border-bottom">Customer Details</h3>
                                </div>
                                <div class="d-flex flex-column rounded-3">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 text-center text-dark border-bottom pt-2">
                                            <h4 class="fw-bolder fs-5">Customer Name:</h4>
                                            <p class="fs-6"><?= $data['task']['customer']['name'] ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-6 text-center text-dark border-bottom pt-2">
                                            <h4 class="fw-bolder fs-5">Telephone:</h4>
                                            <p class="fs-6"><?= $data['task']['customer']['telefon'] ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 text-center border-bottom text-dark pt-2">
                                            <h4 class="fw-bolder fs-5">Email:</h4>
                                            <p class="fs-6"><?= $data['task']['customer']['email'] ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-6 text-center border-bottom text-dark pt-2">
                                            <h4 class="fw-bolder fs-5">Platform:</h4>
                                            <p class="fs-6"><?= $data['task']['customer']['platform'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex my-2">
                                    <h3 class="w-100 text-center pb-3 border-bottom">Finance Details</h3>
                                </div>
                                <div class="d-flex flex-column rounded-3">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 text-center text-dark border-end py-1">
                                            <h4 class="fw-bolder fs-5">Total Price:</h4>
                                            <p class="fs-6">RM <?= $data['task']['finance']['harga_total'] ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-6 text-center text-dark py-1">
                                            <h4 class="fw-bolder fs-5">Payment Status:</h4>
                                            <p class="fs-6"><?= $data['task']['finance']['status_pembayaran'] === 0 ? "<button class='btn btn-danger btn-sm'>Not Paid</button>" : "<button class='btn btn-success btn-sm'>Paid</button>"; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
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
                    <button class="btn btn-danger mt-2" id="delete-task-btn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/task/fetch.js"></script>