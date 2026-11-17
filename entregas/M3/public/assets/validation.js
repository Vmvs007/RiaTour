// EXEMPLO: Add immediate feedback to native HTML validation.
// ATENCAO: The PHP checks still run for every POST.
document.querySelectorAll('form[data-validate]').forEach((form) => {
    const fields = form.querySelectorAll('input[required][type="text"], textarea[required]');
    fields.forEach((field) => {
        const validate = () => {
            let message = '';
            if (field.value !== '' && field.value.trim() === '') {
                message = 'Preencha este campo.';
            } else if (field.hasAttribute('data-positive-price')
                && field.value !== ''
                && Number(field.value.replace(',', '.')) === 0) {
                message = 'Introduza um preço superior a zero.';
            }
            field.setCustomValidity(message);
        };
        field.addEventListener('input', validate);
        validate();
    });
});
