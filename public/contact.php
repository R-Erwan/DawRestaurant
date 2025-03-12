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

            <div class="contact-schedules">
                <h2>Nos horaires</h2>
                <ul>
                    <li><span class="contact-day">Lundi</span> 12h - 14h et 19h - 22h</li>
                    <li><span class="contact-day">Mardi</span> 12h - 14h et 19h - 22h</li>
                    <li><span class="contact-day">Mercredi</span> Le jours des enfants </li>
                    <li><span class="contact-day">Jeudi</span> 12h - 14h et 19h - 22h</li>
                    <li><span class="contact-day">Vendredi</span> 12h - 14h et 17h - 23h</li>
                    <li><span class="contact-day">Samedi</span> 12h - 14h et 17h - 23h</li>
                    <li><span class="contact-day">Dimanche</span> 7h - 14h et 19h - 22h</li>
                </ul>
            </div>


            <div class="contact-infos">
                <h2>Informations de contact </h2>
                <span>9 Av. Alain Savary, <br> 21000 Dijon</span>
                <span>Téléphone : <span class="telNumber">03 80 39 50 04</span> </span>
                <div class="social-media">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="horizontal-bar-XL"></div>
        </aside>

        <div class="contact-form">
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

    <div class="contact-maps">
        <h1>Nous localisez</h1>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5410.130151987485!2d5.0661039477340255!3d47.3130672!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47f29e699d8a11ed%3A0xa0e60f1d3359296c!2sUFR%20-%20Sciences%20et%20techniques!5e0!3m2!1sfr!2sfr!4v1741786650885!5m2!1sfr!2sfr" width="1200" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <?php include_once "includes/footer/footer.php"?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>
</html>

