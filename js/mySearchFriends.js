let timer;

        async function mySearchFriends() {
            clearTimeout(timer);

            timer = setTimeout(async () => {
                const mySearchInput = document.getElementById('mySearchInput').value;
                const myFriendsDataContainer = document.getElementById('myFriendsDataContainer');

                try {
                    if (mySearchInput.trim() === '') {

                        myFriendsDataContainer.innerHTML = '';
                        await getFriendsData(loggedInUserId);
                        return;
                    }

                    const response = await fetch('hack/actions/search-friends.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: mySearchInput
                        }),
                    });

                    if (response.ok) {
                        const myFriends = await response.json();
                        myFriendsDataContainer.innerHTML = '';
                        myFriends.forEach(myFriend => {
                            myFriendsDataContainer.innerHTML += `
                            <li class="friend-list__li">
                                <a href='index.php?page=user&username=${encodeURIComponent(myFriend.name)}'>
                                    <img class="friend-list__img" src='hack/${myFriend.avatar}' alt='${myFriend.name}'>
                                    <p class="friend-list__name">${myFriend.name}</p>
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