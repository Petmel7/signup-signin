// let initialMessageText;

// async function openUpdateFormAndCloseModal(messageId) {
//     try {
//         const response = await fetch('hack/messages/get_update_messages.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 id: messageId
//             }),
//         });

//         if (response.ok) {
//             const data = await response.json();
//             const messageText = data.message_text.message_text;

//             initialMessageText = messageText;

//             openUpdateForm(messageId, messageText);
//             closeModal();

//             const updateButton = document.getElementById('updateButton');

//             updateButton.innerHTML = `
//                         <button id="disabledButton" class="message-button" type="..." disabled onclick="updateMessages(${messageId}, event)">Update</button>
//                     `;
//         } else {
//             console.error('Failed to get data from server');
//         }
//     } catch (error) {
//         console.error('Помилка:', error);
//     }
// }

// const updateTextarea = document.getElementById('updateTextarea');
// updateTextarea.addEventListener('input', updateUpdateButtonState);

// function updateUpdateButtonState() {
//     const disabledButton = document.getElementById('disabledButton')

//     const updatedMessageText = updateTextarea.value.trim();

//     const isMessageChanged = updatedMessageText !== initialMessageText;

//     isMessageChanged ?
//         disabledButton.removeAttribute('disabled') :
//         disabledButton.setAttribute('disabled', true);
// }

// function openUpdateForm(messageId, messageText) {
//     const openEditForm = document.getElementById("openEditForm");
//     const hideForm = document.getElementById("hideForm");
//     const updateTextarea = document.getElementById('updateTextarea');

//     openEditForm.style.display = 'block';
//     hideForm.style.display = 'none';

//     updateTextarea.value = messageText;
// }

// function closeUpdateForm() {
//     const openEditForm = document.getElementById("openEditForm");
//     const hideForm = document.getElementById("hideForm");

//     openEditForm.style.display = 'none';
//     hideForm.style.display = 'block';
// }

// async function updateMessages(messageId, event) {
//     event.preventDefault();
//     try {
//         const updateTextarea = document.getElementById('updateTextarea');

//         const updateMessageText = updateTextarea.value.trim();

//         const response = await fetch("hack/messages/update_messages.php", {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 message_id: messageId,
//                 message_text: updateMessageText
//             }),
//         });

//         if (response.ok) {
//             const result = await response.json();

//             closeUpdateForm();
//             loadMessages(loggedInUserId, recipientId);
//         }
//     } catch (error) {
//         console.log("error", error);
//     }
// }