<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
    $loggedInUserId = currentUserId();
    $recipientId = $userData['id'];

    echo "<script>let recipientId = " . json_encode($recipientId) . ";</script>";
    echo "<script>let loggedInUserId = " . json_encode($loggedInUserId) . ";</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <header class="user-header">
        <h1 class="user-name"><?php echo $userData['name'] ?></h1>
    </header>

    <section class="container textarea-container">

        <ul class="messages-container" id="messagesContainer"></ul>

        <div class="textarea" id="hideForm">
            <textarea class="message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
            <button class="message-button" type="button" onclick="sendMessages('<?php echo $userData['id']; ?>', event)">Send</button>
        </div>

        <div class="textarea" id="openEditForm" style="display: none">
            <div class="update-container">
                <button class="close-update--form" type="button" onclick="closeUpdateForm()">&times;</button>
                <textarea class="message-textarea search-friend__input" id="updateTextarea" placeholder="" rows="1"></textarea>
            </div>

            <div class="update-button" id="updateButton"></div>

        </div>
    </section>

    <script>
        let initialMessageText;

        async function openUpdateFormAndCloseModal(messageId) {
            try {
                const response = await fetch('hack/messages/get_update_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: messageId
                    }),
                });

                if (response.ok) {
                    const data = await response.json();
                    const messageText = data.message_text.message_text;

                    initialMessageText = messageText;

                    openUpdateForm(messageId, messageText);
                    closeModal();

                    const updateButton = document.getElementById('updateButton');

                    updateButton.innerHTML = `
                        <button id="disabledButton" class="message-button" type="..." disabled onclick="updateMessages(${messageId}, event)">Update</button>
                    `;
                } else {
                    console.error('Failed to get data from server');
                }
            } catch (error) {
                console.error('Помилка:', error);
            }
        }

        const updateTextarea = document.getElementById('updateTextarea');
        updateTextarea.addEventListener('input', updateUpdateButtonState);

        function updateUpdateButtonState() {
            const disabledButton = document.getElementById('disabledButton')

            const updatedMessageText = updateTextarea.value.trim();

            const isMessageChanged = updatedMessageText !== initialMessageText;

            isMessageChanged ?
                disabledButton.removeAttribute('disabled') :
                disabledButton.setAttribute('disabled', true);
        }

        function openUpdateForm(messageId, messageText) {
            const openEditForm = document.getElementById("openEditForm");
            const hideForm = document.getElementById("hideForm");
            const updateTextarea = document.getElementById('updateTextarea');

            openEditForm.style.display = 'block';
            hideForm.style.display = 'none';

            updateTextarea.value = messageText;
        }

        function closeUpdateForm() {
            const openEditForm = document.getElementById("openEditForm");
            const hideForm = document.getElementById("hideForm");

            openEditForm.style.display = 'none';
            hideForm.style.display = 'block';
        }
    </script>

    <script>
        async function updateMessages(messageId, event) {
            event.preventDefault();
            try {
                const updateTextarea = document.getElementById('updateTextarea');

                const updateMessageText = updateTextarea.value.trim();

                const response = await fetch("hack/messages/update_messages.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        message_id: messageId,
                        message_text: updateMessageText
                    }),
                });

                if (response.ok) {
                    const result = await response.json();

                    closeUpdateForm();
                    loadMessages(loggedInUserId, recipientId);
                }
            } catch (error) {
                console.log("error", error);
            }
        }
    </script>

    <script src="js/modal.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/autoTextareaExpansion.js"></script>
    <script src="utils/utilities.js"></script>
    <script src="utils/style.js"></script>
    <script src="js/sendMessages.js"></script>
    <script src="js/loadMessages.js"></script>
    <script src="js/deleteMessage.js"></script>
    <script src="js/markMessageAsViewed.js"></script>

</body>

</html>