async function getFriendsData(loggedInUserId) {
            try {
                const response = await fetch('hack/subscription/get_subscriptions.php', {
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
                    const myFriends = await response.json();
                    console.log('myFriends', myFriends);
                    const myFriendsDataContainer = document.getElementById('myFriendsDataContainer');
                    myFriendsDataContainer.innerHTML = '';

                    const friendsHTML = myFriends.map(myFriend => `
                        <li class="friend-list__li">
                            <a href='index.php?page=user&username=${encodeURIComponent(myFriend.name)}'>
                                <img class="friend-list__img" src='hack/${myFriend.avatar}' alt='${myFriend.name}'>
                                <p class="friend-list__name">${myFriend.name}</p>
                            </a>
                        </li>
                    `).join('');

                    myFriendsDataContainer.insertAdjacentHTML('beforeend', friendsHTML);
                    return myFriends || [];
                } else {
                    console.error('Failed to fetch user subscriptions');
                    return [];
                }
            } catch (error) {
                console.error('Error in fetch request', error);
                return [];
            }
        }
        getFriendsData(loggedInUserId);