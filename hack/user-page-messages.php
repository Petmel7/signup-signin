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

    <section class="container textarea-container">
        <ul class="messages-container" id="messagesContainer"></ul>

        <div class="textarea">
            <textarea class="message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
            <button class="message-button" type="button" onclick="sendMessages('<?php echo $userData['id']; ?>', event)">Send</button>
        </div>
    </section>

    <script src="js/modal.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/autoTextareaExpansion.js"></script>
    <script src="js/sendMessages.js"></script>
    <script src="js/loadMessages.js"></script>
    <script src="js/deleteMessage.js"></script>
    <script src="js/markMessageAsViewed.js"></script>

</body>

</html>