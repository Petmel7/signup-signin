Зробити підписку на друга

Для реалізації функціоналу підписки на друга, вам знадобиться взаємодія з базою даних та робота зі скриптами на стороні клієнта та сервера. Щоб вам було зручніше розробляти цю функціональність, ось загальний план:

База Даних:

Створіть в базі даних таблицю для відстеження підписок. Наприклад, таблицю "subscriptions" із колонками, такими як "id", "subscriber_id" (користувач, який підписується), та "target_id" (користувач, на якого підписуються).

Серверна Частина (PHP):

Розробіть PHP-скрипти, які взаємодіють з базою даних для:
Додавання підписки.
Видалення підписки.
Отримання списку підписчиків для конкретного користувача.
Отримання списку користувачів, на які підписаний конкретний користувач.

Клієнтська Частина (JavaScript):

На сторінці профілю користувача додайте кнопку або елемент для підписки чи відписки від іншого користувача.
Використовуйте AJAX або Fetch для виклику відповідних серверних методів при кліканні на кнопку підписки.
Оновлюйте інтерфейс користувача в реальному часі, показуючи зміни в списку підписчиків та підписок.

Безпека:

Забезпечте валідацію та санітацію вхідних даних, щоб уникнути SQL-ін'єкцій та інших атак.
Враховуйте права доступу: користувач може підписуватися лише на тих, хто публічно доступний для підписки.

Зручність для Користувача:

Додайте зручний інтерфейс для перегляду підписок та підписчиків на сторінці користувача.
Виводьте зміст, який дозволяє користувачеві взаємодіяти з підписками (наприклад, перегляд новин від підписаних користувачів).
Нехай цей загальний план послужить вам основою для подальшої розробки вашого функціоналу підписки.

//========================================================

Зробити можливість перегляду підписок другого користувача

В мене така ось проблема

<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <form class="search-friend" id="searchForm">
        <input class="search-friend__input" type="text" id="mySearchInput" name="mySearchInput" placeholder="Search" required oninput="mySearchFriends()">
    </form>

    <ul id="hisFriendsDataContainer"></ul>

    <script>
        async function hisGetFriendsData(hisUserId) {
            try {
                const response = await fetch(`hack/subscription/get_his_subscriptions.php?user_id=${hisUserId}`);

                if (response.ok) {
                    const hisSubscriptions = await response.json();
                    // Обробка отриманих підписок, наприклад, вивід на сторінку
                    console.log(hisSubscriptions);
                } else {
                    console.error('Failed to fetch user subscriptions');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }

        hisGetFriendsData(<?php echo $userData['id']; ?>)
    </script>

    такі помилки
    <b>Warning</b>:  Undefined variable $userData in <b>C:\xampp\htdocs\signup-signin\hack\his-friends-list.php</b> on line <b>84</b><br />

<br />
<b>Warning</b>:  Trying to access array offset on value of type null in <b>C:\xampp\htdocs\signup-signin\hack\his-friends-list.php</b> on line <b>84</b><br />

</body>

</html>

<!-- ЗБЕРЕЖЕННЯ КНОПОК БЕЗ ЛОКАЛСТОРЕДЖ -->

<!-- <script>
        // Функція для зміни стану кнопок
        function updateButtons(isSubscribed) {
            const subscribeButton = document.getElementById('subscribeButton');
            const unsubscribeButton = document.getElementById('unsubscribeButton');

            if (isSubscribed) {
                subscribeButton.style.display = 'none';
                unsubscribeButton.style.display = 'block';
            } else {
                unsubscribeButton.style.display = 'none';
                subscribeButton.style.display = 'block';
            }
        }

        // Функція для підписки на користувача
        async function subscribe(userId) {
            try {
                const response = await fetch('hack/subscription/add_subscription.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        subscriber_id: loggedInUserId,
                        target_user_id: userId
                    }),
                });

                if (response.ok) {
                    // Оновити інтерфейс
                    updateButtons(true);
                } else {
                    alert('Failed to subscribe');
                }
            } catch (error) {
                console.log(error);
                alert('Error in fetch request');
            }
        }

        // Функція для відписки від користувача
        async function unsubscribe(userId) {
            try {
                const response = await fetch('hack/subscription/remove_subscription.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        subscriber_id: loggedInUserId,
                        target_user_id: userId
                    }),
                });

                if (response.ok) {
                    // Оновити інтерфейс
                    updateButtons(false);
                } else {
                    alert('Failed to unsubscribe');
                }
            } catch (error) {
                console.log(error);
                alert('Error in fetch request');
            }
        }

        // Перевірка і встановлення стану при завантаженні сторінки
        document.addEventListener('DOMContentLoaded', async function() {
            // Отримати інформацію про підписки поточного користувача
            const currentUserSubscriptions = await getCurrentUserSubscriptions();

            console.log('currentUserSubscriptions', currentUserSubscriptions);

            // Перевірка і встановлення стану кнопок на основі інформації від сервера
            const userId = loggedInUserId;

            console.log('userId', userId);
            console.log('loggedInUserId', loggedInUserId);

            const isSubscribed = currentUserSubscriptions.some(user => user.id === userId);
            updateButtons(isSubscribed);
        });

        // Функція для отримання інформації про підписки поточного користувача
        async function getCurrentUserSubscriptions() {
            try {
                const response = await fetch('hack/subscription/get_subscriptions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: loggedInUserId,
                    }),
                });

                if (response.ok) {
                    const result = await response.json();
                    return result || [];
                } else {
                    console.error('Failed to fetch user subscriptions');
                    return [];
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return [];
            }
        }
    </script> -->
