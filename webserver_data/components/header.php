<?php
$groups = sqlsrv_query($conn,"
    SELECT 
        me.[GroupId] AS GroupId,
        COUNT(m2.[GroupId]) AS MemberCount,
        gr.[Name] AS GroupName
    FROM [dbo].[MEMBER] as me

    LEFT JOIN [MEMBER] AS m2 ON m2.[GroupId] = me.[GroupId]
    LEFT JOIN [GROUP] AS gr ON gr.[GroupId] = me.[GroupId]
    WHERE me.[UserId] = 1 GROUP BY me.[GroupId], gr.[Name]");
if ($groups === false) {
    die(print_r(sqlsrv_errors(), true));
}

$groups_array = [];
while ($row = sqlsrv_fetch_array($groups, SQLSRV_FETCH_ASSOC)) {
    $groups_array[] = $row;
}
?>
<header>
    <nav class="nav navbar main-nav">
        <div class="nav-wrapper container">
            <a href="../index.php" class="brand-logo">
                <i class="material-icons left">diversity_3</i>
                Better Itslearning
            </a>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE): ?>
            <a href="#" data-target="mobile-nav" class="sidenav-trigger right">
                <i class="material-icons">menu</i>
            </a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li class="gruppen-nav-item">
                    <a href="../gruppeansicht.php" id="gruppenNavLink">
                        <i class="material-icons left">group</i>Gruppen
                        <i class="material-icons" style="font-size:16px; margin-left:2px; opacity:.7">expand_more</i>
                    </a>
                    <div class="gruppen-popup" id="gruppenPopup">
                        <div class="gruppen-popup-inner">
                            <div class="gruppen-search-wrap">
                                <i class="material-icons gruppen-search-icon">search</i>
                                <input type="text" id="gruppenSearch" placeholder="Gruppe suchen…" autocomplete="off" />
                            </div>
                            <div class="gruppen-section-label">Zuletzt verwendet</div>
                            <ul class="gruppen-list" id="gruppenList">
                                <?php foreach($groups_array as $row): ?>
                                    <?php
                                        $groupName = $row['GroupName'];
                                        $avatarColor = '#' . substr(md5($groupName), 0, 6);;
                                        ?>
                                    <li class="gruppen-list-item" data-name="<?php echo $groupName ?>">
                                        <div class="gruppen-avatar" style="background:<?php echo $avatarColor ?>"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($groupName, 0, 2))); ?></div>
                                        <div class="gruppen-info">
                                            <div class="gruppen-name"><?php echo $groupName ?></div>
                                            <div class="gruppen-meta"><?php echo $row["MemberCount"] ?> Mitglieder</div>
                                        </div>
                                        <i class="material-icons gruppen-arrow">chevron_right</i>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="gruppen-no-results hidden" id="gruppenNoResults">Keine Gruppen gefunden</div>
                            <a href="gruppen/erstellen.php" class="gruppen-create-btn">
                                <span class="create-icon"><i class="material-icons">add</i></span>
                                Neue Gruppe erstellen
                            </a>
                        </div>
                    </div>
                </li>
                <li><a href="#"><i class="material-icons left">calendar_today</i>Kalender</a></li>
                <li>
                    <a href="#" id="openChatPopup">
                        <i class="material-icons left">chat</i>Nachrichten
                    </a>
                </li>
                <li>
                    <a href="actionScripts/logout.php" id="logout">
                        <i class="material-icons left">logout</i>Abmelden
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <ul class="sidenav" id="mobile-nav">
        <li>
            <div class="user-view" style="background: var(--gold); padding: 24px 16px 16px;">
                <span class="white-text" style="font-size:1.2rem; font-weight:600;">
                    Better Itslearning
                </span>
            </div>
        </li>
        <li><a href="#"><i class="material-icons">group</i>Gruppen</a></li>
        <li><a href="../calendar.php"><i class="material-icons">calendar_today</i>Kalender</a></li>
        <li><a href="#" id="openChatPopupMobile"><i class="material-icons">chat</i>Nachrichten</a></li>
    </ul>
    <?php endif; ?>
</header>

<?php include 'chatPopup.php'; ?>

<script>
(function () {
    function initGruppenPopup() {
        var navItem  = document.querySelector('.gruppen-nav-item');
        var popup    = document.getElementById('gruppenPopup');
        var search   = document.getElementById('gruppenSearch');
        var list     = document.getElementById('gruppenList');
        var noResult = document.getElementById('gruppenNoResults');

        if (!navItem || !popup) return;

        var hideTimer;

        function show() {
            clearTimeout(hideTimer);
            popup.classList.add('visible');
        }

        function hide() {
            hideTimer = setTimeout(function () {
                popup.classList.remove('visible');
                search.value = '';
                filterGroups('');
            }, 120);
        }

        navItem.addEventListener('mouseenter', show);
        navItem.addEventListener('mouseleave', hide);
        popup.addEventListener('mouseenter', function () { clearTimeout(hideTimer); });
        popup.addEventListener('mouseleave', hide);

        search.addEventListener('input', function () {
            filterGroups(this.value.trim().toLowerCase());
        });

        function filterGroups(query) {
            var items   = list.querySelectorAll('.gruppen-list-item');
            var visible = 0;
            items.forEach(function (item) {
                var name = item.getAttribute('data-name').toLowerCase();
                if (!query || name.includes(query)) {
                    item.style.display = '';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });
            noResult.classList.toggle('hidden', visible > 0);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGruppenPopup);
    } else {
        initGruppenPopup();
    }
})();
</script>