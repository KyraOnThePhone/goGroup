<?php
define('GOGROUP', true);
include 'actionScripts/dbConnect.php';
include 'actionScripts/sessioncheck.php';

$groupArray = GetUserGroups($_SESSION['user_id']) ?? [];
?>
<!DOCTYPE html>
<html lang="de">
<?php include 'components/head.php'; ?>
<body>
<?php include 'components/header.php'; ?>

<div class="gruppen-page-wrapper">

    <main class="gruppen-page-content">

        <div class="gruppen-content-header">
            <div class="gruppen-content-header-left">
                <div class="gruppen-content-avatar">
                    <i class="material-icons">groups</i>
                </div>
                <div>
                    <h1 class="gruppen-content-title">Gruppenübersicht</h1>
                </div>
            </div>
            
            <a href="../gruppenErstellen.php" class="gruppen-create-btn" style="margin: 0;">
                <span class="create-icon"><i class="material-icons">add</i></span>
                Gruppe erstellen
            </a>
        </div>
        
        <div class="aufgaben-filterbar">
            <div class="aufgaben-search-wrap" style="min-width: 100%; max-width: 400px;">
                <i class="material-icons">search</i>
                <input type="text" id="gruppenPageSearch" placeholder="Gruppe suchen…" autocomplete="off" />
            </div>
        </div>

        <ul class="gruppen-list" id="gruppenPageList" style="margin-top: 20px;">
            <?php foreach($groupArray as $row): ?>
                <?php
                    $groupName = $row["GroupName"];
                    $avatar = GenerateAvatarByName($groupName);
                    $avatarColor = GenerateAvatarColor($groupName);
                    $memberCount = $row["MemberCount"] . " Mitglieder";
                    $groupId = $row["GroupId"]
                    ?>
                <a href="group.php?groupId=<?= $groupId?>">
                    <li class="gruppen-list-item" data-name="<?php echo $groupId?>">
                        <div class="gruppen-avatar" style="background: <?php echo $avatarColor?>;"><?php echo $avatar?></div>
                        <div class="gruppen-info">
                            <div class="gruppen-name"><?php echo $groupName?></div>
                            <div class="gruppen-meta"><?php echo $memberCount?></div>
                        </div>
                        <i class="material-icons gruppen-arrow">chevron_right</i>
                    </li>
                </a>
            <?php endforeach; ?>
        </ul>
        
        <div class="gruppen-no-results hidden" id="gruppenPageNoResults">Keine Gruppen gefunden</div>

    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('gruppenPageSearch');
    var listItems = document.querySelectorAll('#gruppenPageList .gruppen-list-item');
    var noResults = document.getElementById('gruppenPageNoResults');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var query = this.value.trim().toLowerCase();
            var visibleCount = 0;

            listItems.forEach(function (item) {
                var name = item.getAttribute('data-name').toLowerCase();
                if (!query || name.includes(query)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (visibleCount === 0 && query !== '') {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        });
    }
});
</script>
</body>
</html>