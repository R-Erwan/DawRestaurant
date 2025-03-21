<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Le Resto</title>
    <link rel="stylesheet" href="./css/client.css">
    <link rel="icon" type="image/png" href=" ./resources/images/favicon.png">
</head>
<body>
<div class="container">
    <aside class="menu-container">
        <div class="logo">
            <a href="/DawRestaurant/public/index.php">Le<span class="l-green">R</span>esto</a>
        </div>
        <nav>
            <div class="nav-item active-nav">Vue d'ensemble</div>
            <div class="nav-cat">Réservations</div>
            <div class="nav-item">Mes réservations</div>
            <div class="nav-item">Historique</div>
            <div class="nav-cat">Paramètres</div>
            <div class="nav-item">Compte</div>
            <div class="nav-item">Confidentialité</div>
            <div class="nav-cat"></div>
            <div class="nav-item">Déconnexion</div>
        </nav>
    </aside>
    <div class="reservation-container">
        <div class="header">
            <h2>

            </h2>
        </div>
        <div class="tabs-menu">
            <button class="tabs-button tabs-selected" id="tabs-next">Prochaines réservations</button>
            <button class="tabs-button" id="tabs-old">Anciennes réservations </button>
        </div>
        <div class="tabs-content">
            <?php include_once "includes/client-page/reservation-list/reservation-list.php" ?>
        </div>
    </div>
</div>

</body>
</html>