document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem("jwt");
    if (!token) {
        window.location.href = "login.php";
    }

    document.getElementById("logout").addEventListener("click", function () {
        localStorage.removeItem('jwt');
        window.location.href = "login.php";
    });
});
