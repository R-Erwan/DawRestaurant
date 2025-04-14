export const showBanner = (type, message, timeout = 3000) => {
    hideBanner();

    requestAnimationFrame(() => {
        const banner = document.querySelector(`.banner.${type}`);
        if (banner) {
            banner.querySelector('.banner-message').textContent = message; // Mise à jour du message
            banner.classList.add('visible');

            // Disparaît après 2s
            setTimeout(() => {
                hideBanner();
            }, timeout);
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
