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
        <input class="search-friend__input" type="text" id="mySearchInput" name="mySearchInput" placeholder="Search" required oninput="mySearchFriends()">
    </form>

    <ul id="hisFriendsDataContainer"></ul>

    <!-- <script>
        async function hisGetFriendsData(subscriberId, targetUserId) {
            try {
                const response = await fetch('hack/subscription/get_subscriptions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        subscriber_id: subscriberId,
                        target_user_id: targetUserId
                    }),
                });
                console.log('subscriberId', subscriberId);
                console.log('targetUserId', targetUserId)
                if (response.ok) {
                    const hisFriends = await response.json();
                    console.log('hisFriends', hisFriends);
                    const hisFriendsDataContainer = document.getElementById('hisFriendsDataContainer');
                    hisFriendsDataContainer.innerHTML = '';

                    const friendsHTML = hisFriends.map(hisFriend => `
                        <li class="friend-list__li">
                            <a href='index.php?page=user&username=${encodeURIComponent(hisFriend.name)}'>
                                <img class="friend-list__img" src='hack/${hisFriend.avatar}' alt='${hisFriend.name}'>
                                <p class="friend-list__name">${hisFriend.name}</p>
                            </a>
                        </li>
                    `).join('');

                    hisFriendsDataContainer.insertAdjacentHTML('beforeend', friendsHTML);
                    return hisFriends || [];
                } else {
                    console.error('Failed to fetch user subscriptions');
                    return [];
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return [];
            }
        }

        hisGetFriendsData(21);
    </script> -->

    <script>
        async function viewSubscriptions(userId) {
            try {
                const response = await fetch(`hack/subscription/get_subscriptions.php?user_id=${userId}`);

                if (response.ok) {
                    const subscriptions = await response.json();
                    // Обробка отриманих підписок, наприклад, вивід на сторінку
                    console.log(subscriptions);
                } else {
                    console.error('Failed to fetch user subscriptions');
                }
            } catch (error) {
                console.error('Error in fetch request', error);
            }
        }

        viewSubscriptions(<?php echo $userData['id']; ?>)
    </script>
</body>

</html>

<!-- user_id: hisUserId -->