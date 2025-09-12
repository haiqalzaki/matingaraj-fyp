<div class="content full-width container-fluid">
    <div class="row">
        <!-- Task Form -->
        <div class="container mt-3">
            <div class="row">
                <!-- Form Column -->
                <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                    <form id="taskForm" autocomplete="off">
                        <div class="card p-3">
                            <div class="d-flex flex-column card-body">
                                <div class="row pb-3 text-center">
                                    <h4>Task Handler</h4>
                                </div>
                                <div class="row pb-3">
                                    <div class="col" id="customer-box">
                                        <label class="form-label" for="customer-search">Customer Name: </label>
                                        <div class="col d-flex gap-2" id="customer-box-inside">
                                            <input type="hidden" name="customerID" id="customer-id">
                                            <input class="form-control" type="text" name="customer" id="customer-search" placeholder="Search customer here..">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="clearCustomer()">Clear</button>
                                        </div>
                                        <div id="customer-search-container"></div>
                                    </div> 
                                </div>
                                <div class="row pb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="motor">Motorcycle Type: </label>
                                        <select class="form-select" name="motor" id="motor">
                                            <option value="" selected disabled>Select motor type</option>
                                            <option value="Yamaha 125Z">Yamaha 125Z</option>
                                            <option value="Yamaha LC135">Yamaha LC135</option>
                                            <option value="Yamaha Y15">Yamaha Y15</option>
                                            <option value="Yamaha Y16">Yamaha Y16</option>
                                            <option value="Honda EX5">Honda EX5</option>
                                            <option value="Honda RS150">Honda RS150</option>
                                            <option value="Honda Wave 115">Honda Wave 115</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="plate">Motorcycle Plate: </label>
                                        <input class="form-control" type="text" name="plate" id="plate" placeholder="Enter plate here">
                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="servis">Service Type: </label>
                                        <select class="form-select" name="servis" id="servis">
                                            <option value="" selected disabled>Select service type</option>
                                            <option value="Major">Major</option>
                                            <option value="Minor">Minor</option>
                                            <option value="Modify">Modify</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="form-label" for="status">Status: </label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="" selected disabled>Select status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Ongoing">Ongoing</option>
                                            <option value="Completed">Completed</option>
                                        </select>
                                    </div>
                                </div>   
                                <div class="row pb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label for="task-markup-add" class="form-label">Task Markup: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">RM</span>
                                            <input type="number" step="0.01" class="form-control" name="markup"
                                                id="task-markup-add" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label for="task-harga-total" class="form-label">Task Total: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">RM</span>
                                            <input type="number" step="0.01" class="form-control"
                                                id="task-harga-add" readonly disabled>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row pb-3">
                                    <div class="col">
                                        <label for="task-remark-add" class="form-label">Task Remark/Instructions: </label>
                                        <textarea class="form-control" name="remark" id="task-remark-add" rows="5" value="" placeholder="Any remarks/instructions here"></textarea>
                                    </div>          
                                </div>
                                <div class="row pb-3">
                                    <div class="col">
                                        <label class="form-label mb-3" for="status">List of Items: </label>
                                        <div class="table-responsive">
                                            <table class="table text-center fs-6" id="myBarang">
                                                <!-- Dynamic Table -->
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col d-flex justify-content-end">
                                        <button type="button" class="btn btn-success w-25" id="add-task-btn">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Details Column -->
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <!-- Select Barang Column -->
                    <!-- <div class="row px-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex my-2 justify-content-between">
                                    <h4 class="w-100">Search Barang</h4>
                                    <div>
                                        <div class="d-flex justify-content-end" role="search">
                                            <input class="form-control w-100" id="barang-search" type="search" placeholder="Search">
                                            <div id="barang-search-container"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-wrap my-2">
                                    <div class="p-1">
                                        <label class="form-label" for="stok">Nama Barang: </label>
                                        <input type="text" class="form-control" name="barangName" id="barang-name" readonly disabled>
                                    </div>
                                    <div class="p-1">
                                        <label class="form-label" for="stok">Stok Barang: </label>
                                        <input type="text" class="form-control" name="barangStock" id="barang-stock" readonly disabled>
                                    </div>
                                    <div class="p-1">
                                        <label class="form-label" for="hargaunit">Harga Per Unit: </label>
                                        <input type="text" class="form-control" name="barangHarga" id="barang-hargaunit" readonly disabled>
                                    </div>
                                    <div class="p-1">
                                        <label class="form-label" for="kuantiti">Kuantiti: </label>
                                        <input type="number" class="form-control" name="barangKuantiti" id="barang-kuantiti">
                                    </div> 
                                    <div class="p-1 mt-2 align-self-end">
                                        <input type="hidden" name="barangID" id="barang-id">
                                        <button type="button" class="btn btn-success btn-sm px-5" id="add-task-barang">Add</button>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Barang List Table Column -->
                    <div class="row px-2">
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
                                        <!-- Dynamic Table -->
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- <div class="row px-2 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex my-2 justify-content-center text-center">
                                    <h4 class="w-100">Reserved for Task Details</h4>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable2">
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add TaskBarang Kuantiti Modal -->
<div class="modal fade in" id="add-taskbarang-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100">Barang Quantity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-control" id="add-taskbarang-form">
            <div class="row">
                <div class="col">
                    <div class="mb-2">
                        <label for="brg-quantity-edit" class="form-label">Kuantiti: </label>
                        <input type="text" class="form-control" id="brg-quantity-edit">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3 mb-2">
                <button type="button" class="btn btn-success w-25" id="add-taskBarang-btn">Add</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/task/handler.js"></script>