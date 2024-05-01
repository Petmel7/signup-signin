
// async function sendMessages(recipientId, event) {
//     event.preventDefault();

//     const messageTextarea = document.getElementById('messageTextarea');
//     const messageText = messageTextarea.value.trim();

//     if (messageText === '') {
//         alert('Please enter the text of the message.');
//         return;
//     }

//     try {
//         const messageTextarea = document.getElementById('messageTextarea');
//         const messageText = messageTextarea.value.trim();

//         if (messageText === '') {
//             alert('Please enter the text of the message.');
//             return;
//         }

//         const response = await fetch('hack/messages/messages.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 sender_id: loggedInUserId,
//                 recipient_id: recipientId,
//                 message_text: messageText
//             }),
//         });

//         if (response.ok) {
//             messageTextarea.value = '';

//             const messagesContainer = document.getElementById('messagesContainer');

//             await loadMessages(loggedInUserId, recipientId);

//             messagesContainer.scrollTop = messagesContainer.scrollHeight;
//         } else {
//             alert('Failed to send message');
//         }
//     } catch (error) {
//         console.log(error);
//         alert('Error in fetch request');
//     }

//     return;
// }


async function sendMessages(recipientId, event) {
    event.preventDefault();

    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();

    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }

    try {
        const socket = new WebSocket('ws://localhost:2346');

        socket.onopen = function (event) {
            console.log('Connected to WebSocket server');

            const message = {
                sender_id: loggedInUserId,
                recipient_id: recipientId,
                message_text: messageText
            };

            socket.send(JSON.stringify(message));
        };

        socket.onmessage = function (event) {
            console.log('Received message from server:', event.data);
            // Тут можна додати обробку отриманого повідомлення, якщо потрібно
        };

        socket.onclose = function (event) {
            console.log('Connection closed');
        };

        socket.onerror = function (error) {
            console.error('WebSocket error:', error);
            alert('WebSocket error');
        };

        messageTextarea.value = '';

        const messagesContainer = document.getElementById('messagesContainer');
        await loadMessages(loggedInUserId, recipientId);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

    } catch (error) {
        console.log(error);
        alert('Error connecting to WebSocket server');
    }
}



