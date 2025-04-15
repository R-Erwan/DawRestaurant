export function parseJwt(token) {
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    let jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

export function checkToken() {
    const token = localStorage.getItem('jwt');
    if (token) {
        const decoded = JSON.parse(atob(token.split('.')[1])); // Décoder le JWT
        const currentTime = Math.floor(Date.now() / 1000); // Temps actuel en secondes
        if (decoded.exp < currentTime) {
            // Token expiré, supprimer du localStorage
            localStorage.removeItem('jwt');
        }
    }
}

export async function fetchUserData() {
    const token = localStorage.getItem('jwt');

    if (!token) {
        throw new Error('Not authenticated.')
    }

    try {
        const decoded = parseJwt(token);
        // Fetch data
        const response = await fetch(`/api/user?id=${decoded.user_id}`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
            }
        });
        const dataJson = await response.json();
        if (response.ok) {
            return dataJson;
            // showBanner('success',data.message);
        } else {
            // showBanner('error', data.message || "Une erreur est survenu");
        }
    } catch (e) {
        console.error(e);
    }
}

export function convertTimeValue(value) {
    const floatVal = parseFloat(value);
    const hours = Math.floor(floatVal);
    const minutes = (floatVal % 1 === 0.5) ? '30' : '00';
    return `${hours}:${minutes}`;
}

export function convertToFloatTime(timeStr) {
    const [hours, minutes] = timeStr.split(':').map(Number);
    return hours + (minutes === 30 ? 0.5 : 0);
}

export function displayTimesSelect(select,startTime){
    let msg = startTime === 8 ? "Heure de début" : "Heure de fin";
    startTime = parseInt(startTime);
    select.innerHTML = `<option value='' disabled selected>${msg}</option>`
    for (let i = startTime; i < 24; i++) {
        select.innerHTML += `<option value="${i}">${i}:00</option>`;
        select.innerHTML += `<option value="${i}.5">${i}:30</option>`;
    }
    select.innerHTML += `<option value="24">24:00</option>`;
}

/**
 * Génère des options d'heures à partir de plusieurs créneaux et les insère dans un <select>
 * @param {HTMLSelectElement} select - L'élément <select> dans lequel insérer les options
 * @param {string[]} creneaux - Un tableau de chaînes représentant les créneaux (ex: ["12-15", "18-24"])
 * @param {string} placeholder - Texte par défaut (ex: "Heure de début", "Heure de fin")
 */
export function displayMultipleTimeSlots(select, creneaux, placeholder = "Choisir une heure") {
    select.innerHTML = `<option value='' disabled selected>${placeholder}</option>`;

    creneaux.forEach(creneau => {
        const [start, end] = creneau.split("-").map(Number);
        for (let i = start; i < end; i++) {
            select.innerHTML += `<option value="${i}">${i}:00</option>`;
            select.innerHTML += `<option value="${i}.5">${i}:30</option>`;
        }
    });

    // Optionnel : si la fin d'un créneau est 24, tu peux ajouter "24:00"
    if (creneaux.some(c => c.endsWith("24"))) {
        select.innerHTML += `<option value="24">24:00</option>`;
    }
}



export function getFrenchWeekdayName(dateString) {
    const jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
    const date = new Date(dateString);
    return jours[date.getDay()];
}


export function isOverlapping(times) {
    // Convertit une heure "HH:MM" en minutes
    function toMinutes(str) {
        const [h, m] = str.split(":").map(Number);
        return h * 60 + m;
    }

    // Transforme les horaires en objets avec les temps en minutes
    const intervals = times.map(t => ({
        start: toMinutes(t.time_start),
        end: toMinutes(t.time_end)
    }));

    // Trie les intervalles par heure de début
    intervals.sort((a, b) => a.start - b.start);

    // Vérifie s'il y a un chevauchement entre deux intervalles consécutifs
    for (let i = 0; i < intervals.length - 1; i++) {
        if (intervals[i].end > intervals[i + 1].start) {
            return true; // chevauchement détecté
        }
    }

    return false; // aucun chevauchement
}

export function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

export function formatDate(dateString) {
    const date = new Date(dateString);
    const mois = [
        "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
        "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
    ];

    const jour = date.getDate();
    const moisTexte = mois[date.getMonth()];
    const annee = date.getFullYear();
    const heures = date.getHours();
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${jour} ${moisTexte} ${annee} à ${heures}h${minutes}`;
}
