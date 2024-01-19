1 На домашній сторінці створити кнопку друзі, при кліку на яку відкриється модалка з вибором двох кнопок "рекомендовані" і "мої друзі"

2 Створити запит get php

3 Розмістити корисеувачів на сторінці js

Не розумію чому воно не працює, навіть консоль не показує

    <script>
        const friendsForm = document.getElementById('friendsForm');

        async function displayFriends(event) {
            event.preventDefault();

            const friendsForm = document.getElementById('friendsForm');
            friendsForm.addEventListener('submit', displayFriends);

            try {
                const response = await fetch('friends.php', {
                    method: 'POST'
                });

                console.log('response', response)

                if (response.ok) {
                    const friends = await response.json();
                    const friendsContainer = document.getElementById('friendsDataContainer');
                    friendsContainer.innerHTML = '';

                    const friendsHTML = friends.map(friend => `
                        <li>
                            <p>${friend.name}</p>
                            <p>${friend.avatar}</p>
                        </li>
                    `).join('');

                    friendsContainer.insertAdjacentHTML('beforeend', friendsHTML);
                } else {
                    throw new Error('Network response was not ok.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Помилка');
            }
        }?
