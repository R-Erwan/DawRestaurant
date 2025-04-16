const params = new URLSearchParams(window.location.search);
const token = params.get("token");
const error = document.getElementById("error-msg");
const psw1 = document.getElementById("psw1");
const psw2 = document.getElementById("psw2");
const send = document.getElementById("send");
psw2.addEventListener("input", (e) => {
    if(psw1.value !== psw2.value){
        error.innerText = "Les mots de passes ne correspondent pas !";
        send.classList.toggle("disabled",true);
    } else {
        error.innerText = "";
        send.classList.toggle("disabled",false);
    }
})

send.addEventListener("click", async (e) => {
    try {
        const response = await fetch("/api/auth/reset-password", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                "token": token,
                "password": psw1.value
            })
        });
        const json = await response.json();
        if(response.ok){

            window.location.replace(window.location.origin+"/login.php");
        } else {
            error.innerText = "Une erreur c'est produite, veuillez réessayer";
        }

    } catch (error){
        error.innerText = "Une erreur c'est produite, veuillez réessayer";
    }
})