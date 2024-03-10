
// async function getMessageForAuthorizedUser(currentUserId) {
//     const messagesContainer = document.getElementById('messagesContainer');
//     try {
//         const [messagesResponse, userDataResponse] = await Promise.all([
//             fetch('hack/actions/get-message-for-authorized-user.php', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                 },
//                 body: JSON.stringify({
//                     recipient_id: currentUserId
//                 }),
//             }),
//             fetch('hack/messages/get_user_by_id.php')
//         ]);

//         if (!messagesResponse.ok ?? !userDataResponse.ok) {
//             throw new Error('Failed to fetch messages');
//         }

//         const [messagesData, userData] = await Promise.all([
//             messagesResponse.json(),
//             userDataResponse.json()
//         ]);

//         if (messagesData.message_authors.length === 0) {
//             const noMessageContainer = document.getElementById('noMessageContainer');
//             const noMessagesText = `<h3 class="no-messages__text" >There are no new messages</h3>`;
//             noMessageContainer.innerHTML = noMessagesText;
//             return;
//         }

//         if (Array.isArray(messagesData.message_authors)) {
//             const messages = messagesData.message_authors;

//             const messageCounts = {};
//             const uniqueUsers = [];

//             messages.forEach(message => {
//                 const sender = userData.find(user => user.id === message.sender_id);
//                 const unreadCount = message.viewed === 0 ? 1 : 0;

//                 if (!messageCounts[message.sender_id]) {
//                     messageCounts[message.sender_id] = unreadCount;
//                     uniqueUsers.push({ ...message, user: sender });
//                 } else {
//                     messageCounts[message.sender_id] += unreadCount;
//                 }
//             });

//             const uniqueFilterUsers = uniqueUsers.filter((message, index, array) => {
//                 return array.findIndex(m => m.sender_id === message.sender_id) === index;
//             });

//             const messagesHTML = uniqueFilterUsers.map(message => {
//                 const unreadCount = messageCounts[message.sender_id] ?? 0;

//                 return `
//             <li class="message-conteaner">
//                 <div class="message messages">
//                     <a class="message-author" href='index.php?page=user-page-messages&username=${encodeURIComponent(message.user.name)}'>
//                     <img class="message-author--avatar message-img" src='hack/${message.user.avatar}' alt='${message.user.name}'>
//                 </a>
//                     <div class="message-details">
//                         <div class="message-header">
//                             <p class="message-author--name">${message.user.name}</p>
//                             <p class="message-content">Sent you a message...</p>
//                         </div>
//                         <span class="message-badge" style="display: ${unreadCount > 0 ? 'block' : 'none'}">${unreadCount > 10 ? '9+' : unreadCount}</span>

//                         <button class="message-delete--button" data-userid="${message.user.id}" onclick="openModalDeleteAllChat(${message.user.id})">🗑️</button>

//                         <div id="myModal" class="modal">
//                             <div class="modal-content" id="modalContentAllChat"></div>
//                         </div>
//                     </div>
//                 </div>
//             </li>`;
//             }).join('');

//             messagesContainer.innerHTML = messagesHTML;

//         } else {
//             console.error('No messages found');
//         }
//     } catch (error) {
//         console.error('Error in fetch request', error);
//     }
// }

// getMessageForAuthorizedUser(currentUserId);






async function getMessageForAuthorizedUser(currentUserId) {
    const messagesContainer = document.getElementById('messagesContainer');
    try {
        const [messagesResponse, userDataResponse] = await Promise.all([
            fetch('hack/actions/get-message-for-authorized-user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    recipient_id: currentUserId
                }),
            }),
            fetch('hack/messages/get_user_by_id.php')
        ]);

        if (!messagesResponse.ok ?? !userDataResponse.ok) {
            throw new Error('Failed to fetch messages');
        }

        const [messagesData, userData] = await Promise.all([
            messagesResponse.json(),
            userDataResponse.json()
        ]);

        if (messagesData.message_authors.length === 0) {
            const noMessageContainer = document.getElementById('noMessageContainer');
            const noMessagesText = `<h3 class="no-messages__text" >There are no new messages</h3>`;
            noMessageContainer.innerHTML = noMessagesText;
            return;
        }

        if (Array.isArray(messagesData.message_authors)) {
            const messages = messagesData.message_authors;

            const messageCounts = {};
            const uniqueUsers = [];

            messages.forEach(message => {
                const sender = userData.find(user => user.id === message.sender_id);
                const unreadCount = message.viewed === 0 ? 1 : 0;

                if (!messageCounts[message.sender_id]) {
                    messageCounts[message.sender_id] = unreadCount;
                    uniqueUsers.push({ ...message, user: sender });
                } else {
                    messageCounts[message.sender_id] += unreadCount;
                }
            });

            const uniqueFilterUsers = uniqueUsers.filter((message, index, array) => {
                return array.findIndex(m => m.sender_id === message.sender_id) === index;
            });

            const messagesHTML = uniqueFilterUsers.reverse().map(message => {
                const unreadCount = messageCounts[message.sender_id] ?? 0;
                const shortMessageText = message.message_text.substring(0, 25);
                // const lastMessageIndex = uniqueFilterUsers.length - 1;
                // const shortMessageText = uniqueFilterUsers[lastMessageIndex].message_text.substring(0, 25);

                console.log("shortMessageText", shortMessageText)

                const visibilityStyle = unreadCount > 0 ? 'block' : 'none'
                const unreadDisplay = unreadCount > 10 ? '9+' : unreadCount

                return `
            <li class="message-conteaner">
                <div class="message messages">
                    <a class="message-author" href='index.php?page=user-page-messages&username=${encodeURIComponent(message.user.name)}'>
                    <img class="message-author--avatar message-img" src='hack/${message.user.avatar}' alt='${message.user.name}'>
                </a>
                    <div class="message-details">
                        <div class="message-header">
                            <p class="message-author--name">${message.user.name}</p>
                            <p class="message-content">${shortMessageText}</p>
                        </div>
                        <span class="message-badge" style="display: ${visibilityStyle}">${unreadDisplay}</span>

                        <button class="message-delete--button" data-userid="${message.user.id}" onclick="openModalDeleteAllChat(${message.user.id})">🗑️</button>

                        <div id="myModal" class="modal">
                            <div class="modal-content" id="modalContentAllChat"></div>
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

getMessageForAuthorizedUser(currentUserId);
