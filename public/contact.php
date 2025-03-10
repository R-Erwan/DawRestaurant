<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DawRestaurant</title>
    <link rel="stylesheet" href="./css/contact.css">
</head>
<body>
    <?php include_once "includes/header/header.php"?>
    <div class="contact-title">
        <img src="resources/images/contact_img2.jpg" alt="">
        <h1>Contact & accès</h1>
    </div>
    <div class="horizontal-bar"></div>

    <div class="contact-container">
        <aside class="contact-sidebar">
            <div class="contact-infos">
                <span>9 Av. Alain Savary, <br> 21000 Dijon</span>
                <span>Téléphone : <span class="telNumber">03 80 39 50 04</span> </span>
                <div class="social-media">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d1912.7774139342628!2d5.071819747920001!3d47.31285846274087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sufr%20sciences%20et%20techniques!5e0!3m2!1sfr!2sfr!4v1741609164024!5m2!1sfr!2sfr" width="300" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </aside>
        <div class="contact-form">
            <div class="form-title">
                <h1>Envoyez-nous un message !</h1>
            </div>
            <form class="contact-form-content" action="">
                <div class="input-contact-form">
                    <label for="input-firstName">Prénom</label>
                    <input name="firstName" type="text" id="input-firstName">
                </div>
                <div class="input-contact-form">
                    <label for="input-lastName">Nom</label>
                    <input name="lastName" type="text" id="input-lastName">
                </div>
                <div class="input-contact-form">
                    <label for="input-email">Adresse mail</label>
                    <input name="email" type="email" id="input-email">
                </div>
                <div class="input-contact-form">
                    <label for="input-phone">Téléphone</label>
                    <input name="phone" type="tel" id="input-phone">
                </div>
                <div class="input-contact-form">
                    <label for="input-message">Message</label>
                    <textarea name="message" id="input-message" cols="30" rows="8"></textarea>
                </div>
                <div class="input-contact-form">
                    <span>
                        <input type="checkbox" name="agree" id="input-agree">
                        <label for="input-agree">
                            En utilisant ce formulaire, vous acceptez le stockage et le traitement de vos données
                        </label>
                    </span>
                </div>
                <input type="submit" value="Envoyer">
            </form>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>
</html>