import {showBanner} from "../../popup/popup";
import {convertTimeValue, convertToFloatTime, displayTimesSelect} from "../../../js/utils";
const weekDays = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
const nbTimes = 8
function displayCalendarTimes(){
    const times = document.querySelector(".calendar-times");
    for (let i = nbTimes; i <= 24; i++) {
        if(i%2 === 0){
            times.innerHTML += `<span class="times-item">${i}:00</span>`;
        } else {
            times.innerHTML += `<span class="times-item"></span>`;
        }
    }
}

function displayEvent(data){
    const days = document.querySelectorAll('.calendar-item-content');
    days.forEach(day => {
        day.classList.add("dayoff");
        day.innerHTML = `
        <div class="cal-event off-day-event">
            <p>Fermer</p>
        </div>
        `
    });

    data.forEach((item) => {
        const calItem = document.querySelector(`#cal-item-${item.id_days}`);
        if(calItem.classList.contains('dayoff')){
            calItem.classList.remove('dayoff');
            calItem.innerHTML = ``;
        }
        let event = document.createElement("div");
        event.classList.add("cal-event");
        event.setAttribute('oid',item.id);
        const timeStart = parseInt(item.time_start.split(":")[0]) + 1;
        const timeEnd = parseInt(item.time_end.split(":")[0]) + 1;
        event.style.gridRowStart = `${timeStart - nbTimes}`;
        event.style.gridRowEnd= `${timeEnd - nbTimes}`;
        event.innerHTML = `<p class="nb-places">${item.number_places}</p>`
        event.addEventListener("click",(e) =>{
            e.stopPropagation();
            displayModal(
                item.id_days,
                data,
                item.id
            );
        })
        calItem.appendChild(event);
    });
}

let confirmHandler = null;
let deleteHandler = null;
let timeStartHandler = null;
function displayModal(wid, data, oid) {
    const modal = document.querySelector('.modal-container');
    const modalTitle = document.querySelector('.modal-title');
    const timeStart = document.querySelector('#time-start');
    const timeEnd = document.querySelector('#time-end');
    const confirm = document.querySelector('#modal-confirm');
    const nbPlaces = document.querySelector('#nb-places');
    const deleteBtn = document.querySelector('#modal-delete');
    const dataOid = data.find(obj => obj.id === parseInt(oid));

    modal.style.display = 'flex';
    modalTitle.innerHTML = weekDays[wid - 1];

    nbPlaces.value = "";
    deleteBtn.classList.add('hidden');
    timeEnd.classList.add('hidden');


    // Supprimer les anciens listeners s'ils existent
    if (confirmHandler) confirm.removeEventListener('click', confirmHandler);
    if (deleteHandler) deleteBtn.removeEventListener('click', deleteHandler);
    if (timeStartHandler) timeStart.removeEventListener('change', timeStartHandler);

    // Recréation des horaires
    displayTimesSelect(timeStart, nbTimes);

    if (oid) {
        deleteBtn.classList.remove('hidden');
        timeStart.value = convertToFloatTime(dataOid.time_start);
        displayTimesSelect(timeEnd, timeStart.value);
        timeEnd.classList.remove('hidden');
        timeEnd.value = convertToFloatTime(dataOid.time_end);
        nbPlaces.value = dataOid.number_places;
    }

    // Créer des nouveaux handlers spécifiques
    timeStartHandler = () => {
        timeEnd.classList.remove('hidden');
        const value = timeStart.value;
        displayTimesSelect(timeEnd, value);
    };

    deleteHandler = async (e) => {
        e.stopPropagation();
        e.preventDefault();
        if (window.confirm("Supprimer la règle ?")) {
            const json = await fetchDeleteOpening(oid);
            if (json.success) {
                modal.style.display = 'none';
                timeEnd.classList.add('hidden');
                deleteBtn.classList.add('hidden');
                nbPlaces.value = "";
                data = data.filter(item => item.id !== parseInt(oid));
                displayEvent(data);
            }
        }
    };

    confirmHandler = async (e) => {
        e.stopPropagation();
        e.preventDefault();
        let json;
        const wid = parseInt(modalTitle.innerHTML && weekDays.indexOf(modalTitle.innerHTML) + 1);
        const time_s = convertTimeValue(timeStart.value);
        const time_e = convertTimeValue(timeEnd.value);
        const nb = nbPlaces.value;

        if (oid) {
            json = await fetchPutOpening(oid, time_s, time_e, nb);
            if(json.success) {
                modal.style.display = 'none';
                timeEnd.classList.add('hidden');
                nbPlaces.value = "";

                dataOid.time_start = time_s;
                dataOid.time_end = time_e;
                dataOid.number_places = parseInt(nb);
                displayEvent(data);
            }
        } else {
            json = await fetchPostOpening(wid, time_s, time_e, nb);
            if(json.success) {
                modal.style.display = 'none';
                timeEnd.classList.add('hidden');
                nbPlaces.value = "";

                const newRules = {
                    id: parseInt(json.data.id),
                    id_days: wid,
                    name: weekDays[wid],
                    number_places: parseInt(nb),
                    open: true,
                    time_end: time_e,
                    time_start: time_s,
                };
                data.push(newRules);
                displayEvent(data);
            }
        }

    };

    // Ajouter les nouveaux event listeners
    timeStart.addEventListener('change', timeStartHandler);
    deleteBtn.addEventListener('click', deleteHandler);
    confirm.addEventListener('click', confirmHandler);

}

