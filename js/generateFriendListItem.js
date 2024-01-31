function generateFriendListItem(friends) {
    const friendsContainer = document.getElementById('friendsDataContainer');
    friendsContainer.innerHTML = '';

    const friendsHTML = friends.map(friend => `
        <li class="friend-list__li">
            <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
                <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
                <p class="friend-list__name">${friend.name}</p>
            </a>
        </li>
    `).join('');

    friendsContainer.insertAdjacentHTML('beforeend', friendsHTML);
}