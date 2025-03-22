/* UI */
const data = {
    "firstName": null,
    "lastName": 'Mate',
    "email": 'toto@gmail.com',
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
    document.getElementById('lastName').value = data.lastName ?? "";
    document.getElementById('email').value = data.email ?? "";
    document.getElementById('phone').value = data.phone ?? "";
}

window.onload = () => {
    displayUserInfo(data);
}