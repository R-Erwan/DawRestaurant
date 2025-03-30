<main>
    <div class="container">
        <h1>Réservation</h1>
        <form id="reservation-form" action="#">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name">
            <div id="name-error" class="error-message"></div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email">
            <div id="email-error" class="error-message"></div>
            <label for="date">Date</label>
            <input type="date" name="date" id="date">
            <div id="date-error" class="error-message"></div>
            <label for="time">Heure</label>
            <select id="time" name="time">
                <option value="" disabled selected>Choisir une heure</option>
                <option value="19:00">19:00</option>
                <option value="20:00">20:00</option>
                <option value="21:00">21:00</option>
                <option value="22:00">22:00</option>
            </select>
            <div id="time-error" class="error-message"></div>
            <label for="guests">Nombre de personnes</label>
            <input type="number" id="guests" name="guests">
            <div id="guests-error" class="error-message"></div>
            <button type="submit">Réserver</button>
        </form>
    </div>
</main>
<script type="module" src="includes/reservation-page/reservation.js"></script>
