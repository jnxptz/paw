document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdown = dropdownBtn.parentElement;
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('open');
    });

    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
}); 