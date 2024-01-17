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

        <form action="hack/actions/delete-photo.php" method="post">
            <button class="account-button__delete" type="submit">Delete photo</button>
        </form>

        <h1 class="account-title"><?php echo $user['name']; ?></h1>

        <form action="hack/actions/logout.php" method="post">
            <button class="account-button" type="submit">Logout</button>
        </form>
    </div>

    <script src="index.js"></script>
</body>

</html>