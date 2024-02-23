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

</body>

</html>