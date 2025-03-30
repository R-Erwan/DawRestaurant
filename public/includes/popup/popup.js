export const showBanner = (type, message) => {
    hideBanner();

    requestAnimationFrame(() => {
        const banner = document.querySelector(`.banner.${type}`);
        if (banner) {
            banner.querySelector('.banner-message').textContent = message; // Mise à jour du message
            banner.classList.add('visible');

            // Disparaît après 2s
            setTimeout(() => {
                hideBanner();
            }, 3000);
        }
    });
};

const hideBanner = () => {
    document.querySelectorAll('.banner.visible')
        .forEach((el) => el.classList.remove('visible'));
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.banner-close')
        .forEach((el) => el.addEventListener('click', hideBanner));

    document.querySelectorAll('.banner')
        .forEach((el) => el.addEventListener('click', hideBanner));
});
