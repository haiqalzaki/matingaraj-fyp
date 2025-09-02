<!-- NAVBAR (Muncul di Mobile Sahaja) -->
<nav class="navbar navbar-expand-md navbar-light bg-light d-md-none">
    <div class="container-fluid">
        <img src="<?= $_ENV['HOME_URL'] ?>/image/logo.png" alt="" class="img-fluid rounded-circle" width="100">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mt-4" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item text-center color">
                    <a class="nav-link" href="<?= $_ENV['HOME_URL'] ?>/">Dashboard</a>
                </li>
                <li class="nav-item text-center bgcolor-primary">
                    <a class="nav-link" href="<?= $_ENV['HOME_URL'] ?>/task">Task</a>
                </li>
                <li class="nav-item text-center">
                    <a class="nav-link" href="<?= $_ENV['HOME_URL'] ?>/customer">Customer</a>
                </li>
                <?php if ($_SESSION['isAdmin'] === true) : ?>
                    <li class="nav-item text-center ">
                        <a class="nav-link" href="<?= $_ENV['HOME_URL'] ?>/statistic">Statistic</a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="<?= $_ENV['HOME_URL'] ?>/barang">Inventory</a>
                    </li>
                    <li class="nav-item text-center ">
                        <a class="nav-link" href="<?= $_ENV['HOME_URL'] ?>/user">User</a>
                    </li>
                <?php endif ; ?>
		        <li class="nav-item text-center ">
                    <a class="nav-link" href="<?= $_ENV['HOME_URL'] ?>/logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- SIDEBAR (Muncul di Tablet dan keatas) -->
<div class="sidebar shrink row d-none d-md-block shadow" id="sidebar">
    <div class="col">
        <div class="row">
            <div class="sidebar-content text-center mb-3 d-none">
                <img src="<?= $_ENV['HOME_URL'] ?>/image/logo.png" alt="" class="img-fluid rounded-circle" width="150">
            </div>
        </div>
        <div class="row">
            <button class="toggle-btn btn btn-light mb-3" id="toggleSidebar">
                <i class="bi bi-arrow-right-circle" id="toggleIcon" style="color: #007bff;"></i>
            </button>
        </div>
        <div class="row d-flex flex-column justify-content-around">
            <ul class="nav d-flex flex-column mt-5">
                <li class="nav-item rounded-4">
                    <a href="<?= $_ENV['HOME_URL'] ?>/" class="nav-link text-secondary">
                        <i class="bi bi-house-door fs-5"></i> 
                        <span class="ms-3 fs-6 fw-bold">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item rounded-4">
                    <a href="<?= $_ENV['HOME_URL'] ?>/task" class="sidebarhover nav-link text-secondary">
                        <i class="bi bi-list-task fs-5"></i> 
                        <span class="ms-3 fs-6 fw-bold">Task</span>
                    </a>
                </li>
                <li class="nav-item rounded-4">
                    <a href="<?= $_ENV['HOME_URL'] ?>/customer" class="nav-link text-secondary">
                        <i class="bi bi-people fs-5"></i> 
                        <span class="ms-3 fs-6 fw-bold">Customer</span>
                    </a>
                </li>
                <?php if ($_SESSION['isAdmin'] === true) : ?>
                    <li class="nav-item rounded-4">
                        <a href="<?= $_ENV['HOME_URL'] ?>/statistic" class="nav-link text-secondary">
                            <i class="bi bi-bar-chart fs-5"></i> 
                            <span class="ms-3 fs-6 fw-bold">Statistic</span>
                        </a>
                    </li>
                    <li class="nav-item rounded-4">
                        <a href="<?= $_ENV['HOME_URL'] ?>/barang" class="nav-link text-secondary">
                            <i class="bi bi-cart2 fs-5"></i> 
                            <span class="ms-3 fs-6 fw-bold">Inventory</span>
                        </a>
                    </li>
                    <li class="nav-item rounded-4">
                        <a href="<?= $_ENV['HOME_URL'] ?>/user" class="nav-link text-secondary">
                            <i class="bi bi-people fs-5"></i> 
                            <span class="ms-3 fs-6 fw-bold">User</span>
                        </a>
                    </li>
                <?php endif ; ?>
                <li class="nav-item rounded-4 mt-5">
                    <a href="<?= $_ENV['HOME_URL'] ?>/logout" class="nav-link text-secondary">
                        <i class="bi bi-box-arrow-left fs-5"></i>
                        <span class="ms-3 fs-6 fw-bold">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>


