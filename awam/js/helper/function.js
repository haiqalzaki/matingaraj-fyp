console.log('Hello world from public JS functions folder!');

async function showAlert(message) {
    alert(message);
}

function debounce(func, delay) {
    let timeout;
    
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

function reverseDateFormat(dateString) {
    let dateParts = dateString.split('-');

    return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
}