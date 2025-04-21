import {showBanner} from "../../popup/popup";
import {capitalizeFirstLetter, formatDate} from "../../../js/utils";
let currentTabFilter = "today";
let currentDateFilter = "";
let currentFilterFilter = "none";
document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetchAllReservations();
    console.log(response);
    const data = response.data;
    const filter = filterData(data,"today")
    displayReservations(filter.filteredData,filter.count);

    // Today tab
    tabsFilterEvent(document.querySelector("#tabs-today"),"today",data);
    tabsFilterEvent(document.querySelector("#tabs-tomorrow"),"tomorrow",data);
    tabsFilterEvent(document.querySelector("#tabs-all"),"all",data);

    const selectFilter = document.querySelector("#tabs-filter");
    selectFilter.addEventListener("change", (e) => {
        const filter = filterData(data,currentTabFilter,selectFilter.value,currentDateFilter);
        displayReservations(filter.filteredData,filter.count);
        currentFilterFilter = selectFilter.value;
    })

    const datePicker = document.querySelector("#tabs-date");
    datePicker.addEventListener("change", (e) => {
        setActiveTab(e.target);
        const filter = filterData(data,"date","none",datePicker.value);
        displayReservations(filter.filteredData,filter.count);
        currentDateFilter = datePicker.value;
        currentTabFilter = "date";
    })

});

function tabsFilterEvent(tab,filterType,data) {
    tab.addEventListener("click", (e) => {
        document.querySelector("#tabs-date").value="";
        setActiveTab(e.target);
        const filter = filterData(data,filterType,currentFilterFilter,currentDateFilter);
        displayReservations(filter.filteredData,filter.count);
        currentTabFilter = filterType;
    })
}

async function fetchAllReservations() {
    const jwt = localStorage.getItem('jwt');
    try {
        const response = await fetch(`/api/reservation`, {
            headers: {
                "Authorization": `Bearer ${jwt}`,
                'Content-Type': 'application/json',
            },
        });
        const dataJson = await response.json();
        if (response.ok) {
            return dataJson;
        } else {
            showBanner('error',"Failed to fetch reservations, try reloading the page");
            return [];
        }
    } catch (error) {
        showBanner('error',"Failed to fetch reservations, try reloading the page" + error);
        return [];
    }
}

function displayReservations(data, couv = "") {
    const tableContent = document.querySelector("#table-content");
    tableContent.innerHTML = `
        <li class="table-header">
            <div class="col c1">Date</div>
            <div class="col c2">Heure</div>
            <div class="col c3">Nombres de couverts <span class="nb-couv">${couv}</span></div>
            <div class="col c4">Status</div>
            <div class="col c5"></div>
        </li>
    `;

    data.forEach(item => {
        const row = document.createElement('li');
        row.classList.add('table-row');
        row.innerHTML = `
            <div class="col c1">${item.reservation_date}</div>
            <div class="col c2">${item.reservation_time}</div>
            <div class="col c3">${item.number_of_people}</div>
            <div class="col c4 state-${item.status}">${capitalizeFirstLetter(item.status)}</div>
            <div class="col c5">Details</div>
        `;

        const detailsRow = document.createElement('div')
        detailsRow.classList.add('details-row', 'hidden');
        detailsRow.innerHTML = `
            <div class="details-header details-container">
                <span class="details-elmt" id="d-name">${capitalizeFirstLetter(item.name)}</span>
                <span class="details-elmt" id="d-fname">${capitalizeFirstLetter(item.first_name ?? "")}</span>
            </div>
            <div class="details-body details-container">
                <span class="details-elmt" id="d-mail"> <i class="fa-solid fa-envelope"></i>${item.email}</span>
                <span class="details-elmt" id="d-phone"> <i class="fa-solid fa-phone"></i>${item.phone_number ?? ""}</span>
                <span class="details-elmt" id="d-created">Date de demande de réservation : ${formatDate(item.created_at)}</span>
            </div>
             <select id="details-state" class="details-container">
                <option value="confirmed">Confirmed</option>
                <option value="waiting">Waiting</option>
                <option value="cancelled">Cancelled</option>
            </select> 
        `

        tableContent.appendChild(row);
        tableContent.appendChild(detailsRow);

        const detailsBtn = row.querySelector('.c5');
        detailsBtn.addEventListener('click', (e) => {
            detailsRow.classList.toggle('hidden');
        });
        const detailsState = detailsRow.querySelector("#details-state");
        detailsState.value = item.status;
        detailsState.addEventListener("change", async (e) => {
            setDatePickerColor(detailsState.value,detailsState);
            const ok = await fetchUpdateReservationState(item.id,detailsState.value);
            if(ok) {
                const rowState = row.querySelector('.c4');
                rowState.className = `col c4 state-${detailsState.value}`
                rowState.innerText=capitalizeFirstLetter(detailsState.value);
                item.status=detailsState.value;
            }
        })

        setDatePickerColor(item.status,detailsState);
    });
}

