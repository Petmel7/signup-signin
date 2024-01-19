<!-- <!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <div id="friendsDataContainer"></div>

    <script>
        function fetchFriendsData() {
            // Використовуйте AJAX або Fetch API для виклику серверного скрипта friends.php
            // Тут наведено приклад використання Fetch API:

            fetch('friends.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    // Можливо, передайте параметри, якщо потрібно
                    body: JSON.stringify({}),
                })
                .then(response => response.json())
                .then(data => displayFriendsData(data))
                .catch(error => console.error('Error fetching friends data:', error));
        }

        function displayFriendsData(data) {
            // Отримані дані буде масив користувачів. Можете використовувати ці дані для відображення на сторінці.

            // Наприклад, використовуйте контейнер friendsDataContainer для відображення фото та імен користувачів.
            const friendsDataContainer = document.getElementById('friendsDataContainer');

            // Очищаємо контейнер перед відображенням нових даних
            friendsDataContainer.innerHTML = '';

            // Проходимося по кожному користувачеві і відображаємо його фото та ім'я
            data.forEach(user => {
                const userElement = document.createElement('div');
                userElement.classList.add('user');

                const imgElement = document.createElement('img');
                imgElement.src = user.photo;
                imgElement.alt = user.name;

                const nameElement = document.createElement('p');
                nameElement.textContent = user.name;

                userElement.appendChild(imgElement);
                userElement.appendChild(nameElement);

                friendsDataContainer.appendChild(userElement);
            });
        }
    </script>


</body>

</html> -->