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
        dropzone.id = `drop-${i}`
        if(an.type === 1){
            dropzone.innerHTML = `
                 <div class="dragElement content-text" draggable="true" id="an-${an.id}">
                     <h3> ${an.title}</h3>
                     <p> ${an.description.slice(0,100)}... </p>
                 </div>
                `;
        } else if (an.type === 2){
            dropzone.innerHTML = `
            <div class="dragElement content-img " draggable="true" id="an-${an.id}">
                <img src="${an.image_url}" />
            </div>
            `;
        }

        dragDropContainer.appendChild(dropzone);
    })
}

document.addEventListener('DOMContentLoaded', async () => {
    const data = await fetchAnnounces();
    const annonces = data.result;
    displayAnnouces(annonces);



})