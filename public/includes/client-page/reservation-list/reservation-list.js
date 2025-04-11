import {parseJwt} from "../../../js/utils";

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
    // Get the current date
    const currentDate = new Date();

    // Filter data based on state (confirmed, waiting, canceled, etc.)
    let filteredData = state === 'all' ? data : data.filter(item => item.status === state);

    // Filter based on the type (current or history)
    filteredData = filteredData.filter(item => {
        const reservationDate = new Date(item.reservation_date + ' ' + item.reservation_time);
        if (type === 'current') {
            return reservationDate > currentDate;
        } else if (type === 'history') {
            return reservationDate < currentDate;
        }
        return false;
    });

    // Generate rows for the filtered data
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

        tableContent.appendChild(row);
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