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
        <input class="search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="searchFriends()">
    </form>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/mySubscribersList.js"></script>
    <script src="js/generateFriendListItem.js"></script>
    <script src="js/search-friends.js"></script>
</body>

</html>