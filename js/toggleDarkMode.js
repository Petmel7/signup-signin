
function changeInputStyle(isDarkModeEnabled) {
    const searchFriendAdd = document.querySelector('.search-friend--add');
    if (searchFriendAdd) {
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

function toggleDarkModeAndRefresh() {
    toggleDarkMode();
    location.reload();
}

