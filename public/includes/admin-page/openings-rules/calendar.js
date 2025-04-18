import {showBanner} from "../../popup/popup";
import {convertTimeValue, displayTimesSelect, isOverlapping} from "../../../js/utils";
let fieldsCount = 0;
document.addEventListener('DOMContentLoaded', async () => {
    /* Calendar */
    const data = await fetchCalexc();
    const events = convertExcToEvent(data.data);
    const calEl = document.querySelector(".calendarExc")
    const calendar = new FullCalendar.Calendar(calEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        dateClick : async function (info) {
            await displayExcForm(info.dateStr,info.date)
       },
        eventSources: [
            {
                events: events.open,
                color: '#86B971',
                textColor:'#ffffff'
            },
            {
                events: events.closed,
                color: '#fab369',
                textColor:'#ffffff'
            }
        ]
    })
    calendar.render();

    const addBtn = document.querySelector('.add-rule');
    addBtn.addEventListener('click', () => {
        document.querySelector(".sub-forms").classList.toggle('hidden',false);
        addSubForm(fieldsCount);
        fieldsCount++;
    })

    const openSwitch = document.querySelector('#calexc-open');
    openSwitch.addEventListener('change', () => {
        if(openSwitch.checked) {
            addBtn.classList.remove('hidden');
        } else {
            addBtn.classList.add('hidden');
            const subForms = document.querySelector(".sub-forms");
            subForms.classList.toggle('hidden',true);


        }
    })

    const cfBtn = document.querySelector('#calexc-confirm');
    cfBtn.addEventListener('click', async () => {
        const data = computeFormData();

        if(checkValidity(data)){
            await fetchPostException(data);
            setTimeout(()=>{
                location.reload();

            },1000);
        }

    })

    const delBtn = document.querySelector('#calexc-delete');
    delBtn.addEventListener('click', async () => {
        const id = document.querySelector(".calendarExc-form").getAttribute("id_exc");
        await fetchDeleteException(id);
        setTimeout(()=>{
            location.reload();

        },1000);
    })
})

