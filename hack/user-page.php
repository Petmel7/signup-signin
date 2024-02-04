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
            <label for="messageTextarea">
                <textarea class="message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
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
    <script>
        const textarea = document.getElementById('messageTextarea');

        textarea.addEventListener('input', function() {
            this.style.height = 'auto'; // Автоматично збільшити висоту
            this.style.height = (this.scrollHeight) + 'px'; // Збільшити висоту до фактичного розміру
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
        async function loadMessages() {
            const messagesContainer = document.getElementById('messagesContainer');

            try {
                const response = await fetch('hack/messages/get_messages.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    const messages = await response.json();

                    const fragment = document.createDocumentFragment();

                    for (const message of messages.reverse()) {
                        const user = await getUserInfo(message.sender_id);

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
                    console.error('Failed to fetch messages');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }

        async function getUserInfo(userId) {

            try {
                const userResponse = await fetch(`hack/messages/get_user_by_id.php?id=${userId}`);
                if (userResponse.ok) {
                    return await userResponse.json();

                } else {
                    console.error('Failed to fetch user info');
                    return {};
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return {};
            }
        }

        loadMessages();

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

        //             console.log('messages', messages);

        //             // Очищення контейнера перед вставкою нових повідомлень
        //             messagesContainer.innerHTML = '';

        //             messages.map(async (message) => {
        //                 const user = await getUserInfo(message.sender_id);

        //                 const messageHTML = `
        //             <li class="message-li">
        //                 <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(user.name)}'>

        //                     <img class="message-img" src='hack/${user.avatar}' alt='${user.name}'> 

        //                     <div class="message-div">
        //                         <div class="message-blk">
        //                             <p class="message-name">${user.name}</p>
        //                             <p class="message-a__text">${message.message_text}</p>
        //                         </div>
        //                         <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
        //                     </div>
        //                 </a>
        //             </li>`;

        //                 messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
        //             });
        //         } else {
        //             console.error('Failed to fetch messages');
        //         }
        //     } catch (error) {
        //         console.error('Error in fetch request', error);
        //     }
        // }
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

                loadMessages();

            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>

</body>

</html>