const dark = document.getElementById('dark-mode')
const parentElement = document.getElementById('parent')

dark.addEventListener('click', (e) => {
if (parentElement.hasAttribute('data-bs-theme')) {
    parentElement.removeAttribute('data-bs-theme');
    } else {
    parentElement.setAttribute('data-bs-theme', 'dark');
    }
});