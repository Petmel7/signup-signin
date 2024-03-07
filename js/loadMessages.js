
async function loadMessages(loggedInUserId, recipientId) {
    const messagesContainer = document.getElementById('messagesContainer');
    try {
        const [messagesResponse, userDataResponse] = await Promise.all([
            fetch('hack/messages/get_messages.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    sender_id: loggedInUserId,
                    recipient_id: recipientId
                }),
            }),
            fetch('hack/messages/get_user_by_id.php')
        ]);

        if (!messagesResponse.ok ?? !userDataResponse.ok) {
            throw new Error('Failed to fetch messages or user info');
        }

        const [messagesData, userData] = await Promise.all([
            messagesResponse.json(),
            userDataResponse.json()
        ]);

        if (Array.isArray(messagesData.success)) {
            const messages = messagesData.success;

            const messagesHTML = messages.map(message => {
                const sender = userData.find(user => user.id === message.sender_id);
                const isSender = sender.id === loggedInUserId;
                const messageClass = isSender ? 'message-sender' : 'message-recipient';

                const encodedUsername = encodeURIComponent(sender.name);
                const avatarSrc = `hack/${sender.avatar}`;

                return `
                    <li class="${messageClass}">
                        <div class="messages">
                            <a href='index.php?page=user&username=${encodedUsername}'>
                                <img class="message-img" src='${avatarSrc}' alt='${sender.name}'>
                            </a>
                            <div class="message-body">
                                <div class="message-header">
                                    <p class="message-author--name">${sender.name}</p>
                                    <p class="message-content">${message.message_text}</p>
                                </div>
                                <button class="message-delete--button delete-button" onclick="openModalDelete(${message.id})">🗑️</button>
                                <div id="myModal" class="modal">
                                    <div class="modal-content" id="modalContent"></div>
                                </div>
                            </div>
                        </div>
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