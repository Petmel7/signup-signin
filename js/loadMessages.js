
// async function loadMessages(loggedInUserId, recipientId) {
//     const messagesContainer = document.getElementById('messagesContainer');
//     try {
//         // Отримуємо WebSocket підключення
//         const socket = new WebSocket('ws://localhost:2346');
//         console.log('socketLoadMessages', socket)
//         // Відправляємо запит на отримання повідомлень
//         socket.onopen = function () {
//             const requestData = {
//                 action: 'load_messages',
//                 loggedInUserId: loggedInUserId,
//                 recipientId: recipientId
//             };
//             socket.send(JSON.stringify(requestData));
//         };

//         // Обробляємо отримані повідомлення
//         socket.onmessage = async function (event) {
//             const messagesData = JSON.parse(event.data);
//             console.log('messagesData', messagesData);
//             if (messagesData && Array.isArray(messagesData.success)) {
//                 const messages = messagesData.success;
//                 console.log('messages', messages);

//                 let lastSenderId = null;
//                 let lastMessageTime = null;

//                 const messagesHTML = messages.map(message => {
//                     const sender = message.sender_id === loggedInUserId ? loggedInUserId : recipientId;
//                     const isSender = sender === loggedInUserId;
//                     const formattedTime = formatTime(message.sent_at);

//                     const { messageClass, displayStyle } = getMessageStyles(isSender);

//                     const currentTime = new Date(message.timestamp).getTime();

//                     const showAvatar = message.sender_id !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

//                     const {
//                         avatarDisplayStyle,
//                         marginLeftStyle,
//                         dynamicBorderStyle
//                     } = calculateStyles(showAvatar, isSender, displayStyle);

//                     if (showAvatar) {
//                         lastSenderId = message.sender_id;
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

//                     const { encodedUsername,
//                         avatarSrc,
//                         messageContent,
//                         backgroundImage,
//                         backgroundImageSize,
//                         backgroundSizeCover,
//                         imageButtonStyle
//                         // imageTimeStyle
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

//             } else {
//                 console.error('No messages found');
//             }
//         };

//     } catch (error) {
//         console.error('Error in WebSocket request', error);
//     }
// }

// async function run() {
//     try {
//         await loadMessages(loggedInUserId, recipientId);
//         // Виконується тільки після завершення функції loadMessages
//         // Решта коду, який залежить від результату loadMessages
//     } catch (error) {
//         console.error('Error:', error);
//     }
// }

// run();

// ===================================================

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

// ========================================================


// async function loadMessages(loggedInUserId, recipientId) {
//     const messagesContainer = document.getElementById('messagesContainer');
//     try {
//         const socket = new WebSocket('ws://localhost:2346');

//         console.log('socket', socket);

//         // Відправляємо запит на отримання повідомлень
//         socket.onopen = function () {
//             const requestData = {
//                 action: 'load_messages',
//                 loggedInUserId: loggedInUserId,
//                 recipientId: recipientId
//             };
//             socket.send(JSON.stringify(requestData));
//             console.log('requestData', socket);
//         };

//         // Обробляємо отримані повідомлення
//         const messagesPromise = new Promise((resolve, reject) => {
//             socket.onmessage = function (event) {
//                 const messagesData = JSON.parse(event.data);
//                 if (messagesData && Array.isArray(messagesData.success)) {
//                     resolve(messagesData.success);
//                 } else {
//                     reject('No messages found');
//                 }
//             };
//         });

//         const messages = await messagesPromise;

//         // Отримано дані, обробляємо їх та відображаємо на сторінці
//         // ваш код для обробки повідомлень
//         let lastSenderId = null;
//         let lastMessageTime = null;
//         console.log('lastSenderId', lastSenderId);

//         const messagesHTML = messages.map(message => {
//             const sender = message.sender_id === loggedInUserId ? loggedInUserId : recipientId;
//             const isSender = sender === loggedInUserId;
//             const formattedTime = formatTime(message.sent_at);

//             const { messageClass, displayStyle } = getMessageStyles(isSender);

//             const currentTime = new Date(message.timestamp).getTime();

//             const showAvatar = message.sender_id !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

//             const {
//                 avatarDisplayStyle,
//                 marginLeftStyle,
//                 dynamicBorderStyle
//             } = calculateStyles(showAvatar, isSender, displayStyle);

//             if (showAvatar) {
//                 lastSenderId = message.sender_id;
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

//             const { encodedUsername,
//                 avatarSrc,
//                 messageContent,
//                 backgroundImage,
//                 backgroundImageSize,
//                 backgroundSizeCover,
//                 imageButtonStyle
//                 // imageTimeStyle
//             } = processMessageData(sender, message, recipientWhiteText);

//             return `
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
//         }).join('');
//         messagesContainer.innerHTML = messagesHTML;
//     } catch (error) {
//         console.error('Error in WebSocket request', error);
//     }
// }

// async function run() {
//     try {
//         await loadMessages(loggedInUserId, recipientId);
//         // Виконується тільки після завершення функції loadMessages
//         // Решта коду, який залежить від результату loadMessages
//     } catch (error) {
//         console.error('Error:', error);
//     }
// }

// run();





