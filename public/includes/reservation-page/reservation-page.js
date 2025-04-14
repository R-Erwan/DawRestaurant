import {checkToken, parseJwt} from "../../js/utils";

document.addEventListener("DOMContentLoaded", async function () {
    checkToken();
    const token = localStorage.getItem("jwt");
    if (!token) {
        window.location.href = "login.php";
    }
    const tokenContent = parseJwt(token);
    if (tokenContent.email) {
        document.getElementById("email").value = tokenContent.email;
        document.getElementById("email").disabled = true;
    }

    document.getElementById("reservation-form").addEventListener("submit", async function (e) {
        e.preventDefault();

        document.querySelectorAll('.error-message').forEach(function (elem) {
            elem.textContent = "";
        });

        const email = document.getElementById("email").value.trim();
        const date = document.getElementById("date").value;
        const time = document.getElementById("time").value;
        const guests = document.getElementById("guests").value;

        let isValid = true;

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
            const id = tokenContent.user_id;
            try {
                const reservationData = {
                    email: email,
                    date: date,
                    time: time,
                    guests: guests,
                };
                const response = await fetch(`/api/reservation?id=${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(reservationData)
                });

                if (response.ok) {
                    document.getElementById("reservation-status").textContent = `Réservation réussie`;
                    window.location.href = 'client.php'

                } else {
                    document.getElementById("reservation-status").textContent = "La réservation a échoué. Veuillez réessayer.";
                }

            } catch (error) {
                console.error('Erreur lors du décodage du token ou de l\'envoi de la réservation:', error);
                document.getElementById("reservation-status").textContent = "Une erreur est survenue, veuillez réessayer.";
            }
        }
    });

});
