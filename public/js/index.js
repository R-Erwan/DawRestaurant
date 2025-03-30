const token = localStorage.getItem('token');
if (token) {
    const decoded = JSON.parse(atob(token.split('.')[1])); // Décoder le JWT
    const currentTime = Math.floor(Date.now() / 1000); // Temps actuel en secondes
    if (decoded.exp < currentTime) {
        // Token expiré, supprimer du localStorage
        localStorage.removeItem('token');
    }
}