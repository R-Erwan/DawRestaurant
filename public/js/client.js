window.onload = function () {
    const token = localStorage.getItem("jwt");
    if (!token) {
        window.location.href = "login.php";
    }
}
