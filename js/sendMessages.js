async function sendMessages(recipientId, event) {
    event.preventDefault();

    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();

    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }

    try {
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
            loadMessages(loggedInUserId, recipientId);

            messageTextarea.value = '';
        } else {
            alert('Failed to message');
        }
    } catch (error) {
        console.log(error);
        alert('Error in fetch request');
    }
    return;
}