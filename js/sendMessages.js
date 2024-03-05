async function sendMessages(recipientId, event) {
    event.preventDefault();

    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();

    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }

    // try {
    //     const response = await fetch('hack/messages/messages.php', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //         },
    //         body: JSON.stringify({
    //             sender_id: loggedInUserId,
    //             recipient_id: recipientId,
    //             message_text: messageText
    //         }),
    //     });

    //     if (response.ok) {
    //         loadMessages(loggedInUserId, recipientId);

    //         messageTextarea.value = '';
    //     } else {
    //         alert('Failed to message');
    //     }
    // } catch (error) {
    //     console.log(error);
    //     alert('Error in fetch request');
    // }

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
            // Завантаження повідомлень
            await loadMessages(loggedInUserId, recipientId);

            // Прокручування контейнера у верхню позицію
            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.scrollTop = 0;

            // Очищення текстового поля повідомлення
            messageTextarea.value = '';
        } else {
            alert('Failed to send message');
        }
    } catch (error) {
        console.log(error);
        alert('Error in fetch request');
    }

    return;
}