async function fetchOpeningBasic(){
    try {
        const response = await fetch(`/api/opening/basic`, {
            methode: "GET",
            headers : {'Content-Type': 'application/json'},
        });
        const dataJson = await response.json();
        if(response.ok){
            return dataJson;
        } else {
            showBanner('error',dataJson.message)
        }
    } catch (e){
        showBanner('error',"Erreur lors de la récupération des données");
    }
}

async function fetchPostOpening(id_day,time_s,time_e,nb_places){
    const jwt = localStorage.getItem("jwt");
    try {
        const response = await fetch(`/api/opening/basic`, {
            method: 'POST',
            headers : {'Content-Type': 'application/json',"Authorization": `Bearer ${jwt}`},
            body: JSON.stringify({
                "id_day" : id_day,
                "time_start" : time_s,
                "time_end" : time_e,
                "nb_places" : nb_places,
            })
        });
        const dataJson = await response.json();
        if(response.ok){
            showBanner('success',dataJson.message)
            return dataJson;
        } else {
            showBanner('error',dataJson.message);
        }
    } catch (e) {
        showBanner('error',e);
    }

}

async function fetchPutOpening(id_time,time_s,time_e,nb_places){
    const jwt = localStorage.getItem("jwt");
    try {
        const response = await fetch(`/api/opening/basic`, {
            method: 'PUT',
            headers : {'Content-Type': 'application/json',"Authorization": `Bearer ${jwt}`},
            body: JSON.stringify({
                "id_time" : id_time,
                "time_start" : time_s,
                "time_end" : time_e,
                "nb_places" : nb_places,
            })
        });
        const dataJson = await response.json();
        if(response.ok){
            showBanner('success',dataJson.message)
            return dataJson;
        } else {
            showBanner('error',dataJson.message);
        }
    } catch (e) {
        showBanner('error',e);
    }

}

async function fetchDeleteOpening(id_time){
    const jwt = localStorage.getItem("jwt");
    try {
        const response = await fetch(`/api/opening/basic?id_time=${id_time}`, {
            method: 'DELETE',
            headers : {'Content-Type': 'application/json', "Authorization": `Bearer ${jwt}`},
        })
        const dataJson = await response.json();
        if(response.ok){
            showBanner('success',dataJson.message)
            return dataJson;
        } else {
            showBanner('error',dataJson.message);
        }
    } catch (e) {
        showBanner('error',e);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    displayCalendarTimes();
    const data = await fetchOpeningBasic();
    displayEvent(data.data);
    const calendarContents = document.querySelectorAll(".calendar-item-content");
    //Modal t1
    await calendarContents.forEach((item) => {
        item.addEventListener("click", () => {
            displayModal(item.getAttribute("weekid"), data.data);
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === document.querySelector('.modal-container')) {
            document.querySelector('.modal-container').style.display = 'none';
            document.querySelector('#time-end').classList.add('hidden');
            document.querySelector('#modal-delete').classList.add('hidden');
            document.querySelector('#nb-places').value = "";
        }
    });
});

