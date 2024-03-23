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
        <div class="icon-block">
            <span class="mode-icon" id="whiteModeIcon" onclick="toggleDarkModeAndRefresh()">&#9728;</span>
            <span class="mode-icon--dark" id="darkModeIcon" onclick="toggleDarkModeAndRefresh()">&#127769;</span>
        </div>
    </header>

    <section class="container textarea-container">

        <ul class="messages-container" id="messagesContainer"></ul>

        <div class="textarea" id="hideForm">
            <textarea class="search-friend--add message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
            <button class="message-button" type="button" onclick="sendMessages('<?php echo $userData['id']; ?>', event)">Send</button>
        </div>

        <div class="textarea" id="openEditForm" style="display: none">
            <div class="update-container">
                <button class="close-update--form" type="button" onclick="closeUpdateForm()">&times;</button>
                <textarea class="search-friend--add message-textarea search-friend__input" id="updateTextarea" placeholder="" rows="1"></textarea>
            </div>

            <div class="update-button" id="updateButton"></div>

        </div>
    </section>

    <script src="js/updateMessages.js"></script>
    <script src="js/toggleDarkMode.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/autoTextareaExpansion.js"></script>
    <script src="js/loadMessages.js"></script>
    <script src="utils/utilities.js"></script>
    <script src="utils/style.js"></script>
    <script src="js/sendMessages.js"></script>
    <script src="js/deleteMessage.js"></script>
    <script src="js/markMessageAsViewed.js"></script>

</body>

</html>