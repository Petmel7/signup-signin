<?php
require_once __DIR__ . '/actions/helpers.php';
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <form class="search-friend" id="searchForm">
        <input class="search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="searchFriends()">
    </form>

    <button class="my-friends__button" type="button" onclick="redirectToMyFriends()">My friends &rarr;</button>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/display-friends.js"></script>
    <script src="js/generateFriendListItem.js"></script>
    <script src="js/search-friends.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/generateSearchListItem.js"></script>
    <script src="js/generateGetElementById.js"></script>
</body>

</html>