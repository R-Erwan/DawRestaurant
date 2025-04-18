const modalBackdrop = document.querySelector(".modal-backdrop");
const modal = modalBackdrop.querySelector(".modal-content");
const submitBtn = modal.querySelector("#btn-reset-passwd");
const mailInput = document.querySelector("#reset-mail");

export function displayModal(){
    const rect = event.target.getBoundingClientRect();
    modal.style.left = `${rect.left}px`;
    modal.style.top = `${rect.top + window.scrollY}px`;
    modal.style.transformOrigin = "top left";

    // Réinitialiser pour permettre une nouvelle animation
    modal.classList.remove("show");
    modal.style.transition = "none";
    modal.style.transform = "translate(-40%, -80%) scale(0)";

    // Forcer un reflow pour "relancer" l'animation (astuce JS)
    void modal.offsetWidth;

    // Appliquer la transition et l'animation
    modal.style.transition = "all 0.4s ease";
    modalBackdrop.style.display = "block";

    requestAnimationFrame(() => {
        modal.classList.add("show");
        modal.style.transform = "translate(-40%, -80%) scale(1)";
    });
}

window.onclick = (e) => {
    if (e.target === modalBackdrop) {
        modal.classList.remove("show");
        setTimeout(() => {
            modalBackdrop.style.display = 'none';
        }, 400);
    }
};

submitBtn.addEventListener("click", async (e) => {
    const mail = mailInput.value;
    console.log(mail);
    const res = await fetchResetPasswd(mail);

    modal.classList.remove("show");
    setTimeout(() => {
        modalBackdrop.style.display = 'none';
    }, 400);

})


async function fetchResetPasswd(email) {
    try {
        const result = await fetch("/api/auth/request-reset-password", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({"email": email})
        });
        const dajaJson = await result.json();
        return result.ok;
    } catch (error) {
        return false;
    }
}