<!---detailcustomer-->
<div class="container d-flex flex-column justify-content-center mt-3">
    <div class="row py-2 w-100">
        <div class="col-sm-12 col-md-12 col-lg-4 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="pb-2 border-bottom">Customer Details</h3>
                    <div class="mb-3 text-start">
                        <label for="customer" class="form-label">Customer Name:</label>
                        <input type="text" class="form-control" name="customer" id="customer"
                            value="<?= $data['customer']['nama'] ?>" disabled>

                    </div>
                    <div class="mb-3 text-start">
                        <label for="No-tel" class="form-label">Telephone:</label>
                        <input type="text" class="form-control" name="No-tel" id="No-tel"
                            value="<?= $data['customer']['tel'] ?>" disabled>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="Platform" class="form-label">Platform:</label>
                        <input type="" class="form-control" name="Platform" id="Platform"
                            value="<?= $data['customer']['platform'] ?>" disabled>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="Jumlah Task" class="form-label">Total Task: </label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="jumlah_task" id="jumlah_task"
                                value="<?= $data['customer']['jumlah-task'] ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row py-2 w-100 mt-2">
        <div class="col-sm-12 col-md-12 col-lg-10 mx-auto pb-3">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between mb-1">
                        <div class="p-2 mb-1">
                            <h4 class="fw-bold border-bottom">Related Task</h4>
                        </div>
                        <div class="py-2 mb-1">
                            <input class="form-control w-100" id="search-control-task" type="search"
                                placeholder="Search">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table-task">

                        </table>
                    </div>
                </div>
                <input type="hidden" id="cx-id" value="<?= $data['customerid'] ?>">
            </div>
        </div>
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/customer/detailcustomer.js"></script>