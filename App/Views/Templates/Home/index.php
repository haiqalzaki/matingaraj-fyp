<!-- Login Form -->

<div class="container d-flex flex-column align-items-center justify-content-center">
    <div class="mx-auto text-center p-4 bg-white shadow border rounded-2" style="max-width: 400px; width: 100%;">
        <img src='<?= $_ENV['HOME_URL'] ?>/image/logo.png' class="image-fluid" alt="martin-garaj-logo">

        <p class="fs-5 pb-2">LOGIN PANEL</p>

        <form id="login-form">
            <div class="mb-3 text-start">
                <label for="user-email-log" class="form-label">Username/Email: </label>
                <input type="text" class="form-control" name="userNameEmail" id="user-email-log">
            </div>
            <div class="mb-3 text-start">
                <label for="user-password-log" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="user-password-log">
            </div>
            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </div>
        </form>
    </div>
</div>

<script src="js/home/home.js"></script>