
async function getMessageForAuthorizedUser(currentUserId) {
    const messagesContainer = document.getElementById('messagesContainer');
    try {
        const messagesResponse = await fetch('hack/actions/get-message-for-authorized-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                recipient_id: currentUserId
            }),
        });

        if (!messagesResponse.ok) {
            throw new Error('Failed to fetch messages');
        }
        const messagesData = await messagesResponse.json();

        if (Array.isArray(messagesData.message_authors)) {
            const messages = messagesData.message_authors;

            const userDataResponse = await fetch('hack/messages/get_user_by_id.php')

            if (!userDataResponse.ok) {
                throw new Error('Failed to fetch user info');
            }

            const userData = await userDataResponse.json();

            const messagesWithUserData = messages.map(message => {
                const sender = userData.find(user => user.id === message.sender_id);
                return {
                    ...message,
                    user: sender
                };
            });

            const messageCounts = {};

            messagesWithUserData.forEach(message => {
                const senderId = message.sender_id;
                if (!messageCounts[senderId]) {
                    messageCounts[senderId] = 0;
                }
                if (message.viewed === 0) {
                    messageCounts[senderId]++;
                }
            });

            const uniqueUsers = messagesWithUserData.map(message => {
                return message.sender_id;
            })
                .filter((sender_id, index, array) => array.indexOf(sender_id) === index)
                .map(uniqueSenderId => messagesWithUserData.find(message => message.sender_id === uniqueSenderId));

            const messagesHTML = uniqueUsers.map(message => {
                const unreadCount = messageCounts[message.id] ?? 0;
                return `
            <li class="message-conteaner">
                <a class="message-a" href='index.php?page=user-page-messages&username=${encodeURIComponent(message.user.name)}'>
                    <img class="message-img__who-wrote message-img" src='hack/${message.user.avatar}' alt='${message.user.name}'>
                    <div class="message-div">
                        <div class="message-blk">
                            <p class="message-name">${message.user.name}</p>
                            <p class="message-a__text">Sent you a message...</p>
                        </div>
                        <span class="message-badge" style="display: ${unreadCount > 0 ? 'block' : 'none'}">${unreadCount > 10 ? '9+' : unreadCount}</span>
                        <button class="message-a__button" onclick="deleteUser(event)">Delete</button>
                    </div>
                </a>
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

//         if (Array.isArray(messagesData.message_authors)) {
//             const messages = messagesData.message_authors;

//             const messagesWithUserData = messages.map(message => {
//                 const sender = userData.find(user => user.id === message.sender_id);
//                 return {
//                     ...message,
//                     user: sender
//                 };
//             });

//             const messageCounts = {};

//             messagesWithUserData.forEach(message => {
//                 const senderId = message.sender_id;
//                 if (!messageCounts[senderId]) {
//                     messageCounts[senderId] = 0;
//                 }
//                 if (message.viewed === 0) {
//                     messageCounts[senderId]++;
//                 }
//             });

//             const uniqueUsers = messagesWithUserData.map(message => {
//                 return message.sender_id;
//             })
//                 .filter((sender_id, index, array) => array.indexOf(sender_id) === index)
//                 .map(uniqueSenderId => messagesWithUserData.find(message => message.sender_id === uniqueSenderId));

//             const messagesHTML = uniqueUsers.map(message => {
//                 const unreadCount = messageCounts[message.id] ?? 0;

//                 return `
//             <li class="message-conteaner">
//                 <a class="message-a" href='index.php?page=user-page-messages&username=${encodeURIComponent(message.user.name)}'>
//                     <img class="message-img__who-wrote message-img" src='hack/${message.user.avatar}' alt='${message.user.name}'>
//                     <div class="message-div">
//                         <div class="message-blk">
//                             <p class="message-name">${message.user.name}</p>
//                             <p class="message-a__text">Sent you a message...</p>
//                         </div>
//                         <span class="message-badge" style="display: ${unreadCount > 0 ? 'block' : 'none'}">${unreadCount > 10 ? '9+' : unreadCount}</span>
//                         <button class="message-a__button" onclick="deleteUser(${message.id}, event)">Delete</button>
//                     </div>
//                 </a>
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

//         if (!messagesResponse.ok || !userDataResponse.ok) {
//             throw new Error('Failed to fetch messages');
//         }

//         const [messagesData, userData] = await Promise.all([
//             messagesResponse.json(),
//             userDataResponse.json()
//         ]);

//         if (Array.isArray(messagesData.message_authors)) {
//             const messages = messagesData.message_authors;

//             const messagesWithUserData = messages.map(message => {
//                 const sender = userData.find(user => user.id === message.sender_id);
//                 return {
//                     ...message,
//                     user: sender
//                 };
//             });

//             const messageCounts = {};

//             messagesWithUserData.forEach(message => {
//                 const senderId = message.sender_id;
//                 if (!messageCounts[senderId]) {
//                     messageCounts[senderId] = 0;
//                 }
//                 if (message.viewed === 0) {
//                     messageCounts[senderId]++;
//                 }
//             });

//             const uniqueUsers = messagesWithUserData
//                 .map(message => message.sender_id)
//                 .filter((sender_id, index, array) => array.indexOf(sender_id) === index)
//                 .map(uniqueSenderId => messagesWithUserData.find(message => message.sender_id === uniqueSenderId));

//             const messagesHTML = uniqueUsers.map(message => {
//                 const unreadCount = messageCounts[message.id] ?? 0;
//                 return `
//             <li class="message-conteaner">
//                 <a class="message-a" href='index.php?page=user-page-messages&username=${encodeURIComponent(message.user.name)}'>
//                     <img class="message-img__who-wrote message-img" src='hack/${message.user.avatar}' alt='${message.user.name}'>
//                     <div class="message-div">
//                         <div class="message-blk">
//                             <p class="message-name">${message.user.name}</p>
//                             <p class="message-a__text">Sent you a message...</p>
//                         </div>
//                         <span class="message-badge" style="display: ${unreadCount > 0 ? 'block' : 'none'}">${unreadCount > 10 ? '9+' : unreadCount}</span>
//                         <button class="message-a__button" onclick="deleteUser(${message.id}, event)">Delete</button>
//                     </div>
//                 </a>
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
