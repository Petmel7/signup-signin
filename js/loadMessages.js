
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

            const messagesWithUserData = messages.map(message => {
                const sender = userData.find(user => user.id === message.sender_id);
                return {
                    ...message,
                    user: sender
                };
            });

            const messagesHTML = messagesWithUserData.map(message => {
                const senderId = message.sender_id;
                const isSender = senderId === loggedInUserId;
                console.log("loggedInUserId", loggedInUserId)

                const messageClass = isSender ? 'message-sender' : 'message-recipient';

                return `<li class="${messageClass}">
                <div class="message-a">
                    <a  href='index.php?page=user&username=${encodeURIComponent(message.user.name)}'>
                        <img class="message-img" src='hack/${message.user.avatar}' alt='${message.user.name}'>
                    </a> 
                        <div class="message-div">
                            <div class="message-blk">
                                <p class="message-name">${message.user.name}</p>
                                <p class="message-a__text">${message.message_text}</p>
                            </div>
                            <button class="message-a__button" onclick="openModalDelete(${message.id})">🗑️</button>

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


