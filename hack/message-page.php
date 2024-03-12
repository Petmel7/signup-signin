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
    <script src="utils/utilities.js"></script>
    <script src="js/deleteUserAllChat.js"></script>
    <script type="module" src="js/getMessageForAuthorizedUser.js"></script>

</body>

</html>