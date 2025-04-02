import {showBanner} from "../../popup/popup";

async function fetchAnnounces(){
    try {
        const response = await fetch(`http://localhost:8000/announce`,{
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
        console.error(e);
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
    const response = await fetch(`http://localhost:8000/announce?action=positions`, {
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





