// import {showBanner} from "../../popup/popup";
import {fetchUserData, parseJwt} from "../../../js/utils";
import {showBanner} from "../../popup/popup";
/* UI */
let data = {
    "first_name": null,
    "name": null,
    "email": null,
    "phone_number": null,
}

function toggleUpdateForm(){
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

/* API calls and Forms */

// Contact form
const formContact = document.querySelector('#form-contact');
formContact.addEventListener('submit', async function (e) {
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
    if(response.ok ) {
        toggleUpdateForm();
        showBanner('success',data.message);
    } else {
        showBanner('error', data.message || "Une erreur est survenu");
    }
})


document.addEventListener('DOMContentLoaded', async () => {
    const dataUser = await fetchUserData();
    data = dataUser.user
    displayUserInfo(data);
    // Bouton modifier
    document.querySelector('#toggle-update').addEventListener('click', toggleUpdateForm);
    document.querySelector('#cancel-button').addEventListener('click', toggleUpdateForm);
})
