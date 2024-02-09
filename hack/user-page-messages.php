<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
    $loggedInUserId = currentUserId();
    // $senderById = getMessagesBysenderId($userData['id']);
    $recipientId = $userData['id'];
    // Передаємо дані на клієнтську сторону в форматі JSON
    echo "<script>let recipientId = " . json_encode($recipientId) . ";</script>";
    echo "<script>let loggedInUserId = " . json_encode($loggedInUserId) . ";</script>";
    // echo "<script>let senderById = " . json_encode($senderById) . ";</script>";
}

// echo "<pre>";
// print_r($username);
// print_r($userData);
// print_r($senderById);
// print_r($senderById);
// echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

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

                if (response.ok) {
                    loadMessages(25, recipientId);

                    messageTextarea.value = '';
                } else {
                    alert('Failed to message');
                }
            } catch (error) {
                console.log(error);
                alert('Error in fetch request');
            }
            return;
        }
    </script>



    <!-- <script>
        async function loadMessages(recipientId, senderId) {
            const messagesContainer = document.getElementById('messagesContainer');
            try {
                const messagesResponse = await fetch('hack/messages/get_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        recipient_id: recipientId,
                    }),
                });

                if (!messagesResponse.ok) {
                    throw new Error('Failed to fetch messages');
                }

                const messagesData = await messagesResponse.json();

                if (Array.isArray(messagesData.success)) {
                    const messages = messagesData.success;
                    console.log("messages", messages);

                    const promises = messages.map(async message => {
                        const senderId = message.sender_id;
                        const userDataResponse = await fetch(`hack/messages/get_user_by_id.php?id=${senderId}`);
                        const userData = await userDataResponse.json();
                        console.log("userData", userData);
                        return {
                            ...userData,
                            ...message
                        };
                    });

                    Promise.all(promises).then(messagesWithUserData => {
                        const messagesHTML = messagesWithUserData.map(message =>
                            `<li class="message-li">
                        <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(message.name)}'>
                            <img class="message-img" src='hack/${message.avatar}' alt='${message.name}'> 
                            <div class="message-div">
                                <div class="message-blk">
                                    <p class="message-name">${message.name}</p>
                                    <p class="message-a__text">${message.message_text}</p>
                                </div>
                                <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
                            </div>
                        </a>
                    </li>`
                        ).join('');
                        messagesContainer.innerHTML = messagesHTML;
                        console.log("messagesWithUserData", messagesWithUserData);
                    });
                } else {
                    console.error('Response data is not in the expected format');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }

        loadMessages(parseInt(''), 25);
    </script> -->



    <!-- <script>
        async function loadMessages(senderId, recipientId) {
            const messagesContainer = document.getElementById('messagesContainer');
            try {
                const messagesResponse = await fetch('hack/messages/get_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        sender_id: senderId,
                        recipient_id: recipientId
                    }),
                });

                console.log("sender_id_", senderId);
                console.log("recipientId_", recipientId);

                const messagesData = await messagesResponse.json();
                console.log("messagesData", messagesData)

                // Перетворюємо отримані дані у масив, якщо вони не масив
                const messagesArray = Array.isArray(messagesData) ? messagesData : [messagesData];

                messagesHTML = messagesArray.map(message => `
            <li class="message-li">
                <div class="message-div">
                    <div class="message-blk">
                        // <p class="message-a__text">${message.success.message_text}</p>
                    </div>
                    <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
                </div>
            </li>
        `).join();
                messagesContainer.innerHTML = messagesHTML;

            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }
        loadMessages(25, recipientId);
    </script> -->



    <script>
        async function loadMessages(senderId, recipientId) {
            const messagesContainer = document.getElementById('messagesContainer');
            try {
                const messagesResponse = await fetch('hack/messages/get_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        sender_id: senderId,
                        recipient_id: recipientId
                    }),
                });

                console.log("senderId", senderId);
                console.log("recipientId", recipientId);

                if (!messagesResponse.ok) {
                    throw new Error('Failed to fetch messages');
                }

                const messagesData = await messagesResponse.json();

                if (Array.isArray(messagesData.success)) {
                    const messages = messagesData.success;

                    // Отримати дані про користувача
                    const userDataResponse = await fetch('hack/messages/get_user_by_id.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: senderId
                        }),
                    });

                    console.log("senderId", senderId)

                    if (!userDataResponse.ok) {
                        throw new Error('Failed to fetch user info');
                    }

                    const userData = await userDataResponse.json();

                    console.log("userData", userData)

                    // Об'єднати дані про користувача з кожним повідомленням
                    const messagesWithUserData = messages.map(message => {
                        return {
                            ...userData,
                            ...message
                        };
                    });

                    console.log("messagesWithUserData", messagesWithUserData)

                    // Відобразити повідомлення
                    const messagesHTML = messagesWithUserData.map(message => {
                        return `<li class="message-li">
                            <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(message.success.name)}'>
                                <img class="message-img" src='hack/${message.success.avatar}' alt='${message.success.name}'> 
                                    <div class="message-div">
                                        <div class="message-blk">
                                            <p class="message-name">${message.success.name}</p>
                                            <p class="message-a__text">${message.message_text}</p>
                                        </div>
                                    <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
                                </div>
                            </a>
                        </li>`
                    }).join('');

                    messagesContainer.innerHTML = messagesHTML;
                    console.log("messagesHTML", messagesHTML)
                } else {
                    console.error('No messages found');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }

        loadMessages(25, recipientId);
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

                loadMessages(25, recipientId);
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>

</body>

</html>