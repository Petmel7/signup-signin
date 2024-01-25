
// // Функція для зміни стану кнопок
// function updateButtons(isSubscribed) {
//     const subscribeButton = document.getElementById('subscribeButton');
//     const unsubscribeButton = document.getElementById('unsubscribeButton');

//     if (isSubscribed) {
//         subscribeButton.style.display = 'none';
//         unsubscribeButton.style.display = 'block';
//     } else {
//         unsubscribeButton.style.display = 'none';
//         subscribeButton.style.display = 'block';
//     }

//     localStorage.setItem(`isSubscribed_${loggedInUserId}`, isSubscribed);  // Виправлено ключ
// }

// // Перевірка і встановлення стану при завантаженні сторінки
// document.addEventListener('DOMContentLoaded', async function() {
    
//     // Отримати збережений стан з localStorage
//     const isSubscribed = localStorage.getItem(`isSubscribed_${loggedInUserId}`) === 'true';

//     updateButtons(isSubscribed);
// });

// // Функція для підписки на користувача
// async function subscribe(userId) {
//     try {
//         const response = await fetch('hack/subscription/add_subscription.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 subscriber_id: loggedInUserId,
//                 target_user_id: userId
//             }),
//         });

//         if (response.ok) {
//              // Оновити інтерфейс
//             updateButtons(true);
//         } else {
//             alert('Failed to subscribe');
//         }
//     } catch (error) {
//         console.log(error);
//         alert('Error in fetch request');
//     }
// }

// // Функція для відписки від користувача
// async function unsubscribe(userId) {
//     try {
//         const response = await fetch('hack/subscription/remove_subscription.php', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({
//                 subscriber_id: loggedInUserId,
//                 target_user_id: userId
//             }),
//         });

//         if (response.ok) {
//              // Оновити інтерфейс
//             updateButtons(false);
//         } else {
//             alert('Failed to unsubscribe');
//         }
//     } catch (error) {
//         console.log(error);
//         alert('Error in fetch request');
//     }
// }











// Функція для зміни стану кнопок
function updateButtons(isSubscribed) {
    const subscribeButton = document.getElementById('subscribeButton');
    const unsubscribeButton = document.getElementById('unsubscribeButton');

    if (isSubscribed) {
        subscribeButton.style.display = 'none';
        unsubscribeButton.style.display = 'block';
    } else {
        unsubscribeButton.style.display = 'none';
        subscribeButton.style.display = 'block';
    }
    console.log('isSubscribed', isSubscribed)
}

// Оновлена функція для отримання інформації про підписки поточного користувача
async function getCurrentUserSubscriptions(loggedInUserId) {
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
            const subscriptions = await response.json();

            // Вибрати лише id користувачів зі списку підписок
            const subscribedUserIds = subscriptions.map(user => user.id);

            return subscribedUserIds || [];
        } else {
            console.error('Failed to fetch user subscriptions');
            return [];
        }
    } catch (error) {
        console.error('Error in fetch request', error);
        return [];
    }
}

// Оновлена функція для перевірки стану підписки перед відправкою запиту на підписку
async function subscribe(userId) {
    const currentUserSubscriptions = await getCurrentUserSubscriptions(loggedInUserId);

    // Перевірити, чи ви вже підписані на цього користувача
    const isSubscribed = currentUserSubscriptions.some(user => user.id === userId);

    if (!isSubscribed) {
        try {
            const response = await fetch('hack/subscription/add_subscription.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    subscriber_id: loggedInUserId,
                    target_user_id: userId
                }),
            });

            if (response.ok) {
                // Оновити інтерфейс
                updateButtons(true);
            } else {
                alert('Failed to subscribe');
            }
        } catch (error) {
            console.log(error);
            alert('Error in fetch request');
        }
    } else {
        alert('You are already subscribed to this user.');
    }
}

// Оновлена функція для перевірки стану підписки перед відправкою запиту на відписку
async function unsubscribe(userId) {
    const currentUserSubscriptions = await getCurrentUserSubscriptions(loggedInUserId);

    // Перевірити, чи ви підписані на цього користувача
    const isSubscribed = currentUserSubscriptions.some(user => user.id === userId);

    if (isSubscribed) {
        try {
            const response = await fetch('hack/subscription/remove_subscription.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    subscriber_id: loggedInUserId,
                    target_user_id: userId
                }),
            });

            if (response.ok) {
                // Оновити інтерфейс
                updateButtons(false);
            } else {
                alert('Failed to unsubscribe');
            }
        } catch (error) {
            console.log(error);
            alert('Error in fetch request');
        }
    } else {
        alert('You are not subscribed to this user.');
    }
}

// Перевірка і встановлення стану при завантаженні сторінки
document.addEventListener('DOMContentLoaded', async function () {
    const userTestId = loggedInUserId;
    const currentUserSubscriptions = await getCurrentUserSubscriptions(loggedInUserId);

    // Перевірити, чи користувач підписаний
    const isSubscribed = currentUserSubscriptions.includes(userTestId);

    updateButtons(isSubscribed);
});
