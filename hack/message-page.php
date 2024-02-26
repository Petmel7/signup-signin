<?php
require_once __DIR__ . '/actions/helpers.php';

$currentUserId = currentUserId();

echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <ul class="message-block" id="messagesContainer"></ul>

    <script src="js/getMessageForAuthorizedUser.js"></script>
    <script>
        async function deleteUser(messageId, event) {
            event.preventDefault();
            try {
                const response = await fetch('hack/messages/delete_messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        message_id: messageId
                    }),
                });

                console.log("messageId", messageId)

                getMessageForAuthorizedUser(currentUserId)

            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>

</body>

</html>