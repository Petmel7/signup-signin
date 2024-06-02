
const socket = new WebSocket(`ws://localhost:2346/?sender_id=${loggedInUserId}&recipient_id=${recipientId}`);
// import { socket } from '../componentsJs/socket.js';

socket.onopen = function () {
    console.log('WebSocket connection opened');
};

socket.onmessage = async function (event) {
    const messagesData = JSON.parse(event.data);
    console.log('onmessage->messagesData', messagesData);

    if (messagesData.success) {
        const messages = messagesData.success.messages;
        const users = messagesData.success.users;

        if (!Array.isArray(messages) || !Array.isArray(users)) {
            console.error('Invalid messages or users format', { messages, users });
            return;
        }
        await displayMessages(messages, users);
    } else if (messagesData.delete) {
        const messages = messagesData.delete.messages;
        const users = messagesData.delete.users;

        if (!Array.isArray(messages) || !Array.isArray(users)) {
            console.error('Invalid messages or users format', { messages, users });
            return;
        }
        await displayMessages(messages, users);
    }
};

socket.onerror = function (error) {
    console.error('WebSocket error:', error);
};

socket.onclose = function () {
    console.log('WebSocket connection closed');
};

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

    if (socket.readyState === WebSocket.OPEN) {
        socket.send(JSON.stringify(message));
    } else {
        alert('WebSocket connection is not open.');
    }
    messageTextarea.value = '';
}

async function deleteMessage(messageId, event) {
    event.preventDefault();
    try {
        const deleteMessage = {
            action: 'delete',
            message_id: messageId
        }

        socket.send(JSON.stringify(deleteMessage));

    } catch (error) {
        console.error('Error:', error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("imagesButton").addEventListener("click", function () {
        addImages();
    });
});
//================================================================
// async function addImages() {
//     const imagesForm = document.getElementById('imagesForm');
//     const formData = new FormData(imagesForm);

//     formData.append('sender_id', loggedInUserId);
//     formData.append('recipient_id', recipientId);

//     try {
//         const response = await fetch('hack/messages/add_images.php', {
//             method: 'POST',
//             body: formData
//         });

//         const result = await response.json();

//         if (result.error) {
//             console.log(result.error);
//         } else {
//             const addImages = {
//                 action: 'add_image',
//                 image_url: result.image_url,
//                 sender_id: loggedInUserId,
//                 recipient_id: recipientId
//             };

//             socket.send(JSON.stringify(addImages));
//         }

//     } catch (error) {
//         console.log("error", error);
//     }
// }

// function handleImageChange() {
//     const fileInput = document.getElementById('addImages');
//     const imagesButton = document.getElementById('imagesButton');
//     const messageButton = document.getElementById('messageButton');
//     const messageTextarea = document.getElementById('messageTextarea');

//     if (fileInput.files.length > 0) {
//         imagesButton.style.display = 'block';
//         messageButton.style.display = 'none';
//     } else {
//         imagesButton.style.display = 'none';
//         messageButton.style.display = 'block';
//     }
// }

// document.addEventListener("DOMContentLoaded", function () {
//     document.getElementById('messageTextarea').addEventListener('click', function () {
//         handleMesageChange()
//     })
// })

// function handleMesageChange() {
//     const imagesButton = document.getElementById('imagesButton');
//     const messageButton = document.getElementById('messageButton');
//     const messageTextarea = document.getElementById('messageTextarea');

//     if (messageTextarea.value.trim() !== '') {
//         messageButton.style.display = 'none';
//         imagesButton.style.display = 'block';
//     } else {
//         messageButton.style.display = 'block';
//         imagesButton.style.display = 'none';
//     }
// }
//========================================================================
async function displayMessages(messages, users) {
    if (!Array.isArray(messages) || !Array.isArray(users)) {
        console.error('Invalid messages or users format', { messages, users });
        return;
    }

    const messagesContainer = document.getElementById('messagesContainer');

    let lastSenderId = null;
    let lastMessageTime = null;

    const messagesHTML = messages.map(message => {
        const senderId = message.sender_id;
        const isSender = senderId === loggedInUserId;
        const formattedTime = formatTime(message.sent_at);

        const { messageClass, displayStyle } = getMessageStyles(isSender);

        const currentTime = new Date(message.timestamp).getTime();
        const showAvatar = senderId !== lastSenderId || (currentTime - lastMessageTime > 60000);

        const { avatarDisplayStyle, marginLeftStyle, dynamicBorderStyle } = calculateStyles(showAvatar, isSender, displayStyle);

        const sender = users.find(user => user.id === senderId);

        if (!sender) {
            console.error('Sender not found:', senderId);
            return '';
        }

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
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}
