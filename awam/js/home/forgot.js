const forgotForm = document.getElementById('forgot-form');

forgotForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const formForgot = new FormData(forgotForm);
    const emailInput = document.getElementById('user-email');
    const keyInput = document.getElementById('user-key');

    fetch(`${BASE_PATH}/home/verifyKey`, {
        method: 'POST',
        body: formForgot
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

            emailInput.value = "";
            keyInput.value = "";
        } else {
            alert(data.message);
            window.location.href = `${BASE_PATH}/home/passwordSetting`;
        }         
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error: Invalid request.");
    })
})
