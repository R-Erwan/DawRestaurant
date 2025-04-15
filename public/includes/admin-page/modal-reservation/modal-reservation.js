import {formatDate} from "../../../js/utils";

export function displayModal(data, btn) {
    const modal = document.querySelector(".modal-content");
    const modalBackdrop = document.querySelector(".modal-backdrop");

    btn.addEventListener("click", (e) => {
        const rect = btn.getBoundingClientRect();
        modal.style.left = `${rect.left}px`;
        modal.style.top = `${rect.top + window.scrollY}px`;
        modal.style.transformOrigin = "top left";

        // Réinitialiser pour permettre une nouvelle animation
        modal.classList.remove("show");
        modal.style.transition = "none";
        modal.style.transform = "translate(-40%, -80%) scale(0)";

        // Forcer un reflow pour "relancer" l'animation (astuce JS)
        void modal.offsetWidth;

        // Appliquer la transition et l'animation
        modal.style.transition = "all 0.4s ease";
        modalBackdrop.style.display = "block";

        requestAnimationFrame(() => {
            modal.classList.add("show");
            modal.style.transform = "translate(-40%, -80%) scale(1)";
        });
        setModalAttr(data.name,"m-name");
        setModalAttr(data.first_name,"m-firstName");
        setModalAttr(data.email,"m-mail");
        setModalAttr(data.phone_number,"m-phone");
        setModalAttr(formatDate(data.created_at),"m-created");
        const statePicker = document.querySelector("#modal-state");
        statePicker.value = data.status;
        statePicker.addEventListener("change", (e) => {
            setDatePickerColor(statePicker.value,statePicker);
        })
        setDatePickerColor(data.status,statePicker);


    });

    window.onclick = (e) => {
        if (e.target === modalBackdrop) {
            modal.classList.remove("show");
            setTimeout(() => {
                modalBackdrop.style.display = 'none';
            }, 400);
        }
    };
}

function setModalAttr(data,id){
    document.getElementById(id).innerText = data;
}



function setDatePickerColor(status,statePicker){
    switch (status) {
        case "confirmed":
            statePicker.style.backgroundColor = "#87B971";
            break;
        case "waiting":
            statePicker.style.backgroundColor = "#FAB36A";
            break;
        case "cancelled":
            statePicker.style.backgroundColor = "#881112";
            break;
    }
}