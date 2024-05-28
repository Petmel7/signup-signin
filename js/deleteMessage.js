async function deleteMessage(messageId, event) {
    event.preventDefault();
    try {
        const response = await fetch('hack/messages/delete_messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                message_id: messageId
            }),
        });

        // loadMessages(loggedInUserId, recipientId);
        await loadAndScrollMessages(loggedInUserId, recipientId);
    } catch (error) {
        console.error('Error:', error);
    }
}