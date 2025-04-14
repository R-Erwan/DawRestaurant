import {showBanner} from "../../popup/popup";
import {capitalizeFirstLetter} from "../../../js/utils";
let currentTabFilter = "today";
let currentDateFilter = "";
let currentFilterFilter = "none";
document.addEventListener('DOMContentLoaded', async () => {
    const data = await fetchAllReservations();
    console.log(data);
    displayReservations(filterData(data.reservations,"today"));

    // Today tab
    tabsFilterEvent(document.querySelector("#tabs-today"),"today",data);
    tabsFilterEvent(document.querySelector("#tabs-tomorrow"),"tomorrow",data);
    tabsFilterEvent(document.querySelector("#tabs-all"),"all",data);

    const selectFilter = document.querySelector("#tabs-filter");
    selectFilter.addEventListener("change", (e) => {
        const filter = filterData(data.reservations,currentTabFilter,selectFilter.value,currentDateFilter);
        displayReservations(filter);
        currentFilterFilter = selectFilter.value;
    })

    const datePicker = document.querySelector("#tabs-date");
    datePicker.addEventListener("change", (e) => {
        setActiveTab(e.target);
        const filter = filterData(data.reservations,"date","none",datePicker.value);
        displayReservations(filter);
        currentDateFilter = datePicker.value;
        currentTabFilter = "date";
    })

});

function tabsFilterEvent(tab,filterType,data) {
    tab.addEventListener("click", (e) => {
        document.querySelector("#tabs-date").value="";
        setActiveTab(e.target);
        const filter = filterData(data.reservations,filterType,currentFilterFilter,currentDateFilter);
        displayReservations(filter);
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

function displayReservations(data,couv = "",maxcouv = "") {
    const tableContent = document.querySelector("#table-content");
    tableContent.innerHTML = `
        <li class="table-header">
            <div class="col c1">Date</div>
            <div class="col c2">Heure</div>
            <div class="col c3">Nombres de couverts ${couv} / ${maxcouv} </div>
            <div class="col c4">Status</div>
            <div class="col c5"></div>
        </li>
    `;

    // Generate rows from data
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

        tableContent.appendChild(row);
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
    return filteredData;
}

function setActiveTab(activeTab) {
    document.querySelectorAll('.tabs-button').forEach(tab => {
        tab.classList.toggle('tabs-selected',false);
    });
    activeTab.classList.toggle('tabs-selected',true);
}