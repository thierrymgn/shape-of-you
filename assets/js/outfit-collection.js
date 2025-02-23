document.addEventListener('DOMContentLoaded', () => {
    console.log('Script loaded');
    const addButton = document.getElementById('add-another-item');
    const container = document.getElementById('outfitItems');

    if (addButton && container) {
        let index = container.dataset.index || 0;

        addButton.addEventListener('click', () => {
            console.log('Button clicked');
            const prototype = container.dataset.prototype;
            const token = container.dataset.token;
            let newForm = prototype.replace(/__name__/g, index);

            newForm += `<input type="hidden" name="_token" value="${token}">`;

            const div = document.createElement('div');
            div.classList.add('item');
            div.innerHTML = newForm;

            addButton.parentNode.insertBefore(div, addButton);

            index++;
        });
    }
});
