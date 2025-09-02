<!-- Login Form -->

<div class="container d-flex flex-column align-items-center justify-content-center">
    <div class="mx-auto text-center p-4 bg-white shadow border rounded-2" style="max-width: 400px; width: 100%;">
        <img src='<?= $_ENV['HOME_URL'] ?>/image/logo.png' class="image-fluid" alt="martin-garaj-logo">
        
        <p class="fs-5 pb-2">SESSION LOCKOUT</p>
      
        <form id="login-form">
            <div class="d-flex justify-content-center mt-2">
                <p>You have reached maximum login attempt! Please try again in 15 minutes.</p>
            </div>
        </form>
    </div>
</div>