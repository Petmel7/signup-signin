let timer;

async function searchFriends() {
    clearTimeout(timer);

    timer = setTimeout(async () => {
        const searchInput = document.getElementById('searchInput').value;
        const friendsContainer = document.getElementById('friendsDataContainer');

        try {
            if (searchInput.trim() === '') {

                friendsContainer.innerHTML = '';
                await getFriendsData(loggedInUserId);
                return;
            }

            const response = await fetch('hack/actions/search-friends.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: searchInput
                }),
            });

            if (response.ok) {
                const friends = await response.json();
                friendsContainer.innerHTML = '';
                friends.forEach(friend => {
                    friendsContainer.innerHTML += `
                        <li class="friend-list__li">
                            <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
                                <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
                                <p class="friend-list__name">${friend.name}</p>
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
};