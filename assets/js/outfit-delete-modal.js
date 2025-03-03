document.addEventListener('DOMContentLoaded', function() {

    const deleteButtons = document.querySelectorAll('.delete-trigger');
    const cancelBtn = document.getElementById('cancelDelete');
    const modal = document.getElementById('deleteModal');

    if (!modal) return;

    const deleteForm = document.getElementById('deleteForm');
    const csrfToken = document.getElementById('csrfToken');
    const deleteMessage = document.getElementById('deleteMessage');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const outfitId = this.getAttribute('data-outfit-id');
            const outfitName = this.getAttribute('data-outfit-name');
            const tokenValue = this.getAttribute('data-csrf-token');

            deleteMessage.textContent = `Êtes-vous sûr de vouloir supprimer la tenue "${outfitName}" ? Cette action est irréversible.`;

            const baseUrl = deleteForm.getAttribute('data-base-url');
            deleteForm.action = baseUrl.replace('OUTFIT_ID', outfitId);

            csrfToken.value = tokenValue;

            modal.classList.remove('hidden');
        });
    });

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
