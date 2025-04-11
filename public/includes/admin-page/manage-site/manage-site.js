import {showBanner} from "../../popup/popup";

async function fetchAnnounces(){
    try {
        const response = await fetch(`/api/announce`,{
            method: "GET",
            headers: {'Content-Type': 'application/json'}
        });
        const dataJson = await response.json();
        if (response.ok) {
            return dataJson;
        } else {
            //Page d'erreur
        }
    } catch (e) {
        showBanner('error',"Erreur lors de la récupération des données");
    }
}

function displayAnnouces(announces) {
    const dragDropContainer = document.querySelector('.drag-drop-container');
    announces.forEach((an,i) => {
        const dropzone = document.createElement("div");
        dropzone.classList.add('dropzone');
        dropzone.setAttribute("position",i);
        if(an.type === 1){
            dropzone.innerHTML = `
                 <div class="dragElement content-text" draggable="true" id_a="${an.id}">
                     <h3> ${an.title}</h3>
                     <p> ${an.description.slice(0,100)}... </p>
                 </div>
                `;
        } else if (an.type === 2){
            dropzone.innerHTML = `
            <div class="dragElement content-img " draggable="true" id_a="${an.id}">
                <img src="${an.image_url}" />
            </div>
            `;
        }
        dropzone.addEventListener("click", (e) => {
            displayUpdateForm(an,i);
        })

        dragDropContainer.appendChild(dropzone);
    })
}

async function sendOrder(){
    const dropZones = document.querySelectorAll('.dropzone');
    let positions = [];
    dropZones.forEach((dropzone) => {
        const pos = dropzone.getAttribute('position');
        const id_a = dropzone.firstElementChild.getAttribute('id_a');
        positions[pos] = parseInt(id_a);
    })

    const jwt = localStorage.getItem("jwt");
    const response = await fetch(`/api/announce?action=positions`, {
        method: "PUT",
        headers: {
            'Content-Type': 'application/json',
            "Authorization": `Bearer ${jwt}`,
        },
        body: JSON.stringify({positions})
    });
    const data = await response.json();
    if(response.ok ) {
        showBanner('success',data.message);
    } else {
        showBanner('error', data.message || "Une erreur est survenu");
    }
}

function displayForm(type){
    if(type === 1){
        document.getElementById("title").style.display = 'block';
        document.getElementById("desc").style.display = 'block';
        document.getElementById("dropcontainer").style.display = 'none';
    } else if (type === 2){
        document.getElementById("title").style.display = 'none';
        document.getElementById("desc").style.display = 'none';
        document.getElementById("dropcontainer").style.display = 'flex';
    }
}

