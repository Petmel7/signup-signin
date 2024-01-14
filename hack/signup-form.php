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
    <form class="form" action="php/signup.php" method="post" enctype="multipart/form-data">
        <p class="form-title">Signup</p>

        <label class="form-label" for="name">Name
            <input class="form-input" type="text" placeholder="Macaulay Culkin" name="name" aria-invalid="true" <?php validationErrorAttr(fieldName: 'name'); ?> value="<?php echo old(key: 'name') ?>">

            <?php if (hasValidationError(fieldName: 'name')) : ?>
                <small><?php validationErrorMessage(fieldName: 'name'); ?></small>
            <?php endif; ?>
        </label>

        <label class="form-label" for="">E-mail
            <input class="form-input" type="text" placeholder="MacaulayCulkin@gmail.com" name="email" <?php validationErrorAttr(fieldName: 'email'); ?> value="<?php echo old(key: 'email') ?>">

            <?php if (hasValidationError(fieldName: 'email')) : ?>
                <small><?php validationErrorMessage(fieldName: 'email'); ?></small>
            <?php endif; ?>
        </label>

        <div class="form-block">
            <label class="custom-file-upload">
                <span>Choose File</span>
                <input class="button-input" type="file" id="avatar" name="avatar" accept="image/*" <?php validationErrorAttr(fieldName: 'avatar'); ?>>

                <?php if (hasValidationError(fieldName: 'avatar')) : ?>
                    <small><?php validationErrorMessage(fieldName: 'avatar'); ?></small>
                <?php endif; ?>
            </label>
        </div>

        <label class="form-label" for="">Password
            <input class="form-input" type="text" placeholder="*******" name="password" <?php validationErrorAttr(fieldName: 'password'); ?>>

            <?php if (hasValidationError(fieldName: 'password')) : ?>
                <small><?php validationErrorMessage(fieldName: 'password'); ?></small>
            <?php endif; ?>
        </label>

        <label class="form-label" for="">Repeat password
            <input class="form-input" type="text" placeholder="*******" name="repeat-password">
        </label>

        <div class="form-block">
            <input type="checkbox" name="checkbox">
            <label class="" for="">I accept all user terms</label>
        </div>

        <button type="submit">Continue</button>

        <p class="form-account">I already have an <a href="index.php?page=signin">account</a></p>
    </form>
</body>

</html>