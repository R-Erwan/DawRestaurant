<?php include_once "includes/popup/popup.php"?>
<link rel="stylesheet" href="includes/admin-page/manage-site/manage-site.css">

<div>
    <div class="manager-title">
        <div>
            <h2>Agencer les annonces du site</h2>
            <p class="desc">Déplacer les éléments pour organiser l'ordre des annonces </p>
        </div>
        <button class="validate-button" id="validate-update">Valider</button>
    </div>

    <div class="manager-container">
        <div class="manager-annouces-container">
            <div class="drag-drop-container">
            </div>
        </div>
        <div class="annouces-form-container">
            <h2>Ajouter une annonce</h2>
            <form action="" id="announce-form">
                <label for="type">
                    <select name="type" id="type" required>
                        <option value='' disabled selected>Select option</option>
                        <option value="1">Titre + Description</option>
                        <option value="2">Image</option>
                    </select>
                </label>
                <input type="text" name="title" id="title" placeholder="Titre de l'annonce"/>
                <textarea name="desc" id="desc" cols="30" rows="10" placeholder="Dexcription de l'annonce"></textarea>
                <label for="images" class="drop-container" id="dropcontainer" >
                    <span class="drop-title">Déposer l'image ici</span>
                    ou
                    <input type="file" id="images" accept="image/*">
                </label>
                <input class="validate-button" type="submit" id="submit-form" value="Créer l'annonce">
            </form>

        </div>
    </div>
</div>


<script type="module" src="includes/admin-page/manage-site/manage-site.js"></script>
