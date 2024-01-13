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

        <label class="form-label" for="">Name
            <input class="form-input" type="text" placeholder="Macaulay Culkin" name="name">
        </label>

        <label class="form-label" for="">Password
            <input class="form-input" type="text" placeholder="*******" name="password">
        </label>

        <button type="submit">Continue</button>

        <p class="form-account">I don't have an <a href="index.php?page=signup">account</a> yet</p>
    </form>
</body>

</html>