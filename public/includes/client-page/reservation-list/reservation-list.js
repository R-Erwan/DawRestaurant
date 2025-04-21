import { formatDate, parseJwt } from "../../../js/utils";
import { showBanner } from "../../popup/popup";

async function fetchReservation() {
    const jwt = localStorage.getItem("jwt");
    if (!jwt) {
        console.error("Pas de JWT");
        return [];
    }
    const token = parseJwt(jwt);
    const user_id = token.user_id;
    try {
        const response = await fetch(`/api/reservation?action=User&id=${user_id}`, {
            headers: {
                Authorization: `Bearer ${jwt}`,
                "Content-Type": "application/json",
            },
        });
        if (response.ok) {
            return await response.json();
        } else {
            console.error("Failed to fetch reservations:", response.status, response.statusText);
            return [];
        }
    } catch (error) {
        console.error("Error fetching reservations:", error);
        return [];
    }
}

async function fetchUpdateReservationState(id, state) {
    const jwt = localStorage.getItem("jwt");
    try {
        const response = await fetch(`/api/reservation?id_reservation=${id}`, {
            method: "PUT",
            headers: {
                Authorization: `Bearer ${jwt}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ status: state }),
        });
        const dataJson = await response.json();
        if (response.ok) {
            return true;
        } else {
            showBanner("error", `Failed to update state: ${dataJson.message}`);
            return false;
        }
    } catch (error) {
        showBanner("error", "Failed to update state");
        return false;
    }
}

const tabsAll = document.querySelector("#tabs-all");
const tabsConfirmed = document.querySelector("#tabs-confirmed");
const tabsWaiting = document.querySelector("#tabs-waiting");
const tabsCanceled = document.querySelector("#tabs-cancelled");
const tableContent = document.querySelector("#table-content");

async function displayReservations(state = "all", type = "current") {
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
    data = data.reservation || [];
    const currentDate = new Date();

    const filteredData = data
        .filter(item => (state === "all" || item.status === state))
        .filter(item => {
            const reservationDate = new Date(`${item.reservation_date} ${item.reservation_time}`);
            return type === "current" ? reservationDate > currentDate : reservationDate < currentDate;
        });

    filteredData.forEach(item => {
        const uniqueSuffix = `${item.id}-${item.reservation_date}-${item.reservation_time.replace(':', '')}`;
        const row = document.createElement("li");
        row.classList.add("table-row");

        const reservationDateTime = new Date(`${item.reservation_date}T${item.reservation_time}`);
        const isMoreThan24h = reservationDateTime - new Date() > 24 * 60 * 60 * 1000;

        row.innerHTML = `
      <div class="col c1">${item.reservation_date}</div>
      <div class="col c2">${item.reservation_time}</div>
      <div class="col c3">${item.number_of_people}</div>
      <div class="col c4 state-${item.status}">${capitalizeFirstLetter(item.status)}</div>
      <div class="col c5">${isMoreThan24h ? "Détails" : "<span class='locked-details'>Indisponible</span>"}</div>
    `;

        tableContent.appendChild(row);

        if (!isMoreThan24h) return;

        const detailsRow = document.createElement("div");
        detailsRow.classList.add("details-row", "hidden");

        detailsRow.innerHTML = `
      <div class="details-header details-container">
          <span class="details-elmt" id="d-name-${uniqueSuffix}">${capitalizeFirstLetter(item.name)}</span>
          <span class="details-elmt" id="d-fname-${uniqueSuffix}">${capitalizeFirstLetter(item.first_name ?? "")}</span>
      </div>
      <div class="details-body details-container">
          <span class="details-elmt" id="d-people-${uniqueSuffix}">
              <label for="input-people-${uniqueSuffix}">Nombre de couverts :</label>
              <button id="decrease-btn-${uniqueSuffix}" class="change-people-btn">-</button>
              <span id="people-count-${uniqueSuffix}">${item.number_of_people}</span>
              <button id="increase-btn-${uniqueSuffix}" class="change-people-btn">+</button>
              <button class="modify-btn" id="modify-btn-${uniqueSuffix}">Modifier</button>
          </span>
      </div>
      <button id="details-state-${uniqueSuffix}" class="btn-delete-reservation details-container" value="cancelled">Annuler la réservation</button>
    `;

        tableContent.appendChild(detailsRow);

        // Déroule détails
        const detailsBtn = row.querySelector('.c5');
        detailsBtn.addEventListener('click', () => {
            detailsRow.classList.toggle("hidden");
        });

        // Gestion des boutons d'ajout et de réduction
        const decreaseBtn = detailsRow.querySelector(`#decrease-btn-${uniqueSuffix}`);
        const increaseBtn = detailsRow.querySelector(`#increase-btn-${uniqueSuffix}`);
        const peopleCount = detailsRow.querySelector(`#people-count-${uniqueSuffix}`);

        decreaseBtn.addEventListener("click", () => {
            let newCount = parseInt(peopleCount.innerText, 10) - 1;
            if (newCount < 1) newCount = 1;
            peopleCount.innerText = newCount;
        });

        increaseBtn.addEventListener("click", () => {
            let newCount = parseInt(peopleCount.innerText, 10) + 1;
            if (newCount > 9) newCount = 9;
            peopleCount.innerText = newCount;
        });

        // Changement de statut
        const detailsState = detailsRow.querySelector(`#details-state-${uniqueSuffix}`);

        detailsState.addEventListener("click", async () => {
            const ok = await fetchUpdateReservationState(item.id, "cancelled");
            if (ok) {
                const rowState = row.querySelector('.c4');
                rowState.className = `col c4 state-${detailsState.value}`;
                rowState.innerText = capitalizeFirstLetter(detailsState.value);
                item.status = 'cancelled';
            }
        });

        // Modifier nombre de couverts
        const modifyBtn = detailsRow.querySelector(`#modify-btn-${uniqueSuffix}`);

        modifyBtn.addEventListener("click", async () => {
            const newNumberOfPeople = parseInt(peopleCount.innerText, 10);
            const success = await fetchUpdateReservationPeople(item.id, newNumberOfPeople, item.reservation_time, item.status);
            if (success) {
                showBanner("success", "Nombre de couverts mis à jour avec succès");
                const mainRowPeople = row.querySelector('.c3');
                mainRowPeople.innerText = newNumberOfPeople;
                item.number_of_people = newNumberOfPeople;
            }
        });
    });

}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Event listeners for the tabs
tabsAll.addEventListener("click", () => {
    setActiveTab(tabsAll);
    const type = getUrlParamType();
    displayReservations("all", type);
});

tabsConfirmed.addEventListener("click", () => {
    setActiveTab(tabsConfirmed);
    const type = getUrlParamType();
    displayReservations("confirmed", type);
});

tabsWaiting.addEventListener("click", () => {
    setActiveTab(tabsWaiting);
    const type = getUrlParamType();
    displayReservations("waiting", type);
});

tabsCanceled.addEventListener("click", () => {
    setActiveTab(tabsCanceled);
    const type = getUrlParamType();
    displayReservations("cancelled", type);
});

function setActiveTab(activeTab) {
    document.querySelectorAll(".tabs-button").forEach((tab) => {
        tab.classList.remove("tabs-selected");
    });
    activeTab.classList.add("tabs-selected");
}

function getUrlParamType() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get("type") || "current";
}

window.onload = () => {
    const type = getUrlParamType();
    displayReservations("all", type);
};

async function fetchUpdateReservationPeople(id, people, time, status) {
    const jwt = localStorage.getItem("jwt");
    if (!jwt) {
        showBanner("error", "No JWT found");
        return false;
    }

    try {
        const response = await fetch(`/api/reservation?id_reservation=${id}`, {
            method: "PUT",
            headers: {
                Authorization: `Bearer ${jwt}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                reservation_time: time,
                status: status,
                number_of_people: people
            }),
        });
        const dataJson = await response.json();
        if (response.ok) {
            return true;
        } else {
            showBanner("error", `Failed to update number of people: ${dataJson.message || "Unknown error"}`);
            return false;
        }
    } catch (error) {
        console.error("Error updating reservation:", error);
        showBanner("error", "Failed to update number of people");
        return false;
    }
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
