<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
    $loggedInUserId = currentUserId();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<script>
    let loggedInUserId = <?php echo json_encode($loggedInUserId); ?>;
</script>

<body>
    <div class="account">
        <img class="account-img" src="hack/<?php echo $userData['avatar']; ?>" width="200px" height="200px" alt="<?php echo $userData['name']; ?>">
        <h1 class="account-title"><?php echo $userData['name']; ?></h1>

        <div class="subscription" id="subscription-buttons">
            <button class="subscription-buttons" id="subscribeButton" onclick="subscribe(<?php echo $userData['id']; ?>)">Subscribe</button>
            <button class="subscription-buttons" id="unsubscribeButton" onclick="unsubscribe(<?php echo $userData['id']; ?>)">Unsubscribe</button>
        </div>

        <button class="subscription-buttons" type="button" onclick="redirectionHisFriends(<?php echo $userData['id']; ?>)">His friends</button>

        <ul class="message-block" id="messagesContainer"></ul>

        <form>
            <label for="">
                <textarea class="message-textarea search-friend__input" name="" id="messageTextarea" placeholder="Write your message" cols="30" rows="10"></textarea>
            </label>
            <button class="message-button" type="button" onclick="sendMessages(<?php echo $userData['id']; ?>, event)">Send</button>
        </form>

    </div>

    <script src="js/subscribers.js"></script>
    <script src="js/forwarding.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getCurrentUserSubscriptions(<?php echo $userData['id']; ?>);
        });
    </script>
    <!-- <script src="messages-js/messages.js"></script> -->
    <script>
        async function sendMessages(recipientId, event) {
            event.preventDefault();

            const messageTextarea = document.getElementById('messageTextarea');
            const messageText = messageTextarea.value.trim();

            if (messageText === '') {
                alert('Please enter the text of the message.');
                return;
            }

            try {
                const response = await fetch('hack/messages/messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        sender_id: loggedInUserId,
                        recipient_id: recipientId,
                        message_text: messageText
                    }),
                });

                if (response.ok) {
                    console.log('responseMessage', response);
                    loadMessages();
                    messageTextarea.value = '';
                } else {
                    alert('Failed to message');
                }
            } catch (error) {
                console.log(error);
                alert('Error in fetch request');
            }
        }
    </script>

    <script>
        const messagesContainer = document.getElementById('messagesContainer');

        // async function loadMessages() {
        //     try {
        //         const response = await fetch('hack/messages/get_messages.php', {
        //             method: 'GET',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //         });

        //         if (response.ok) {
        //             const messages = await response.json();

        //             console.log('messages', messages);

        //             const messagesHTML = messages.reverse().map(message => `
        //                 <li class="message-li">
        //                     <p>${message.message_text}</p>
        //                     <button class="message-li__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
        //                 </li>
        //             `).join('');

        //             messagesContainer.innerHTML = messagesHTML;
        //             // messagesContainer.insertAdjacentHTML('', messagesHTML);

        //         } else {
        //             console.error('Failed to fetch messages');
        //         }
        //     } catch (error) {
        //         console.error('Error in fetch request', error);
        //     }
        // }

        // loadMessages();

        // async function loadMessages() {
        //     try {
        //         const response = await fetch('hack/messages/get_messages.php', {
        //             method: 'GET',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //         });

        //         if (response.ok) {
        //             const messages = await response.json();

        //             console.log('messages', messages);

        //             // Вивести кожне повідомлення та інформацію про користувача
        //             for (const message of messages) {
        //                 const user = await getUserInfo(message.sender_id);
        //                 console.log('User Info:', user);

        //                 const messageHTML = `
        //                     <li>
        //                         <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(user.name)}'>
        //                             <div>
        //                                 <img class="message-img" src='hack/${user.avatar}' alt='${user.name}'>
        //                                 <p class="">${user.name}</p>
        //                             </div>
        //                             <p class="message-a__text">${message.message_text}</p>
        //                             <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
        //                         </a>
        //                     </li>`;

        //                 messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
        //             }
        //         } else {
        //             console.error('Failed to fetch messages');
        //         }
        //     } catch (error) {
        //         console.error('Error in fetch request', error);
        //     }
        // }
        // loadMessages();

        async function loadMessages() {
            try {
                const response = await fetch('hack/messages/get_messages.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    const messages = await response.json();

                    console.log('messages', messages);

                    // Очищення контейнера перед вставкою нових повідомлень
                    messagesContainer.innerHTML = '';

                    // Вивести кожне повідомлення та інформацію про користувача
                    for (const message of messages) {
                        const user = await getUserInfo(message.sender_id);
                        console.log('User Info:', user);

                        const messageHTML = `
                    <li>
                        <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(user.name)}'>
                            <div>
                                <img class="message-img" src='hack/${user.avatar}' alt='${user.name}'>
                                <p class="">${user.name}</p>
                            </div>
                            <p class="message-a__text">${message.message_text}</p>
                            <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
                        </a>
                    </li>`;

                        messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
                    }
                } else {
                    console.error('Failed to fetch messages');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }
        loadMessages();


        async function getUserInfo(userId) {
            try {
                const userResponse = await fetch(`hack/messages/get_user_by_id.php?id=${userId}`);
                if (userResponse.ok) {
                    return await userResponse.json();

                    console.log("userId", userId)
                } else {
                    console.error('Failed to fetch user info');
                    return {};
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return {};
            }
        }
    </script>

    <script>
        // Функція для видалення повідомлення
        async function deleteMessage(messageId, event) {
            event.preventDefault();
            try {
                const response = await fetch('hack/messages/delete_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        message_id: messageId
                    }),
                });

                console.log('messageId', messageId);

                loadMessages();

            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>





    <!-- <li class="">
        <a href='index.php?page=user&username=${encodeURIComponent(user.name)}'>
            <img class="" src='hack/${user.avatar}' alt='${user.name}'>
            <p class="">${user.name}</p>
            <p>${message.message_text}</p>
            <button onclick="deleteMessage(${message.id}, event)">Delete</button>
        </a>
    </li>`; -->

</body>

</html>