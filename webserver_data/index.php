<!DOCTYPE html>
<html lang="de">
<?php include 'components/head.php'; ?>
<body>
<?php include 'components/header.php'; ?>
<main class="container">
    <div class="row" style="margin-bottom: 0;">
        <div class="col s12 m8" style="margin-bottom: 20px;">
            <div class="aktuelles-box z-depth-1">
                <h4 class="center-align aktuelles-title">Aktuelles</h4>
                <div class="aktuelles-item">
                    <i class="material-icons">assignment</i>
                    <span>Aufgabe xyz fällig am <strong>17.08.26</strong></span>
                </div>
                <div class="aktuelles-item">
                    <i class="material-icons">assignment</i>
                    <span>Aufgabe abc fällig am <strong>19.08.26</strong></span>
                </div>
                <div class="aktuelles-item termin">
                    <i class="material-icons">event</i>
                    <span>Termin am <strong>09.09.26</strong></span>
                </div>
            </div>
        </div>
        <div class="col s12 m4" style="margin-bottom: 20px;">
            <?php include 'components/calendar.php'; ?>
        </div>
    </div>
</main>
<?php include 'components/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        M.Sidenav.init(document.querySelectorAll('.sidenav'));
        const mobileChat = document.getElementById('openChatPopupMobile');
        if (mobileChat) {
            mobileChat.addEventListener('click', (e) => {
                e.preventDefault();
                M.Sidenav.getInstance(document.getElementById('mobile-nav')).close();
                setTimeout(() => document.getElementById('chatFab')?.click(), 200);
            });
        }
    });
</script>
</body>
</html>