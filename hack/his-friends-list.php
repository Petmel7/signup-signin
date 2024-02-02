<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <form class="search-friend" id="searchForm">
        <input class="search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="mySearchFriends()">
        <button class="subscription-buttons" type="button" onclick="redirectionHisSubscribers('<?php echo $username; ?>')">His subscribers</button>
    </form>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/forwarding.js"></script>
    <script src="js/hisGetFriendsData.js"></script>
    <script src="js/generateFriendListItem.js"></script>
    <script>
        hisGetFriendsData(<?php echo $userData['id']; ?>);
    </script>
    <script src="js/search-friends.js"></script>
    <!-- <script src="js/mySearchFriends.js"></script> -->
</body>

</html>