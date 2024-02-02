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

        <button class="subscription-buttons" type="button" onclick="redirectionHisFriends('<?php echo $username; ?>')">His friends</button>

        <ul id="messagesContainer"></ul>

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

                    const messagesHTML = messages.reverse().map(message => `
                        <li>
                            <p>${message.message_text}</p>
                            <button onclick="deleteComment(<?php echo $userData['id']; ?>)">Delete</button>
                        </li>
                    `).join('');

                    messagesContainer.innerHTML = messagesHTML;
                    // messagesContainer.insertAdjacentHTML('', messagesHTML);

                } else {
                    console.error('Failed to fetch messages');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }

        loadMessages();
    </script>

    <script>
        // Функція для видалення коментаря
        async function deleteComment(commentId) {
            try {
                const response = await fetch('hack/comments/delete_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        comment_id: commentId,
                    }),
                });

                console.log('commentId', commentId)

                if (response.ok) {
                    const result = await response.json();

                    if (result.success) {
                        // Виконати додаткові дії, якщо видалення пройшло успішно
                        console.log('Comment deleted successfully');
                    } else {
                        console.error('Failed to delete comment');
                    }
                } else {
                    console.error('Network response was not ok.');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>


</body>

</html>