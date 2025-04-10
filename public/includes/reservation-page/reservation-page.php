<main>
    <div class="container">
        <h1>Réservation</h1>
        <form id="reservation-form">
            <div id="name-error" class="error-message"></div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email">
            <div id="email-error" class="error-message"></div>
            <label for="date">Date</label>
            <input type="date" name="date" id="date">
            <div id="date-error" class="error-message"></div>
            <label for="time">Heure</label>
            <select id="time">
                <option value="18:00">18:00</option>
                <option value="18:30">18:30</option>
                <option value="19:00">19:00</option>
                <option value="19:30">19:30</option>
                <option value="20:00">20:00</option>
                <option value="20:30">20:30</option>
                <option value="21:00">21:00</option>
                <option value="21:30">21:30</option>
            </select>
            <div id="time-error" class="error-message"></div>
            <label for="guests">Nombre de personnes</label>
            <input type="number" id="guests" name="guests">
            <div id="guests-error" class="error-message"></div>
            <button type="submit">Réserver</button>
            <div id="reservation-status" class="error-message"></div>
        </form>
        <div id="container-time"></div>
    </div>
</main>
<script type="module" src="includes/reservation-page/reservation-page.js"></script>
