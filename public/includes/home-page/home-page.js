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

document.addEventListener('DOMContentLoaded', async () => {
    const data = await fetchAnnounces();
    const annonces = data.result;
    const container = document.querySelector('.announce-container');
    annonces.map(annonce => {
        if(annonce.type === 1){
            const an = document.createElement('div')
            an.classList.add('announce-text','announce');
            an.innerHTML = ` 
                <h3>${annonce.title}</h3>
                <p>${annonce.description}</p>
            `
            container.appendChild(an);
        } else if (annonce.type === 2){
            const an = document.createElement('div')
            an.classList.add('announce-img','announce');
            an.innerHTML = `<img src="${annonce.image_url}" alt="Un cuisinier">`
            container.appendChild(an);
        }
    })
});