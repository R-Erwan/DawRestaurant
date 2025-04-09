import { parseJwt } from "../../js/utils";

document.getElementById("reservation-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    // Clear any previous error messages
    document.querySelectorAll('.error-message').forEach(function (elem) {
        elem.textContent = "";
    });

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const date = document.getElementById("date").value;
    const time = document.getElementById("time").value;
    const guests = document.getElementById("guests").value;

    let isValid = true;

    // Validation checks
    if (!name) {
        document.getElementById("name-error").textContent = "Le nom est obligatoire.";
        isValid = false;
    }
    if (!email) {
        document.getElementById("email-error").textContent = "L'email est obligatoire.";
        isValid = false;
    }
    if (!date) {
        document.getElementById("date-error").textContent = "La date est obligatoire.";
        isValid = false;
    }
    if (!time) {
        document.getElementById("time-error").textContent = "L'heure est obligatoire.";
        isValid = false;
    }
    if (!guests || guests <= 0) {
        document.getElementById("guests-error").textContent = "Le nombre de personnes doit être supérieur à 0.";
        isValid = false;
    }
    if (guests > 8) {
        document.getElementById("guests-error").textContent = "Le nombre de personnes max doit être inférieur à 9.";
        isValid = false;
    }

    if (isValid) {
        const jwt = localStorage.getItem("jwt");
        if (jwt) {
            const id = parseJwt(jwt).user_id;
            try {
                const reservationData = {
                    user_id: id,
                    email: email,
                    name: name,
                    date: date,
                    time: time,
                    guests: guests,
                };

                console.log(reservationData);

                const response = await fetch("http://localhost:8000/reservation?action=createReservation", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${jwt}`
                    },
                    body: JSON.stringify(reservationData)
                });

                if (response.ok) {
                    document.getElementById("reservation-status").textContent = `Réservation réussie`;

                } else {
                    document.getElementById("reservation-status").textContent = "La réservation a échoué. Veuillez réessayer.";
                }
            } catch (error) {
                console.error('Erreur lors du décodage du token ou de l\'envoi de la réservation:', error);
                document.getElementById("reservation-status").textContent = "Une erreur est survenue, veuillez réessayer.";
            }
        } else {
            window.location.href = "/login.php";
        }
    }
});
