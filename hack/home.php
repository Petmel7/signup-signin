<?php
require_once __DIR__ . '/actions/helpers.php';
$user = currentUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="account">
        <img class="account-img" src="hack/<?php echo $user['avatar']; ?>" width="200px" height="200px" alt="<?php echo $user['name']; ?>">

        <h1 class="account-title">Привіт! <?php echo $user['name']; ?></h1>
        <a class="account-button" href="#" role="button">Вийти з акаунту</a>
    </div>
</body>

</html>