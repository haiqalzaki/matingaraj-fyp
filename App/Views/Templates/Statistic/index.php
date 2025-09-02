<!--- table dection -->

<div class="container pt-2">
    <div class="card px-3 pb-4">
	<div class="mt-4">
            <h3 class="text-center mb-4 fw-bold">Statistic Summary</h3>
        </div>
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <div class="d-flex justify-content-center">
                                <div>
                                    <h4 class=" fw-bold border-bottom">Popular Items</h4>
                                </div>
                            </div>
                            <table class="table align-middle" id="table-barang">
                                <!-- Dynamic content -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <div class="d-flex justify-content-center">
                                <div>
                                    <h4 class=" fw-bold border-bottom">Most Serviced Motorcyle</h4>
                                </div>
                            </div>
                            <table class="table align-middle" id="table-motosikal">
                                <!-- Dynamic content -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                <div class="card bg-light ">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <div class="d-flex justify-content-center">
                                <div>
                                    <h4 class=" fw-bold border-bottom">Customer Origin</h4>
                                </div>
                            </div>
                            <table class="table align-middle" id="table-platform">
                                <!-- Dynamic content -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Charts Section -->
<div class="container text-center">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-3 py-3">
            <div class="card shadow">
                <div class="card-body">
                    <p class="text-primary fs-2 text-light py-1" style="background:rgb(105, 92, 254) ">Service</p>
                    <canvas id="chartbarangan"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 py-3">
            <div class="card shadow">
                <div class="card-body">
                    <p class="text-primary fs-2 text-light py-1" style="background:rgb(105, 92, 254) ">Status</p>
                    <canvas id="chartservis"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 py-3">
            <div class="card shadow">
                <div class="card-body">
                    <p class="text-primary fs-2 text-light py-1" style="background:rgb(105, 92, 254)">Payment</p>
                    <canvas id="chartpayment"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 py-3">
            <div class="card shadow">
                <div class="card-body">
                    <p class="text-primary fs-2  text-light py-1" style="background:rgb(105, 92, 254)">System User</p>
                    <canvas id="chartpalsu"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= $_ENV['HOME_URL'] ?>/js/statistic/statistic.js"></script>