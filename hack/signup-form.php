<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

checkGuest();
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <h1 class="bamboo">Bamboo</h1>
    <form class="form" action="php/signup.php" method="post" enctype="multipart/form-data">
        <p class="form-title">Signup</p>

        <label class="form-label" for="name">Name
            <input class="form-input" type="text" placeholder="Macaulay Culkin" name="name" aria-invalid="true" <?php echo validationErrorAttr(fieldName: 'name'); ?> value="<?php echo old(key: 'name') ?>">

            <?php if (hasValidationError(fieldName: 'name')) : ?>
                <small class="notise"><?php echo validationErrorMessage(fieldName: 'name'); ?></small>
            <?php endif; ?>
        </label>

        <label class="form-label" for="">E-mail
            <input class="form-input" type="text" placeholder="MacaulayCulkin@gmail.com" name="email" <?php echo validationErrorAttr(fieldName: 'email'); ?> value="<?php echo old(key: 'email') ?>">

            <?php if (hasValidationError(fieldName: 'email')) : ?>
                <small class="notise"><?php echo validationErrorMessage(fieldName: 'email'); ?></small>
            <?php endif; ?>
        </label>

        <label class="form-label" for="">Password
            <div class="custom-placeholder">
                <input class="form-input" type="text" placeholder=" " name="password" <?php echo validationErrorAttr(fieldName: 'password'); ?>>
                <span class="placeholder-text">************</span>
            </div>

            <?php if (hasValidationError(fieldName: 'password')) : ?>
                <small class="notise"><?php echo validationErrorMessage(fieldName: 'password'); ?></small>
            <?php endif; ?>
        </label>

        <label class="form-label" for="">Repeat password
            <div class="custom-placeholder">
                <input class="form-input" type="text" placeholder=" " name="repeat-password">
                <span class="placeholder-text">************</span>
            </div>
        </label>

        <div class="form-block">
            <input class="form-checkbox" type="checkbox" id="customCheckbox" name="checkbox">
            <label class="custom-checkbox-label" for="customCheckbox">I accept all user terms</label>
        </div>

        <button type="submit">Continue</button>

        <p class="form-account">I already have an <a href="index.php?page=signin">account</a></p>
    </form>

    <script src="index.js"></script>
</body>

</html>