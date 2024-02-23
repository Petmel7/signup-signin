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

    <div class="message-conteaner">
        <ul class="message-block" id="messagesContainer"></ul>

        <form>
            <label for="messageTextarea">
                <textarea class="message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
            </label>
            <button class="message-button" type="button" onclick="sendMessages('<?php echo $userData['id']; ?>', event)">Send</button>
        </form>

    </div>

    <script src="js/forwarding.js"></script>
    <script src="js/аutoTextareaExpansion.js"></script>
    <script src="js/sendMessages.js"></script>
    <script src="js/loadMessages.js"></script>
    <script src="js/deleteMessage.js"></script>
    <script src="js/markMessageAsViewed.js"></script>

</body>

</html>