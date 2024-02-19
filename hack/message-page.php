<?php
require_once __DIR__ . '/actions/helpers.php';

$loggedInUserId = currentUserId();

echo "<script>let loggedInUserId = " . json_encode($loggedInUserId) . ";</script>";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <ul class="message-block" id="messagesContainer"></ul>

    <script>
        async function getMessageForAuthorizedUser(loggedInUserId) {
            const messagesContainer = document.getElementById('messagesContainer');
            try {
                const messagesResponse = await fetch('hack/actions/get-message-for-authorized-user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        sender_id: loggedInUserId
                    }),
                });

                console.log('loggedInUserId', loggedInUserId);

                if (!messagesResponse.ok) {
                    throw new Error('Failed to fetch messages');
                }

                const messagesData = await messagesResponse.json();

                if (Array.isArray(messagesData.success)) {
                    const messages = messagesData.success;

                    const userDataResponse = await fetch('hack/messages/get_user_by_id.php')

                    if (!userDataResponse.ok) {
                        throw new Error('Failed to fetch user info');
                    }

                    const userData = await userDataResponse.json();

                    console.log("userData", userData)
                    console.log("messages", messages)

                    const messagesWithUserData = messages.map(message => {
                        const sender = userData.find(user => user.id === message.sender_id);
                        return {
                            ...message,
                            user: sender
                        };
                    });

                    console.log("messagesWithUserData", messagesWithUserData)

                    const messagesHTML = messagesWithUserData.map(message => {

                        return `<li class="">
                <a class="message-a" href='index.php?page=user&username=${encodeURIComponent(message.user.name)}'>
                    <img class="message-img" src='hack/${message.user.avatar}' alt='${message.user.name}'> 
                    <div class="message-div">
                        <div class="message-blk">
                            <p class="message-name">${message.user.name}</p>
                            <p class="message-a__text">${message.message_text}</p>
                        </div>
                        <button class="message-a__button" onclick="deleteMessage(${message.id}, event)">Delete</button>
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
        getMessageForAuthorizedUser(loggedInUserId)
    </script>

</body>

</html>