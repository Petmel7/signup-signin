// async function loadMessages(loggedInUserId, recipientId) {
//     const messagesContainer = document.getElementById('messagesContainer');
//     try {
// const response = await fetch('hack/messages/get_messages.php', {
//     method: 'POST',
//     headers: {
//         'Content-Type': 'application/json',
//     },
//     body: JSON.stringify({
//         sender_id: loggedInUserId,
//         recipient_id: recipientId
//     }),
// });

//         if (!response.ok) {
//             throw new Error('Failed to fetch messages or user info');
//         }

//         const messagesData = await response.json();
//         console.log('loadMessagesData:', messagesData);

//         const messages = messagesData.success.messages;
//         const users = messagesData.success.users;

//         let lastSenderId = null;
//         let lastMessageTime = null;

// const messagesHTML = messages.map(message => {
//     const senderId = message.sender_id;
//     const isSender = senderId === loggedInUserId;
//     const formattedTime = formatTime(message.sent_at);

//     const { messageClass, displayStyle } = getMessageStyles(isSender);

//     const currentTime = new Date(message.timestamp).getTime();

//     const showAvatar = senderId !== lastSenderId ?? (currentTime - lastMessageTime > 60000);

//     const {
//         avatarDisplayStyle,
//         marginLeftStyle,
//         dynamicBorderStyle
//     } = calculateStyles(showAvatar, isSender, displayStyle);

//     const sender = users.find(user => user.id === senderId);

//     if (showAvatar) {
//         lastSenderId = senderId;
//         lastMessageTime = currentTime;
//     }
//     const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-heder--sent_at' : 'message-header';

//     const {
//         backgroundSenderClass,
//         backgroundClassMessages,
//         recipientWhiteText,
//         messageDateStyleDisplay,
//         modalThemeStyle,
//         mesageButtonStyle
//     } = calculateStylesLocalStorage(isSender);

//     const {
//         encodedUsername,
//         messageContent,
//         backgroundImage,
//         backgroundImageSize,
//         backgroundSizeCover,
//         imageButtonStyle,
//         avatarSrc
//     } = processMessageData(sender, message, recipientWhiteText);

//     return `
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
// }).join('');
// messagesContainer.innerHTML = messagesHTML;
// return { container: messagesContainer, messages: messages };

//     } catch (error) {
//         console.error('Error in fetch request', error);
//         throw new Error('Failed to load messages');
//     }
// }
// loadMessages(loggedInUserId, recipientId);
