<link rel="stylesheet" href="includes/admin-page/openings-rules/calendar.css">
<h2 class="cal-exc-title">Gérer les évènements exceptionnels </h2>

<div class="calendarExc-container">
    <div class="calendarExc">

    </div>
    <div class="calendarExc-form">
        <div class="date-info">
            <h3 class="date-title">
                Vendredi 11
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </h3>
        </div>
        <div class="date-form">

            <textarea name="calexc-comment" id="calexc-open" cols="50" rows="7"></textarea>
            <div class="sub-form">
                <select name="time-start" id="calExc-time-start" class="">
                    <option value='' disabled selected>Heure de départ</option>
                </select>
                <select name="time-end" id="calExc-time-end" class="">
                    <option value='' disabled selected>Heure de fin</option>
                </select>
                <div class="nb-places">
                    <i class="fa-solid fa-utensils"></i>
                    <input type="number" min="1"  id="calExc-nb-places" placeholder="Limite de couverts">
                </div>
            </div>
            <button>Ajouter la règle</button>

        </div>
    </div>
</div>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/core/locales/fr.global.js'></script>
<script type="module" src="includes/admin-page/openings-rules/calendar.js"></script>