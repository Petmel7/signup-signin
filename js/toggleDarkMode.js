
function changeInputStyle(isDarkModeEnabled) {
    const searchFriendAdd = document.querySelector('.search-friend--add');
    if (searchFriendAdd) { // перевіряємо, чи елемент існує
        if (isDarkModeEnabled) {
            searchFriendAdd.classList.add('background-input');
        } else {
            searchFriendAdd.classList.remove('background-input');
        }
    }
}

// function toggleChangeInputStyle(isDarkModeEnabled) {
//     const searchFriendAdd = document.querySelector('.search-friend--add');
//     if (searchFriendAdd) {
//         if (isDarkModeEnabled) {
//             searchFriendAdd.classList.toggle('background-input');
//         }
//     }
// }

// function modalChangeTheme(isDarkModeEnabled) {
//     document.querySelectorAll('.modal-content').forEach(function (modalContent) {
//         if (isDarkModeEnabled) {
//             if (isDarkModeEnabled) {
//                 modalContent.classList.add('modal-change--theme');
//             } else {
//                 modalContent.classList.remove('modal-change--theme');
//             }
//         }
//     });
// }

function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle('dark-mode');

    const isDarkModeEnabled = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkModeEnabled);

    const darkModeButton = document.getElementById('darkModeIcon');
    const whiteModeButton = document.getElementById('whiteModeIcon');

    // Приховуємо обидві іконки
    darkModeButton.style.display = 'none';
    whiteModeButton.style.display = 'none';

    // Відображаємо одну з іконок залежно від наявності темної теми
    if (isDarkModeEnabled) {
        whiteModeButton.style.display = 'block';
        document.querySelectorAll('.change-color--title').forEach(function (title) {
            title.classList.add('white-text');
        });
    } else {
        darkModeButton.style.display = 'block';
        document.querySelectorAll('.change-color--title').forEach(function (title) {
            title.classList.remove('white-text');
        });
    }

    // Зміна стилю введення
    loadMessages(loggedInUserId, recipientId);
    changeInputStyle(isDarkModeEnabled);
    // modalChangeTheme(isDarkModeEnabled);
}

document.addEventListener('DOMContentLoaded', function () {
    const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
    const darkModeButton = document.getElementById('darkModeIcon');
    const whiteModeButton = document.getElementById('whiteModeIcon');

    // Приховуємо обидві іконки
    darkModeButton.style.display = 'none';
    whiteModeButton.style.display = 'none';

    // Відображаємо одну з іконок залежно від наявності темної теми
    if (isDarkModeEnabled) {
        document.body.classList.add('dark-mode');
        whiteModeButton.style.display = 'block';
        document.querySelectorAll('.change-color--title').forEach(function (title) {
            title.classList.add('white-text');
        });
    } else {
        darkModeButton.style.display = 'block';
    }

    // Зміна стилю введення
    loadMessages(loggedInUserId, recipientId);
    changeInputStyle(isDarkModeEnabled);
    // modalChangeTheme(isDarkModeEnabled);
});

// const darkModeButton = document.getElementById('darkModeIcon');
// darkModeButton.addEventListener('click', toggleDarkMode);


