document.getElementById("reservation-form").addEventListener("submit", function(event) {
    event.preventDefault();

    document.querySelectorAll('.error-message').forEach(function(elem) {
        elem.textContent = "";
    });

    const name = document.getElementById("name").value.trim();
    const date = document.getElementById("date").value;
    const time = document.getElementById("time").value;
    const guests = document.getElementById("guests").value;

    let isValid = true;

    // Validation des champs
    if (!name) {
        document.getElementById("name-error").textContent = "Le nom est obligatoire.";
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

    if (guests >8)
    {
        document.getElementById("guests-error").textContent = "Le nombre de personnes max doit être inférieur à 9.";
        isValid = false;
    }
    if (isValid) {
        document.getElementById("reservation-form").reset();
    }
});