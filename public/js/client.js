import {checkToken, fetchUserData, parseJwt} from "./utils";
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
    const admin = parseJwt(token).roles.includes("admin")
    if (data.success) {
        document.getElementById("header-username").innerText = data.data.name;
        document.getElementById("header-email").innerText = data.data.email;
    }

    if(admin){
        document.querySelector(".hidden").classList.remove("hidden");
    }
});
