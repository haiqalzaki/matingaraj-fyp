const loginForm = document.getElementById('login-form');

loginForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const formLog = new FormData(loginForm);
    const usernameInput = document.getElementById('user-email-log');
    const passwordInput = document.getElementById('user-password-log');

    fetch(`${BASE_PATH}/home/login`, {
        method: 'POST',
        body: formLog
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed logging in. Refresh and try again.");
        }
        return response.json();
    })
    .then(data => {
        if (!data) {
            throw new Error("Error processing request. Try again.");
        } else if (data.status === false) {
            if (data.data && data.data.lockout === true) {
                window.location.href = `${BASE_PATH}`;
            }

            alert(data.message);
            passwordInput.value = "";
        } else {
            alert(data.message);
            window.location.href = `${BASE_PATH}/dashboard`;
        }         
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error: Invalid request.");
    })
})
