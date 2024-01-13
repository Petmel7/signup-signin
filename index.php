
<?php
// index.php
if (isset($_GET['page']) && $_GET['page'] === 'signup') {
    require './hack/signup-form.php';
} else {
    require './hack/signin-form.php';
}
?>

