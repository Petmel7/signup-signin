
// function toggleDarkMode() {
//     const body = document.body;
//     body.classList.toggle('dark-mode');

//     const isDarkModeEnabled = body.classList.contains('dark-mode');
//     localStorage.setItem('darkMode', isDarkModeEnabled);

//     const darkModeButton = document.getElementById('darkModeIcon');
//     const whiteModeButton = document.getElementById('whiteModeIcon');

//     // Приховуємо обидві іконки
//     darkModeButton.style.display = 'none';
//     whiteModeButton.style.display = 'none';

//     // Відображаємо одну з іконок залежно від наявності темної теми
//     if (isDarkModeEnabled) {
//         whiteModeButton.style.display = 'block';
//         document.querySelectorAll('.change-color--title').forEach(function (title) {
//             title.classList.add('white-text');
//         });
//     } else {
//         darkModeButton.style.display = 'block';
//         document.querySelectorAll('.change-color--title').forEach(function (title) {
//             title.classList.remove('white-text');
//         });
//     }
// }

// document.addEventListener('DOMContentLoaded', function () {
//     const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
//     const darkModeButton = document.getElementById('darkModeIcon');
//     const whiteModeButton = document.getElementById('whiteModeIcon');

//     // Приховуємо обидві іконки
//     darkModeButton.style.display = 'none';
//     whiteModeButton.style.display = 'none';

//     // Відображаємо одну з іконок залежно від наявності темної теми
//     if (isDarkModeEnabled) {
//         document.body.classList.add('dark-mode');
//         whiteModeButton.style.display = 'block';
//         document.querySelectorAll('.change-color--title').forEach(function (title) {
//             title.classList.add('white-text');
//         });
//     } else {
//         darkModeButton.style.display = 'block';
//     }
// });






// function toggleDarkMode() {
//     const body = document.body;
//     body.classList.toggle('dark-mode');

//     const isDarkModeEnabled = body.classList.contains('dark-mode');
//     localStorage.setItem('darkMode', isDarkModeEnabled);

//     const darkModeButton = document.getElementById('darkModeIcon');
//     const whiteModeButton = document.getElementById('whiteModeIcon');

//     // Приховуємо обидві іконки
//     darkModeButton.style.display = 'none';
//     whiteModeButton.style.display = 'none';

//     // Відображаємо одну з іконок залежно від наявності темної теми
//     if (isDarkModeEnabled) {
//         whiteModeButton.style.display = 'block';
//         document.querySelectorAll('.change-color--title').forEach(function (title) {
//             title.classList.add('white-text');
//         });
//         const searchFriendAdd = document.querySelector('.search-friend--add');
//         searchFriendAdd.classList.add('background-input');
//     } else {
//         darkModeButton.style.display = 'block';
//         document.querySelectorAll('.change-color--title').forEach(function (title) {
//             title.classList.remove('white-text');
//         });
//         const searchFriendAdd = document.querySelector('.search-friend--add');
//         searchFriendAdd.classList.remove('background-input');
//     }
// }

// document.addEventListener('DOMContentLoaded', function () {
//     const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
//     const darkModeButton = document.getElementById('darkModeIcon');
//     const whiteModeButton = document.getElementById('whiteModeIcon');

//     // Приховуємо обидві іконки
//     darkModeButton.style.display = 'none';
//     whiteModeButton.style.display = 'none';

//     // Відображаємо одну з іконок залежно від наявності темної теми
//     if (isDarkModeEnabled) {
//         document.body.classList.add('dark-mode');
//         whiteModeButton.style.display = 'block';
//         document.querySelectorAll('.change-color--title').forEach(function (title) {
//             title.classList.add('white-text');
//         });
//         const searchFriendAdd = document.querySelector('.search-friend--add');
//         searchFriendAdd.classList.add('background-input');
//     } else {
//         darkModeButton.style.display = 'block';
//     }
// });

// const darkModeButton = document.getElementById('darkModeIcon');
// darkModeButton.addEventListener('click', toggleDarkMode);






// function changeInputStyle(isDarkModeEnabled) {
//     const searchFriendAdd = document.querySelector('.search-friend--add');
//     if (isDarkModeEnabled) {
//         searchFriendAdd.classList.add('background-input');
//     } else {
//         searchFriendAdd.classList.remove('background-input');
//     }
// }

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
    changeInputStyle(isDarkModeEnabled);
    loadMessages(loggedInUserId, recipientId);
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
    changeInputStyle(isDarkModeEnabled);
});

// const darkModeButton = document.getElementById('darkModeIcon');
// darkModeButton.addEventListener('click', toggleDarkMode);


