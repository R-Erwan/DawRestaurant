document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem('jwt');
    if (token) {
        document.getElementById("footer-login-links").classList.toggle("hidden");
        document.getElementById("footer-client-links").classList.toggle("hidden");
    }
});