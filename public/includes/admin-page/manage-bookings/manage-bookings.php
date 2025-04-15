<?php include_once "includes/popup/popup.php"?>
<link rel="stylesheet" href="includes/admin-page/manage-bookings/manage-bookings.css">
<div class="tabs-menu">
    <button class="tabs-button tabs-selected" id="tabs-today">Aujourd'hui</button>
    <button class="tabs-button" id="tabs-tomorrow">Demain</button>
    <button class="tabs-button" id="tabs-all">Tout</button>
    <input type="date" id="tabs-date" class="tabs-button">
    <select id="tabs-filter">
        <option value="none" selected disabled >Filtre</option>
        <option value="dateDesc">Date ▼</option>
        <option value="dateAsc">Date ▲</option>
        <option value="timeDesc">Heure ▼</option>
        <option value="timeAsc">Heure ▲</option>
        <option value="guestDesc">Couverts ▼</option>
        <option value="guestAsc">Couverts ▲</option>
        <option value="stateAccepted">Confirmer</option>
        <option value="stateWaiting">En attente</option>
        <option value="stateCancelled">Annulé</option>
    </select>
</div>
<div class="selector-bars"></div>
<div class="tabs-content">
    <ul class="table" id="table-content">
        <li class="table-header">
            <div class="col c1">Date</div>
            <div class="col c2">Heure</div>
            <div class="col c3">Nombres de couverts</div>
            <div class="col c4">Status</div>
            <div class="col c5"></div>
        </li>
    </ul>
</div>

<script type="module" src="includes/admin-page/manage-bookings/manage-bookings.js"></script>