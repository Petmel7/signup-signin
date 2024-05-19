// async function loadAndScrollMessages(recipientId) {
//     try {
//         messageTextarea.value = '';

//         const { container } = await loadMessages(loggedInUserId, recipientId);
//         console.log('container', container)

//         const scroll = container.scrollTop = container.scrollHeight;
//         console.log('scroll', scroll)

//     } catch (error) {
//         console.error('Error loading messages:', error);
//     }
// }

// window.onload = function () {
//     loadAndScrollMessages(recipientId);
// };

// // Create a WebSocket connection
// const socket = new WebSocket('ws://localhost:2346');

// // Emitted when the WebSocket connection is opened
// socket.onopen = function () {
//     console.log('WebSocket connection opened');
// };

// // Emitted when a message is received from the server
// socket.onmessage = async function (event) {
//     const message = JSON.parse(event.data);
//     // Display the received message on the page (for example, in a chat box)
//     console.log('Received message:', message);

//     await loadAndScrollMessages(recipientId);
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
//         const message = {
//             sender_id: loggedInUserId,
//             recipient_id: recipientId,
//             message_text: messageText
//         };

//         socket.send(JSON.stringify(message));;

//     } catch (error) {
//         console.log('sendMessage-Error', error);
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

const socket = new WebSocket('ws://localhost:2346/?id=' + recipientId);

socket.onopen = function () {
    console.log('WebSocket connection opened');
};

socket.onmessage = async function (event) {
    const message = JSON.parse(event.data);
    console.log('Received message:', message);
    await loadAndScrollMessages(message.recipient_id);
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

        socket.send(JSON.stringify(message));
    } catch (error) {
        console.log('sendMessage-Error', error);
    }
}
