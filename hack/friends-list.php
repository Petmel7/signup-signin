<?php
require_once __DIR__ . '/actions/helpers.php';

// $loggedInUsername = getLoggedInUsername();
// $friends = getSubscriptions($loggedInUsername);

// header('Content-Type: application/json');
// echo json_encode($friends);

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

    <form class="search-friend" id="searchForm">
        <input class="search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="searchFriends()">
    </form>

    <!-- <script>
        let loggedInUserId = <?php echo json_encode($loggedInUserId); ?>;
    </script> -->

    <!-- <form action="hack/subscription/get_subscriptions.php" method="post" id="myFriendsForm"> -->
    <button type="button" onclick="getFriendsData()">My friends</button>
    <!-- </form> -->


    <ul id="myFriendsDataContainer"></ul>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/display-friends.js"></script>
    <script src="js/search-friends.js"></script>

    <script>
        async function getFriendsData(event) {
            event.preventDefault();
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

    <!-- <script>
        
        document.addEventListener('DOMContentLoaded', async function() {
            const friendsDataContainer = document.getElementById('myFriendsDataContainer');
            const myFriendsForm = document.getElementById('myFriendsForm');

            myFriendsForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const friends = await getFriendsData();

                // Видалити попередні елементи з контейнера
                friendsDataContainer.innerHTML = '';

                // Відображення друзів на сторінці
                friends.forEach(friend => {
                    const friendItem = document.createElement('li');
                    friendItem.textContent = friend.name; // Тут можна використати інші властивості з об'єкта друга (наприклад, avatar)

                    friendsDataContainer.appendChild(friendItem);
                });
            });
        });
    </script> -->
</body>

</html>