function addErrorClassToRequiredInputs() {
    const requiredInputs = document.querySelectorAll('.req_');

    requiredInputs.forEach(input => {
        if (input.value.trim() === '') {
            input.classList.add('error');
            input.placeholder = 'Fill in this field';
        }
    });
}

const requiredInputs = document.querySelectorAll('.req_');
requiredInputs.forEach(input => {
    input.addEventListener('input', () => {
        validateInput(input);
    });
});

function validateInput(input) {
    if (input.value.trim() !== '') {
        input.classList.remove('error');
    }
}