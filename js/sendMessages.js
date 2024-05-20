
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

// const socket = new WebSocket('ws://localhost:2346/?id=' + recipientId);

// socket.onopen = function () {
//     console.log('WebSocket connection opened');
// };

// socket.onmessage = async function (event) {
//     const message = JSON.parse(event.data);
//     console.log('Received message:', message);

//     if (message.echo_message) {
//         const echoMessage = message.echo_message;

//         if (Array.isArray(echoMessage)) {
//             await loadAndScrollMessages(echoMessage[0].recipient_id);
//         } else {
//             console.error('Expected an array of messages');
//         }
//     } else {
//         console.error('Invalid message format');
//     }
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

//         socket.send(JSON.stringify(message));
//     } catch (error) {
//         console.log('sendMessage-Error', error);
//     }
// }




async function loadAndScrollMessages(recipientId) {
    try {
        messageTextarea.value = '';

        const { container } = await loadMessages(loggedInUserId, recipientId);
        console.log('container', container);

        const scroll = container.scrollTop = container.scrollHeight;
        console.log('scroll', scroll);
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

window.onload = function () {
    loadAndScrollMessages(recipientId);
};

const socket = new WebSocket('ws://localhost:2346/?sender_id=' + loggedInUserId + '&recipient_id=' + recipientId);
// const socket = new WebSocket('ws://localhost:2346/?sender_id=' + loggedInUserId + '&recipient_id=' + recipientId + '&message_text=' + messageText);
console.log('socket', socket)

socket.onopen = function () {
    console.log('WebSocket connection opened');
};

socket.onmessage = async function (event) {
    const message = JSON.parse(event.data);
    console.log('Received message:', message);

    if (message.echo_message) {
        const echoMessage = message.echo_message;

        if (Array.isArray(echoMessage)) {
            await loadAndScrollMessages(echoMessage[0].recipient_id);
        } else {
            console.error('Expected an array of messages');
        }
    } else {
        console.error('Invalid message format');
    }
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
