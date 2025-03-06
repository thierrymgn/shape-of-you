document.addEventListener('DOMContentLoaded', function() {
    function addItemToCollection(collectionHolder) {
        const prototype = collectionHolder.dataset.prototype;
        const index = collectionHolder.dataset.index;
        const newForm = prototype.replace(/__name__/g, index);
        collectionHolder.dataset.index = parseInt(index) + 1;

        const itemDiv = document.createElement('div');
        itemDiv.classList.add('collection-item', 'flex', 'items-center', 'space-x-2', 'mb-2');
        itemDiv.innerHTML = newForm;

        const input = itemDiv.querySelector('input');
        if (input) {
            input.classList.add('w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-indigo-500');
        }

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('text-red-500', 'hover:text-red-700');
        removeButton.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        removeButton.addEventListener('click', function() {
            itemDiv.remove();
        });

        itemDiv.appendChild(removeButton);
        collectionHolder.appendChild(itemDiv);
    }

    document.querySelectorAll('.collection-holder').forEach(function(collectionHolder) {
        collectionHolder.dataset.index = collectionHolder.querySelectorAll('.collection-item').length;

        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.classList.add('mt-2', 'inline-flex', 'items-center', 'px-3', 'py-1', 'border', 'border-transparent', 'text-sm', 'leading-4', 'font-medium', 'rounded-md', 'text-white', 'bg-indigo-600', 'hover:bg-indigo-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-indigo-500');
        addButton.innerHTML = '<svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Ajouter';

        addButton.addEventListener('click', function() {
            addItemToCollection(collectionHolder);
        });

        collectionHolder.parentNode.appendChild(addButton);

        collectionHolder.querySelectorAll('.collection-item').forEach(function(item) {
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('text-red-500', 'hover:text-red-700');
            removeButton.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            removeButton.addEventListener('click', function() {
                item.remove();
            });

            item.appendChild(removeButton);
        });
    });
});
