<?php include_once "includes/popup/popup.php"?>
<link rel="stylesheet" href="includes/admin-page/openings-rules/openings-rules.css">
<div>
    <h2>Gérer les horaires d'ouverture de l'établissement</h2>
    <div class="calendar-container">
        <div class="calendar-times">
            <div class="spacer"></div>
            <!-- Dynamic -->
        </div>
        <div class="calendar-body">
            <div class="calendar-item">
                <div class="calendar-item-title">Lundi</div>
                <div class="calendar-item-content dayoff" id="cal-item-1" weekid="1">
                    <div class="cal-event off-day-event">
                        <p>Fermer</p>
                    </div>
                </div>
            </div>
            <div class="calendar-item" >
                <div class="calendar-item-title">Mardi</div>
                <div class="calendar-item-content dayoff" id="cal-item-2" weekid="2">
                    <div class="cal-event off-day-event">
                        <p>Fermer</p>
                    </div>
                </div>
            </div>
            <div class="calendar-item" >
                <div class="calendar-item-title">Mercredi</div>
                <div class="calendar-item-content dayoff" id="cal-item-3" weekid="3">
                    <div class="cal-event off-day-event">
                        <p>Fermer</p>
                    </div>
                </div>
            </div>
            <div class="calendar-item">
                <div class="calendar-item-title">Jeudi</div>
                <div class="calendar-item-content dayoff" id="cal-item-4" weekid="4">
                    <div class="cal-event off-day-event">
                        <p>Fermer</p>
                    </div>
                </div>
            </div>
            <div class="calendar-item" >
                <div class="calendar-item-title">Vendredi</div>
                <div class="calendar-item-content dayoff" id="cal-item-5" weekid="5">
                    <div class="cal-event off-day-event">
                        <p>Fermer</p>
                    </div>
                </div>
            </div>
            <div class="calendar-item" >
                <div class="calendar-item-title">Samedi</div>
                <div class="calendar-item-content dayoff" id="cal-item-6" weekid="6">
                    <div class="cal-event off-day-event">
                        <p>Fermer</p>
                    </div>
                </div>
            </div>
            <div class="calendar-item" >
                <div class="calendar-item-title">Dimanche</div>
                <div class="calendar-item-content dayoff" id="cal-item-7" weekid="7">
                    <div class="cal-event off-day-event">
                        <p>Fermer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once "includes/admin-page/openings-rules/calendar.php"?>
</div>

<div class="modal-container">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Jour</h3>
        </div>
        <select name="time-start" id="time-start">
            <option value='' disabled selected>Heure de départ</option>
        </select>
        <select name="time-end" id="time-end" class="hidden">
            <option value='' disabled selected>Heure de fin</option>
        </select>
        <div class="nb-places">
            <i class="fa-solid fa-utensils"></i>
            <input type="number" min="1"  id="nb-places" placeholder="Limite de couverts">
        </div>
        <button id="modal-confirm">Valider</button>
        <button id="modal-delete" class="hidden">Supprimer</button>
    </div>
</div>


<script type="module" src="includes/admin-page/openings-rules/openings-rules.js"></script>