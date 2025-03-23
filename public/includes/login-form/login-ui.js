const signUpButton = document.getElementById("signUp");
const signInButton = document.getElementById("signIn");
const loginContainer = document.getElementById("login-container");

signUpButton.addEventListener('click', () => {
    loginContainer.classList.add("right-panel-active");
});
signInButton.addEventListener('click', () => {
    loginContainer.classList.remove("right-panel-active");
});

const loginForm = document.getElementById("login-form");
loginForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const email = document.getElementById("login-email").value;
    const password = document.getElementById("login-password").value;
    const response = await fetch("http://localhost:8000?action=login", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            email: email,
            password: password,
        })
    });
    const data = await response.json();
    if(response.ok && data.token) {
        localStorage.setItem('jwt', data.token);
        window.location.href ='client.php'
        //TODO popup validation
    } else {
        loginForm.reset();
        //TODO popup erreur
    }
});