<!DOCTYPE html>
<html lang="de">
<?php include 'components/head.php'; ?>

<body>
<?php include 'components/header.php'; ?>

<main class="container">
    <div class="row">

        <div class="col s12 m8">
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
        <div class="col s12 m4">
            <?php include 'components/calendar.php'; ?>
        </div>

    </div>
</main>

<?php include 'components/footer.php'; ?>
</body>
</html>
