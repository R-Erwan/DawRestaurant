
<div class="account-container">
    <div class="account-details">
        <h2>Informations de contact</h2>
        <form action="#" name="update-contact">
            <div class="box">
                <label for="firstName">Prénom</label>
                <input type="text" name="firstName" id="firstName" disabled>
                <label for="lastName">Nom *</label>
                <input type="text" name="lastName" id="lastName" required disabled>
            </div>
            <div class="box">
                <label for="email">email *</label>
                <input type="email" name="email" id="email" required disabled>
                <label for="phone">Numéro de téléphone</label>
                <input type="tel" name="phone" id="phone" disabled>
            </div>
        </form>
        <input type="submit" id="submit-update-contact" form="update-contact" hidden>
        <button class="toggle-update" id="toggle-update">Modifier</button>
    </div>
    <div class="account-password">
        <h2>Changer le mot de passe</h2>
        <form action="" name="update-password">
            <div class="form-content">
                <div>
                    <label for="old-password">Ancien mot de passe *</label>
                    <input type="password" name="old-password" id="old-password" required>
                </div>
                <div>
                    <label for="new-password">Nouveau mot de passe *</label>
                    <input type="password" name="new-password" id="new-password" required>
                </div>
                <div>
                    <label for="new-password">Confirmer le mot de passe *</label>
                    <input type="password" name="confirm-password" id="confirm-password" required>
                </div>
            </div>
            <input type="submit" value="Valider">
        </form>
    </div>
    <div class="account-stats">

    </div>
</div>

<script type="module" src="includes/client-page/settings-account/settings-account.js"></script>