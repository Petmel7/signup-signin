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
    <div class="account">

        <form id="photoForm" action="hack/actions/change-photo.php" method="post" enctype="multipart/form-data">
            <div class="change">
                <label class="change-photo">
                    <input class="button-input" type="file" id="avatar" name="avatar" accept="image/*" <?php echo validationErrorAttr(fieldName: 'avatar'); ?>>

                    <img class="account-img" src="hack/<?php echo $user['avatar']; ?>" width="200px" height="200px" alt="<?php echo $user['name']; ?>">

                    <p class="change-photo__text">Change photo</p>

                    <?php if (hasValidationError(fieldName: 'avatar')) : ?>
                        <small class="notise"><?php echo validationErrorMessage(fieldName: 'avatar'); ?></small>
                    <?php endif; ?>
                </label>
            </div>
        </form>

        <button class="account-button__delete" onclick="openModal()">Delete photo</button>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <p class="modal-content__text">Are you sure you want to delete this photo?</p>
                <form action="hack/actions/delete-photo.php" method="post">
                    <button class="account-button__delete" type="submit">Confirm</button>
                    <button class="account-button__delete" type="button" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>

        <h1 class="account-title"><?php echo $user['name']; ?></h1>

        <div class="me-messages__block">
            <button onclick="redirectToMyMtssages()" class="me-messages">Messages
                <span class="me-messages__span">🔔</span>
                <span class="badge"></span>
            </button>
            <button class="friends" type="button" onclick="forwarding()">Search friends</button>
        </div>

        <button class="account-button" onclick="logout(event)">Logout</button>

    </div>
    <script src="js/logout.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/photo-replacement.js"></script>
    <script src="js/delete-photo.js"></script>
    <script src="js/getNumberMessages.js"></script>

</body>

</html>