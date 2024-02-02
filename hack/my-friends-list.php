<?php
require_once __DIR__ . '/actions/helpers.php';

$loggedInUserId = currentUserId();

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<script>
    let loggedInUserId = <?php echo isset($loggedInUserId) ? json_encode($loggedInUserId) : 'null'; ?>;
    console.log('loggedInUserId', loggedInUserId);
</script>

<body>
    <form class="search-friend" id="searchForm">
        <input class="search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="mySearchFriends()">
        <button class="my-friends__button" type="button" onclick="redirectToMySubscribers()">Subscribers &rarr;</button>
    </form>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/getFriendsData.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/generateFriendListItem.js"></script>
    <script src="js/mySearchFriends.js"></script>
    <script src="js/mySearchSubscribers.js"></script>
    <script src="js/generateSearchListItem.js"></script>
    <script src="js/generateGetElementById.js"></script>
</body>

</html>