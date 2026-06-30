
document.addEventListener('DOMContentLoaded', function () {
    const dateSelect = document.getElementById('res_date');
    const startSelect = document.getElementById('start_hour');

    function updateHours() {
        const today = new Date().toISOString().split('T')[0];
        const currentHour = new Date().getHours();

        Array.from(startSelect.options).forEach(opt => {
            const hour = parseInt(opt.value);
            if (dateSelect.value === today && hour <= currentHour) {
                opt.style.display = 'none';
            } else {
                opt.style.display = 'block';
            }
        });
    }
    const equip = document.querySelector('[name="equip_id"]').value;
    const lab = document.querySelector('[name="lab_id"]').value;

    if (!equip && !lab) {
        e.preventDefault(); // Impede o envio
        alert("Por favor, selecione pelo menos um Equipamento ou um Laboratório.");
    }
    dateSelect.addEventListener('change', updateHours);
    updateHours();
});
