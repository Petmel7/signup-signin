<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
    $loggedInUserId = currentUserId();

    // var_dump("loggedInUserId", $loggedInUserId);
    // var_dump("userData", $userData);

    // // Отримання повідомлень для користувача, чию сторінку переглядаємо
    // $userMessages = getMessagesByRecipient($userData['id']);

    // var_dump("userMessages", $userMessages);

    // // Виведення повідомлень, якщо вони існують
    // if ($userMessages) {
    //     foreach ($userMessages as $message) {
    //         echo "<p>{$message['message_text']}</p>";
    //     }
    // } else {
    //     echo "<p>No messages for this user.</p>";
    // }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<script>
    let loggedInUserId = <?php echo json_encode($loggedInUserId); ?>;
    let username = <?php echo json_encode($username); ?>
</script>

<body>

    <div class="message-conteaner">
        <ul class="message-block" id="messagesContainer"></ul>

        <form>
            <label for="messageTextarea">
                <textarea class="message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
            </label>
            <button class="message-button" type="button" onclick="sendMessages('<?php echo $userData['id']; ?>', event)">Send</button>
        </form>

    </div>

    <script src="js/forwarding.js"></script>

    <script>
        const textarea = document.getElementById('messageTextarea');

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>

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

                // console.log('recipientId', recipientId);

                if (response.ok) {
                    loadMessages(parseInt('<?php echo $userData['id']; ?>'));
                    messageTextarea.value = '';
                } else {
                    alert('Failed to message');
                }
            } catch (error) {
                console.log(error);
                alert('Error in fetch request');
            }
            // return;
        }
    </script>

    <script>
        async function loadMessages(recipientId) {
            const messagesContainer = document.getElementById('messagesContainer');

            try {
                const response = await fetch('hack/messages/get_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        recipient_id: recipientId,
                    }),
                });

                console.log("recipientId", recipientId);

                if (response.ok) {
                    const messages = await response.json();
                    console.log("messages", messages);

                    if (messages.length > 0) { // перевірка наявності повідомлень
                        const fragment = document.createDocumentFragment();

                        for (const message of messages.reverse()) {
                            const user = await getUserInfo(message.sender_id);
                            console.log("user", user);

                            const messageHTML = `
                        <li class="message-li">
                            <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(user.name)}'>
                                <img class="message-img" src='hack/${user.avatar}' alt='${user.name}'> 
                                <div class="message-div">
                                    <div class="message-blk">
                                        <p class="message-name">${user.name}</p>
                                        <p class="message-a__text">${message.message_text}</p>
                                    </div>
                                    <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
                                </div>
                            </a>
                        </li>`;

                            fragment.appendChild(document.createRange().createContextualFragment(messageHTML));
                        }

                        messagesContainer.innerHTML = '';
                        messagesContainer.appendChild(fragment);
                    } else {
                        messagesContainer.innerHTML = '<p>No messages found.</p>'; // вивід повідомлення про відсутність повідомлень
                    }
                } else {
                    console.error('Failed to fetch messages');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }


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

        async function getUserInfo(loggedInUserId) {
            try {
                const response = await fetch('hack/messages/get_user_by_id.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: loggedInUserId
                    }),
                });

                console.log("get_loggedInUserId", loggedInUserId);

                if (response.ok) {
                    return await response.json();
                } else {
                    console.error('Failed to fetch user info');
                    return {};
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return {};
            }
        }
        // getUserInfo(loggedInUserId)


        // async function getUserInfo(userId) {

        //     try {
        //         const userResponse = await fetch(`hack/messages/get_user_by_id.php?id=${userId}`);
        //         if (userResponse.ok) {
        //             console.log("userResponse", userResponse);
        //             return await userResponse.json();

        //         } else {
        //             console.error('Failed to fetch user info');
        //             return {};
        //         }
        //     } catch (error) {
        //         console.error('Error in fetch request', error);
        //         return {};
        //     }
        // }

        loadMessages(parseInt('<?php echo $userData['id']; ?>'));
    </script>

    <script>
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

                loadMessages(parseInt('<?php echo $userData['id']; ?>'));

            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>

</body>

</html>