// document.addEventListener('DOMContentLoaded', function () {
//     const inputs = document.querySelectorAll('.form-input');

//     inputs.forEach(function (input) {
//         input.addEventListener('focus', function () {
//             input.nextElementSibling.classList.add('focused');
//         });

//         input.addEventListener('blur', function () {
//             if (!input.value) {
//                 input.nextElementSibling.classList.remove('focused');
//             }
//         });
//     });
// });

document.getElementById('avatar').addEventListener('change', function() {
            document.getElementById('photoForm').submit();
        });