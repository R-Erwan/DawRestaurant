<?php include_once "includes/popup/popup.php"?>
<link rel="stylesheet" href="includes/admin-page/manage-dishes/manage-dishes.css">

<div class="tabs-menu">
    <button class="tabs-button tabs-selected" id="tabs-starters">Entrée</button>
    <button class="tabs-button" id="tabs-mainFood">Plats</button>
    <button class="tabs-button" id="tabs-desserts">Desserts</button>
    <button class="tabs-button" id="tabs-drinks">Boissons</button>
</div>
<div class="selector-bars"></div>
<div class="sub-categories">
    <span>Sous-catégorie : </span>
    <select class="sub-cat-select" id="sub-cat">
        <option value="all" selected>Tous</option>
    </select>
    <button class="add-sub-cat-btn" id="add-sub-cat">+</button>
    <input class="sub-cat-name-input hidden" id="sub-cat-name" type="text" value="" placeholder="Nouvelle sous-catégorie">
    <button class="add-sub-cat-btn hidden" id="valid-sub-cat">→</button>
</div>
<div class="tabs-content">
    <div class="dish-item-cat"><h2>Sous-catégorie</h2></div>
    <div class="dish-item" itemId="1">
        <input type="text" value="Nom du plat">
        <textarea name="" id="" cols="30" rows="2">Description du plat</textarea>
        <input type="number" value="5" min="0">
        <input type="checkbox">
        <button class="update-btn" itemId="1" >Modifier</button>
    </div>
</div>
<script type="module" src="includes/admin-page/manage-dishes/manage-dishes.js"></script>