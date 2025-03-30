// import {showBanner} from "../../popup/popup";
import {fetchUserData, parseJwt} from "../../../js/utils";
import {showBanner} from "../../popup/popup";

/* UI */

function toggleUpdateForm() {
    const updateInput = document.querySelectorAll('.box > input')
    updateInput.forEach(el => {
        el.disabled = !el.disabled
    })
    document.querySelector('#toggle-update').classList.toggle('hidden');
    document.querySelector('#cancel-button').classList.toggle('hidden');
    document.querySelector('#submit-update-contact').classList.toggle('hidden');
    document.querySelector('#firstName').focus();
}

function displayUserInfo(data) {
    document.getElementById('firstName').value = data.first_name ?? "";
    document.getElementById('lastName').value = data.name ?? "";
    document.getElementById('email').value = data.email ?? "";
    document.getElementById('phone').value = data.phone_number ?? "";
}

// Contact form
async function formContactSubmit(e) {
    e.preventDefault();
    const firstName = document.getElementById("firstName").value ?? null;
    const lastName = document.getElementById("lastName").value;
    const email = document.getElementById("email").value;
    const phone_number = document.getElementById("phone").value ?? null;
    const jwt = localStorage.getItem("jwt");
    const id = parseJwt(jwt).user_id;

    const response = await fetch(`http://localhost:8000/user?id=${id}`, {
        method: "PUT",
        headers: {
            'Content-Type': 'application/json',
            "Authorization": `Bearer ${jwt}`,
        },
        body: JSON.stringify({
            email: email,
            name: lastName,
            first_name: firstName,
            phone_number: phone_number,
        })
    });
    const data = await response.json();
    if (response.ok) {
        toggleUpdateForm();
        showBanner('success', data.message);
    } else {
        showBanner('error', data.message || "Une erreur est survenu");
    }
}

// Password form
async function formPasswordSubmit(e, data) {
    e.preventDefault();
    const newPass = document.getElementById("new-password");
    const confirmPass = document.getElementById("confirm-password");
    const jwt = localStorage.getItem("jwt");
    const id = parseJwt(jwt).user_id;

    // Check both input equals
    if (newPass.value !== confirmPass.value) {
        showBanner('error', "Les mots de passes ne sont pas identiques");
        document.querySelector('#form-password').reset();
        newPass.focus();
        return;
    }

    const response = await fetch(`http://localhost:8000/user?id=${id}`, {
        method: "PUT",
        headers: {
            'Content-Type': 'application/json',
            "Authorization": `Bearer ${jwt}`,
        },
        body: JSON.stringify({
            email: data.email,
            name: data.name,
            password: confirmPass.value,
        })
    });
    const dataR = await response.json();
    if (response.ok) {
        showBanner('success', dataR.message);
    } else {
        showBanner('error', dataR.message || "Une erreur est survenu");
    }
    document.querySelector('#form-password').reset();

}

document.addEventListener('DOMContentLoaded', async () => {
    const dataUser = await fetchUserData();
    let data = dataUser.user
    displayUserInfo(data);
    // Bouton modifier
    document.querySelector('#toggle-update').addEventListener('click', toggleUpdateForm);
    // Cancel button
    document.querySelector('#cancel-button').addEventListener('click', () => {
        toggleUpdateForm();
        displayUserInfo(data);
    });
    document.querySelector('#form-contact').addEventListener('submit', formContactSubmit);
    document.querySelector('#form-password').addEventListener('submit', (e) => {
        formPasswordSubmit(e, data)
    });
})
