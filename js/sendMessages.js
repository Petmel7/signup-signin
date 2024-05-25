
async function loadAndScrollMessages(recipientId) {
    try {
        messageTextarea.value = '';

        const { container } = await loadMessages(loggedInUserId, recipientId);
        console.log('container', container);
        console.log('Scroll-recipientId', recipientId);

        const scroll = container.scrollTop = container.scrollHeight;
        console.log('scroll', scroll);
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

window.onload = function () {
    loadAndScrollMessages(recipientId);
};

const socket = new WebSocket('ws://localhost:2346/?sender_id=' + loggedInUserId + '&recipient_id=' + recipientId);

console.log('socket', socket)
console.log('socket-recipientId', recipientId)

socket.onopen = function () {
    console.log('WebSocket connection opened');
};

socket.onmessage = async function (event) {
    const message = JSON.parse(event.data);
    console.log('Received message:', message);

    if (message.echo_message) {
        const echoMessage = message.echo_message;

        if (Array.isArray(echoMessage)) {
            await loadAndScrollMessages(echoMessage[0].recipient_id);
        } else {
            console.error('Expected an array of messages');
        }
    } else {
        console.error('Invalid message format');
    }
};

async function sendMessages(recipientId, event) {
    event.preventDefault();

    const recipientIdNumber = parseInt(recipientId, 10);

    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();

    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }

    try {
        const message = {
            sender_id: loggedInUserId,
            recipient_id: recipientIdNumber,
            message_text: messageText
        };
        console.log('loggedInUserId', loggedInUserId);
        console.log('recipientIdNumber', recipientIdNumber);
        console.log('message', message);
        socket.send(JSON.stringify(message));
    } catch (error) {
        console.log('sendMessage-Error', error);
    }
}







// const socket = new WebSocket('ws://localhost:2346/?sender_id=' + loggedInUserId + '&recipient_id=' + recipientId);

// socket.onopen = function () {
//     console.log('WebSocket connection opened');
// };

// socket.onmessage = async function (event) {
//     const message = JSON.parse(event.data);
//     console.log('send->message:', message);

//     if (message.echo_message) {
//         const echoMessage = message.echo_message;
//         console.log('echoMessage', echoMessage);

//         if (Array.isArray(echoMessage)) {
//             await loadAndScrollMessages(echoMessage[0].recipient_id);
//         } else {
//             console.error('Expected an array of messages');
//         }
//     } else {
//         console.error('Invalid message format');
//     }
// };

// async function sendMessages(recipientId, event) {
//     event.preventDefault();

//     const recipientIdNumber = parseInt(recipientId, 10);
//     const messageTextarea = document.getElementById('messageTextarea');
//     const messageText = messageTextarea.value.trim();

//     if (messageText === '') {
//         alert('Please enter the text of the message.');
//         return;
//     }

//     try {
//         const message = {
//             sender_id: loggedInUserId,
//             recipient_id: recipientIdNumber,
//             message_text: messageText
//         };
//         console.log('message', message);
//         socket.send(JSON.stringify(message));
//         // Очистка текстової області
//         messageTextarea.value = '';
//     } catch (error) {
//         console.log('sendMessage-Error', error);
//     }
// }






// if (message.echo_message) {
//     const echoMessage = message.echo_message;

//     if (Array.isArray(echoMessage)) {
//         await loadAndScrollMessages(echoMessage[0].recipient_id);
//     } else {
//         console.error('Expected an array of messages');
//     }
// } else if (message.success) {
//     const { messages, users } = message.success;

//     if (messages && users) {
//         let lastSenderId = null;
//         let lastMessageTime = null;

//         const messagesHTML = messages.map(message => {
//             const senderId = message.sender_id;
//             const isSender = senderId === loggedInUserId;
//             const formattedTime = formatTime(message.sent_at);

//             const { messageClass, displayStyle } = getMessageStyles(isSender);

//             const currentTime = new Date(message.timestamp).getTime();

//             const showAvatar = senderId !== lastSenderId || (currentTime - lastMessageTime > 60000);

//             const {
//                 avatarDisplayStyle,
//                 marginLeftStyle,
//                 dynamicBorderStyle
//             } = calculateStyles(showAvatar, isSender, displayStyle);

//             const sender = users.find(user => user.id === senderId);

//             if (showAvatar) {
//                 lastSenderId = senderId;
//                 lastMessageTime = currentTime;
//             }

//             const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-header--sent_at' : 'message-header';

//             const {
//                 backgroundSenderClass,
//                 backgroundClassMessages,
//                 recipientWhiteText,
//                 messageDateStyleDisplay,
//                 modalThemeStyle,
//                 messageButtonStyle
//             } = calculateStylesLocalStorage(isSender);

//             const {
//                 encodedUsername,
//                 messageContent,
//                 backgroundImage,
//                 backgroundImageSize,
//                 backgroundSizeCover,
//                 imageButtonStyle,
//                 avatarSrc
//             } = processMessageData(sender, message, recipientWhiteText);

//             return `
//             <li class="${messageClass}">
//                 <div class="messages">
//                     <a href='index.php?page=user&username=${encodedUsername}'>
//                         <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
//                     </a>
//                     <div class="search-friend--add message-body ${backgroundSenderClass} ${backgroundClassMessages}" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}; ${backgroundImage}; ${backgroundImageSize}; ${backgroundSizeCover}">
//                         <div class="${messageSentAtClass}">
//                             ${messageContent}
//                             <span class="${messageDateStyleDisplay} ${imageButtonStyle}">${formattedTime}</span>
//                         </div>
//                         <button class="message-delete--button delete-button ${messageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
//                         <div id="myModal" class="modal">
//                             <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
//                         </div>
//                     </div>
//                 </div>
//             </li>`;
//         }).join('');

//         const messagesContainer = document.getElementById('messagesContainer');
//         messagesContainer.innerHTML = messagesHTML;
//     } else {
//         console.error('Invalid messages or users data');
//     }
// } else {
//     console.error('Invalid message format');
// }