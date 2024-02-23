async function getNumberMessages(currentUserId) {
    try {
        const response = await fetch('hack/actions/get-unviewed-messages-count.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                recipient_id: currentUserId
            }),
        });

        if (response.ok) {
            const messages = await response.json();

            const unviewedMessagesCount = messages.unviewed_messages_count;

            const spanElement = document.querySelector('.badge');
            spanElement.textContent = unviewedMessagesCount;

        } else {
            console.error('No messages found');
        }
    } catch (error) {
        console.error('Error in fetch request', error);
    }
}
getNumberMessages(currentUserId)