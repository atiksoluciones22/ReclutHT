let cedulaInputs = document.querySelectorAll('.cedula-input');
if (cedulaInputs.length > 0) {
    cedulaInputs.forEach((input) => {
        input.addEventListener('input', function (e) {
            // Remove all non-digit characters
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,7})(\d{0,1})/);
            e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    });
}
