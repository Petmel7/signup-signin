<?php
require_once __DIR__ . '/actions/helpers.php';

$currentUserId = currentUserId();

echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <header class="user-header">
        <h1 class="user-name">Chats</h1>
    </header>

    <section class="container">
        <ul class="messages-container" id="messagesContainer"></ul>
        <div class="no-messages" id="noMessageContainer"></div>
    </section>

    <script src="js/modal.js"></script>
    <script>
        async function deleteUserAllChat(currentUserId, recipientId, event) {
            event.preventDefault();
            const clickedUserId = event.target.dataset.userid;
            try {
                const response = await fetch('hack/messages/delete-all-messages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        sender_id: currentUserId,
                        recipient_id: clickedUserId
                    }),
                });

                if (response.ok) {
                    const result = await response.json();

                    refreshPage();
                } else {
                    console.log("response error");
                }

            } catch (error) {
                console.error('Error:', error);
            }
        }

        function refreshPage() {
            window.location.reload();
        }
    </script>

    <script src="js/getMessageForAuthorizedUser.js"></script>

</body>

</html>