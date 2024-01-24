 <!-- <script>
     // Функція для зміни стану кнопок
     function updateButtons(isSubscribed) {
         const subscribeButton = document.getElementById('subscribeButton');
         const unsubscribeButton = document.getElementById('unsubscribeButton');

         console.log('isSubscribed', isSubscribed);

         if (isSubscribed) {
             subscribeButton.style.display = 'none';
             unsubscribeButton.style.display = 'block';
         } else {
             unsubscribeButton.style.display = 'none';
             subscribeButton.style.display = 'block';
         }
     }

     // Функція для підписки на користувача
     async function subscribe(userId) {
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
     }

     // Функція для відписки від користувача
     async function unsubscribe(userId) {
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
             console.log('db loggedInUserId', loggedInUserId);
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
     }

     // Перевірка і встановлення стану при завантаженні сторінки
     document.addEventListener('DOMContentLoaded', async function() {
         // Отримати інформацію про підписки поточного користувача
         const currentUserSubscriptions = await getCurrentUserSubscriptions();

         console.log('currentUserSubscriptions', currentUserSubscriptions);

         // Перевірка і встановлення стану кнопок на основі інформації від сервера
         const userTestId = loggedInUserId;

         console.log('userTestId', userTestId);
         console.log('loggedInUserId', loggedInUserId);

         const isSubscribed = currentUserSubscriptions.some(user => user.id === userTestId);
         updateButtons(isSubscribed);
     });

     // Функція для отримання інформації про підписки поточного користувача
     async function getCurrentUserSubscriptions() {
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
                 const result = await response.json();
                 return result || [];
             } else {
                 console.error('Failed to fetch user subscriptions');
                 return [];
             }
         } catch (error) {
             console.error('Error in fetch request', error);
             return [];
         }
     }
 </script> -->







 <!-- <script>
     // Зберегти інформацію про підписку в локальному сховищі
     function saveSubscription(userId) {
         let subscribedUsers = JSON.parse(localStorage.getItem('subscribedUsers')) || [];
         if (!subscribedUsers.includes(userId)) {
             subscribedUsers.push(userId);
             localStorage.setItem('subscribedUsers', JSON.stringify(subscribedUsers));
         }
     }

     // Видалити інформацію про підписку з локального сховища
     function removeSubscription(userId) {
         let subscribedUsers = JSON.parse(localStorage.getItem('subscribedUsers')) || [];
         let index = subscribedUsers.indexOf(userId);
         if (index !== -1) {
             subscribedUsers.splice(index, 1);
             localStorage.setItem('subscribedUsers', JSON.stringify(subscribedUsers));
         }
     }

     async function subscribe(userId) {
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
                 const result = await response.json();
                 // Підписка успішно додана
                 // Приховати кнопку "Підписатися" і показати "Відписатися"
                 document.getElementById('subscribeButton').style.display = 'none';
                 document.getElementById('unsubscribeButton').style.display = 'block';

                 // Зберегти інформацію про підписку в локальному сховищі
                 saveSubscription(userId);
             } else {
                 // Помилка при додаванні підписки
                 alert('Failed to subscribe');
             }
         } catch (error) {
             console.log(error);
             alert('Error in fetch request');
         }
     }

     async function unsubscribe(userId) {
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
                 // Підписка успішно видалена
                 // Приховати кнопку "Відписатися" і показати "Підписатися"
                 document.getElementById('unsubscribeButton').style.display = 'none';
                 document.getElementById('subscribeButton').style.display = 'block';

                 // Видалити інформацію про підписку з локального сховища
                 removeSubscription(userId);
             } else {
                 // Помилка при видаленні підписки
                 alert('Failed to unsubscribe');
             }
         } catch (error) {
             console.log(error);
             alert('Error in fetch request');
         }
     }

     // Перевірка і встановлення стану при завантаженні сторінки
     document.addEventListener('DOMContentLoaded', async function() {
         let subscribedUsers = JSON.parse(localStorage.getItem('subscribedUsers')) || [];

         // Ваш код для отримання інформації про підписки поточного користувача
         const currentUserSubscriptions = await getCurrentUserSubscriptions();

         // Перевірка і встановлення стану кнопок на основі інформації в локальному сховищі
         subscribedUsers.forEach(function(userId) {
             // Ваш код для встановлення стану кнопок
             if (currentUserSubscriptions.some(user => user.id === userId)) {
                 // Підписаний, приховати "Підписатися" і показати "Відписатися"
                 document.getElementById('subscribeButton').style.display = 'none';
                 document.getElementById('unsubscribeButton').style.display = 'block';
             } else {
                 // Не підписаний, приховати "Відписатися" і показати "Підписатися"
                 document.getElementById('unsubscribeButton').style.display = 'none';
                 document.getElementById('subscribeButton').style.display = 'block';
             }
         });
     });

     // Приклад функції для отримання інформації про підписки поточного користувача
     async function getCurrentUserSubscriptions() {
         try {
             const response = await fetch('hack/subscription/get_subscriptions.php', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                 },
                 body: JSON.stringify({
                     user_id: loggedInUserId, // Змініть це на відповідний спосіб отримання ID поточного користувача
                 }),
             });

             if (response.ok) {
                 const result = await response.json();
                 console.log('result', result);
                 return result || [];
             } else {
                 console.error('Failed to fetch user subscriptions');
                 return [];
             }
         } catch (error) {
             console.error('Error in fetch request', error);
             return [];
         }
     }
 </script> -->