// function toggleDarkMode() {
//     const body = document.body;
//     const isDarkMode = body.classList.toggle('dark-mode');

//     const isDarkModeEnabled = body.classList.contains('dark-mode');
//     localStorage.setItem('darkMode', isDarkModeEnabled);
// }

// document.addEventListener('DOMContentLoaded', function () {
//     const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
//     const darkModeButton = document.getElementById('darkModeButton');
//     const whiteModeButton = document.getElementById('whiteModeButton');

//     if (isDarkModeEnabled) {
//         document.body.classList.add('dark-mode');
//     }

// isDarkModeEnabled ? (
//     darkModeButton.style.display = 'none',
//     whiteModeButton.style.display = 'block'
// ) : (
//     darkModeButton.style.display = 'block',
//     whiteModeButton.style.display = 'none'
// );
// });

// const darkModeButton = document.querySelector('.modeButton');
// darkModeButton.addEventListener('click', toggleDarkMode);


function toggleDarkMode() {
    const body = document.body;
    const isDarkMode = body.classList.toggle('dark-mode');

    const isDarkModeEnabled = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkModeEnabled);

    const darkModeButton = document.getElementById('darkModeButton');
    const whiteModeButton = document.getElementById('whiteModeButton');

    if (isDarkModeEnabled) {
        darkModeButton.style.display = 'block';
        whiteModeButton.style.display = 'none';
    } else {
        darkModeButton.style.display = 'none';
        whiteModeButton.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
    const darkModeButton = document.getElementById('darkModeButton');
    const whiteModeButton = document.getElementById('whiteModeButton');

    if (isDarkModeEnabled) {
        document.body.classList.add('dark-mode');
        darkModeButton.style.display = 'none';
        whiteModeButton.style.display = 'block';
    }
});
