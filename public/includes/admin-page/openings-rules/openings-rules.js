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
        calItem.appendChild(event);
    })
}

function displayModal(wid,data,oid){
    const modal = document.querySelector('.modal-container');
    const modalTitle = document.querySelector('.modal-title');
    const timeStart = document.querySelector('#time-start');
    const timeEnd = document.querySelector('#time-end');
    const confirm = document.querySelector('#modal-confirm');
    const nbPlaces = document.querySelector('#nb-places');
    const deleteBtn = document.querySelector('#modal-delete');
    const dataOid = data.find(obj => obj.id === parseInt(oid));

    // Affichage de la modal
    modal.style.display = 'flex';
    modalTitle.innerHTML = weekDays[wid-1];

    // Creation des horaires
    displayTimesSelect(timeStart,nbTimes);
    timeStart.addEventListener('change', ()=>{
        timeEnd.classList.remove('hidden');
        const value = timeStart.value;
        displayTimesSelect(timeEnd,value);
    })

    // Sortit par le clic
    window.onclick = (e) => {
        if(e.target === modal){
            modal.style.display = 'none';
            timeEnd.classList.add('hidden');
            deleteBtn.classList.add('hidden');
            nbPlaces.value = "";
        }
    }

    //Si de type update init
    if(oid){
        deleteBtn.classList.remove('hidden');

        displayTimesSelect(timeStart,nbTimes);
        timeStart.value = convertToFloatTime(dataOid.time_start);

        displayTimesSelect(timeEnd,timeStart.value);
        timeEnd.classList.remove('hidden');
        timeEnd.value = convertToFloatTime(dataOid.time_end);
        nbPlaces.value = dataOid.number_places;
    }

    //Delete rules
    deleteBtn.addEventListener('click', async (e) => {
        e.stopPropagation();
        e.preventDefault();
        if(window.confirm("Supprimer la règle ?")){
            const json = await fetchDeleteOpening(oid);
            if(json){
                modal.style.display = 'none';
                timeEnd.classList.add('hidden');
                deleteBtn.classList.add('hidden');
                nbPlaces.value = "";
            }
        }
    })

    // Confirm
    confirm.addEventListener('click', async (e) => {
        e.stopPropagation();
        e.preventDefault();
        let json;
        if(oid){
            json = await fetchPutOpening(
                oid,
                convertTimeValue(timeStart.value),
                convertTimeValue(timeEnd.value),
                nbPlaces.value
            );
        } else {
            json = await fetchPostOpening(
                wid,
                convertTimeValue(timeStart.value),
                convertTimeValue(timeEnd.value),
                nbPlaces.value
            );
        }
        if(json){
            modal.style.display = 'none';
            timeEnd.classList.add('hidden');
            nbPlaces.value = "";
        }
    });
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
            showBanner('error',data.message)
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
    displayEvent(data.result);
    const calendarContents = document.querySelectorAll(".calendar-item-content");
    //Modal t1
    calendarContents.forEach((item) => {
        item.addEventListener("click", () => {
            displayModal(item.getAttribute("weekid"),data.result);
        });
    })
    //Modal t2
    const events = document.querySelectorAll(".cal-event");
    events.forEach((item) => {
        item.addEventListener("click", (e) => {
            e.stopPropagation();
            displayModal(
                item.parentElement.getAttribute("weekid"),
                data.result,
                item.getAttribute("oid"),
                );
        })
    })


});

