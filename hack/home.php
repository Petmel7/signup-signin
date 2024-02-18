<?php
require_once __DIR__ . '/actions/helpers.php';

checkAuth();

$user = currentUser();
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

        <div class="account-button__block">
            <button class="account-button__delete" onclick="openModal()">Delete photo</button>
            <button class="me-messages">Messages
                <span>{1}</span>
            </button>
        </div>

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

        <button class="friends" type="button" onclick="forwarding()">Search friends</button>

        <form action=" hack/actions/logout.php" method="post">
            <button class="account-button" type="submit">Logout</button>
        </form>
    </div>

    <script src="js/forwarding.js"></script>
    <script src="js/photo-replacement.js"></script>
    <script src="js/delete-photo.js"></script>
</body>

</html>