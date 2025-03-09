const signUpButton = document.getElementById("signUp");
const signInButton = document.getElementById("signIn");
const loginContainer = document.getElementById("login-container");

signUpButton.addEventListener('click', () => {
    loginContainer.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    loginContainer.classList.remove("right-panel-active");
});