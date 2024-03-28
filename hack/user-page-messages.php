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
            <span class="mode-icon material-symbols-outlined" id="whiteModeIcon" onclick="toggleDarkModeAndRefresh()">wb_sunny</span>
            <span class="mode-icon--dark material-symbols-outlined" id="darkModeIcon" onclick="toggleDarkModeAndRefresh()">brightness_3</span>
        </div>
    </header>

    <section class="container textarea-container">

        <ul class="messages-container" id="messagesContainer"></ul>

        <div class="textarea" id="hideForm">
            <form id="imagesForm" enctype="multipart/form-data">
                <label class="add-images">
                    <span class="material-symbols-outlined custom-file--uploadImage">cloud_upload</span>
                    <input id="addImages" type="file" name="image" accept="image/*" style="display: none;" onchange="handleImageChange()">
                </label>
            </form>

            <textarea class="search-friend--add message-textarea search-friend__input" id="messageTextarea" placeholder="Write your message" rows="1"></textarea>
            <button id="messageButton" class="message-button" type="button" onclick="sendMessages('<?php echo $userData['id']; ?>', event)">Send</button>
            <button id="imagesButton" class="message-button images-button" type="button" style="display: none;">Send</button>
        </div>

        <div class="textarea" id="openEditForm" style="display: none">
            <div class="update-container">
                <button class="close-update--form" type="button" onclick="closeUpdateForm()">&times;</button>
                <textarea class="search-friend--add message-textarea search-friend__input" id="updateTextarea" placeholder="" rows="1"></textarea>
            </div>

            <div class="update-button" id="updateButton"></div>

        </div>
    </section>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("imagesButton").addEventListener("click", function() {
                addImages();
            });
        });

        async function addImages() {
            const imagesForm = document.getElementById('imagesForm');
            const formData = new FormData(imagesForm);

            formData.append('sender_id', loggedInUserId);
            formData.append('recipient_id', recipientId);

            try {
                const response = await fetch('hack/messages/add_images.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if ('success' in result) {
                    const messagesContainer = document.getElementById('messagesContainer');
                    await loadMessages(loggedInUserId, recipientId);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                } else {
                    alert("Failed to add image");
                }

            } catch (error) {
                console.log("error", error);
            }
        }
    </script>

    <script>
        function handleImageChange() {
            const fileInput = document.getElementById('addImages');
            const imagesButton = document.getElementById('imagesButton');
            const messageButton = document.getElementById('messageButton');
            const messageTextarea = document.getElementById('messageTextarea');

            if (fileInput.files.length > 0) {
                imagesButton.style.display = 'block';
                messageButton.style.display = 'none';
            } else {
                imagesButton.style.display = 'none';
                messageButton.style.display = 'block';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('messageTextarea').addEventListener('click', function() {
                handleMesageChange()
            })
        })

        function handleMesageChange() {
            const imagesButton = document.getElementById('imagesButton');
            const messageButton = document.getElementById('messageButton');
            const messageTextarea = document.getElementById('messageTextarea');

            if (messageTextarea.value.trim() !== '') {
                messageButton.style.display = 'none';
                imagesButton.style.display = 'block';
            } else {
                messageButton.style.display = 'block';
                imagesButton.style.display = 'none';
            }
        }
    </script>

</body>

</html>