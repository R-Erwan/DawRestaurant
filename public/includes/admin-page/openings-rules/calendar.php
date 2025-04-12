<link rel="stylesheet" href="includes/admin-page/openings-rules/calendar.css">
<h2 class="cal-exc-title">Gérer les évènements exceptionnels </h2>
<p class="cal-exc-explanation">
    Les évènements exceptionnels surcharges toutes les autres règles pour une date précise.
</p>

<div class="calendarExc-container">
    <div class="calendarExc">

    </div>
    <div class="calendarExc-form hidden">
        <div class="date-info">
            <h3 class="date-title" >
                <span id="calexc-date-title" ></span>
                <label class="switch ">
                    <input type="checkbox" id="calexc-open" name="calexc-open" class="toggled-input">
                    <span class="slider"></span>
                </label>
            </h3>
        </div>
        <div class="date-form">
            <textarea class="toggled-input" name="calexc-comment" id="calexc-comment" cols="50" rows="3" ">Commentaire</textarea>
            <div class="sub-forms">

            </div>
            <button class="add-rule">+</button>
            <button class="calexc-btn" id="calexc-confirm">Confirmer les règles</button>
            <button class="calexc-btn hidden" id="calexc-delete">Supprimer les règles</button>

        </div>
    </div>
</div>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/core/locales/fr.global.js'></script>
<script type="module" src="includes/admin-page/openings-rules/calendar.js"></script>