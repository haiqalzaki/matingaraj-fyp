document.getElementById("toggleSidebar").addEventListener("click", function () {
    let sidebar = document.getElementById("sidebar");
    let mainContent = document.querySelector(".content");
    let sidebarContent = document.querySelectorAll(".sidebar-content");
    let toggleIcon = document.getElementById("toggleIcon");
    let logoutIcon = document.getElementById("logout-icon-style");

    sidebar.classList.toggle("shrink");
    mainContent.classList.toggle("full-width");

    sidebarContent.forEach(el => {
        el.classList.toggle("d-none");
    });
    
    if (sidebar.classList.contains("shrink")) {
        toggleIcon.classList.remove("bi-arrow-right-circle");
        toggleIcon.classList.add("bi-arrow-left-circle");
    } else {
        toggleIcon.classList.remove("bi-arrow-left-circle");
        toggleIcon.classList.add("bi-arrow-right-circle");
    }
});