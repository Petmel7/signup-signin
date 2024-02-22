<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

checkGuest();
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <h1 class="bamboo">Bamboo</h1>
    <form class="form" action="php/signin.php" method="post">
        <p class="form-title">Signin</p>

        <?php if (hasMessage('error')) : ?>
            <small class="notise error"><?php echo getMessage(key: 'error'); ?></small>
        <?php endif; ?>

        <label class="form-label" for="">E-mail
            <input class="form-input" type="text" placeholder="MacaulayCulkin@gmail.com" name="email" <?php echo validationErrorAttr(fieldName: 'email'); ?> value="<?php echo old(key: 'email') ?>">

            <?php if (hasValidationError(fieldName: 'email')) : ?>
                <small class="notise"><?php echo validationErrorMessage(fieldName: 'email'); ?></small>
            <?php endif; ?>
        </label>

        <label class="form-label" for="">Password
            <div class="custom-placeholder">
                <input class="form-input" type="password" placeholder=" " name="password" <?php echo validationErrorAttr(fieldName: 'password'); ?>>
                <span class="placeholder-text">************</span>
            </div>

            <?php if (hasValidationError(fieldName: 'password')) : ?>
                <small class="notise"><?php echo validationErrorMessage(fieldName: 'password'); ?></small>
            <?php endif; ?>
        </label>

        <button type=" submit">Continue</button>

        <p class="form-account">I don't have an <a href="index.php?page=signup">account</a> yet</p>
    </form>

    <script src="index.js"></script>
</body>

</html>