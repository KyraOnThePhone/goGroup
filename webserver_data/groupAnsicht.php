<?php
$gruppenName   = "Gruppenübersicht";
// $gruppenMeta   = "12 Mitglieder";
// $gruppenAvatar = "IL";
define('GOGROUP', true);
include 'actionScripts/dbConnect.php';
include 'actionScripts/sessioncheck.php';
?>
<!DOCTYPE html>
<html lang="de">
<?php include 'components/head.php'; ?>
<body>
<?php include 'components/header.php'; ?>

<div class="gruppen-page-wrapper">

    <?php include 'components/groupSidebar.php'; ?>

 <main class="gruppen-ansicht-container">

        <div class="gruppen-content-header">
            <div class="gruppen-content-header-left">
                <div>
                    <h1 class="gruppen-content-title"><?= htmlspecialchars($gruppenName) ?></h1>
                </div>
            </div>
        </div>
        
        <div class="gruppen-popup-inner">
                            <div class="gruppen-search-wrap">
                                <i class="material-icons gruppen-search-icon">search</i>
                                <input type="text" id="gruppenSearch" placeholder="Gruppe suchen…" autocomplete="off" />
                            </div>

          <ul class="gruppen-list">
    <li class="gruppen-item"><a href="#">Gruppe 1</a></li>
    <li class="gruppen-item"><a href="#">Gruppe 2</a></li>
    <li class="gruppen-item"><a href="#">Gruppe 3</a></li>
    <li class="gruppen-item"><a href="#">Gruppe 4</a></li>
    <li class="gruppen-item"><a href="#">Gruppe 5</a></li>
    <li class="gruppen-item"><a href="#">Gruppe 6</a></li>
</ul>
    </main>
</div>
</body>
    </html>