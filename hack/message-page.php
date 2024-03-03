<?php
require_once __DIR__ . '/actions/helpers.php';

// if (isset($_GET['username'])) {
//     $username = $_GET['username'];
//     $userData = getUserDataByUsername($username);
//     $currentUserId = currentUserId();
//     $recipientId = $userData['id'];

// echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";
// echo "<script>let recipientId = " . json_encode($recipientId) . ";</script>";
// }

$currentUserId = currentUserId();
echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <section class="container">
        <ul class="message-block" id="messagesContainer"></ul>
        <div class="no-messages" id="noMessageContainer"></div>
    </section>

    <script>
        async function deleteUser(currentUserId, recipientId, event) {
            event.preventDefault();
            try {
                const response = await fetch('hack/messages/delete-all-messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        // message_id: messageId,
                        sender_id: currentUserId,
                        recipent_id: recipientId
                    }),
                });

                console.log("currentUserId", currentUserId)
                console.log("recipientId", recipientId)

                getMessageForAuthorizedUser(currentUserId)

            } catch (error) {
                console.error('Error:', error);
            }
        }
        // deleteUser(currentUserId, recipientId, event)
    </script>

    <script src="js/getMessageForAuthorizedUser.js"></script>

</body>

</html>