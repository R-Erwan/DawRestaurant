export function headerMenuHandler() {
    const menuButton = document.querySelector(".fa-bars");
    const navLinks = document.querySelector(".responsive-nav-links");
    const navItems = document.querySelectorAll(".responsive-nav-links li");

    menuButton.addEventListener("click", function() {
        navLinks.classList.toggle("visible");
    });

    navItems.forEach(item => {
        item.addEventListener("click", function() {
            navLinks.classList.toggle("visible");
        });
    });
}

document.addEventListener("DOMContentLoaded", function() {
    headerMenuHandler();
});
