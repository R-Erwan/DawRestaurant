export function headerMenuHandler() {
    const menuButton = document.querySelector(".fa-bars");
    const navLinks = document.querySelector(".responsive-nav-links");
    const navItems = document.querySelectorAll(".responsive-nav-links li");

    menuButton.addEventListener("click", function () {
        navLinks.classList.toggle("visibleLink");
    });

    navItems.forEach(item => {
        item.addEventListener("click", function () {
            navLinks.classList.toggle("visibleLink");
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem('jwt');
    if (token) {
        document.getElementById("login-links").classList.toggle("hidden");
        document.getElementById("client-links").classList.toggle("hidden");
    }
    headerMenuHandler();
});
