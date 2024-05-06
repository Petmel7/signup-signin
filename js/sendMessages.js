
// async function loadAndScrollMessages(recipientId) {
//     try {
//         messageTextarea.value = '';
//         const messagesContainer = document.getElementById('messagesContainer');
//         await loadMessages(loggedInUserId, recipientId);

//         messagesContainer.scrollTop = messagesContainer.scrollHeight;
//     } catch (error) {
//         console.error('Error loading messages:', error);
//     }
// }

// window.onload = function () {
//     loadAndScrollMessages(recipientId);
// };

// async function sendMessages(recipientId, event) {
//     event.preventDefault();

//     const messageTextarea = document.getElementById('messageTextarea');
//     const messageText = messageTextarea.value.trim();

//     if (messageText === '') {
//         alert('Please enter the text of the message.');
//         return;
//     }

//     try {
//         const socket = new WebSocket('ws://localhost:2346');
//         console.log('socket', socket);

//         socket.onopen = function (event) {
//             console.log('Connected to WebSocket server');

//             const message = {
//                 sender_id: loggedInUserId,
//                 recipient_id: recipientId,
//                 message_text: messageText
//             };

//             socket.send(JSON.stringify(message));
//             console.log('message', message)
//         };

//         socket.onmessage = async function (event) {
//             console.log('Received message from server:', event.data);

//             await loadAndScrollMessages(recipientId);

//         };

//         socket.onclose = function (event) {
//             console.log('Connection closed');
//         };

//         socket.onerror = function (error) {
//             console.error('WebSocket error:', error);
//             alert('WebSocket error');
//         };

//     } catch (error) {
//         console.log(error);
//         alert('Error connecting to WebSocket server');
//     }
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
}





// const receivedMessage = JSON.parse(event.data);
// console.log('receivedMessage', receivedMessage)
// console.log('receivedMessage.recipient_id', receivedMessage.recipient_id)

// Тут можна додати перевірку, чи користувач є отримувачем цього повідомлення
// if (receivedMessage.recipient_id === loggedInUserId) {
//     await loadAndScrollMessages(loggedInUserId); // Оновлюємо повідомлення та прокручуємо вниз для отримувача
// }