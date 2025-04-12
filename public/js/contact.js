async function fetchOpenings() {
    try {
        const response = await fetch('/api/opening/basic', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });
        const json = await response.json();
        if (response.ok) {
            return json.result;
        } else {
            return null;
        }
    } catch (e) {
        console.error('Erreur lors de la récupération des horaires', e);
        return null;
    }
}

function displayTimes(data,times){

    // On définit les jours dans l’ordre voulu
    const daysOfWeek = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

    // Regrouper les horaires par jour
    const scheduleByDay = {};

    for (const entry of data) {
        const day = entry.name.toLowerCase();
        if (!scheduleByDay[day]) {
            scheduleByDay[day] = [];
        }
        if (entry.open) {
            // Format HH:MM (on coupe les secondes)
            const start = entry.time_start.slice(0, 5);
            const end = entry.time_end.slice(0, 5);
            scheduleByDay[day].push(`${start} - ${end}`);
        }
    }

    // Générer dynamiquement la liste
    times.innerHTML = ''; // On vide la liste actuelle

    for (const day of daysOfWeek) {
        const horaires = scheduleByDay[day];
        const prettyDay = day.charAt(0).toUpperCase() + day.slice(1); // Majuscule au début

        const li = document.createElement('li');
        li.innerHTML = `<span class="contact-day">${prettyDay}</span> ` +
            (horaires && horaires.length > 0
                ? horaires.join(' et ')
                : 'Fermé');
        times.appendChild(li);
    }
}

function formContactsubmit(){
    const firstName = document.querySelector('#input-firstName').value.trim();
    const lastName = document.querySelector('#input-lastName').value.trim();
    const email = document.querySelector('#input-email').value.trim();
    const phone = document.querySelector('#input-phone').value.trim();
    const message = document.querySelector('#input-message').value.trim();
    const agreed = document.querySelector('#input-agree').checked;

    if (!agreed) {
        alert("Vous devez accepter les conditions avant d'envoyer.");
        return;
    }

    const fullName = `${firstName} ${lastName}`;
    const subject = encodeURIComponent(`Contact de ${fullName}`);
    const body = encodeURIComponent(
        `Nom : ${fullName}\n` +
        `Email : ${email}\n` +
        `Téléphone : ${phone}\n\n` +
        `Message :\n${message}`
    );

    window.location.href = `mailto:contact@leResto.com?subject=${subject}&body=${body}`;
}

document.addEventListener('DOMContentLoaded', async () => {
    const times = document.querySelector('#times-opening');
    const data = await fetchOpenings();
    displayTimes(data,times);
    const form = document.querySelector('.contact-form-content');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        formContactsubmit();
    })
});
