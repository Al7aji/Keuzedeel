
// resources/js/layout.js

window.onToggleMenu = function (e) {
    const navlinks = document.querySelector('.nav-links');
    navlinks .classList.toggle('top-[100%]');
    navlinks .classList.toggle('top-[5.9%]');

    e.name = e.name === 'menu-outline' ? 'close-outline' : 'menu-outline';
};
