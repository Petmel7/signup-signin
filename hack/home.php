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
        <img class="account-img" src="hack/<?php echo $user['avatar']; ?>" width="200px" height="200px" alt="<?php echo $user['name']; ?>">

        <h1 class="account-title"><?php echo $user['name']; ?></h1>
        <form action="hack/actions/logout.php" method="post">
            <button class="account-button" type="submit">Вийти з акаунту</button>
        </form>
    </div>
</body>

</html>