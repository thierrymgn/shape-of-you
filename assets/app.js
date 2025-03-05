import './styles/app.css';

document.addEventListener('DOMContentLoaded', function(){
    console.log(document.querySelector('#sort-button-wardrobe-item'));
    document.addEventListener('click', (event) => {
        const button = event.target.closest('button[id]');
        if (!button) return;
        console.log('Clicked button id:', button.id);
        revealPopUp(button.id);
    });
})

function revealPopUp(idTarget){
    const targetDiv = document.querySelector(`div[id-target="${idTarget}"]`);
    console.log(targetDiv)
    targetDiv.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const userMenuButton = document.querySelector('#user-menu-button');
    const userMenu = document.querySelector('#user-menu-item');

    userMenuButton.addEventListener('click', function() {
        userMenu.classList.toggle('hidden');
    });
});
