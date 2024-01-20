// const friendsForm = document.getElementById('friendsForm');
//         friendsForm.addEventListener('submit', displayFriends);

//         async function displayFriends(event) {
//             event.preventDefault();

//             try {
//                 const response = await fetch('hack/actions/friends.php', {
//                     method: 'POST'
//                 });

//                 if (response.ok) {
//                     const friends = await response.json();
//                     const friendsContainer = document.getElementById('friendsDataContainer');
//                     friendsContainer.innerHTML = '';

//                     const friendsHTML = friends.map(friend => `
//                     <li class="friend-list__li">
//                         <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
//                             <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
//                             <p class="friend-list__name">${friend.name}</p>
//                         </a>
//                     </li>
//                 `).join('');

//                     friendsContainer.insertAdjacentHTML('beforeend', friendsHTML);
//                 } else {
//                     throw new Error('Network response was not ok.');
//                 }
//             } catch (error) {
//                 console.error('Error:', error);
//                 alert('Помилка');
//             }
//         }