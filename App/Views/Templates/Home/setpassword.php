<!-- Set Password Form -->

<div class="container d-flex flex-column align-items-center justify-content-center">
    <div class="mx-auto text-center p-4 bg-white shadow border rounded-2" style="max-width: 400px; width: 100%;">

        <p class="fw-bold fs-5">Setting Up New Password For: </p>

        <p class="text-success pb-2"><?= isset($_SESSION['password-change']['email']) ? $_SESSION['password-change']['email'] : ''; ?></p>

        <form id="setpassword-form">
            <div class="mb-3 text-start">
                <label for="user-password" class="form-label">Enter new password: </label>
                <input type="password" class="form-control" name="userPassword" id="user-password">
            </div>
            <div class="mb-3 text-start">
                <label for="user-confirm-password" class="form-label">Confirm new password: </label>
                <input type="password" class="form-control" id="user-confirm-password">
            </div>
            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-primary w-100">Set Password</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/home/setpassword.js"></script>