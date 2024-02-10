async function loadMessages(loggedInUserId, recipientId) {
    const messagesContainer = document.getElementById('messagesContainer');
    try {
        const messagesResponse = await fetch('hack/messages/get_messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                sender_id: loggedInUserId,
                recipient_id: recipientId
            }),
        });

        console.log("loggedInUserId", loggedInUserId);
        console.log("recipientId", recipientId);

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
                const senderId = message.sender_id;
                const recipientId = message.recipient_id;
                const isSender = senderId === loggedInUserId;

                const messageClass = isSender ? 'message-sender' : 'message-recipient';

                return `<li class="${messageClass}">
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

loadMessages(loggedInUserId, recipientId);