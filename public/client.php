<?php $type = isset($_GET['type']) ? $_GET['type'] : 'current'?>
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
            <a href="/index.php">Le<span class="l-green">R</span>esto</a>
        </div>
        <nav>
            <div class="nav-container hidden">
                <div class="nav-cat">Admin</div>
                <div class="nav-item <?php echo ($type == 'manageBookings') ? "active-nav" : "" ?>"><a href="client.php?type=manageBookings">Gérer les réservations</a></div>
                <div class="nav-item <?php echo ($type == 'manageHomePage') ? "active-nav" : "" ?>"><a href="client.php?type=manageHomePage">Gérer les annonces</a></div>
                <div class="nav-item <?php echo ($type == 'manageFoods') ? "active-nav" : "" ?>"><a href="client.php?type=manageFoods">Gérer les plats</a></div>
                <div class="nav-item <?php echo ($type == 'manageSchedule') ? "active-nav" : "" ?>"><a href="client.php?type=manageSchedule">Règles d'ouvertures</a></div>
            </div>
            <div class="nav-container">
                <div class="nav-cat">Réservations</div>
                <div class="nav-item <?php echo ($type == 'current') ? "active-nav" : "" ?>"><a href="client.php?type=current">Mes réservations</a></div>
                <div class="nav-item <?php echo ($type == 'history') ? "active-nav" : "" ?>"><a href="client.php?type=history">Historique</a></div>
            </div>
            <div class="nav-container">
                <div class="nav-cat">Paramètres</div>
                <div class="nav-item <?php echo ($type == 'account') ? "active-nav" : "" ?> "  ><a href="client.php?type=account">Compte</a></div>
            </div>
            <div class="nav-container">
                <div class="nav-cat"></div>
                <div class="nav-item" id="logout">Déconnexion</div>
            </div>
        </nav>
        <div class="question-container">
            <i class="fa-solid fa-gear"></i>
            <h2>Questions ?</h2>
            <div>On est la pour vous renseigné, envoyer nous un message.</div>
            <a href="contact.php"><h3>Contacter nous <i class="fa-solid fa-arrow-right"></i> </h3></a>
        </div>
    </aside>
    <div class="page-container">
        <div class="header">
            <div class="header-left">
                <h2>Votre page personnel</h2>
                <div class="page-desc">Regarder vos réservations, gérer votre compte et vos donnée personnel</div>
            </div>
            <div class="header-right">
                <img src="./resources/images/pp1.jpg" alt="profil pic" class="profile-picture">
                <div class="user-info">
                    <span class="username" id="header-username"></span>
                    <span class="email" id="header-email"></span>
                </div>
            </div>
        </div>
        <div class="content">
<!--            Dynamique -->
            <?php if($type == 'current' | $type == 'history') {
                include_once "includes/client-page/reservation-list/reservation-list.php";
            } else if($type == 'account') {
                include_once "includes/client-page/settings-account/settings-account.php";
            } else if($type =='manageHomePage') {
                include_once "includes/admin-page/manage-site/manage-site.php";
            } else if($type =='manageSchedule') {
                include_once "includes/admin-page/openings-rules/openings-rules.php";
            } else if($type =='manageBookings') {
                include_once "includes/admin-page/manage-bookings/manage-bookings.php";
            } else if ($type == 'manageFoods' ) {
                include_once "includes/admin-page/manage-dishes/manage-dishes.php";
            } else echo "Not developed or Not found";
            ?>
        </div>
    </div>
</div>

</body>
<script src="https://kit.fontawesome.com/c4155bf45a.js" crossorigin="anonymous"></script>
<script type="module" src="js/client.js"></script>

</html>