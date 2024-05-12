// async function loadMessages(loggedInUserId, recipientId) {
//     const messagesContainer = document.getElementById('messagesContainer');
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

//         if (!response.ok) {
//             throw new Error('Failed to fetch messages or user info');
//         }

//         const messagesData = await response.json();

//         const messages = messagesData.success.messages;
//         const users = messagesData.success.users;

//         let lastSenderId = null;
//         let lastMessageTime = null;

//         const messagesHTML = messages.map(message => {
//             const senderId = message.sender_id;
//             const isSender = senderId === loggedInUserId;
//             const formattedTime = formatTime(message.sent_at);

//             const { messageClass, displayStyle } = getMessageStyles(isSender);

//             const currentTime = new Date(message.timestamp).getTime();

//             const showAvatar = senderId !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

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
//             const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-heder--sent_at' : 'message-header';

//             const {
//                 backgroundSenderClass,
//                 backgroundClassMessages,
//                 recipientWhiteText,
//                 messageDateStyleDisplay,
//                 modalThemeStyle,
//                 mesageButtonStyle
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
//                         <button class="message-delete--button delete-button ${mesageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
//                         <div id="myModal" class="modal">
//                             <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
//                         </div>

//                     </div>
//                 </div>
//             </li>`;
//         }).join('');
//         messagesContainer.innerHTML = messagesHTML;
//     } catch (error) {
//         console.error('Error in fetch request', error);
//     }
// }
// loadMessages(loggedInUserId, recipientId);




// async function loadMessages(loggedInUserId, recipientId) {
//     const messagesContainer = document.getElementById('messagesContainer');
//     try {
//         const socket = new WebSocket('ws://localhost:2346');

//         socket.onopen = function () {
//             const requestData = {
//                 sender_id: loggedInUserId,
//                 recipient_id: recipientId
//             };
//             socket.send(JSON.stringify(requestData));
//         };

//         return new Promise((resolve, reject) => {
//             socket.onmessage = async function (event) {
//                 const messagesData = JSON.parse(event.data);

//                 const messages = messagesData.messages;
//                 const users = messagesData.users;

//                 let lastSenderId = null;
//                 let lastMessageTime = null;

//                 const messagesHTML = messages.map(message => {
//                     const senderId = message.sender_id;
//                     const isSender = senderId === loggedInUserId;
//                     const formattedTime = formatTime(message.sent_at);

//                     const { messageClass, displayStyle } = getMessageStyles(isSender);

//                     const currentTime = new Date(message.timestamp).getTime();

//                     const showAvatar = senderId !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

//                     const {
//                         avatarDisplayStyle,
//                         marginLeftStyle,
//                         dynamicBorderStyle
//                     } = calculateStyles(showAvatar, isSender, displayStyle);

//                     const sender = users.find(user => user.id === senderId);

//                     if (showAvatar) {
//                         lastSenderId = senderId;
//                         lastMessageTime = currentTime;
//                     }
//                     const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-heder--sent_at' : 'message-header';

//                     const {
//                         backgroundSenderClass,
//                         backgroundClassMessages,
//                         recipientWhiteText,
//                         messageDateStyleDisplay,
//                         modalThemeStyle,
//                         mesageButtonStyle
//                     } = calculateStylesLocalStorage(isSender);

//                     const {
//                         encodedUsername,
//                         messageContent,
//                         backgroundImage,
//                         backgroundImageSize,
//                         backgroundSizeCover,
//                         imageButtonStyle,
//                         avatarSrc
//                     } = processMessageData(sender, message, recipientWhiteText);

//                     return `
//     <li class="${messageClass}">
//         <div class="messages">
//             <a href='index.php?page=user&username=${encodedUsername}'>
//                 <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
//             </a>
//             <div class="search-friend--add message-body ${backgroundSenderClass} ${backgroundClassMessages}" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}; ${backgroundImage}; ${backgroundImageSize}; ${backgroundSizeCover}">

//                 <div class="${messageSentAtClass}">
//                     ${messageContent}
//                     <span class="${messageDateStyleDisplay} ${imageButtonStyle}">${formattedTime}</span>
//                 </div>
//                 <button class="message-delete--button delete-button ${mesageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
//                 <div id="myModal" class="modal">
//                     <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
//                 </div>

//             </div>
//         </div>
//     </li>`;
//                 }).join('');
//                 messagesContainer.innerHTML = messagesHTML;
//                 resolve({ messages, container: messagesContainer });
//             };
//         });
//     } catch (error) {
//         console.error('Error in WebSocket request', error);
//         throw error;
//     }
// }
// loadMessages(loggedInUserId, recipientId);














// ===================================================
//*****************************************************/
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

            let lastSenderId = null;
            let lastMessageTime = null;

            const messagesHTML = messages.map(message => {
                const sender = userData.find(user => user.id === message.sender_id);
                const isSender = sender.id === loggedInUserId;
                const formattedTime = formatTime(message.sent_at);

                const { messageClass, displayStyle } = getMessageStyles(isSender);

                const currentTime = new Date(message.timestamp).getTime();

                const showAvatar = message.sender_id !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

                const {
                    avatarDisplayStyle,
                    marginLeftStyle,
                    dynamicBorderStyle
                } = calculateStyles(showAvatar, isSender, displayStyle);

                if (showAvatar) {
                    lastSenderId = message.sender_id;
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

                const { encodedUsername,
                    avatarSrc,
                    messageContent,
                    backgroundImage,
                    backgroundImageSize,
                    backgroundSizeCover,
                    imageButtonStyle
                    // imageTimeStyle
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

        } else {
            console.error('No messages found');
        }
    } catch (error) {
        console.error('Error in fetch request', error);
    }
}

loadMessages(loggedInUserId, recipientId);

//****************************** */


// async function autoRefreshMessages(loggedInUserId, recipientId) {
//     try {
//         // Отримуємо WebSocket підключення
//         const socket = new WebSocket('ws://localhost:2346');

//         // Відправляємо запит на отримання повідомлень при підключенні до WebSocket
//         socket.onopen = function () {
//             const requestData = {
//                 sender_id: loggedInUserId,
//                 recipient_id: recipientId
//             };
//             socket.send(JSON.stringify(requestData));
//         };

//         // Обробляємо отримані повідомлення
//         socket.onmessage = async function (event) {
//             // Після отримання нових повідомлень оновлюємо відображення
//             await loadMessages(loggedInUserId, recipientId);
//         };

//         // Закриваємо WebSocket з'єднання після завершення роботи
//         socket.onclose = function () {
//             console.log('WebSocket connection closed');
//         };

//     } catch (error) {
//         console.error('Error in WebSocket request', error);
//     }
// }

// // Запускаємо функцію автоматичного оновлення повідомлень
// autoRefreshMessages(loggedInUserId, recipientId);



// ========================================================