async function fetchCalexc() {
    try {
        const response = await fetch(`/api/opening/exception`, {
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

async function fetchExcDate(date){
    try {
        const response = await fetch(`/api/opening/exception?date=${date}`, {
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

async function fetchOpeningBasicDate(date){
    try {
        const response = await fetch(`/api/opening/basic?date=${date}`, {
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
        showBanner('error',"Erreur lors de la récupération des données 2");
    }
}

function convertExcToEvent(data) {
    const events = {
        open: [],
        closed: []
    };

    if(data.length === 0){
        return [];
    }

    data.forEach(item => {
        const event = {
            title: item.comment.split(" ")[0],
            start: item.date
        };

        if (item.open) {
            events.open.push(event);
        } else {
            events.closed.push(event);
        }
    });

    return events;
}

function formExcRender({open = false, comment = false, rules = [] } = {}){
    // Switch btn
    document.querySelector("#calexc-open").checked = open;

    // TextArea
    const commentE = document.querySelector("#calexc-comment");
    if(comment === false){
        // commentE.classList.add("hidden");
    } else {
        commentE.classList.remove("hidden");
        commentE.innerText = comment;

    }

    // SubForm

    const subForms = document.querySelector(".sub-forms");
    if(!open){
        subForms.classList.add("hidden");
        document.querySelector(".add-rule").classList.add("hidden");
    }
    if(rules === null){

    } else {
        subForms.classList.remove("hidden");
        subForms.innerHTML = "";
        rules.forEach((item, i) => {
            const subForm = document.createElement("div");
            subForm.className = "sub-form";

            // Start Select
            const startSelect = document.createElement("select");
            startSelect.name = "time-start";
            startSelect.id = `calExc-time-start-${i}`;
            startSelect.className = "time-select toggled-input";
            displayTimesSelect(startSelect, 8); // ou item.start si tu veux limiter

            // End Select
            const endSelect = document.createElement("select");
            endSelect.name = "time-end";
            endSelect.id = `calExc-time-end-${i}`;
            endSelect.className = "time-select toggled-input";
            displayTimesSelect(endSelect, item.start);

            // Set selected values
            startSelect.value = item.start;
            endSelect.value = item.end;

            // Input number of places
            const nbDiv = document.createElement("div");
            nbDiv.className = `calExc-nb-places-${i}`;
            const nbInput = document.createElement("input");
            nbInput.type = "number";
            nbInput.min = "1";
            nbInput.id = "calExc-nb-places";
            nbInput.placeholder = "Limite de couverts";
            nbInput.value = item.nb;
            nbInput.className = "toggled-input";
            nbDiv.appendChild(nbInput);

            // Append all to subForm
            subForm.appendChild(startSelect);
            subForm.appendChild(endSelect);
            subForm.appendChild(nbDiv);

            // Append subForm to container
            subForms.appendChild(subForm);
        });

    }

}

async function displayExcForm(dateStr) {
    fieldsCount = 0;
    document.querySelector(".calendarExc-form").classList.toggle("hidden",false);
    const cfBtn = document.querySelector("#calexc-confirm");
    const dlBtn = document.querySelector("#calexc-delete");
    const addBtn = document.querySelector(".add-rule");
    document.querySelector(".calendarExc-form").setAttribute("date",dateStr);

    const data = await fetchExcDate(dateStr);
    const result = data.data;
    const title = document.querySelector("#calexc-date-title");
    let openState;
    const hasExepRul = result.length !== 0;
    if (!hasExepRul) {
        const openData = await fetchOpeningBasicDate(dateStr);
        openState = openData.data.length !== 0;
    } else {
        openState = !!result[0].open;
    }


    const dateTitleRaw = new Date(dateStr).toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long'});
    title.innerText = dateTitleRaw.charAt(0).toUpperCase() + dateTitleRaw.slice(1);

    document.querySelector(".sub-forms").innerHTML = ``

    if(hasExepRul){
        cfBtn.classList.add("hidden");
        dlBtn.classList.remove("hidden");
        addBtn.classList.add("hidden");
        document.querySelector(".calendarExc-form").setAttribute("id_exc",result[0].id_exc);
        if(openState) {
            const rules = result.map(item => ({
                start: parseInt(item.time_start.slice(0, 2)),
                end: parseInt(item.time_end.slice(0, 2)),
                nb: item.number_of_places
            }));

            formExcRender({
                open: openState,
                comment: result[0].comment,
                rules: rules
            });
        } else {
            formExcRender({
                open: openState,
                comment: result[0].comment,
                rules: null
            });
        }
        toggleAllInput(true);
    } else {
        cfBtn.classList.remove("hidden");
        dlBtn.classList.add("hidden");
        addBtn.classList.remove("hidden");
        if(openState) {
            formExcRender({open: openState,comment: false, rules : null});
        } else {
            formExcRender({open: openState,comment: false, rules : null});
        }
        toggleAllInput(false);
    }
}

function addSubForm(i){
    const subForms = document.querySelector(".sub-forms");
    const subForm = document.createElement("div");
    subForm.className = "sub-form";

    // Start Select
    const startSelect = document.createElement("select");
    startSelect.name = "time-start";
    startSelect.id = `calExc-time-start-${i}`;
    startSelect.className = "time-select toggled-input";
    displayTimesSelect(startSelect, 8); // ou item.start si tu veux limiter

    // End Select
    const endSelect = document.createElement("select");
    endSelect.name = "time-end";
    endSelect.id = `calExc-time-end-${i}`;
    endSelect.className = "time-select toggled-input hidden";

    //Start select change event
    startSelect.addEventListener("change", (e) => {
        endSelect.classList.remove("hidden");
        displayTimesSelect(endSelect, startSelect.value);
    })

    // Input number of places
    const nbDiv = document.createElement("div");
    const nbInput = document.createElement("input");
    nbInput.type = "number";
    nbInput.min = "1";
    nbInput.id = `calExc-nb-places-${i}`;
    nbInput.placeholder = "Limite de couverts";
    nbInput.className = "toggled-input";
    nbDiv.appendChild(nbInput);

    // Append all to subForm
    subForm.appendChild(startSelect);
    subForm.appendChild(endSelect);
    subForm.appendChild(nbDiv);

    // Append subForm to container
    subForms.appendChild(subForm);
}

function toggleAllInput(toggle){
    document.querySelectorAll(".toggled-input").forEach(item => {
        item.disabled = toggle;
    })
}

/* SUBMIT FORM */
function computeFormData(){
    const date = document.querySelector(".calendarExc-form").getAttribute("date");
    const open = document.querySelector("#calexc-open").checked;
    const comment = document.querySelector("#calexc-comment").value;
    let data;
    if(open){
        const subForms = document.querySelectorAll(".sub-form");
        let subFormsData = [];
        subForms.forEach((item,index) => {
            subFormsData.push({
                time_start: convertTimeValue(document.getElementById("calExc-time-start-"+index).value),
                time_end: convertTimeValue(document.getElementById("calExc-time-end-"+index).value),
                nb_places: parseInt(document.getElementById("calExc-nb-places-"+index).value),
            });
        })
        data = {
            date: date,
            open: open,
            comment: comment,
            times : subFormsData,
        }
    } else {
        data = {
            date: date,
            open: open,
            comment: comment,
            times : null
        }
    }
    return data;
}

async function fetchPostException(data){
    const jwt = localStorage.getItem("jwt");
    try {
        const response = await fetch(`/api/opening/exception`, {
            method: 'POST',
            headers : {'Content-Type': 'application/json',"Authorization": `Bearer ${jwt}`},
            body: JSON.stringify(data)
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

async function fetchDeleteException(id){
    const jwt = localStorage.getItem("jwt");
    try {
        const response = await fetch(`/api/opening/exception?id_exc=${id}`, {
            method: 'DELETE',
            headers : {'Content-Type': 'application/json',"Authorization": `Bearer ${jwt}`}
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

function checkValidity(data){
    const dateR = new Date(data.date);
    if(dateR < Date.now()){
        showBanner('info',"Impossible de modifié une date antérieur");
        return false;
    }

    if(data.times !== null && data.times !== undefined && data.times.length > 0){
        if(isOverlapping(data.times)){
            showBanner('info',"Les créneaux horaires se chevauchent");
            return false;
        }
    }

    if(data.open){
        if(data.times.length === 0){
            showBanner('info',"Il est nécessaire de renseigné au moin un créneau");
            return false;
        }

        if (data.times.some(item =>
            item.time_start === "NaN:00" ||
            item.time_end === "NaN:00" ||
            isNaN(item.nb_places)
        )) {
            showBanner('info',"Tous les champs des créneaux doivent être renseigné");
            return false;
        }

    }

    return true;
}