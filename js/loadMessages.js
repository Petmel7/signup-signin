
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

            // Зберігаємо id відправника останнього відображеного повідомлення і мітку часу
            let lastSenderId = null;
            let lastMessageTime = null;

            const messagesHTML = messages.map(message => {
                const sender = userData.find(user => user.id === message.sender_id);
                const isSender = sender.id === loggedInUserId;
                const messageClass = isSender ? 'message-sender' : 'message-recipient';
                const displayStyle = isSender ? 'none' : 'block';

                const currentTime = new Date(message.timestamp).getTime();

                // Перевіряємо, чи це новий відправник або чи минула хвилина з останнього відображення аватара
                const showAvatar = message.sender_id !== lastSenderId ?? (currentTime - lastMessageTime > 60000);
                const avatarDisplayStyle = showAvatar ? displayStyle : 'none';
                const marginLeftStyle = showAvatar ? '0px' : '50px';
                const borderStyle = showAvatar ? '0 10px 10px 10px' : '10px';
                const borderStyleSender = showAvatar ? '10px 0 10px 10px' : '10px';
                const dynamicBorderStyle = isSender ? borderStyleSender : borderStyle;

                if (showAvatar) {
                    lastSenderId = message.sender_id;
                    lastMessageTime = currentTime;
                }

                // Використання функції для форматування часу
                const formattedTime = formatTime(message.sent_at);

                const encodedUsername = encodeURIComponent(sender.name);
                const avatarSrc = `hack/${sender.avatar}`;

                return `
        <li class="${messageClass}">
            <div class="messages">
                <a href='index.php?page=user&username=${encodedUsername}'>
                    <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
                </a>
                <div class="message-body" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}">
                    <div class="message-header">
                        <p class="message-content">${message.message_text}</p>
                        <span class="message-date">${formattedTime}</span>
                    </div>
                    <button class="message-delete--button delete-button" onclick="openModalDelete(${message.id})">&#8942;</button>
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