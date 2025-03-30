const data = [
    // Réservations passées
    {"id": 0, "date": "10/03/2025", "hour": "12:00", "nCouv": 2, "state": "confirmed"},
    {"id": 1, "date": "11/03/2025", "hour": "13:00", "nCouv": 4, "state": "canceled"},
    {"id": 2, "date": "12/03/2025", "hour": "19:30", "nCouv": 3, "state": "waiting"},
    {"id": 3, "date": "13/03/2025", "hour": "20:00", "nCouv": 5, "state": "confirmed"},
    {"id": 4, "date": "14/03/2025", "hour": "21:00", "nCouv": 2, "state": "canceled"},
    {"id": 5, "date": "15/03/2025", "hour": "18:45", "nCouv": 6, "state": "confirmed"},
    {"id": 6, "date": "16/03/2025", "hour": "17:30", "nCouv": 3, "state": "waiting"},
    {"id": 7, "date": "17/03/2025", "hour": "12:15", "nCouv": 4, "state": "confirmed"},
    {"id": 8, "date": "18/03/2025", "hour": "19:00", "nCouv": 5, "state": "canceled"},
    {"id": 9, "date": "19/03/2025", "hour": "20:30", "nCouv": 2, "state": "confirmed"},

    // Réservations futures
    {"id": 10, "date": "22/03/2025", "hour": "12:30", "nCouv": 4, "state": "confirmed"},
    {"id": 11, "date": "22/03/2025", "hour": "12:30", "nCouv": 4, "state": "waiting"},
    {"id": 12, "date": "22/03/2025", "hour": "12:30", "nCouv": 4, "state": "canceled"},
    {"id": 13, "date": "23/03/2025", "hour": "14:00", "nCouv": 2, "state": "confirmed"},
    {"id": 14, "date": "23/03/2025", "hour": "14:30", "nCouv": 5, "state": "waiting"},
    {"id": 15, "date": "24/03/2025", "hour": "19:00", "nCouv": 3, "state": "confirmed"},
    {"id": 16, "date": "25/03/2025", "hour": "20:00", "nCouv": 6, "state": "waiting"},
    {"id": 17, "date": "26/03/2025", "hour": "21:30", "nCouv": 2, "state": "canceled"},
    {"id": 18, "date": "27/03/2025", "hour": "18:00", "nCouv": 4, "state": "confirmed"},
    {"id": 19, "date": "28/03/2025", "hour": "17:45", "nCouv": 5, "state": "waiting"}
];

const tabsAll = document.querySelector('#tabs-all');
const tabsConfirmed = document.querySelector('#tabs-confirmed');
const tabsWaiting = document.querySelector('#tabs-waiting');
const tabsCanceled = document.querySelector('#tabs-canceled');
const tableContent = document.querySelector('#table-content');

function displayReservations(state = 'all', type = 'current') {
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

    // Get the current date
    const currentDate = new Date();

    // Filter data based on state (confirmed, waiting, canceled, etc.)
    let filteredData = state === 'all' ? data : data.filter(item => item.state === state);

    // Filter based on the type (current or history)
    filteredData = filteredData.filter(item => {
        const reservationDate = new Date(item.date.split('/').reverse().join('-') + ' ' + item.hour);
        if (type === 'current') {
            return reservationDate > currentDate; // Future reservations
        } else if (type === 'history') {
            return reservationDate < currentDate; // Past reservations
        }
        return false;
    });

    // Generate rows for the filtered data
    filteredData.forEach(item => {
        const row = document.createElement('li');
        row.classList.add('table-row');

        row.innerHTML = `
            <div class="col c1">${item.date}</div>
            <div class="col c2">${item.hour}</div>
            <div class="col c3">${item.nCouv}</div>
            <div class="col c4 state-${item.state}">${capitalizeFirstLetter(item.state)}</div>
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
    const type = getUrlParamType(); // Get type from URL
    displayReservations('all', type);
});

tabsConfirmed.addEventListener('click', () => {
    setActiveTab(tabsConfirmed);
    const type = getUrlParamType(); // Get type from URL
    displayReservations('confirmed', type);
});

tabsWaiting.addEventListener('click', () => {
    setActiveTab(tabsWaiting);
    const type = getUrlParamType(); // Get type from URL
    displayReservations('waiting', type);
});

tabsCanceled.addEventListener('click', () => {
    setActiveTab(tabsCanceled);
    const type = getUrlParamType(); // Get type from URL
    displayReservations('canceled', type);
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
    return urlParams.get('type') || 'current'; // Default to 'current' if not specified
}

// Window onload function to handle URL parameter and filter accordingly
window.onload = () => {
    const type = getUrlParamType(); // Get type from URL
    displayReservations('all', type); // Display all reservations based on the type (current or history)
};
