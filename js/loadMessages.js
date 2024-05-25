async function loadMessages(loggedInUserId, recipientId) {
    const messagesContainer = document.getElementById('messagesContainer');
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

        if (!response.ok) {
            throw new Error('Failed to fetch messages or user info');
        }

        const messagesData = await response.json();
        console.log('Messages data:', messagesData);

        const messages = messagesData.success.messages;
        const users = messagesData.success.users;

        let lastSenderId = null;
        let lastMessageTime = null;

        const messagesHTML = messages.map(message => {
            const senderId = message.sender_id;
            const isSender = senderId === loggedInUserId;
            const formattedTime = formatTime(message.sent_at);

            const { messageClass, displayStyle } = getMessageStyles(isSender);

            const currentTime = new Date(message.timestamp).getTime();

            const showAvatar = senderId !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

            const {
                avatarDisplayStyle,
                marginLeftStyle,
                dynamicBorderStyle
            } = calculateStyles(showAvatar, isSender, displayStyle);

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

    } catch (error) {
        console.error('Error in fetch request', error);
        throw new Error('Failed to load messages');
    }
}
loadMessages(loggedInUserId, recipientId);







// async function loadMessages(loggedInUserId, recipientId) {
//     const messagesContainer = document.getElementById('messagesContainer');
//     try {
//         const socket = new WebSocket('ws://localhost:2346/?sender_id=' + loggedInUserId + '&recipient_id=' + recipientId);

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

//                 console.log('messagesData', messagesData);

//                 if (messagesData && messagesData.messages && messagesData.users) {
//                     // const messages = messagesData.messages;
//                     // const users = messagesData.users;
//                     const messages = messagesData.success.messages;
//                     const users = messagesData.success.users;

//                     console.log('messages', messages);
//                     console.log('users', users);

//                     let lastSenderId = null;
//                     let lastMessageTime = null;

//                     const messagesHTML = messages.map(message => {
//                         const senderId = message.sender_id;
//                         const isSender = senderId === loggedInUserId;
//                         const formattedTime = formatTime(message.sent_at);

//                         const { messageClass, displayStyle } = getMessageStyles(isSender);

//                         const currentTime = new Date(message.timestamp).getTime();

//                         const showAvatar = senderId !== lastSenderId || (currentTime - lastMessageTime > 60000);

//                         const {
//                             avatarDisplayStyle,
//                             marginLeftStyle,
//                             dynamicBorderStyle
//                         } = calculateStyles(showAvatar, isSender, displayStyle);

//                         const sender = users.find(user => user.id === senderId);

//                         if (showAvatar) {
//                             lastSenderId = senderId;
//                             lastMessageTime = currentTime;
//                         }

//                         const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-header--sent_at' : 'message-header';

//                         const {
//                             backgroundSenderClass,
//                             backgroundClassMessages,
//                             recipientWhiteText,
//                             messageDateStyleDisplay,
//                             modalThemeStyle,
//                             messageButtonStyle
//                         } = calculateStylesLocalStorage(isSender);

//                         const {
//                             encodedUsername,
//                             messageContent,
//                             backgroundImage,
//                             backgroundImageSize,
//                             backgroundSizeCover,
//                             imageButtonStyle,
//                             avatarSrc
//                         } = processMessageData(sender, message, recipientWhiteText);

//                         return `
//                             <li class="${messageClass}">
//                                 <div class="messages">
//                                     <a href='index.php?page=user&username=${encodedUsername}'>
//                                         <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
//                                     </a>
//                                     <div class="search-friend--add message-body ${backgroundSenderClass} ${backgroundClassMessages}" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}; ${backgroundImage}; ${backgroundImageSize}; ${backgroundSizeCover}">
//                                         <div class="${messageSentAtClass}">
//                                             ${messageContent}
//                                             <span class="${messageDateStyleDisplay} ${imageButtonStyle}">${formattedTime}</span>
//                                         </div>
//                                         <button class="message-delete--button delete-button ${messageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
//                                         <div id="myModal" class="modal">
//                                             <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
//                                         </div>
//                                     </div>
//                                 </div>
//                             </li>`;
//                     }).join('');

//                     messagesContainer.innerHTML = messagesHTML;
//                     resolve({ messages, container: messagesContainer });
//                 } else {
//                     reject(new Error('Invalid messages data'));
//                 }
//             };
//         });
//     } catch (error) {
//         console.error('Error in WebSocket request', error);
//         throw error;
//     }
// }






// async function loadMessages(loggedInUserId, recipientId) {
//     const messagesContainer = document.getElementById('messagesContainer');
//     return new Promise((resolve, reject) => {
//         try {
//             const socket = new WebSocket('ws://localhost:2347/?sender_id=' + loggedInUserId + '&recipient_id=' + recipientId);

//             socket.onopen = function () {
//                 const requestData = {
//                     sender_id: loggedInUserId,
//                     recipient_id: recipientId
//                 };
//                 socket.send(JSON.stringify(requestData));
//             };

//             socket.onmessage = async function (event) {
//                 try {
//                     const messagesData = JSON.parse(event.data);
//                     console.log('messagesData', messagesData);

//                     if (messagesData && messagesData.success && messagesData.success.messages && messagesData.success.users) {
//                         const messages = messagesData.success.messages;
//                         const users = messagesData.success.users;

//                         console.log('messages', messages);
//                         console.log('users', users);

//                         let lastSenderId = null;
//                         let lastMessageTime = null;

//                         const messagesHTML = messages.map(message => {
//                             const senderId = message.sender_id;
//                             const isSender = senderId === loggedInUserId;
//                             const formattedTime = formatTime(message.sent_at);

//                             const { messageClass, displayStyle } = getMessageStyles(isSender);
//                             const currentTime = new Date(message.timestamp).getTime();
//                             const showAvatar = senderId !== lastSenderId || (currentTime - lastMessageTime > 60000);

//                             const {
//                                 avatarDisplayStyle,
//                                 marginLeftStyle,
//                                 dynamicBorderStyle
//                             } = calculateStyles(showAvatar, isSender, displayStyle);

//                             const sender = users.find(user => user.id === senderId);

//                             if (showAvatar) {
//                                 lastSenderId = senderId;
//                                 lastMessageTime = currentTime;
//                             }

//                             const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-header--sent_at' : 'message-header';

//                             const {
//                                 backgroundSenderClass,
//                                 backgroundClassMessages,
//                                 recipientWhiteText,
//                                 messageDateStyleDisplay,
//                                 modalThemeStyle,
//                                 messageButtonStyle
//                             } = calculateStylesLocalStorage(isSender);

//                             const {
//                                 encodedUsername,
//                                 messageContent,
//                                 backgroundImage,
//                                 backgroundImageSize,
//                                 backgroundSizeCover,
//                                 imageButtonStyle,
//                                 avatarSrc
//                             } = processMessageData(sender, message, recipientWhiteText);

//                             return `
//                                 <li class="${messageClass}">
//                                     <div class="messages">
//                                         <a href='index.php?page=user&username=${encodedUsername}'>
//                                             <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
//                                         </a>
//                                         <div class="search-friend--add message-body ${backgroundSenderClass} ${backgroundClassMessages}" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}; ${backgroundImage}; ${backgroundImageSize}; ${backgroundSizeCover}">
//                                             <div class="${messageSentAtClass}">
//                                                 ${messageContent}
//                                                 <span class="${messageDateStyleDisplay} ${imageButtonStyle}">${formattedTime}</span>
//                                             </div>
//                                             <button class="message-delete--button delete-button ${messageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
//                                             <div id="myModal" class="modal">
//                                                 <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
//                                             </div>
//                                         </div>
//                                     </div>
//                                 </li>`;
//                         }).join('');

//                         messagesContainer.innerHTML = messagesHTML;
//                         resolve({ messages, container: messagesContainer });
//                     } else {
//                         reject(new Error('Invalid messages data'));
//                     }
//                 } catch (error) {
//                     console.error('Error processing messages:', error);
//                     reject(new Error('Error processing messages data'));
//                 }
//             };

//             socket.onerror = function (error) {
//                 console.error('WebSocket error:', error);
//                 reject(new Error('WebSocket error'));
//             };

//             socket.onclose = function () {
//                 console.log('WebSocket connection closed');
//             };
//         } catch (error) {
//             console.error('Error in WebSocket request', error);
//             reject(error);
//         }
//     });
// }

// async function loadAndScrollMessages(recipientId) {
//     try {
//         const { container } = await loadMessages(loggedInUserId, recipientId);
//         console.log('container', container);
//         const scroll = container.scrollTop = container.scrollHeight;
//         console.log('scroll', scroll);
//     } catch (error) {
//         console.error('Error loading messages:', error);
//     }
// }

// window.onload = function () {
//     loadAndScrollMessages(recipientId);
// };

