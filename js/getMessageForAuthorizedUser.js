
async function getMessageForAuthorizedUser(currentUserId) {
    const messagesContainer = document.getElementById('messagesContainer');
    try {
        const messagesResponse = await fetch('hack/actions/get-message-for-authorized-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                recipient_id: currentUserId
            }),
        });

        if (!messagesResponse.ok) {
            throw new Error('Failed to fetch messages');
        }

        const messagesData = await messagesResponse.json();

        if (Array.isArray(messagesData.message_authors)) {
            const messages = messagesData.message_authors;

            const userDataResponse = await fetch('hack/messages/get_user_by_id.php')

            if (!userDataResponse.ok) {
                throw new Error('Failed to fetch user info');
            }

            const userData = await userDataResponse.json();

            const messagesWithUserData = messages.map(message => {
                const sender = userData.find(user => user.id === message.sender_id);
                return {
                    ...message,
                    ...sender
                };
            });

            console.log("messagesWithUserData", messagesWithUserData)

            const messageCounts = {}; // Об'єкт для збереження кількості повідомлень для кожного користувача

            // Перебір повідомлень та підрахунок кількості повідомлень для кожного користувача
            messagesWithUserData.forEach(message => {
                const senderId = message.sender_id;
                if (!messageCounts[senderId]) {
                    messageCounts[senderId] = 0;
                    console.log("messageCounts", messageCounts)
                }
                if (message.viewed === 0) { // Якщо повідомлення не переглянуте, збільшуємо лічильник
                    messageCounts[senderId]++;
                }
            });

            // Створення порожнього масиву для збереження унікальних sender_id
            const uniqueUsers = [];

            // Прохід через кожне повідомлення
            messagesWithUserData.forEach(message => {
                // Перевірка, чи sender_id є унікальним і не міститься в uniqueUsers
                if (!uniqueUsers.some(user => user.id === message.sender_id)) {
                    // Додавання sender_id до uniqueUsers
                    uniqueUsers.push(message);
                }
            });

            console.log("uniqueUsers", uniqueUsers)

            // Відображення кількості непереглянутих повідомлень біля кожного користувача
            const messagesHTML = uniqueUsers.map(user => {
                const unreadCount = messageCounts[user.id] ?? 0; // Отримуємо кількість непереглянутих повідомлень

                console.log("unreadCount", unreadCount)

                return `
            <li class="message-conteaner">
                <a class="message-a" href='index.php?page=user-page-messages&username=${encodeURIComponent(user.name)}'>
                    <img class="message-img__who-wrote message-img" src='hack/${user.avatar}' alt='${user.name}'>
                    <div class="message-div">
                        <div class="message-blk">
                            <p class="message-name">${user.name}</p>
                            <p class="message-a__text">Sent you a message...</p>
                        </div>
                        <span class="message-badge" style="display: ${unreadCount > 0 ? 'block' : 'none'}">${unreadCount > 10 ? '9+' : unreadCount}</span>
                        <button class="message-a__button" onclick="deleteMessage(${user.id}, event)">Delete</button>
                    </div>
                </a>
            </li>`;


            }).join('');


            messagesContainer.innerHTML = messagesHTML;


        } else {
            console.error('No messages found');
        }
    } catch (error) {
        console.error('Error in fetch request', error);
    }
}
getMessageForAuthorizedUser(currentUserId);
