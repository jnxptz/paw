// client.js

document.addEventListener('DOMContentLoaded', function () {
  const dropdownBtn = document.getElementById('dropdownBtn');
  const dropdownMenu = document.getElementById('dropdownMenu');
  if (!dropdownBtn || !dropdownMenu) return;
  const dropdown = dropdownBtn.parentElement;

  dropdownBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    dropdown.classList.toggle('open');
  });

  // Allow clicking links inside dropdown to navigate; close after click
  dropdownMenu.addEventListener('click', function () {
    dropdown.classList.remove('open');
  });

  document.addEventListener('click', function (e) {
    if (!dropdown.contains(e.target)) {
      dropdown.classList.remove('open');
    }
  });
});