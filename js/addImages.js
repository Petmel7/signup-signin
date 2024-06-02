// document.addEventListener("DOMContentLoaded", function () {
//     document.getElementById("imagesButton").addEventListener("click", function () {
//         addImages();
//     });
// });

// async function addImages() {
//     const imagesForm = document.getElementById('imagesForm');
//     const formData = new FormData(imagesForm);

//     formData.append('sender_id', loggedInUserId);
//     formData.append('recipient_id', recipientId);

//     try {
//         const response = await fetch('hack/messages/add_images.php', {
//             method: 'POST',
//             body: formData
//         });

//         const result = await response.json();

//         if ('success' in result) {

//             await loadAndDisplayMessages(loggedInUserId, recipientId);

//         } else {
//             alert("Failed to add image");
//         }

//     } catch (error) {
//         console.log("error", error);
//     }
// }

// function handleImageChange() {
//     const fileInput = document.getElementById('addImages');
//     const imagesButton = document.getElementById('imagesButton');
//     const messageButton = document.getElementById('messageButton');
//     const messageTextarea = document.getElementById('messageTextarea');

//     if (fileInput.files.length > 0) {
//         imagesButton.style.display = 'block';
//         messageButton.style.display = 'none';
//     } else {
//         imagesButton.style.display = 'none';
//         messageButton.style.display = 'block';
//     }
// }

// document.addEventListener("DOMContentLoaded", function () {
//     document.getElementById('messageTextarea').addEventListener('click', function () {
//         handleMesageChange()
//     })
// })

// function handleMesageChange() {
//     const imagesButton = document.getElementById('imagesButton');
//     const messageButton = document.getElementById('messageButton');
//     const messageTextarea = document.getElementById('messageTextarea');

//     if (messageTextarea.value.trim() !== '') {
//         messageButton.style.display = 'none';
//         imagesButton.style.display = 'block';
//     } else {
//         messageButton.style.display = 'block';
//         imagesButton.style.display = 'none';
//     }
// }


// const socket = new WebSocket(`ws://localhost:2346/?sender_id=${loggedInUserId}&recipient_id=${recipientId}`);

// document.addEventListener("DOMContentLoaded", function () {
//     document.getElementById("imagesButton").addEventListener("click", function () {
//         addImages();
//     });
// });

// async function addImages() {
//     const imagesForm = document.getElementById('imagesForm');
//     const formData = new FormData(imagesForm);

//     formData.append('sender_id', loggedInUserId);
//     formData.append('recipient_id', recipientId);

//     try {
//         const response = await fetch('/upload_image.php', {
//             method: 'POST',
//             body: formData
//         });

//         const result = await response.json();

//         if (result.error) {
//             console.log(result.error);
//         } else {
//             const addImages = {
//                 action: 'add_image',
//                 image_url: result.image_url,
//                 sender_id: loggedInUserId,
//                 recipient_id: recipientId
//             };

//             socket.send(JSON.stringify(addImages));
//         }

//     } catch (error) {
//         console.log("error", error);
//     }
// }

// function handleImageChange() {
//     const fileInput = document.getElementById('addImages');
//     const imagesButton = document.getElementById('imagesButton');
//     const messageButton = document.getElementById('messageButton');
//     const messageTextarea = document.getElementById('messageTextarea');

//     if (fileInput.files.length > 0) {
//         imagesButton.style.display = 'block';
//         messageButton.style.display = 'none';
//     } else {
//         imagesButton.style.display = 'none';
//         messageButton.style.display = 'block';
//     }
// }

// document.addEventListener("DOMContentLoaded", function () {
//     document.getElementById('messageTextarea').addEventListener('click', function () {
//         handleMesageChange()
//     })
// })

// function handleMesageChange() {
//     const imagesButton = document.getElementById('imagesButton');
//     const messageButton = document.getElementById('messageButton');
//     const messageTextarea = document.getElementById('messageTextarea');

//     if (messageTextarea.value.trim() !== '') {
//         messageButton.style.display = 'none';
//         imagesButton.style.display = 'block';
//     } else {
//         messageButton.style.display = 'block';
//         imagesButton.style.display = 'none';
//     }
// }