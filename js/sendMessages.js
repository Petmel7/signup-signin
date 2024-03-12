// import { loadMessages } from './loadMessages.js';

async function sendMessages(recipientId, event) {
    event.preventDefault();

    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();

    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }

    try {
        const messageTextarea = document.getElementById('messageTextarea');
        const messageText = messageTextarea.value.trim();

        if (messageText === '') {
            alert('Please enter the text of the message.');
            return;
        }

        const response = await fetch('hack/messages/messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                sender_id: loggedInUserId,
                recipient_id: recipientId,
                message_text: messageText
            }),
        });

        if (response.ok) {
            messageTextarea.value = '';

            const messagesContainer = document.getElementById('messagesContainer');

            await loadMessages(loggedInUserId, recipientId);

            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } else {
            alert('Failed to send message');
        }
    } catch (error) {
        console.log(error);
        alert('Error in fetch request');
    }

    return;
}
