document.addEventListener('DOMContentLoaded', function() {
    
    function filtrarTabela(inputId, tableId) {
        const input = document.getElementById(inputId);
        const table = document.getElementById(tableId);
        
        if (!input || !table) return;

        input.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase().trim();
            let rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                let rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(filter) ? '' : 'none';
            });
        });
    }

    filtrarTabela('searchEquipments', 'tableEquipments');
    filtrarTabela('searchLabs', 'tableLabs');
    filtrarTabela('searchInput', 'tableUsers'); 
});