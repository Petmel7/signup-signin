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


// Авто заміна фото
document.getElementById('avatar').addEventListener('change', function() {
            document.getElementById('photoForm').submit();
});

// Модал видалення фото
function openModal() {
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        window.onclick = function(event) {
            let modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
}
        
// // Модалка для друзів
// function openFriendsModal() {
//     document.getElementById('friendsModal').style.display = 'block';
// }

// function closeFriendsModal() {
//     document.getElementById('friendsModal').style.display = 'none';
// }
