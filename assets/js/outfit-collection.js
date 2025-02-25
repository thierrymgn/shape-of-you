document.addEventListener('DOMContentLoaded', () => {
    console.log('Script loaded');
    const addButton = document.getElementById('add-another-item');
    const container = document.getElementById('outfitItems');

    if (addButton && container) {
        let index = parseInt(container.dataset.index || 0);

        const addRemoveButton = (item) => {
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'Supprimer ce vêtement';
            removeBtn.classList.add('remove-item', 'bg-red-500', 'hover:bg-red-700', 'text-white', 'font-bold', 'py-1', 'px-3', 'rounded', 'mt-2');
            removeBtn.type = 'button';

            removeBtn.addEventListener('click', function() {
                item.remove();
            });

            item.appendChild(removeBtn);
        };

        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.item').remove();
            });
        });

        addButton.addEventListener('click', () => {
            const prototype = container.dataset.prototype;
            let newForm = prototype.replace(/__name__/g, index);

            const div = document.createElement('div');
            div.classList.add('item', 'mb-4', 'p-3', 'border', 'rounded');
            div.innerHTML = newForm;

            addRemoveButton(div);

            container.insertBefore(div, addButton);

            index++;
        });
    }
});
