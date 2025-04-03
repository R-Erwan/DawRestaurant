export function parseJwt (token) {
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    let jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

export function checkToken() {
    const token = localStorage.getItem('jwt');
    if (token) {
        const decoded = JSON.parse(atob(token.split('.')[1])); // Décoder le JWT
        const currentTime = Math.floor(Date.now() / 1000); // Temps actuel en secondes
        if (decoded.exp < currentTime) {
            // Token expiré, supprimer du localStorage
            localStorage.removeItem('jwt');
        }
    }
}

export async function fetchUserData() {
    const token = localStorage.getItem('jwt');

    if(!token) {
        throw new Error('Not authenticated.')
    }

    try {
        const decoded = parseJwt(token);
        // Fetch data
        const response = await fetch(`/api/user?id=${decoded.user_id}`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
            }
        });
        const dataJson = await response.json();
        if(response.ok) {
            return dataJson;
            // showBanner('success',data.message);
        } else {
            // showBanner('error', data.message || "Une erreur est survenu");
        }
    } catch (e) {
        console.error(e);
    }
}