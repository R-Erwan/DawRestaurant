import {checkToken, fetchUserData} from "./utils";
document.addEventListener("DOMContentLoaded", async function () {
    checkToken();
    const token = localStorage.getItem("jwt");
    if (!token) {
        window.location.href = "login.php";
    }

    document.getElementById("logout").addEventListener("click", function () {
        localStorage.removeItem('jwt');
        window.location.href = "login.php";
    });

    const data = await fetchUserData();
    if (data) {
        document.getElementById("header-username").innerText = data.user.name;
        document.getElementById("header-email").innerText = data.user.email;
    }
});
