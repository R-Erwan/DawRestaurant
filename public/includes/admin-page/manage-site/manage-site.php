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

        <div class="annouces-form-container" id="create-form">
            <h2>Ajouter une annonce</h2>
            <form action="" id="announce-form">
                <label for="type">
                    <select name="type" id="type" required>
                        <option value='' disabled selected>Select option</option>
                        <option value="1">Titre + Description</option>
                        <option value="2">Image</option>
                    </select>
                </label>
                <input type="text" name="title" id="title" maxlength="40" minlength="3" placeholder="Titre de l'annonce"/>
                <textarea name="desc" id="desc" cols="30" rows="10" maxlength="700" minlength="100" placeholder="Description de l'annonce"></textarea>
                <label for="images" class="drop-container" id="dropcontainer" >
                    <span class="drop-title">Déposer l'image ici</span>
                    ou
                    <input type="file" id="images" accept="image/*">
                </label>
                <input class="validate-button" type="submit" id="submit-form" value="Créer l'annonce">
            </form>
        </div>

        <div class="annouces-form-container hidden" id="update-form">
            <div>
                <i id="back-update" class="fa-solid fa-arrow-left"></i>
                <button id="del-announce"><i class="fa-solid fa-triangle-exclamation"></i> Supprimer</button>
            </div>
            <h2 class="update-form-title">Modifié ou supprimé l'annonce<span id="update-id-position"></span></h2>

            <form action="" id="update-announce-form">
                <input type="text" name="title" id="update-title" maxlength="40" minlength="3" placeholder="Titre de l'annonce"/>
                <textarea name="desc" id="update-desc" cols="30" rows="10" maxlength="700" minlength="100" placeholder="Description de l'annonce"></textarea>
                <label for="images" class="drop-container" id="update-dropcontainer" >
                    <span class="drop-title">Déposer l'image ici</span>
                    ou
                    <input type="file" id="update-images" accept="image/*">
                </label>
                <input class="validate-button" type="submit" id="submit-update-form" value="Modifier l'annonce">
            </form>

        </div>
    </div>
</div>


<script type="module" src="includes/admin-page/manage-site/manage-site.js"></script>
