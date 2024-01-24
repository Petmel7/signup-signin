<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
    $loggedInUserId = currentUserId();
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <div class="account">
        <img class="account-img" src="hack/<?php echo $userData['avatar']; ?>" width="200px" height="200px" alt="<?php echo $userData['name']; ?>">
        <h1 class="account-title"><?php echo $userData['name']; ?></h1>

        <!-- Передача loggedInUserId в JavaScript -->
        <script>
            let loggedInUserId = <?php echo json_encode($loggedInUserId); ?>;
        </script>

        <div id="subscription-buttons">
            <button id="subscribeButton" onclick="subscribe(<?php echo $userData['id']; ?>)">Підписатися</button>
            <button id="unsubscribeButton" onclick="unsubscribe(<?php echo $userData['id']; ?>)">Відписатися</button>
        </div>
    </div>

    <script src="js/subscribers.js"></script>

</body>

</html>