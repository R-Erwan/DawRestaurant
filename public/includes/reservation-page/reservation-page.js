import {parseJwt} from "../../js/utils";

document.getElementById("reservation-form").addEventListener("submit",
    async function (e) {
        e.preventDefault();
        document.querySelectorAll('.error-message').forEach(function (elem) {
            elem.textContent = "";
        });

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const date = document.getElementById("date").value;
        const time = document.getElementById("time").value;
        const guests = document.getElementById("guests").value;

        let isValid = true;

        // Validation des champs
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
            isValid =  false;
        }
        if (isValid) {
            const token = localStorage.getItem("jwt");
            if (token) {
                try {
                    const decodedToken = parseJwt(token);
                    const userID = decodedToken.id;
                    const reservationData = {
                        user_id: userID,
                        email: email,
                        name: name,
                        date: date,
                        time: time,
                        guests: guests,
                    }
                    const response = await fetch(`http://localhost:8080/api/reservation?action=createReservation`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(reservationData)
                    });
                    if (response.ok) {
                        document.getElementById("reservation-status").textContent = "Réservation réussis";
                    } else {
                        document.getElementById("reservation-status").textContent = "Réservation a échoué";
                    }
                } catch (error) {
                    console.error('Erreur lors du décodage du token ou de l\'envoi de la réservation:', error);
                }


            } else {
                window.location.href = "/login";
            }
        }

    });