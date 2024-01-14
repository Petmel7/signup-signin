<?php
require_once __DIR__ . '/../hack/actions/helpers.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup-Signin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <form class="form" action="php/signin.php" method="post">
        <p class="form-title">Signin</p>

        <?php if (hasMessage(key: 'error')) : ?>
            <div class="notise error"><?php echo getMessage(key: 'error'); ?></div>
        <?php endif; ?>

        <label class="form-label" for="">E-mail
            <input class="form-input" type="text" placeholder="MacaulayCulkin@gmail.com" name="email" <?php validationErrorAttr(fieldName: 'email'); ?> value="<?php echo old(key: 'email') ?>">

            <?php if (hasValidationError(fieldName: 'email')) : ?>
                <small><?php validationErrorMessage(fieldName: 'email'); ?></small>
            <?php endif; ?>
        </label>

        <label class="form-label" for="">Password
            <input class="form-input" type="text" placeholder="*******" name="password" <?php validationErrorAttr(fieldName: 'password'); ?>>

            <?php if (hasValidationError(fieldName: 'password')) : ?>
                <small><?php validationErrorMessage(fieldName: 'password'); ?></small>
            <?php endif; ?>
        </label>

        <button type="submit">Continue</button>

        <p class="form-account">I don't have an <a href="index.php?page=signup">account</a> yet</p>
    </form>
</body>

</html>