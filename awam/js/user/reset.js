

document.addEventListener("DOMContentLoaded", async () => {

    document.getElementById("reset-pswd-btn").addEventListener('click', async (e) => {
        e.preventDefault();

        const passwordVerify = document.getElementById('user-password-new').value;
        const passwordConfirm = document.getElementById('user-password-confirm-new').value;
        const secretKey = document.getElementById('user-recovery-key');

        if (passwordVerify.length < 10) {
            showAlert('Password must be greater than 10 characters! Try again.');
            document.getElementById('user-password-new').value = '';
            document.getElementById('user-password-confirm-new').value = '';
            return;
        }

        if (!/[!@#$%^&*(),.?":{}|<>]/.test(passwordVerify)) {
            showAlert('Password must contain at least one special character!');
            document.getElementById('user-password-new').value = '';
            document.getElementById('user-password-confirm-new').value = '';
            return;
        }

        if (/\s/.test(passwordVerify)) {
            showAlert('Password cannot contain spaces!');
            document.getElementById('user-password-new').value = '';
            document.getElementById('user-password-confirm-new').value = '';
            return;
        }

        if (passwordVerify !== passwordConfirm) {
            showAlert('Password didn\'t match! Try again.');
            document.getElementById('user-password-confirm-new').value = '';
            return;
        }

        const url = `${BASE_PATH}/user/resetPasswordConfirm`;
        const userForm = document.getElementById('reset-password-form');
        const formPasswordUpdate = new FormData(userForm);

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formPasswordUpdate,
            })

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const json = await response.json();

            if (!json.status) {
                showAlert(json.message);

                secretKey.value = '';
            } else {
                showAlert(json.message);
                window.location.href = `${BASE_PATH}/user`;
            }
        } catch (error) {
            console.error(error.message);
        }
    })
})

