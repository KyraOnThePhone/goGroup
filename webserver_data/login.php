<!DOCTYPE html>
<html lang="de">
<?php
    include 'components/head.php';
?>
<header>
    <link rel="stylesheet" href="login.css">
</header>
<body>
    <?php
        include 'components/header.php';
    ?>
<main>
<div class="login-container">
    <div class="login-box">
        <div class="login-left">
            <h2>Login</h2>

            <form method="post" action="login_check.php">
                <div class="input-field">
                    <input id="username" type="text" name="username" required>
                    <label for="username">Username</label>
                </div>

                <div class="input-field">
                    <input id="password" type="password" name="password" required>
                    <label for="password">Passwort</label>
                </div>

                <button type="submit" class="btn login-btn waves-effect">
                    Submit
                </button>
            </form>
        </div>

        <div class="login-right">
            <i class="material-icons">groups</i>
            <span>Better Itslearning</span>
        </div>
    </div>
</div>
</main>

    <?php
        include 'components/footer.php';
    ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
