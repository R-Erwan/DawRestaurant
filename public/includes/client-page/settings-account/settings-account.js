// import {showBanner} from "../../popup/popup";
import {parseJwt} from "/js/utils";
/* UI */
let data = {
    "firstName": null,
    "lastName": null,
    "email": null,
    "phone": null,
}

const updateBtn = document.querySelector('#toggle-update');
updateBtn.onclick = (e) => {
    const updateInput = document.querySelectorAll('.box > input')
    updateInput.forEach(el => {
        el.disabled = !el.disabled
    })
    updateBtn.style.display = 'none'
    document.querySelector('#firstName').focus();
    document.querySelector('#submit-update-contact').hidden = false;
}

function displayUserInfo(data) {
    document.getElementById('firstName').value = data.firstName ?? "";
    document.getElementById('lastName').value = data.name ?? "";
    document.getElementById('email').value = data.email ?? "";
    document.getElementById('phone').value = data.phone ?? "";
}

/* API calls */

async function fetchUserData() {
    const token = localStorage.getItem('jwt');

    if(!token) {
        throw new Error('Not authenticated.')
    }

    try {
        const decoded = parseJwt(token);
        // Fetch data
        const response = await fetch(`http://localhost:8000/user?id=${decoded.user_id}`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
            }
        });
        const dataJson = await response.json();
        if(response.ok) {
            data = dataJson.user;
            // showBanner('success',data.message);
        } else {
            // showBanner('error', data.message || "Une erreur est survenu");
        }
    } catch (e) {
        console.error(e);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    await fetchUserData();
    displayUserInfo(data);
})
