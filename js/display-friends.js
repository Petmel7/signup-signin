
const friendsForm = document.getElementById('friendsForm');

async function displayFriends() {
    try {
        const response = await fetch('hack/actions/friends.php', {
            method: 'POST'
        });

        if (response.ok) {
            const friendsText = await response.text();

            const friends = JSON.parse(friendsText);
            const friendsContainer = document.getElementById('friendsDataContainer');

            const friendsHTML = friends.map(friend => `
                <li class="friend-list__li">
                    <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
                        <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
                        <p class="friend-list__name">${friend.name}</p>
                    </a>
                </li>
            `).join('');

            friendsContainer.insertAdjacentHTML('beforeend', friendsHTML);
        } else {
            throw new Error('Network response was not ok.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Помилка');
    }
}

displayFriends();








// const friendsForm = document.getElementById('friendsForm');

// async function displayFriends() {
//     try {
//         const response = await fetch('hack/actions/friends.php', {
//             method: 'POST'
//         });

//         if (response.ok) {
//             const friendsText = await response.text();

//             const friends = JSON.parse(friendsText);
//             const friendsContainer = document.getElementById('friendsDataContainer');
//             friendsContainer.innerHTML = '';

//             const currentUserSubscriptions = await getCurrentUserSubscriptions();

//             const friendsHTML = friends.map(friend => {
//                 const isSubscribed = currentUserSubscriptions.includes(String(friend.id));

//                 return `
//                     <li class="friend-list__li">
//                         <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
//                             <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
//                             <p class="friend-list__name">${friend.name}</p>
//                             <div id="subscription-buttons">
//                                 <button id="subscribeButton_${friend.id}" style="display: ${isSubscribed ? 'none' : 'block'}" onclick="subscribe(${friend.id})">Підписатися</button>
//                                 <button id="unsubscribeButton_${friend.id}" style="display: ${isSubscribed ? 'block' : 'none'}" onclick="unsubscribe(${friend.id})">Відписатися</button>
//                             </div>
//                         </a>
//                     </li>
//                 `;
//             }).join('');

//             friendsContainer.insertAdjacentHTML('beforeend', friendsHTML);
//         } else {
//             throw new Error('Network response was not ok.');
//         }
//     } catch (error) {
//         console.error('Error:', error);
//         alert('Помилка');
//     }
// }

// displayFriends();
