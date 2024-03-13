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
            <button class="close-update--form" type="button" onclick="closeUpdateForm()">&times;</button>
            <textarea class="message-textarea search-friend__input" id="updateTextarea" placeholder="Write your message" rows="1"></textarea>
            <button class="message-button" type="button" onclick="updateMessages(messageId, event)">Update</button>
        </div>
    </section>

    <script>
        // function openUpdateFormAndCloseModal(messageId) {
        //     openUpdateForm(messageId);
        //     closeModal();
        // }

        async function openUpdateFormAndCloseModal(messageId) {

            try {
                const response = await fetch('hack/messages/get_update_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: messageId,
                    }),
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log("data", data)
                    const messageText = data.message_text.message_text;
                    console.log("messageText->", messageText)
                    openUpdateForm(messageId, messageText);
                    closeModal();
                } else {
                    console.error('Не вдалося отримати дані з сервера');
                }
            } catch (error) {
                console.error('Помилка:', error);
            }
        }

        function openUpdateForm(messageId, messageText) {
            const openEditForm = document.getElementById("openEditForm");
            const hideForm = document.getElementById("hideForm");
            const updateTextarea = document.getElementById('updateTextarea');

            openEditForm.style.display = 'block';
            hideForm.style.display = 'none';

            updateTextarea.value = messageText;
            console.log("messageText", messageText)
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

                console.log("mesageId", mesageId);
                console.log("updateMessageText", updateMessageText);


                if (response.ok) {
                    const result = await response.json();
                    console.log("result", result);
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
    <script src="js/sendMessages.js"></script>
    <script src="js/loadMessages.js"></script>
    <script src="js/deleteMessage.js"></script>
    <script src="js/markMessageAsViewed.js"></script>

</body>

</html>