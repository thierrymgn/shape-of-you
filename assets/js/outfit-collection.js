document.addEventListener('DOMContentLoaded', () => {
    console.log('Script loaded');
    const addButton = document.getElementById('add-another-item');
    const container = document.getElementById('outfitItems');

    if (addButton && container) {
        let index = parseInt(container.dataset.index || 0);

        const addRemoveButton = (item) => {
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'Supprimer ce vêtement';
            removeBtn.classList.add('remove-item', 'mt-3', 'px-3', 'py-1', 'bg-red-500', 'text-white', 'rounded-md', 'hover:bg-red-600', 'text-sm');
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
            const newForm = prototype.replace(/__name__/g, index);

            const div = document.createElement('div');
            div.classList.add('item', 'p-4', 'border', 'border-gray-200', 'rounded-md', 'bg-gray-50', 'mb-4');
            div.innerHTML = newForm;

            const inputs = div.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.classList.add('w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
            });

            const labels = div.querySelectorAll('label');
            labels.forEach(label => {
                label.classList.add('block', 'text-sm', 'font-medium', 'text-gray-700', 'mb-1');
            });

            const formGroup = document.createElement('div');
            formGroup.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-4');

            while (div.firstChild) {
                const childDiv = document.createElement('div');
                childDiv.appendChild(div.firstChild);
                formGroup.appendChild(childDiv);
            }

            div.appendChild(formGroup);
            addRemoveButton(div);

            container.appendChild(div);

            index++;
        });
    }
});
