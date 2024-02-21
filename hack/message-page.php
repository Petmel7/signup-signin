<?php
require_once __DIR__ . '/actions/helpers.php';

$currentUserId = currentUserId();

echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <ul class="message-block" id="messagesContainer"></ul>



    <script>
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

                console.log("messagesData", messagesData);

                if (Array.isArray(messagesData.unviewed_messages)) {
                    const messages = messagesData.unviewed_messages;

                    const userDataResponse = await fetch('hack/messages/get_user_by_id.php')

                    if (!userDataResponse.ok) {
                        throw new Error('Failed to fetch user info');
                    }

                    const userData = await userDataResponse.json();

                    const messagesWithUserData = messages.map(message => {
                        const sender = userData.find(user => user.id === message.sender_id);
                        return {
                            ...message,
                            user: sender
                        };
                    });
                    // Створіть об'єкт для зберігання унікальних користувачів
                    const uniqueUsers = {};
                    // Пройдіться по всім повідомленням і додайте користувачів до об'єкта uniqueUsers
                    messagesWithUserData.forEach(message => {
                        uniqueUsers[message.user.id] = message.user;
                    });
                    // Перетворіть об'єкт у масив унікальних користувачів
                    const uniqueUsersArray = Object.values(uniqueUsers);

                    const messagesHTML = uniqueUsersArray.map(user => {
                        return `<li class="message-conteaner">
                    <a class="message-a" href='index.php?page=user-page-messages&username=${encodeURIComponent(user.name)}'>
                        <img class="message-img__who-wrote message-img" src='hack/${user.avatar}' alt='${user.name}'> 
                        <div class="message-div">
                            <div class="message-blk">
                                <p class="message-name">${user.name}</p>
                                <p class="message-a__text">Sent you a message...</p>
                            </div>
                            
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
        getMessageForAuthorizedUser(currentUserId)

        // <button class = "message-a__button" onclick = "deleteMessage(${message.id}, event)" > Delete < /button>
    </script>

</body>

</html>