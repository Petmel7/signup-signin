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
        <input class="search-friend__input" type="text" id="mySearchSubscribersInput" name="mySearchSubscribersInput" placeholder="Search" required oninput="mySearchSubscribers()">
    </form>

    <ul id="mySubscribersContainer"></ul>

    <script>
        async function mySubscribersList(loggedInUserId) {
            try {
                const response = await fetch('hack/subscription/get_subscribers.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: loggedInUserId
                    }),
                });

                console.log('loggedInUserId', loggedInUserId);

                if (response.ok) {
                    const mySubscribers = await response.json();
                    console.log('mySubscribers', mySubscribers);
                    const mySubscribersContainer = document.getElementById('mySubscribersContainer');
                    mySubscribersContainer.innerHTML = '';

                    const friendsHTML = mySubscribers.map(mySubscriber => `
                        <li class="friend-list__li">
                            <a href='index.php?page=user&username=${encodeURIComponent(mySubscriber.name)}'>
                                <img class="friend-list__img" src='hack/${mySubscriber.avatar}' alt='${mySubscriber.name}'>
                                <p class="friend-list__name">${mySubscriber.name}</p>
                            </a>
                        </li>
                    `).join('');

                    mySubscribersContainer.insertAdjacentHTML('beforeend', friendsHTML);
                    return mySubscribers || [];
                } else {
                    console.error('Failed to fetch user subscriptions');
                    return [];
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return [];
            }
        }
        mySubscribersList(loggedInUserId);
    </script>

    <script>
        let timer;

        async function mySearchSubscribers() {
            clearTimeout(timer);

            timer = setTimeout(async () => {
                const mySearchSubscribersInput = document.getElementById('mySearchSubscribersInput').value;
                const mySubscribersContainer = document.getElementById('mySubscribersContainer');

                try {
                    if (mySearchSubscribersInput.trim() === '') {

                        mySubscribersContainer.innerHTML = '';
                        await mySubscribersList(loggedInUserId);
                        return;
                    }

                    const response = await fetch('hack/actions/search-friends.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: mySearchSubscribersInput
                        }),
                    });

                    if (response.ok) {

                        const mySubscribers = await response.json();
                        mySubscribersContainer.innerHTML = '';
                        mySubscribers.forEach(mySubscriber => {
                            mySubscribersContainer.innerHTML += `
                            <li class="friend-list__li">
                                <a href='index.php?page=user&username=${encodeURIComponent(mySubscriber.name)}'>
                                    <img class="friend-list__img" src='hack/${mySubscriber.avatar}' alt='${mySubscriber.name}'>
                                    <p class="friend-list__name">${mySubscriber.name}</p>
                                </a>
                            </li>`;
                        });
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Помилка');
                }
            }, 300);
        }
    </script>
</body>

</html>