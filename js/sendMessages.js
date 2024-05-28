
// Підключення WebSocket до сервера
const socket = new WebSocket(`ws://localhost:2346/?sender_id=${loggedInUserId}&recipient_id=${recipientId}`);

socket.onopen = function () {
    console.log('WebSocket connection opened');
};

socket.onmessage = async function (event) {
    const message = JSON.parse(event.data);
    console.log('socket.onmessage->message:', message);
    console.log('socket.onmessage->message.messages', message.messages);

    // Логіка для обробки отриманих повідомлень
    if (message.messages) {
        // await displayMessages(message.messages, message.users);
        await loadAndScrollMessages(loggedInUserId, recipientId);
    }
};

socket.onerror = function (error) {
    console.error('WebSocket error:', error);
};

socket.onclose = function () {
    console.log('WebSocket connection closed');
};

// Функція для відображення отриманих повідомлень
async function displayMessages(messages, users) {
    const messagesContainer = document.getElementById('messagesContainer');

    let lastSenderId = null;
    let lastMessageTime = null;

    const messagesHTML = messages.map(message => {
        const senderId = message.sender_id;
        const isSender = senderId === loggedInUserId;
        const formattedTime = formatTime(message.sent_at);

        const { messageClass, displayStyle } = getMessageStyles(isSender);

        const currentTime = new Date(message.timestamp).getTime();
        const showAvatar = senderId !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

        const { avatarDisplayStyle, marginLeftStyle, dynamicBorderStyle } = calculateStyles(showAvatar, isSender, displayStyle);

        const sender = users.find(user => user.id === senderId);

        if (showAvatar) {
            lastSenderId = senderId;
            lastMessageTime = currentTime;
        }

        const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-heder--sent_at' : 'message-header';

        const {
            backgroundSenderClass,
            backgroundClassMessages,
            recipientWhiteText,
            messageDateStyleDisplay,
            modalThemeStyle,
            mesageButtonStyle
        } = calculateStylesLocalStorage(isSender);

        const {
            encodedUsername,
            messageContent,
            backgroundImage,
            backgroundImageSize,
            backgroundSizeCover,
            imageButtonStyle,
            avatarSrc
        } = processMessageData(sender, message, recipientWhiteText);

        return `
        <li class="${messageClass}">
            <div class="messages">
                <a href='index.php?page=user&username=${encodedUsername}'>
                    <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
                </a>
                <div class="search-friend--add message-body ${backgroundSenderClass} ${backgroundClassMessages}" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}; ${backgroundImage}; ${backgroundImageSize}; ${backgroundSizeCover}">

                    <div class="${messageSentAtClass}">
                        ${messageContent}
                        <span class="${messageDateStyleDisplay} ${imageButtonStyle}">${formattedTime}</span>
                    </div>
                    <button class="message-delete--button delete-button ${mesageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
                    <div id="myModal" class="modal">
                        <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
                    </div>

                </div>
            </div>
        </li>`;
    }).join('');
    messagesContainer.innerHTML = messagesHTML;
    return { container: messagesContainer, messages: messages };
}

// Функція для відправки повідомлень
async function sendMessages(recipientId, event) {
    event.preventDefault();
    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();
    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }
    const message = {
        sender_id: loggedInUserId,
        recipient_id: parseInt(recipientId, 10),
        message_text: messageText
    };

    console.log('sendMessages->message', message);

    // Перевірка стану WebSocket перед відправкою
    if (socket.readyState === WebSocket.OPEN) {
        socket.send(JSON.stringify(message));
    } else {
        alert('WebSocket connection is not open.');
    }
    messageTextarea.value = '';
}

// window.onload = function () {
//     loadAndScrollMessages(loggedInUserId, recipientId);
// };

async function loadAndScrollMessages(loggedInUserId, recipientId) {
    try {

        const response = await fetch('hack/messages/get_messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                sender_id: loggedInUserId,
                recipient_id: recipientId
            }),
        });
        const messagesData = await response.json();
        console.log('loadAndScrollMessages->messagesData', messagesData);
        const messages = messagesData.success.messages;
        const users = messagesData.success.users;
        const { container } = await displayMessages(messages, users);
        console.log('container', container);
        const scroll = container.scrollTop = container.scrollHeight;
        console.log('scroll', scroll);

    } catch (error) {
        console.error('Error loading messages:', error);
    }
}
loadAndScrollMessages(loggedInUserId, recipientId);





// const socket = new WebSocket(`ws://localhost:2346/?sender_id=${loggedInUserId}&recipient_id=${recipientId}`);

