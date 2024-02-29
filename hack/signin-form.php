<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

checkGuest();
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <h1 class="bamboo">Bamboo</h1>

    <form id="signinForm" class="form">
        <p class="form-title">Signin</p>

        <label class="form-label" for="">E-mail
            <input class="form-input req_" type="text" placeholder="MacaulayCulkin@gmail.com" name="email">
        </label>

        <label class="form-label" for="">Password
            <div class="custom-placeholder">
                <input class="form-input req_" type="password" placeholder="" name="password">
                <span class="placeholder-text">************</span>
            </div>
        </label>

        <button type="button" onclick="signinSubmitForm()">Continue</button>

        <p class="form-account">I don't have an <a href="index.php?page=signup">account</a> yet</p>
    </form>

    <script src="js/forwarding.js"></script>

    <script>
        async function signinSubmitForm() {
            const form = document.getElementById("signinForm");
            const formData = new FormData(form);
            try {
                const response = await fetch('php/signin.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    redirectToHome();
                } else {
                    alert("Authentication failed");
                    addErrorClassToRequiredInputs();
                }
            } catch (error) {
                console.error("Помилка при аутентифікації", error);
            }
        }
    </script>

    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     let passwordInputs = document.querySelectorAll('input[type="password"]');
        //     console.log("passwordInputs", passwordInputs)
        //     passwordInputs.forEach(function(input) {
        //         if (input.value.trim() !== '') {
        //             input.nextElementSibling.style.display = 'none';
        //         }
        //     });
        // });

        // document.addEventListener("DOMContentLoaded", function() {
        //     let passwordInput = document.querySelector('input[type="password"]');
        //     console.log("passwordInput", passwordInput)

        //     let placeholderText = passwordInput.nextElementSibling;
        //     console.log("placeholderText", placeholderText)

        //     if (passwordInput.value.trim() !== '') {
        //         placeholderText.style.display = 'none'; // ховаємо вказівник
        //     }
        // });

        // document.addEventListener("DOMContentLoaded", function() {
        //     var passwordInput = document.querySelector('input[type="password"]');
        //     var placeholderText = passwordInput.nextElementSibling;

        //     // Функція, яка перевіряє, чи поле вводу порожнє і застосовує стилі до вказівника
        //     function togglePlaceholder() {
        //         if (passwordInput.value.trim() !== '') {
        //             placeholderText.style.display = 'none'; // ховаємо вказівник, якщо поле вводу не порожнє
        //         } else {
        //             placeholderText.style.display = 'inline-block'; // показуємо вказівник, якщо поле вводу порожнє
        //         }
        //     }

        //     // Викликаємо функцію togglePlaceholder при завантаженні сторінки і при введенні тексту
        //     togglePlaceholder();
        //     passwordInput.addEventListener('input', togglePlaceholder);
        // });

        // document.addEventListener("DOMContentLoaded", function() {
        //     var passwordInput = document.querySelector('input[type="password"]');
        //     var placeholderText = passwordInput.nextElementSibling;

        //     function togglePlaceholder() {
        //         if (passwordInput.value.trim() !== '') {
        //             placeholderText.classList.add('placeholder-hidden');
        //         } else {
        //             placeholderText.classList.remove('placeholder-hidden');
        //         }
        //     }

        //     togglePlaceholder();
        //     passwordInput.addEventListener('input', togglePlaceholder);
        // });
    </script>

    <script src="js/validation.js"></script>

</body>

</html>