function filterData(data, type, filter = "none", date= "") {
    let filteredData;
    const currentDate = new Date();

    // Fonction utilitaire pour formater une date en YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    switch (type) {
        case "today":
            filteredData = data.filter(item => {
                return item.reservation_date === formatDate(currentDate);
            });
            break;
        case "tomorrow":
            const tomorrow = new Date(currentDate);
            tomorrow.setDate(currentDate.getDate() + 1);
            filteredData = data.filter(item => {
                return item.reservation_date === formatDate(tomorrow);
            });
            break;
        case "all":
            filteredData = data.filter(item => {
                return item.reservation_date > formatDate(currentDate);
            })
            break;
        case "date":
            filteredData = data.filter(item => {
                return item.reservation_date === date;
            })
            break;
        default:
            filteredData = [...data];
    }

    switch (filter) {
        case "dateDesc":
            filteredData = filteredData.sort((a,b) => b.reservation_date.localeCompare(a.reservation_date));
            break;
        case "dateAsc" :
            filteredData = filteredData.sort((a,b) => a.reservation_date.localeCompare(b.reservation_date));
            break;
        case "timeDesc":
            filteredData = filteredData.sort((a,b) => b.reservation_time.localeCompare(a.reservation_time));
            break;
        case "timeAsc":
            filteredData = filteredData.sort((a,b) => a.reservation_time.localeCompare(b.reservation_time));
            break;
        case "guestDesc":
            filteredData = filteredData.sort((a,b) => b.number_of_people - a.number_of_people);
            break;
        case "guestAsc":
            filteredData = filteredData.sort((a,b) => a.number_of_people - b.number_of_people);
            break;
        case "stateAccepted":
            filteredData = filteredData.filter(item => {
                return item.status === "confirmed";
            });
            break;
        case "stateWaiting":
            filteredData = filteredData.filter(item => {
                return item.status === "waiting";
            });
            break;
        case "stateCancelled":
            filteredData = filteredData.filter(item => {
                return item.status === "cancelled";
            });
            break;
    }
    const count = filteredData.reduce((acc, item) => acc + (item.number_of_people || 0), 0);

    return {filteredData, count};
}

function setActiveTab(activeTab) {
    document.querySelectorAll('.tabs-button').forEach(tab => {
        tab.classList.toggle('tabs-selected',false);
    });
    activeTab.classList.toggle('tabs-selected',true);
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

async function fetchUpdateReservationState(id,state){
    const jwt = localStorage.getItem('jwt');
    try {
        const response = await fetch(`/api/reservation?id_reservation=${id}`, {
            method: 'PUT',
            headers: {
                "Authorization": `Bearer ${jwt}`,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({status: state}),
        });
        const dataJson = await response.json();
        if (response.ok) {
            return true;
        } else {
            showBanner('error',"Failed to update state : " + dataJson.message);
            return false;
        }
    } catch (error) {
        showBanner('error',"Failed to update state");
        return false
    }
}

