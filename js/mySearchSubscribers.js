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