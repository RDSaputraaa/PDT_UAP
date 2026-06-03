document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    const plateInput = document.getElementById('plate_number');
    if (plateInput) {
        plateInput.addEventListener('input', function() {
            let value = this.value.toUpperCase().replace(/[^A-Z0-9 ]/g, '');
            this.value = value;
        });
    }

    const formInputs = document.querySelectorAll('input[autofocus]');
    if (formInputs.length > 0) {
        formInputs[0].focus();
    }

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (this.classList.contains('form-entry-exit') && 
                this.getAttribute('action').includes('exit')) {
            }
        });
    });
});

function refreshStats() {
    location.reload();
}

function formatTime(date) {
    const options = { 
        year: 'numeric', 
        month: '2-digit', 
        day: '2-digit', 
        hour: '2-digit', 
        minute: '2-digit' 
    };
    return new Date(date).toLocaleDateString('id-ID', options);
}
