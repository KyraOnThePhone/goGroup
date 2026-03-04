<?php
$gruppenName   = $gruppenName   ?? 'Gruppe';
$gruppenMeta   = $gruppenMeta   ?? '';
$gruppenAvatar = $gruppenAvatar ?? '??';
$activeNav     = $activeNav     ?? '';

function sidebarActive(string $key, string $activeNav): string {
    return $key === $activeNav ? ' active' : '';
}
?>


<button class="sidebar-toggle" id="sidebarToggle" aria-label="Menü öffnen">
    <i class="material-icons">menu</i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="gruppen-sidebar" id="gruppenSidebar">

    <div class="sidebar-header">
        <div class="sidebar-group-avatar"><?= htmlspecialchars($gruppenAvatar) ?></div>
        <div class="sidebar-group-name"><?= htmlspecialchars($gruppenName) ?></div>
        <?php if ($gruppenMeta): ?>
        <div class="sidebar-group-meta">
            <i class="material-icons">group</i>
            <?= htmlspecialchars($gruppenMeta) ?>
        </div>
        <?php endif; ?>
    </div>

    <nav class="sidebar-nav brown darken-4">

        <a href="../gruppenansicht.php" class="sidebar-nav-item<?= sidebarActive('chat', $activeNav) ?>">
            <i class="material-icons">chat</i>
            Gruppenchat
        </a>

        <a href="kalender.php" class="sidebar-nav-item<?= sidebarActive('kalender', $activeNav) ?>">
            <i class="material-icons">calendar_month</i>
            Gruppenkalender
        </a>

        <div class="sidebar-divider"></div>
        <div class="sidebar-section-label">Arbeit</div>

        <a href="projekte.php" class="sidebar-nav-item<?= sidebarActive('projekte', $activeNav) ?>">
            <i class="material-icons">folder_special</i>
            Projekte
        </a>

        <a href="aufgaben.php" class="sidebar-nav-item<?= sidebarActive('aufgaben', $activeNav) ?>">
            <i class="material-icons">task_alt</i>
            Aufgaben
        </a>

        <a href="ressourcen.php" class="sidebar-nav-item<?= sidebarActive('ressourcen', $activeNav) ?>">
            <i class="material-icons">attach_file</i>
            Ressourcen
        </a>

        <div class="sidebar-divider"></div>
        <div class="sidebar-section-label">Verwaltung</div>

        <a href="einstellungen.php" class="sidebar-nav-item<?= sidebarActive('einstellungen', $activeNav) ?>">
            <i class="material-icons">settings</i>
            Einstellungen
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="../gruppen/index.php">
            <i class="material-icons">arrow_back</i>
            Zurück zur Übersicht
        </a>
    </div>

</aside>

<script>
(function () {
    var toggle  = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('gruppenSidebar');
    var overlay = document.getElementById('sidebarOverlay');
    if (!toggle || !sidebar) return;

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('visible');
        toggle.querySelector('i').textContent = 'close';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('visible');
        toggle.querySelector('i').textContent = 'menu';
    }

    toggle.addEventListener('click', function () {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    overlay.addEventListener('click', closeSidebar);
})();
</script>