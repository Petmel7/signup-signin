<?php
require_once __DIR__ . '/actions/helpers.php';

// if (isset($_GET['username'])) {
//     $username = $_GET['username'];
//     $userData = getUserDataByUsername($username);
//     $loggedInUserId = currentUserId();
// }
// var_dump('frieands-list $loggedInUserId', $loggedInUserId);
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <form class="search-friend" id="searchForm">
        <input class="search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="searchFriends()">
    </form>

    <!-- <script>
        let loggedInUserId = <?php echo json_encode($loggedInUserId); ?>;
        console.log('phploggedInUserId', loggedInUserId);
    </script> -->

    <button type="button" onclick="getFriendsData(loggedInUserId)">My friends</button>

    <ul id="myFriendsDataContainer"></ul>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/display-friends.js"></script>
    <script src="js/search-friends.js"></script>

    <script>
        async function getFriendsData(loggedInUserId) {
            // event.preventDefault();
            try {
                const response = await fetch('hack/subscription/get_subscriptions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: loggedInUserId,
                    }),
                });

                console.log('loggedInUserId', loggedInUserId);

                if (response.ok) {
                    const friends = await response.json();
                    console.log('friends', friends);
                    const friendsDataContainer = document.getElementById('friendsDataContainer');

                    friendsDataContainer.innerHTML = '';

                    const friendsHTML = friends.map(friend => `
                        <li class="friend-list__li">
                            <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
                                <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
                                <p class="friend-list__name">${friend.name}</p>
                            </a>
                        </li>
                        `).join('');

                    friendsDataContainer.innerHTML = friendsHTML;
                    return friends || [];
                } else {
                    console.error('Failed to fetch user subscriptions');
                    return [];
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return [];
            }
        }
    </script>

</body>

</html>