<?php
require_once __DIR__ . '/actions/helpers.php';

$loggedInUserId = currentUserId();

echo "<script>let loggedInUserId = " . json_encode($loggedInUserId) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <section class="container">
        <form class="search-friend" id="searchForm">
            <input class="search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="mySearchSubscribers()">
        </form>

        <ul class="friend-list" id="friendsDataContainer"></ul>
    </section>

    <script src="js/mySubscribersList.js"></script>
    <script src="js/generateFriendListItem.js"></script>
    <script src="js/mySearchFriends.js"></script>
    <script src="js/mySearchSubscribers.js"></script>
    <script src="js/generateSearchListItem.js"></script>
    <script src="js/generateGetElementById.js"></script>
</body>

</html>