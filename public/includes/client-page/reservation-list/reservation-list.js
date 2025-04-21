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
          <div class="col c5">${isMoreThan24h ? "Détails" : "<span class='locked-details'>Indispo</span>"}</div>
        `;

        tableContent.appendChild(row);

        if (!isMoreThan24h){
            return;
        }

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
                  <input type="number" id="input-people-${uniqueSuffix}" class="people-input" min="1" max="9" value="${item.number_of_people}" />
                  <button class="modify-btn" id="modify-btn-${uniqueSuffix}">Modifier</button>
              </span>
          </div>
          <select id="details-state-${uniqueSuffix}" class="details-container">
              <option value="waiting">Waiting</option>
              <option value="cancelled">Cancelled</option>
          </select>
        `;

        tableContent.appendChild(detailsRow);

        // Déroule détails
        const detailsBtn = row.querySelector('.c5');
        detailsBtn.addEventListener('click', () => {
            detailsRow.classList.toggle("hidden");
        });

        // Changement de statut
        const detailsState = detailsRow.querySelector(`#details-state-${uniqueSuffix}`);
        detailsState.value = item.status;
        setDatePickerColor(item.status, detailsState);

        detailsState.addEventListener("change", async () => {
            setDatePickerColor(detailsState.value, detailsState);
            const ok = await fetchUpdateReservationState(item.id, detailsState.value);
            if (ok) {
                const rowState = row.querySelector('.c4');
                rowState.className = `col c4 state-${detailsState.value}`;
                rowState.innerText = capitalizeFirstLetter(detailsState.value);
                item.status = detailsState.value;
            }
        });

        // Modifier nombre de couverts
        const modifyBtn = detailsRow.querySelector(`#modify-btn-${uniqueSuffix}`);
        const inputPeople = detailsRow.querySelector(`#input-people-${uniqueSuffix}`);

        modifyBtn.addEventListener("click", async () => {
            const newNumberOfPeople = parseInt(inputPeople.value, 10);
            if (isNaN(newNumberOfPeople) || newNumberOfPeople < 1 || newNumberOfPeople > 9) {
                showBanner("error", "Veuillez entrer un nombre de couverts valide (entre 1 et 9)");
                return;
            }

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
                status:status,
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