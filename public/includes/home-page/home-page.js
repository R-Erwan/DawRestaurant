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
    console.log(annonces);
});