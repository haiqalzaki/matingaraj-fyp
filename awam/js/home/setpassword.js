const passwordForm = document.getElementById('setpassword-form');

passwordForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const formPassword = new FormData(passwordForm);
    const verifyPassword = document.getElementById('user-password');
    const confirmPassword = document.getElementById('user-confirm-password');

    if (verifyPassword.value.length < 10) {
        showAlert('Password must be greater than 10 characters! Try again.');
        verifyPassword.value = '';
        confirmPassword.value = '';
        return;
    }

    if (!/[!@#$%^&*(),.?":{}|<>]/.test(verifyPassword.value)) {
        showAlert('Password must contain at least one special character!');
        verifyPassword.value = '';
        confirmPassword.value = '';
        return;
    }

    if (/\s/.test(verifyPassword.value)) {
        showAlert('Password cannot contain spaces!');
        verifyPassword.value = '';
        confirmPassword.value = '';
        return;
    }

    if (verifyPassword.value !== confirmPassword.value) {
        showAlert('Password didn\'t match! Try again.');
        confirmPassword.value = '';
        return;
    }

    fetch(`${BASE_PATH}/home/setPassword`, {
        method: 'POST',
        body: formPassword
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed to verify! Refresh and try again.");
        }
        return response.json();
    })
    .then(data => {
        if (!data) {
            throw new Error("Error processing request. Try again.");
        } else if (data.status === false) {
            alert(data.message);

            verifyPassword.value = "";
            confirmPassword.value = "";
        } else {
            alert(data.message);
            window.location.href = `${BASE_PATH}`;
        }         
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error: Invalid request.");
    })
})
