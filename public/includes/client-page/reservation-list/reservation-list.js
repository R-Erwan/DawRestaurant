import {parseJwt} from "../../../js/utils";
import {showBanner} from "../../popup/popup";

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
            console.error("Échec de la récupération des réservations :", response.status, response.statusText);
            return [];
        }
    } catch (error) {
        console.error("Erreur lors de la récupération des réservations :", error);
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
            body: JSON.stringify({status: state}),
        });
        const dataJson = await response.json();
        if (response.ok) {
            return true;
        } else {
            showBanner("error", `Échec de la mise à jour de l'état : ${dataJson.message}`);
            return false;
        }
    } catch (error) {
        showBanner("error", "Échec de la mise à jour de l'état");
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
        <div class="col c3">Nombre de couverts</div>
        <div class="col c4">Statut</div>
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

        // Vérification de l'heure pour ne pas que ce soit une modification à - 24h
        const reservationDateTime = new Date(`${item.reservation_date.trim()}T${item.reservation_time.trim()}`);
        const isMoreThan24h = !isNaN(reservationDateTime) && reservationDateTime - new Date() > 24 * 60 * 60 * 1000;
        const isCancelled = item.status.trim().toLowerCase() === "cancelled";

        row.innerHTML = `
          <div class="col c1">${item.reservation_date}</div>
          <div class="col c2">${item.reservation_time}</div>
          <div class="col c3">${item.number_of_people}</div>
          <div class="col c4 state-${item.status}">${capitalizeFirstLetter(item.status)}</div>
          <div class="col c5">${isMoreThan24h && !isCancelled ? "Détails" : "<span class='locked-details'>Indisponible</span>"}</div>
        `;

        tableContent.appendChild(row);

        if (!isMoreThan24h) {
            return;
        }
        if (!isCancelled)
        {
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

            // Bouton de déroulement des détails
            const detailsBtn = row.querySelector('.c5');
            detailsBtn.addEventListener('click', () => {
                detailsRow.classList.toggle("hidden");
            });

            // Gestion des boutons d'ajout et de réduction
            const decreaseBtn = detailsRow.querySelector(`#decrease-btn-${uniqueSuffix}`);
            const increaseBtn = detailsRow.querySelector(`#increase-btn-${uniqueSuffix}`);
            const peopleCount = detailsRow.querySelector(`#people-count-${uniqueSuffix}`);

            // Gestion de la diminution des invités
            decreaseBtn.addEventListener("click", () => {
                let newCount = parseInt(peopleCount.innerText, 10) - 1;
                if (newCount < 1) newCount = 1;
                peopleCount.innerText = newCount;
                peopleCount.style.color
            });

            // Gestion de l'augmentation des invités
            increaseBtn.addEventListener("click", () => {
                let newCount = parseInt(peopleCount.innerText, 10) + 1;
                if (newCount > 9) {
                    newCount = 9;
                }
                peopleCount.innerText = newCount;
            });

            console.log(item);
            // Bouton et écouteur pour l'annulation
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

            // Bouton et écouteur pour la modification
            const modifyBtn = detailsRow.querySelector(`#modify-btn-${uniqueSuffix}`);
            modifyBtn.addEventListener("click", async () => {
                const n_nop = peopleCount.innerText;
                const success = await fetchUpdateReservationPeople(item.id, n_nop);
                if (success) {
                    showBanner("success", "Nombre de couverts mis à jour avec succès");
                    const mainRowPeople = row.querySelector('.c3');
                    mainRowPeople.innerText = n_nop;
                    item.number_of_people = n_nop;
                }
            });
        }
    });
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Écouteurs d'événements pour les onglets
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

async function fetchUpdateReservationPeople(id, people) {
    const jwt = localStorage.getItem("jwt");
    if (!jwt) {
        showBanner("error", "Aucun JWT trouvé");
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
                guests: people
            }),
        });
        const dataJson = await response.json();
        console.info(dataJson);
        if (response.ok) {
            return true;
        } else {
            showBanner("error", `Échec de la mise à jour du nombre de personnes : ${dataJson.message || "Erreur inconnue"}`);
            return false;
        }
    } catch (error) {
        console.error("Erreur lors de la mise à jour de la réservation :", error);
        showBanner("error", "Échec de la mise à jour du nombre de personnes");
        return false;
    }
}
