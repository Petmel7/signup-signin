
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

                const encodedUsername = encodeURIComponent(sender.name);
                const avatarSrc = `hack/${sender.avatar}`;

                const imageSrc = `hack/${message.image_url}`;
                const messageContent = message.image_url ? '' : `<p class="change-color--title message-content ${recipientWhiteText}">${message.message_text}</p>`;
                const backgroundImage = message.image_url ? `background-image: url(${imageSrc})` : '';

                const backgroundImageSize = message.image_url ? 'max-width: 60%; width: 100%; max-height: 60%; height: 200px;' : '';
                const backgroundSizeCover = message.image_url ? 'background-size: cover; background-position: center center;' : ''
                const imageButtonStyle = message.image_url ? 'image-button--style' : '';
                // const imageTimeStyle = message.image_url ? 'image-time--style' : '';

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
    return {

    }
}

loadMessages(loggedInUserId, recipientId);

//<img style="" id="userImge" class="" src="${message.image_url}" alt="image"></img>
// const dateElement = document.getElementById('dateElement'); // Замість 'dateElement' вкажіть ідентифікатор елемента, де потрібно відобразити дату
// const messageDate = new Date('2024-03-17'); // Припустимо, що у вас є дата повідомлення

// dateElement.textContent = formatDate(messageDate); // Відображаємо дату з використанням функції formatDate

// &#8942;