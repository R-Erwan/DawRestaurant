<?php include_once "includes/popup/popup.php"?>
<link rel="stylesheet" href="includes/login-form/login-form.css">
<div class="login-main-container">
    <div class="login-container" id="login-container">
        <div class="form-container sign-up-container">
            <form action="#" id="register-form">
                <h1>Créer un compte</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>ou utiliser votre email</span>
                <input type="text" placeholder="Nom" name="name" id="register-name" required/>
                <input type="email" placeholder="Email" name="email" id="register-email" required/>
                <input type="password" placeholder="Mot de passe" name="password" id="register-password" required/>
                <button>Créer le compte</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="#" id="login-form">
                <h1>Se connecter</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>ou utiliser votre compte</span>
                <input type="email" placeholder="Email" name="email" id="login-email" required/>
                <input type="password" placeholder="Mot de passe" name="password" id="login-password" required/>
                <a href="#">Mot de passe oublié ?</a>
                <button>Se connecter</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Bon retour !</h1>
                    <p>Pour rester connecté, rentrer vos informations personnelles</p>
                    <button class="ghost" id="signIn">Se connecter</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Bonjour !</h1>
                    <p>Entrer vos informations de connexions</p>
                    <button class="ghost" id="signUp">Créer un compte</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/c4155bf45a.js" crossorigin="anonymous"></script>
<script type="module" src="includes/login-form/login-ui.js"></script>
