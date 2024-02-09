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
Реалізувати щоб повідомлення було адресовано конкретному користувачеві

Потрібно дістати значення sender_id з таблиці messages

<!-- Потрібно дістати фото та імя з таблиці users по id і зрівняти це id з sender_id в таблиці messages якщо вони рівні то відображати коментарі з цими id === sender_id -->


<script>
    // async function loadMessages() {
        //     const messagesContainer = document.getElementById('messagesContainer');

        //     try {
        //         const response = await fetch('hack/messages/get_messages.php', {
        //             method: 'GET',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //         });

        //         if (response.ok) {
        //             const messages = await response.json();

        //             const fragment = document.createDocumentFragment();

        //             for (const message of messages.reverse()) {
        //                 const user = await getUserInfo(message.sender_id);

        //                 const messageHTML = `
        //                     <li class="message-li">
        //                         <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(user.name)}'>

        //                             <img class="message-img" src='hack/${user.avatar}' alt='${user.name}'> 

        //                             <div class="message-div">
        //                                 <div class="message-blk">
        //                                     <p class="message-name">${user.name}</p>
        //                                     <p class="message-a__text">${message.message_text}</p>
        //                                 </div>
        //                                 <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
        //                             </div>
        //                         </a>
        //                     </li>`;

        //                 fragment.appendChild(document.createRange().createContextualFragment(messageHTML));
        //             }

        //             messagesContainer.innerHTML = '';
        //             messagesContainer.appendChild(fragment);
        //         } else {
        //             console.error('Failed to fetch messages');
        //         }
        //     } catch (error) {
        //         console.error('Error in fetch request', error);
        //     }
        // }
</script>