<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
    $loggedInUserId = currentUserId();

    echo "<script>let loggedInUserId = " . json_encode($loggedInUserId) . ";</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <div class="account">
        <img class="account-img" src="hack/<?php echo $userData['avatar']; ?>" width="200px" height="200px" alt="<?php echo $userData['name']; ?>">
        <h1 class="account-title"><?php echo $userData['name']; ?></h1>

        <div class="subscription" id="subscription-buttons">
            <button class="subscription-buttons" id="subscribeButton" onclick="subscribe(<?php echo $userData['id']; ?>)">Subscribe</button>
            <button class="subscription-buttons" id="unsubscribeButton" onclick="unsubscribe(<?php echo $userData['id']; ?>)">Unsubscribe</button>
        </div>

        <button class="subscription-buttons" onclick="redirectionToMessages('<?php echo $userData['name']; ?>')">Messages</button>

        <button class="subscription-buttons" type="button" onclick="redirectionHisFriends('<?php echo $userData['name']; ?>')">His friends</button>
    </div>

    <script src="js/toggleDarkMode.js"></script>
    <script src="js/subscribers.js"></script>
    <script src="js/forwarding.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getCurrentUserSubscriptions(<?php echo $userData['id']; ?>);
        });
    </script>

</body>

</html>