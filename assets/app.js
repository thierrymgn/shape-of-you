import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

console.log('test');

document.addEventListener('DOMContentLoaded', function() {
    const userMenuButton = document.querySelector('#user-menu-button');
    const userMenu = document.querySelector('#user-menu-item');

    userMenuButton.addEventListener('click', function() {
        userMenu.classList.toggle('hidden');
    });
});