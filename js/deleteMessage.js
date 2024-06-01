// async function deleteMessage(messageId, event) {
//     event.preventDefault();
//     try {
//         const response = await fetch('hack/messages/delete_messages.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 message_id: messageId
//             }),
//         });

//         const data = await response.json();
//         console.log('data', data);
//         if (data.success) {
//             const { messages, users } = await displayMessages(messages, users);
//             console.log('deleteMessage->messages', messages);
//             console.log('deleteMessage->users', users);
//         }

//     } catch (error) {
//         console.error('Error:', error);
//     }
// }