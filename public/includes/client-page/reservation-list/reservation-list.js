import {formatDate, parseJwt} from "../../../js/utils";
import {showBanner} from "../../popup/popup";

async function fetchReservation() {
    const jwt = localStorage.getItem('jwt');
    if (!jwt) {
        console.error('Pas de JWT');
        return [];
    }
    const token = parseJwt(jwt);
    const user_id = token.user_id;
    try {
        const response = await fetch(`/api/reservation?action=User&id=${user_id}`, {
            headers: {
                Authorization: `Bearer ${jwt}`,
                'Content-Type': 'application/json',
            },
        });
        if (response.ok) {
            return await response.json();
        } else {
            console.error('Failed to fetch reservations:', response.status, response.statusText);
            return [];
        }
    } catch (error) {
        console.error('Error fetching reservations:', error);
        return [];
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


const tabsAll = document.querySelector('#tabs-all');
const tabsConfirmed = document.querySelector('#tabs-confirmed');
const tabsWaiting = document.querySelector('#tabs-waiting');
const tabsCanceled = document.querySelector('#tabs-cancelled');
const tableContent = document.querySelector('#table-content');


async function displayReservations(state = 'all', type = 'current') {
    // Clear existing rows
    tableContent.innerHTML = `
        <li class="table-header">
            <div class="col c1">Date</div>
            <div class="col c2">Heure</div>
            <div class="col c3">Nombres de couverts</div>
            <div class="col c4">Status</div>
            <div class="col c5"></div>
        </li>
    `;
    let data = await fetchReservation();
    data = data.reservation;
    const currentDate = new Date();

    let filteredData = state === 'all' ? data : data.filter(item => item.status === state);

    filteredData = filteredData.filter(item => {
        const reservationDate = new Date(item.reservation_date + ' ' + item.reservation_time);
        if (type === 'current') {
            return reservationDate > currentDate;
        } else if (type === 'history') {
            return reservationDate < currentDate;
        }
        return false;
    });

    filteredData.forEach(item => {
        const row = document.createElement('li');
        row.classList.add('table-row');
        row.innerHTML = `
            <div class="col c1">${item.reservation_date}</div>
            <div class="col c2">${item.reservation_time}</div>
            <div class="col c3">${item.number_of_people}</div>
            <div class="col c4 state-${item.status}">${capitalizeFirstLetter(item.status)}</div>
            <div class="col c5">Details</div>
        `;

        const detailsRow = document.createElement('div');
        detailsRow.classList.add('details-row', 'hidden');
        detailsRow.innerHTML = `
            <div class="details-header details-container">
                <span class="details-elmt" id="d-name">${capitalizeFirstLetter(item.name)}</span>
                <span class="details-elmt" id="d-fname">${capitalizeFirstLetter(item.first_name ?? "")}</span>
            </div>
            <div class="details-body details-container">
                <span class="details-elmt" id="d-people">Changement du nombre d'invités</span> <br>
                <input type="number" min="1" max="9" class="details-elmt" id="d-people-${item.id}" value="${item.number_of_people}">
            </div>
            <div class="details-body details-container">
                <button class="modify-btn" data-id="${item.id}">Modifier</button>
            </div>
            <div class="details-body details-container">
                <button class="cancel-btn" data-id="${item.id}">Annuler la commande</button>
            </div>
        `;

        tableContent.appendChild(row);
        tableContent.appendChild(detailsRow);
    console.log(item)
        const detailsBtn = row.querySelector('.c5');
        detailsBtn.addEventListener('click', (e) => {
            detailsRow.classList.toggle('hidden');
        });

        const modifyBtn = detailsRow.querySelector('.modify-btn');
        modifyBtn.addEventListener('click', async () => {
            const newNumberOfPeople = parseInt(detailsRow.querySelector(`#d-people-${item.id}`).value, 10);
            if (isNaN(newNumberOfPeople) || newNumberOfPeople < 1 || newNumberOfPeople > 9) {
                showBanner('error', 'Please enter a valid number of people (1-9)');
                return;
            }
            console.log('Updating number of people to:', newNumberOfPeople);
            const success = await fetchUpdateReservationPeople(item.id, newNumberOfPeople);
            if (success) {
                showBanner('success', 'Réservation mise à jour avec succès');
                displayReservations(state, type);
            }
        });

        const cancelBtn = detailsRow.querySelector('.cancel-btn');
        cancelBtn.addEventListener('click', async () => {
            const success = await fetchUpdateReservationState(item.id, 'cancelled');
            if (success) {
                showBanner('success', 'Reservation cancelled successfully');
                displayReservations(state, type);
            }
        });
    });
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Event listeners for the tabs
tabsAll.addEventListener('click', () => {
    setActiveTab(tabsAll);
    const type = getUrlParamType();
    displayReservations('all', type);
});

tabsConfirmed.addEventListener('click', () => {
    setActiveTab(tabsConfirmed);
    const type = getUrlParamType();
    displayReservations('confirmed', type);
});

tabsWaiting.addEventListener('click', () => {
    setActiveTab(tabsWaiting);
    const type = getUrlParamType();
    displayReservations('waiting', type);
});

tabsCanceled.addEventListener('click', () => {
    setActiveTab(tabsCanceled);
    const type = getUrlParamType();
    displayReservations('cancelled', type);
});

// Helper function to set active tab
function setActiveTab(activeTab) {
    document.querySelectorAll('.tabs-button').forEach(tab => {
        tab.classList.remove('tabs-selected');
    });
    activeTab.classList.add('tabs-selected');
}

// Function to retrieve the 'type' parameter from URL
function getUrlParamType() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('type') || 'current';
}

// Window onload function to handle URL parameter and filter accordingly
window.onload = () => {
    const type = getUrlParamType();
    displayReservations('all', type);
};


async function fetchUpdateReservationPeople(id, people) {
    const jwt = localStorage.getItem('jwt');
    if (!jwt) {
        showBanner('error', 'No JWT found');
        return false;
    }
    try {
        const response = await fetch(`/api/reservation?id_reservation=${id}`, {
            method: 'PUT',
            headers: {
                Authorization: `Bearer ${jwt}`,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ number_of_people: people }),
        });
        const dataJson = await response.json();
        console.log('API Response:', response.status, dataJson);
        if (response.ok) {
            return true;
        } else {
            showBanner('error', `Failed to update number of people: ${dataJson.message || 'Unknown error'}`);
            return false;
        }
    } catch (error) {
        console.error('Error updating reservation:', error);
        showBanner('error', 'Failed to update number of people');
        return false;
    }
}