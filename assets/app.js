import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', function(){
    console.log(document.querySelector('#sort-button-wardrobe-item'));
    document.addEventListener('click', (event) => {
        // const clickedElement = event.target;
        // if (!clickedElement.id) return;
        // console.log(clickedElement.id)
        // revealPopUp(clickedElement.id)
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