// socket.onopen = function () {
//     console.log('WebSocket connection opened');
// };

// socket.onmessage = async function (event) {
//     const message = JSON.parse(event.data);
//     console.log('socket.onmessage->message:', message);

//     // Логіка для обробки отриманих повідомлень
//     if (message.messages) {
//         // Виклик функції для відображення повідомлень з WebSocket
//         await displayMessages(message.messages, message.users);
//     }
// };

// socket.onerror = function (error) {
//     console.error('WebSocket error:', error);
// };

// socket.onclose = function () {
//     console.log('WebSocket connection closed');
// };

// // Функція для відображення отриманих повідомлень
// async function displayMessages(messages, users) {
//     const messagesContainer = document.getElementById('messagesContainer');

//     let lastSenderId = null;
//     let lastMessageTime = null;

//     if (!Array.isArray(messages)) {
//         console.error('Messages is not an array:', messages);
//         return;
//     }

//     const messagesHTML = messages.map(message => {
//         const senderId = message.sender_id;
//         const isSender = senderId === loggedInUserId;
//         const formattedTime = formatTime(message.sent_at);

//         const { messageClass, displayStyle } = getMessageStyles(isSender);

//         const currentTime = new Date(message.timestamp).getTime();
//         const showAvatar = senderId !== lastSenderId || (currentTime - lastMessageTime > 60000);

//         const { avatarDisplayStyle, marginLeftStyle, dynamicBorderStyle } = calculateStyles(showAvatar, isSender, displayStyle);

//         const sender = users.find(user => user.id === senderId);

//         if (showAvatar) {
//             lastSenderId = senderId;
//             lastMessageTime = currentTime;
//         }

//         const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-header--sent_at' : 'message-header';

//         const {
//             backgroundSenderClass,
//             backgroundClassMessages,
//             recipientWhiteText,
//             messageDateStyleDisplay,
//             modalThemeStyle,
//             mesageButtonStyle
//         } = calculateStylesLocalStorage(isSender);

//         const {
//             encodedUsername,
//             messageContent,
//             backgroundImage,
//             backgroundImageSize,
//             backgroundSizeCover,
//             imageButtonStyle,
//             avatarSrc
//         } = processMessageData(sender, message, recipientWhiteText);

//         return `
//         <li class="${messageClass}">
//             <div class="messages">
//                 <a href='index.php?page=user&username=${encodedUsername}'>
//                     <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
//                 </a>
//                 <div class="search-friend--add message-body ${backgroundSenderClass} ${backgroundClassMessages}" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}; ${backgroundImage}; ${backgroundImageSize}; ${backgroundSizeCover}">

//                     <div class="${messageSentAtClass}">
//                         ${messageContent}
//                         <span class="${messageDateStyleDisplay} ${imageButtonStyle}">${formattedTime}</span>
//                     </div>
//                     <button class="message-delete--button delete-button ${mesageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
//                     <div id="myModal" class="modal">
//                         <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
//                     </div>

//                 </div>
//             </div>
//         </li>`;
//     }).join('');
//     messagesContainer.innerHTML = messagesHTML;
//     return { container: messagesContainer, messages: messages };
// }

// // Функція для відправки повідомлень
// async function sendMessages(recipientId, event) {
//     event.preventDefault();
//     const messageTextarea = document.getElementById('messageTextarea');
//     const messageText = messageTextarea.value.trim();
//     if (messageText === '') {
//         alert('Please enter the text of the message.');
//         return;
//     }
//     const message = {
//         sender_id: loggedInUserId,
//         recipient_id: parseInt(recipientId, 10),
//         message_text: messageText
//     };

//     console.log('sendMessages->message', message);

//     // Перевірка стану WebSocket перед відправкою
//     if (socket.readyState === WebSocket.OPEN) {
//         socket.send(JSON.stringify(message));
//     } else {
//         alert('WebSocket connection is not open.');
//     }
//     messageTextarea.value = '';
// }

// window.onload = function () {
//     loadAndScrollMessages(recipientId);
// };

// async function loadAndScrollMessages(recipientId) {
//     try {
//         const response = await fetch('hack/messages/get_messages.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 sender_id: loggedInUserId,
//                 recipient_id: recipientId
//             }),
//         });
//         const messagesData = await response.json();
//         console.log('loadAndScrollMessages->messagesData', messagesData);
//         const messages = messagesData.success.messages;
//         const users = messagesData.success.users;
//         const { container } = await displayMessages(messages, users);
//         console.log('container', container);
//         container.scrollTop = container.scrollHeight;

//     } catch (error) {
//         console.error('Error loading messages:', error);
//     }
// }
