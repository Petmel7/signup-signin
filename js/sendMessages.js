// async function loadAndScrollMessages(recipientId) {
//     try {
//         messageTextarea.value = '';

//         const { container } = await loadMessages(loggedInUserId, recipientId);

//         container.scrollTop = container.scrollHeight;
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

//             await loadAndScrollMessages(recipientId);

//         } else {
//             alert('Failed to send message');
//         }
//     } catch (error) {
//         console.log(error);
//         alert('Error in fetch request');
//     }
// }




// async function loadAndScrollMessages(recipientId) {
//     try {
//         messageTextarea.value = '';

//         const { container } = await loadMessages(loggedInUserId, recipientId);

//         container.scrollTop = container.scrollHeight;
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



async function loadAndScrollMessages(recipientId) {
    try {
        messageTextarea.value = '';

        const { container } = await loadMessages(loggedInUserId, recipientId);
        console.log('container', container)

        const scroll = container.scrollTop = container.scrollHeight;
        console.log('scroll', scroll)

    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

window.onload = function () {
    loadAndScrollMessages(recipientId);
};

// Create a WebSocket connection
// const socket = new WebSocket('ws://localhost:2346');
const socket = new WebSocket(`ws://localhost:2346/?recipient_id=${recipientId}`);

// Emitted when the WebSocket connection is opened
socket.onopen = function () {
    console.log('WebSocket connection opened');
};

// Emitted when a message is received from the server
socket.onmessage = async function (event) {
    const message = JSON.parse(event.data);
    // Display the received message on the page (for example, in a chat box)
    console.log('Received message:', message);

    await loadAndScrollMessages(recipientId);
};

async function sendMessages(recipientId, event) {
    event.preventDefault();

    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();

    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }

    try {
        const message = {
            sender_id: loggedInUserId,
            recipient_id: recipientId,
            message_text: messageText
        };

        socket.send(JSON.stringify(message));;

    } catch (error) {
        console.log('sendMessage-Error', error);
    }
}


// // Емітовано, коли WebSocket-з'єднання відкрите
// socket.onopen = function () {
//     // Відправити ідентифікатор користувача на сервер
//     const userId = recipientId; // Замініть це на ідентифікатор вашого користувача
//     console.log('userId', userId);
//     console.log('recipientId', recipientId);
//     socket.send(JSON.stringify({ recipient_id: userId }));
// };
