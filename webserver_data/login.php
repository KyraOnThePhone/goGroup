<!DOCTYPE html>
<html lang="de">
<?php include 'components/head.php'; ?>
<body>
<?php $hideNavButtons = true; include 'components/header.php'; ?>
<main style="width:100%">
    <div class="login-container">
        <div class="login-box">
            <div class="login-left">
                <h2>Login</h2>
                <!--Fehlermeldung-->
                <?php if (isset($_GET['error'])): ?>
                    <div id="error-box" class="card-panel red lighten-4 red-text text-darken-4" style="padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                        <i class="material-icons left" style="margin-right: 8px;">error_outline</i>
                        <span><?php echo htmlspecialchars($_GET['message']); ?></span>
                    </div>
                    <script>
                        if (typeof window.history.replaceState === 'function') {
                            window.history.replaceState({}, document.title, window.location.pathname);
                        }
                    </script>
                <?php endif; ?>
                <!--Fehlermeldung-->
                <form method="post" action="actionScripts/login.php">
                    <div class="input-field">
                        <input id="username" type="text" name="uname" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="input-field">
                        <input id="password" type="password" name="pw" required>
                        <label for="password">Passwort</label>
                    </div>
                    <button type="submit" class="btn login-btn waves-effect">
                        Anmelden
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
<?php include 'components/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
