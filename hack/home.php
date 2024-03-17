<?php
require_once __DIR__ . '/actions/helpers.php';

checkAuth();

$user = currentUser();

$currentUserId = currentUserId();

echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <header class="user-header">
        <h1 class="user-name">Home</h1>
        <div class="icon-block">
            <span class="mode-icon" id="whiteModeIcon" onclick="toggleDarkMode()">&#9728;</span>
            <span class="mode-icon--dark" id="darkModeIcon" onclick="toggleDarkMode()">&#127769;</span>
        </div>
    </header>

    <div class="account">
        <form id="photoForm" enctype="multipart/form-data">
            <div class="change">
                <label class="change-photo">

                    <img id="userAvatar" class="account-img" src="hack/<?php echo $user['avatar']; ?>" width="200px" height="200px" alt="<?php echo $user['name']; ?>">

                    <input class="button-input" onchange="changePhoto()" type="file" id="avatar" name="avatar" accept="image/*">

                    <p class="change-photo__text">Change photo</p>
                </label>
            </div>
        </form>

        <button class="account-button__delete" onclick="openModal()">Delete photo</button>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <button class="account-button__delete" type="button" onclick="comfirmSubmit()">Confirm</button>
                <button class="account-button__delete" type="button" onclick="closeModal()">Cancel</button>
            </div>
        </div>

        <h1 id="accountTitle" class="change-color--title account-title"><?php echo $user['name']; ?></h1>

        <div class="me-messages__block">
            <button onclick="redirectToMyMtssages()" class="me-messages">Messages
                <span class="me-messages__span">🔔</span>
                <span class="badge"></span>
            </button>
            <button class="friends" type="button" onclick="forwarding()">Search friends</button>
        </div>

        <button class="account-button" onclick="logout(event)">Logout</button>

    </div>

    <script src="js/toggleDarkMode.js"></script>
    <script src="js/comfirmSubmit.js"></script>
    <script src="js/logout.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/changePhoto.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/getNumberMessages.js"></script>

</body>

</html>