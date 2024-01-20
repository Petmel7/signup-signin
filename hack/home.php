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

        <form id="friendsForm" action="hack/actions/friends.php" method="post">
            <button class="friends" type="submit">Search friends</button>
        </form>

        <!-- <ul class="friend-list" id="friendsDataContainer"></ul> -->

        <form action="hack/actions/logout.php" method="post">
            <button class="account-button" type="submit">Logout</button>
        </form>
    </div>

    <!-- <script src="js/display-friends.js"></script> -->

    <!-- Додайте цей скрипт в ваш HTML-код home.php -->
    <!-- <script>
        const friendsForm = document.getElementById('friendsForm');

        async function displayFriends(event) {
            event.preventDefault();

            try {
                const response = await fetch('hack/actions/friends.php', {
                    method: 'POST'
                });

                if (response.ok) {
                    // Після успішного запиту, перенаправте користувача на сторінку friends-list.php
                    window.location.href = 'friends-list.php';
                } else {
                    throw new Error('Network response was not ok.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Помилка');
            }
        }

        friendsForm.addEventListener('submit', displayFriends);
    </script> -->


    <script src="index.js"></script>
</body>

</html>