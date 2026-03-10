// script.js — Kun brukt for live søk i demo-modus
// Når PHP er koblet på, håndteres søk i index.php via GET-parameter

const searchInput = document.getElementById('searchInput');
const tableBody   = document.getElementById('customerTableBody');

if (searchInput && tableBody) {
  searchInput.addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const rows  = tableBody.querySelectorAll('tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(query) ? '' : 'none';
    });
  });
}
