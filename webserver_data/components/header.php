<header>
    <nav class="nav navbar main-nav">
        <div class="nav-wrapper container">
            <a href="#" class="brand-logo">
                <i class="material-icons left">diversity_3</i>
                Better Itslearning
            </a>
            <!-- Mobile hamburger -->
            <a href="#" data-target="mobile-nav" class="sidenav-trigger right">
                <i class="material-icons">menu</i>
            </a>
            <!-- Desktop nav -->
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li class="gruppen-nav-item">
                    <a href="#" id="gruppenNavLink">
                        <i class="material-icons left">group</i>Gruppen
                        <i class="material-icons" style="font-size:16px; margin-left:2px; opacity:.7">expand_more</i>
                    </a>
                    <!-- Gruppen Dropdown Popup -->
                    <div class="gruppen-popup" id="gruppenPopup">
                        <div class="gruppen-popup-inner">
                            <div class="gruppen-search-wrap">
                                <i class="material-icons gruppen-search-icon">search</i>
                                <input type="text" id="gruppenSearch" placeholder="Gruppe suchen…" autocomplete="off" />
                            </div>
                            <div class="gruppen-section-label">Zuletzt verwendet</div>
                            <ul class="gruppen-list" id="gruppenList">
                                <li class="gruppen-list-item" data-name="Informatik LK">
                                    <div class="gruppen-avatar" style="background:#4a7c59">IL</div>
                                    <div class="gruppen-info">
                                        <div class="gruppen-name">Informatik LK</div>
                                        <div class="gruppen-meta">12 Mitglieder</div>
                                    </div>
                                    <i class="material-icons gruppen-arrow">chevron_right</i>
                                </li>
                                <li class="gruppen-list-item" data-name="Mathe Kurs 11b">
                                    <div class="gruppen-avatar" style="background:#8b0000">MK</div>
                                    <div class="gruppen-info">
                                        <div class="gruppen-name">Mathe Kurs 11b</div>
                                        <div class="gruppen-meta">28 Mitglieder</div>
                                    </div>
                                    <i class="material-icons gruppen-arrow">chevron_right</i>
                                </li>
                                <li class="gruppen-list-item" data-name="Projektarbeit 2026">
                                    <div class="gruppen-avatar">PA</div>
                                    <div class="gruppen-info">
                                        <div class="gruppen-name">Projektarbeit 2026</div>
                                        <div class="gruppen-meta">5 Mitglieder</div>
                                    </div>
                                    <i class="material-icons gruppen-arrow">chevron_right</i>
                                </li>
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
            </ul>
        </div>
    </nav>

    <!-- Mobile sidenav -->
    <ul class="sidenav" id="mobile-nav">
        <li>
            <div class="user-view" style="background: var(--gold); padding: 24px 16px 16px;">
                <span class="white-text" style="font-size:1.2rem; font-weight:600;">
                    Better Itslearning
                </span>
            </div>
        </li>
        <li><a href="#"><i class="material-icons">group</i>Gruppen</a></li>
        <li><a href="#"><i class="material-icons">calendar_today</i>Kalender</a></li>
        <li><a href="#" id="openChatPopupMobile"><i class="material-icons">chat</i>Nachrichten</a></li>
    </ul>
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

        // Search
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