<link rel="stylesheet" href= "includes/admin-page/modal-reservation/modal-reservation.css">

<div class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-elmt " id="m-name">Nom</span>
            <span class="modal-elmt" id="m-firstName">Prenom</span>
        </div>
        <div class="modal-body">

            <div class="modal-elmt"><i class="fa-solid fa-envelope"></i> <span  id="m-mail">Mail</span></div>
            <div class="modal-elmt"><i class="fa-solid fa-phone"></i> <span id="m-phone">Téléphone</span></div>

            <span class="modal-elmt" id="m-created">Fait le : </span>
            <select id="modal-state" class="modal-elmt" >
                <option value="confirmed">Confirmed</option>
                <option value="waiting">Waiting</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>
</div>