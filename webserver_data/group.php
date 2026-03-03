<?php
$gruppenName   = "Informatik LK";
$gruppenMeta   = "12 Mitglieder";
$gruppenAvatar = "IL";
$activeNav     = "kalender";
?>
<!DOCTYPE html>
<html lang="de">
<?php include 'components/head.php'; ?>
<body>

<?php include 'components/header.php'; ?>

<div class="gruppen-page-wrapper">

    <?php include 'components/groupSidebar.php'; ?>

    <main class="gruppen-page-content">

        <div class="gruppen-content-header">
            <div class="gruppen-content-header-left">
                <div class="gruppen-content-avatar"><?= htmlspecialchars($gruppenAvatar) ?></div>
                <div>
                    <h1 class="gruppen-content-title"><?= htmlspecialchars($gruppenName) ?></h1>
                    <span class="gruppen-content-meta">
                        <i class="material-icons">group</i>
                        <?= htmlspecialchars($gruppenMeta) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="kalender-gross">
            <?php
                $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
                $year  = isset($_GET['year'])  ? (int)$_GET['year']  : date('Y');
                if ($month < 1)      { $month = 12; $year--; }
                elseif ($month > 12) { $month = 1;  $year++; }
                include 'components/calendar.php';
            ?>
        </div>

    </main>
</div>

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