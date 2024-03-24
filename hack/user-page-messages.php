<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
    $loggedInUserId = currentUserId();
    $recipientId = $userData['id'];

    echo "<script>let recipientId = " . json_encode($recipientId) . ";</script>";
    echo "<script>let loggedInUserId = " . json_encode($loggedInUserId) . ";</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <header class="user-header">
        <h1 class="user-name"><?php echo $userData['name'] ?></h1>
        <div class="icon-block">
            <span class="mode-icon" id="whiteModeIcon" onclick="toggleDarkModeAndRefresh()">&#9728;</span>
            <span class="mode-icon--dark" id="darkModeIcon" onclick="toggleDarkModeAndRefresh()">&#127769;</span>
        </div>
    </header>

    <section class="container textarea-container">

        <ul class="messages-container" id="messagesContainer"></ul>

        <div class="textarea" id="hideForm">
            <form id="imagesForm" enctype="multipart/form-data">
                <label class="add-images">
                    <input id="addImages" class="" type="file" id="imagesId" name="images" accept="image/*">
                    <button type="button" onclick="addImages()">Send</button>
                </label>
            </form>

            <textarea class="search-friend--add message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
            <button class="message-button" type="button" onclick="sendMessages('<?php echo $userData['id']; ?>', event)">Send</button>
        </div>

        <div class="textarea" id="openEditForm" style="display: none">
            <div class="update-container">
                <button class="close-update--form" type="button" onclick="closeUpdateForm()">&times;</button>
                <textarea class="search-friend--add message-textarea search-friend__input" id="updateTextarea" placeholder="" rows="1"></textarea>
            </div>

            <div class="update-button" id="updateButton"></div>

        </div>
    </section>

    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     document.querySelector("button[type='button']").addEventListener("click", function() {
        //         addImages();
        //     });
        // });

        // async function addImages() {

        //     const imagesForm = document.getElementById('imagesForm');
        //     const formData = new FormData(imagesForm);

        //     try {
        //         const response = await fetch('hack/messages/add_images.php', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json'
        //             },
        //             body: JSON.stringify({
        //                 sender_id: loggedInUserId,
        //                 image_url: formData
        //             })
        //         });

        //         console.log('formData', formData);

        //         const result = await response.json();

        //         if (result.succes) {
        //             const imageHtml = document.getElementById('imageHtml');
        //             imageHtml.innerHTML = `
        //                 <img style="" id="userImge" class="" ${mesage.image_url} ?>" alt="image">
        //             `;

        //         } else {
        //             alert("Failed to add image");
        //         }

        //     } catch (error) {
        //         console.log("error", error);
        //     }
        // }




        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("button[type='button']").addEventListener("click", function() {
                addImages();
            });
        });

        function addImages() {
            var form = document.getElementById("imagesForm");
            var formData = new FormData(form);
            formData.append('sender_id', loggedInUserId);

            fetch('hack/messages/add_images.php', {
                    method: 'POST',
                    body: formData
                })

                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    console.log('loggedInUserId', loggedInUserId);
                    // Тут ви можете виконати додаткові дії в залежності від відповіді сервера
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

    <script src="js/updateMessages.js"></script>
    <script src="js/toggleDarkMode.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/autoTextareaExpansion.js"></script>
    <script src="js/loadMessages.js"></script>
    <script src="utils/utilities.js"></script>
    <script src="utils/style.js"></script>
    <script src="js/sendMessages.js"></script>
    <script src="js/deleteMessage.js"></script>
    <script src="js/markMessageAsViewed.js"></script>

</body>

</html>