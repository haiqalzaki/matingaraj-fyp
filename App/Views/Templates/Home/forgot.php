<!-- Forgot Password Form -->

<div class="container d-flex flex-column align-items-center justify-content-center">
    <div class="mx-auto text-center p-4 bg-white shadow border rounded-2" style="max-width: 400px; width: 100%;">

        <div class="d-flex justify-content-center">
            <p class="fw-bold fs-4 pb-2">Forgot password?</p>
        </div>

        <form id="forgot-form">
            <div class="mb-3 text-start">
                <label for="user-email" class="form-label">Enter a valid email: </label>
                <input type="text" class="form-control" name="userEmail" id="user-email">
            </div>
            
            <div class="mb-3 text-start">
                <label for="user-key" class="form-label">Recovery key: </label>
                <input type="password" class="form-control" name="userKey" id="user-key">
            </div>

            <div class="d-flex justify-content-center mt-2">
                <button type="submit" class="btn btn-primary w-100">Verify</button>
            </div>

            <?php if (isset($_SESSION['password-change'])) : ?>
                <div class="d-flex justify-content-between">
                    <p class="text-end mt-4">
                        <a class="fs-6 link-underline-light" href="<?= $_ENV['HOME_URL'] ?>/home/passwordSetting">Continue changing password</a>
                    </p>
                    <p class="text-end mt-4">
                        <a class="fs-6 link-underline-light" href="<?= $_ENV['HOME_URL'] ?>">Back to Login</a>
                    </p>
                </div>
            <?php else : ?>
                <div class="d-flex justify-content-center">
                    <p class="text-end mt-4">
                        <a class="fs-6 link-underline-light" href="<?= $_ENV['HOME_URL'] ?>">Back to Login</a>
                    </p>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/home/forgot.js"></script>