async function submitAnnounce() {
    const type = parseInt(document.querySelector('#type').value);
    const title = document.querySelector('#title').value;
    const desc = document.querySelector('#desc').value;
    let imageUrl;
    let body = { "type": type };

    /* Traiter l'image si besoin */
    if (type === 2) {
        const imageFile = document.querySelector('#images').files[0];

        if (!imageFile) {
            showBanner('error', "Aucune image sélectionnée !");
            return;
        }

        const imageFormData = new FormData();
        imageFormData.append('file', imageFile);

        try {
            const response = await fetch('/includes/admin-page/manage-site/upload.php', {
                method: 'POST',
                body: imageFormData,
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("jwt")}`
                }
            });

            const result = await response.json();
            if (response.ok) {
                imageUrl = result.url;
                body.image_url = imageUrl;
            } else {
                showBanner('error', result.error);
                return;
            }
        } catch (e) {
            showBanner('error', "Erreur lors de l'upload de l'image");
            return;
        }
    } else if (type === 1) {
        body.title = title;
        body.description = desc;
    }

    /* Requête à l'API */
    const response = await fetch(`/api/announce`, {
        method: "POST",
        body: JSON.stringify(body),
        headers: {
            'Content-Type': 'application/json',
            "Authorization": `Bearer ${localStorage.getItem("jwt")}`
        }
    });

    const result = await response.json();
    if (response.ok) {
        showBanner('success', result.message);
        setTimeout(() => {
            location.reload();
        }, 1000);
    } else {
        showBanner('error', result.message);
    }
}

function displayUpdateForm(announce,i){

    const updateForm = document.querySelector("#update-form");
    updateForm.classList.remove("hidden");
    updateForm.setAttribute("id_a", announce.id);
    updateForm.setAttribute("type", announce.type);
    document.querySelector("#create-form").classList.add("hidden");
    const updateTitle  = document.querySelector('#update-title');
    const updateDesc = document.querySelector('#update-desc');
    const updateImage = document.querySelector('#update-dropcontainer');
    const posId = document.querySelector('#update-id-position');

    posId.textContent = ` ${i+1}`;
    if(announce.type === 1){
        updateTitle.style.display = 'block';
        updateTitle.value = announce.title;
        updateDesc.style.display = 'block';
        updateDesc.value = announce.description;
        updateImage.style.display = 'none';
    } else if (announce.type === 2) {
        updateTitle.style.display = 'none';
        updateDesc.style.display = 'none';
        updateImage.style.display = 'flex';
    }
}

async function updateAnnounce(announceId){
    const title = document.querySelector('#update-title').value;
    const desc = document.querySelector('#update-desc').value;
    const type = parseInt(document.querySelector("#update-form").getAttribute('type'));

    let imageUrl;
    let body = {"id" : announceId};

    /* Traiter l'image si besoin */
    if (type === 2) {
        const imageFile = document.querySelector('#update-images').files[0];

        if (!imageFile) {
            showBanner('error', "Aucune image sélectionnée !");
            return;
        }

        const imageFormData = new FormData();
        imageFormData.append('file', imageFile);

        try {
            const response = await fetch('/includes/admin-page/manage-site/upload.php', {
                method: 'POST',
                body: imageFormData,
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("jwt")}`
                }
            });

            const result = await response.json();
            if (response.ok) {
                imageUrl = result.url;
                body.image_url = imageUrl;
            } else {
                showBanner('error', result.error);
                return;
            }
        } catch (e) {
            showBanner('error', "Erreur lors de l'upload de l'image");
            return;
        }
    } else if (type === 1) {
        body.title = title;
        body.description = desc;
    }

    /* Requête à l'API */
    const response = await fetch(`/api/announce`, {
        method: "PUT",
        body: JSON.stringify(body),
        headers: {
            'Content-Type': 'application/json',
            "Authorization": `Bearer ${localStorage.getItem("jwt")}`
        }
    });

    const result = await response.json();
    if (response.ok) {
        showBanner('success', result.message);
        setTimeout(() => {
            location.reload();
        }, 1000);
    } else {
        showBanner('error', result.message);
    }


}
async function deleteAnnounce(announceId){
    if(confirm("Valider la suppression de l'annonce ? ")){
        try{
            const response = await fetch(`/api/announce?announce_id=${announceId}`, {
                method: "DELETE",
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem("jwt")}`,
                    "Content-Type": "application/json",
                }
            });
            const result = await response.json();
            if (response.ok) {
                showBanner('success', result.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showBanner('error', result.message);
            }
        } catch (error){
            showBanner('error', "Erreur de suppression");
        }
    }
}
document.addEventListener('DOMContentLoaded', async () => {

    /* Fetch data */
    const data = await fetchAnnounces();
    const annonces = data.result;
    displayAnnouces(annonces);

    /* Button event */
    document.querySelector("#validate-update").addEventListener("click", async e => {
        const result = await sendOrder();
    })

    /* Drop Images */
    const dropContainer = document.getElementById("dropcontainer");
    const fileInput = document.getElementById("images");

    dropContainer.addEventListener("dragover", (e) => {
        // prevent default to allow drop
        e.preventDefault();
    }, false);

    dropContainer.addEventListener("dragenter", () => {
        dropContainer.classList.add("drag-active");
    });

    dropContainer.addEventListener("dragleave", () => {
        dropContainer.classList.remove("drag-active");
    });

    dropContainer.addEventListener("drop", (e) => {
        e.preventDefault();
        dropContainer.classList.remove("drag-active");
        fileInput.files = e.dataTransfer.files;
    });

    /* Forms */

    const typeInput = document.getElementById("type");
    typeInput.addEventListener("change", e => {displayForm(parseInt(typeInput.value));});

    const submitForm = document.getElementById("submit-form");
    submitForm.addEventListener("click", async e => {
        e.preventDefault();
        await submitAnnounce();
    })


    /* Update Form */
    document.querySelector("#back-update").addEventListener("click",  (e) => {
        document.querySelector("#create-form").classList.remove("hidden");
        document.querySelector("#update-form").classList.add("hidden");
    })
    document.querySelector("#del-announce").addEventListener("click",  async (e) => {
        const ida = document.querySelector("#update-form").getAttribute('id_a');
        await deleteAnnounce(ida);
        document.querySelector("#create-form").classList.remove("hidden");
        document.querySelector("#update-form").classList.add("hidden");
    })

    document.querySelector("#submit-update-form").addEventListener("click", async (e) => {
        const ida = document.querySelector("#update-form").getAttribute('id_a');
        e.preventDefault();
        await updateAnnounce(ida);
        document.querySelector("#create-form").classList.remove("hidden");
        document.querySelector("#update-form").classList.add("hidden");
    })

    /* Draggable */
    const dropZones = document.querySelectorAll('.dropzone');
    let dragItem = null;
    let sourceDropZone = null;

    document.querySelectorAll('.dragElement').forEach(item => {
        item.addEventListener('dragstart', (e) => {
            if (e.target.tagName === "IMG") {
                e.preventDefault(); // Empêche l’image de capturer le drag
                return;
            }
            dragItem = item;
            sourceDropZone = item.parentElement;
            setTimeout(() => {
                dragItem.style.display = "none"; // Cache temporairement l’élément
            }, 0);
            item.classList.add('draggin');
        });

        item.addEventListener('dragend', () => {
            dragItem.style.display = "flex"; // Ré-affiche l’élément
            dragItem = null;
            sourceDropZone = null;
            item.classList.remove('draggin');
        });
    });

    dropZones.forEach(dropZone => {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('hoverOver');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('hoverOver');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('hoverOver');

            if (dragItem && sourceDropZone !== dropZone) {
                const targetItem = dropZone.firstElementChild; // Élément existant dans la dropzone cible

                // Si la dropzone cible contient déjà un élément, on l’échange avec dragItem
                if (targetItem) {
                    sourceDropZone.appendChild(targetItem);
                }

                // Déplacer l’élément dragItem dans la nouvelle dropzone
                dropZone.appendChild(dragItem);
                dragItem.style.display = "flex"; // S’assurer qu’il est visible
            }
        });
    });
});
