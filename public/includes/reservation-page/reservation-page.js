import {checkToken, parseJwt, displayMultipleTimeSlots, getFrenchWeekdayName, convertTimeValue} from "../../js/utils";
import {showBanner} from "../popup/popup";

document.addEventListener("DOMContentLoaded", async function () {
    checkToken();

    const today = new Date();
    const maxDate = new Date(today);
    maxDate.setMonth(today.getMonth() + 3);
    const dateInput = document.querySelector("#date");
    dateInput.setAttribute("min",today.toISOString().split("T")[0]);
    dateInput.setAttribute("max",maxDate.toISOString().split("T")[0]);
    const token = localStorage.getItem("jwt");
    if (!token) {
        redirectToLogin();
        return;
    }

    const tokenContent = parseJwt(token);
    if (tokenContent.email) {
        setEmailField(tokenContent.email);
    }

    setupFormHandler(tokenContent, token);

    const datePicker = document.querySelector("#date");
    datePicker.addEventListener("change", async () => {
        await dateChecker(datePicker.value);
    })

});

function redirectToLogin() {
    window.location.href = "login.php";
}

function setEmailField(email) {
    const emailInput = document.getElementById("email");
    emailInput.value = email;
    emailInput.disabled = true;
}

function setupFormHandler(tokenContent, token) {
    document.getElementById("reservation-form").addEventListener("submit", async function (e) {
        e.preventDefault();
        clearErrorMessages();

        const formData = getFormData();
        const validation = validateFormData(formData);

        if (validation.isValid) {
            await submitReservation(tokenContent.user_id, token, formData);
        }
    });
}

function clearErrorMessages() {
    document.querySelectorAll('.error-message').forEach(elem => {
        elem.textContent = "";
    });
}

function getFormData() {
    return {
        email: document.getElementById("email").value.trim(),
        date: document.getElementById("date").value,
        time: convertTimeValue(document.getElementById("time").value),
        guests: parseInt(document.getElementById("guests").value, 10)
    };
}

function validateFormData({ email, date, time, guests }) {
    let isValid = true;

    if (!email) {
        setError("email-error", "L'email est obligatoire.");
        isValid = false;
    }

    if (!date) {
        setError("date-error", "La date est obligatoire.");
        isValid = false;
    }

    if (!time) {
        setError("time-error", "L'heure est obligatoire.");
        isValid = false;
    }

    if (!guests || guests <= 0) {
        setError("guests-error", "Le nombre de personnes doit être supérieur à 0");
        isValid = false;
    } else if (guests > 8) {
        setError("guests-error", "Pour une réservation de plus de 9 personnes, contactez-nous par mail ou par téléphone");
        isValid = false;
    }

    return { isValid };
}

function setError(elementId, message) {
    document.getElementById(elementId).textContent = message;
}

async function submitReservation(userId, token, reservationData) {
    try {
        const response = await fetch(`/api/reservation?id=${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(reservationData)
        });

        const statusElement = document.getElementById("reservation-status");
        if (response.ok) {
            statusElement.textContent = "Réservation réussie";
            window.location.href = 'client.php';
        } else {
            statusElement.textContent = "La réservation a échoué. Veuillez réessayer.";
        }
    } catch (error) {
        console.error("Erreur lors de la réservation :", error);
        document.getElementById("reservation-status").textContent = "Une erreur est survenue, veuillez réessayer.";
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
            console.error("error fetching opening basic")
        }
    } catch (e){
        console.error("error fetching opening basic")
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
            console.error("error fetching opening")
        }
    } catch (e){
        console.error("error fetching opening exceptional")
    }
}

async function dateChecker(date) {
    const excRules = await fetchExcDate(date);
    if(excRules.result.length === 0) {
        const basicsRules = await fetchOpeningBasicDate(date);
        if(basicsRules.result.length === 0) {
            displayMultipleTimeSlots(document.querySelector("#time"),[]);
            showBanner("info","Le restaurant est fermé le "+ getFrenchWeekdayName(date));
        } else {
            let times = [];
            basicsRules.result.forEach(element => {
                times.push(`${element.time_start.split(':')[0]}-${element.time_end.split(':')[0]}`)
            });
            displayMultipleTimeSlots(document.querySelector("#time"),times);
        }
    } else {
        if(excRules.result[0].open === false){
            displayMultipleTimeSlots(document.querySelector("#time"),[]);
            showBanner("info","Le restaurant est fermé exceptionnelement le  "+ date + " : " + excRules.result[0].comment, 5000);
        } else {
            let times = [];
            excRules.result.forEach(element => {
                times.push(`${element.time_start.split(':')[0]}-${element.time_end.split(':')[0]}`)
            });
            displayMultipleTimeSlots(document.querySelector("#time"),times);
        }
    }

}