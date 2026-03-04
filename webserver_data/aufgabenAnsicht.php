<?php
//define('GOGROUP', true);
//include 'actionScripts/dbConnect.php';
include 'actionScripts/sessioncheck.php';
$gruppenName   = "Informatik LK";
$gruppenMeta   = "12 Mitglieder";
$gruppenAvatar = "IL";
$activeNav     = "aufgaben";
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
            <button class="aufgaben-new-btn" id="newTaskBtn">
                <i class="material-icons">add</i>
                Neue Aufgabe
            </button>
        </div>

        <div class="aufgaben-filterbar">
            <div class="aufgaben-filter-tabs" id="filterTabs">
                <button class="aufgaben-filter-tab active" data-filter="alle">Alle</button>
                <button class="aufgaben-filter-tab" data-filter="offen">Offen</button>
                <button class="aufgaben-filter-tab" data-filter="in_bearbeitung">In Bearbeitung</button>
                <button class="aufgaben-filter-tab" data-filter="abgegeben">Abgegeben</button>
            </div>
            <div class="aufgaben-search-wrap">
                <i class="material-icons" style="color:var(--text-muted);font-size:18px">search</i>
                <input type="text" id="aufgabenSearch" placeholder="Aufgabe suchen…" autocomplete="off">
            </div>
        </div>

        <div class="aufgaben-layout" id="aufgabenLayout">

            <div class="aufgaben-list" id="aufgabenList">

                <div class="aufgaben-gruppe-label">Fällig diese Woche</div>

                <div class="aufgaben-card" data-id="1" data-status="offen">
                    <div class="aufgaben-card-left">
                        <div class="aufgaben-status-dot offen"></div>
                        <div class="aufgaben-card-info">
                            <div class="aufgaben-card-title">Klassendiagramm UML</div>
                            <div class="aufgaben-card-meta">
                                <span class="aufgaben-fach-tag">Informatik</span>
                                <span class="aufgaben-due"><i class="material-icons">schedule</i>17.03.26</span>
                            </div>
                        </div>
                    </div>
                    <div class="aufgaben-card-right">
                        <div class="aufgaben-assignees">
                            <div class="aufgaben-avatar" title="Max Mustermann">MM</div>
                            <div class="aufgaben-avatar" title="Luca Bauer" style="background:#4a7c59">LB</div>
                        </div>
                        <button class="aufgaben-assign-btn" title="Mir zuweisen">
                            <i class="material-icons">person_add</i>
                        </button>
                    </div>
                </div>

                <div class="aufgaben-card" data-id="2" data-status="in_bearbeitung">
                    <div class="aufgaben-card-left">
                        <div class="aufgaben-status-dot in_bearbeitung"></div>
                        <div class="aufgaben-card-info">
                            <div class="aufgaben-card-title">Analyse: Sortieralgorithmen</div>
                            <div class="aufgaben-card-meta">
                                <span class="aufgaben-fach-tag">Informatik</span>
                                <span class="aufgaben-due aufgaben-due--bald"><i class="material-icons">schedule</i>19.03.26</span>
                            </div>
                        </div>
                    </div>
                    <div class="aufgaben-card-right">
                        <div class="aufgaben-assignees">
                            <div class="aufgaben-avatar" style="background:#8b0000" title="Erika Muster">EM</div>
                        </div>
                        <button class="aufgaben-assign-btn" title="Mir zuweisen">
                            <i class="material-icons">person_add</i>
                        </button>
                    </div>
                </div>

                <div class="aufgaben-gruppe-label">Nächste Woche</div>

                <div class="aufgaben-card" data-id="3" data-status="offen">
                    <div class="aufgaben-card-left">
                        <div class="aufgaben-status-dot offen"></div>
                        <div class="aufgaben-card-info">
                            <div class="aufgaben-card-title">Reflexionsbericht Praktikum</div>
                            <div class="aufgaben-card-meta">
                                <span class="aufgaben-fach-tag" style="background:rgba(74,124,89,.12);color:#4a7c59">Deutsch</span>
                                <span class="aufgaben-due"><i class="material-icons">schedule</i>24.03.26</span>
                            </div>
                        </div>
                    </div>
                    <div class="aufgaben-card-right">
                        <div class="aufgaben-assignees">
                            <div class="aufgaben-avatar" title="Max Mustermann">MM</div>
                            <div class="aufgaben-avatar" title="Jana Klein" style="background:#5b4fcf">JK</div>
                            <div class="aufgaben-avatar aufgaben-avatar--more">+3</div>
                        </div>
                        <button class="aufgaben-assign-btn" title="Mir zuweisen">
                            <i class="material-icons">person_add</i>
                        </button>
                    </div>
                </div>

                <div class="aufgaben-card" data-id="4" data-status="offen">
                    <div class="aufgaben-card-left">
                        <div class="aufgaben-status-dot offen"></div>
                        <div class="aufgaben-card-info">
                            <div class="aufgaben-card-title">Präsentation: KI & Ethik</div>
                            <div class="aufgaben-card-meta">
                                <span class="aufgaben-fach-tag">Informatik</span>
                                <span class="aufgaben-due"><i class="material-icons">schedule</i>26.03.26</span>
                            </div>
                        </div>
                    </div>
                    <div class="aufgaben-card-right">
                        <div class="aufgaben-assignees">
                            <div class="aufgaben-avatar" style="background:#8b0000" title="Erika Muster">EM</div>
                            <div class="aufgaben-avatar" title="Luca Bauer" style="background:#4a7c59">LB</div>
                        </div>
                        <button class="aufgaben-assign-btn" title="Mir zuweisen">
                            <i class="material-icons">person_add</i>
                        </button>
                    </div>
                </div>

                <div class="aufgaben-gruppe-label">Später</div>

                <div class="aufgaben-card" data-id="5" data-status="abgegeben">
                    <div class="aufgaben-card-left">
                        <div class="aufgaben-status-dot abgegeben"></div>
                        <div class="aufgaben-card-info">
                            <div class="aufgaben-card-title">Datenbankmodell ERD</div>
                            <div class="aufgaben-card-meta">
                                <span class="aufgaben-fach-tag">Informatik</span>
                                <span class="aufgaben-due aufgaben-due--done"><i class="material-icons">check_circle</i>Abgegeben</span>
                            </div>
                        </div>
                    </div>
                    <div class="aufgaben-card-right">
                        <div class="aufgaben-assignees">
                            <div class="aufgaben-avatar" title="Max Mustermann">MM</div>
                        </div>
                        <button class="aufgaben-assign-btn" title="Mir zuweisen">
                            <i class="material-icons">person_add</i>
                        </button>
                    </div>
                </div>

                <div class="aufgaben-card" data-id="6" data-status="in_bearbeitung">
                    <div class="aufgaben-card-left">
                        <div class="aufgaben-status-dot in_bearbeitung"></div>
                        <div class="aufgaben-card-info">
                            <div class="aufgaben-card-title">Projektdokumentation Phase 2</div>
                            <div class="aufgaben-card-meta">
                                <span class="aufgaben-fach-tag" style="background:rgba(139,0,0,.1);color:#8b0000">Projekt</span>
                                <span class="aufgaben-due"><i class="material-icons">schedule</i>09.04.26</span>
                            </div>
                        </div>
                    </div>
                    <div class="aufgaben-card-right">
                        <div class="aufgaben-assignees">
                            <div class="aufgaben-avatar" title="Max Mustermann">MM</div>
                            <div class="aufgaben-avatar" title="Jana Klein" style="background:#5b4fcf">JK</div>
                            <div class="aufgaben-avatar" title="Luca Bauer" style="background:#4a7c59">LB</div>
                        </div>
                        <button class="aufgaben-assign-btn" title="Mir zuweisen">
                            <i class="material-icons">person_add</i>
                        </button>
                    </div>
                </div>

            </div>

            <aside class="aufgaben-detail" id="aufgabenDetail">
                <div class="aufgaben-detail-empty" id="detailEmpty">
                    <i class="material-icons">assignment</i>
                    <p>Aufgabe auswählen um Details anzuzeigen</p>
                </div>
                <div class="aufgaben-detail-content hidden" id="detailContent">
                   
                </div>
            </aside>

        </div>

    </main>
</div>
<?php include 'components/aufgabeErstellen.php'; ?>
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

    const tasks = {
        1: {
            title: 'Klassendiagramm UML',
            status: 'offen',
            fach: 'Informatik',
            due: '17.03.2026',
            beschreibung: 'Erstellt ein vollständiges UML-Klassendiagramm für das Schulverwaltungssystem. Das Diagramm soll alle Klassen, Attribute, Methoden sowie Beziehungen (Vererbung, Assoziation, Aggregation) korrekt darstellen.',
            assignees: [
                { kuerzel: 'MM', name: 'Max Mustermann', color: '#7a653a' },
                { kuerzel: 'LB', name: 'Luca Bauer',     color: '#4a7c59' },
            ],
            ressourcen: [
                { icon: 'picture_as_pdf', name: 'Aufgabenstellung.pdf', size: '142 KB' },
                { icon: 'link',           name: 'UML-Referenz Online',  size: 'Link'   },
            ],
            abgabe: true,
        },
        2: {
            title: 'Analyse: Sortieralgorithmen',
            status: 'in_bearbeitung',
            fach: 'Informatik',
            due: '19.03.2026',
            beschreibung: 'Vergleicht mindestens 4 Sortieralgorithmen (z. B. Bubble Sort, Merge Sort, Quick Sort, Heap Sort) hinsichtlich Zeitkomplexität, Speicherbedarf und praktischer Laufzeit. Erstellt eine Tabelle und ein Diagramm.',
            assignees: [
                { kuerzel: 'EM', name: 'Erika Muster', color: '#8b0000' },
            ],
            ressourcen: [
                { icon: 'description', name: 'Vorlage_Analyse.docx', size: '38 KB' },
            ],
            abgabe: true,
        },
        3: {
            title: 'Reflexionsbericht Praktikum',
            status: 'offen',
            fach: 'Deutsch',
            due: '24.03.2026',
            beschreibung: 'Schreibt einen strukturierten Reflexionsbericht (2–3 Seiten) über euer Betriebspraktikum. Geht auf die Tätigkeiten, Lernerfahrungen und persönliche Entwicklung ein. Format: DIN A4, 12pt, Zeilenabstand 1,5.',
            assignees: [
                { kuerzel: 'MM', name: 'Max Mustermann', color: '#7a653a' },
                { kuerzel: 'JK', name: 'Jana Klein',     color: '#5b4fcf' },
                { kuerzel: 'LB', name: 'Luca Bauer',     color: '#4a7c59' },
                { kuerzel: 'EM', name: 'Erika Muster',   color: '#8b0000' },
                { kuerzel: 'TW', name: 'Tim Wolf',       color: '#7a653a' },
            ],
            ressourcen: [
                { icon: 'picture_as_pdf', name: 'Bewertungskriterien.pdf', size: '95 KB' },
            ],
            abgabe: true,
        },
        4: {
            title: 'Präsentation: KI & Ethik',
            status: 'offen',
            fach: 'Informatik',
            due: '26.03.2026',
            beschreibung: 'Bereitet eine 15-minütige Präsentation zu einem ethischen Aspekt von Künstlicher Intelligenz vor. Wählt ein konkretes Thema (z. B. Deepfakes, algorithmische Diskriminierung, autonomes Fahren) und stellt Pro- und Kontra-Argumente vor.',
            assignees: [
                { kuerzel: 'EM', name: 'Erika Muster', color: '#8b0000' },
                { kuerzel: 'LB', name: 'Luca Bauer',   color: '#4a7c59' },
            ],
            ressourcen: [
                { icon: 'slideshow',   name: 'Präsentation Vorlage.pptx', size: '2.1 MB' },
                { icon: 'link',        name: 'Literaturliste',             size: 'Link'   },
                { icon: 'description', name: 'Bewertungsbogen.docx',       size: '54 KB'  },
            ],
            abgabe: false,
        },
        5: {
            title: 'Datenbankmodell ERD',
            status: 'abgegeben',
            fach: 'Informatik',
            due: '10.03.2026',
            beschreibung: 'Entwurf eines Entity-Relationship-Diagramms für eine Bibliotheksverwaltung. Alle Entitäten, Attribute und Beziehungen sind korrekt zu modellieren. Abgabe als PDF und als draw.io-Datei.',
            assignees: [
                { kuerzel: 'MM', name: 'Max Mustermann', color: '#7a653a' },
            ],
            ressourcen: [
                { icon: 'picture_as_pdf', name: 'Aufgabenstellung_ERD.pdf', size: '88 KB' },
                { icon: 'link',           name: 'draw.io Tool',             size: 'Link'  },
            ],
            abgabe: false,
        },
        6: {
            title: 'Projektdokumentation Phase 2',
            status: 'in_bearbeitung',
            fach: 'Projekt',
            due: '09.04.2026',
            beschreibung: 'Dokumentiert den aktuellen Stand des Schulprojekts (Phase 2). Enthalten sein müssen: Fortschrittsbericht, Aufgabenverteilung, aufgetretene Probleme und Lösungsansätze sowie ein Ausblick auf Phase 3.',
            assignees: [
                { kuerzel: 'MM', name: 'Max Mustermann', color: '#7a653a' },
                { kuerzel: 'JK', name: 'Jana Klein',     color: '#5b4fcf' },
                { kuerzel: 'LB', name: 'Luca Bauer',     color: '#4a7c59' },
            ],
            ressourcen: [
                { icon: 'description',    name: 'Dokumentationsvorlage.docx', size: '120 KB' },
                { icon: 'picture_as_pdf', name: 'Phase1_Abschlussbericht.pdf', size: '340 KB' },
            ],
            abgabe: true,
        },
    };

    const statusLabels = {
        offen:          { label: 'Offen',          cls: 'offen'          },
        in_bearbeitung: { label: 'In Bearbeitung',  cls: 'in_bearbeitung' },
        abgegeben:      { label: 'Abgegeben',       cls: 'abgegeben'      },
    };

    let activeCard = null;

    document.querySelectorAll('.aufgaben-card').forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('.aufgaben-assign-btn')) return;
            const id = this.dataset.id;
            openDetail(id, this);
        });
    });

    function openDetail(id, cardEl) {
        const task = tasks[id];
        if (!task) return;

        document.querySelectorAll('.aufgaben-card').forEach(c => c.classList.remove('active'));
        cardEl.classList.add('active');
        activeCard = id;

        const empty   = document.getElementById('detailEmpty');
        const content = document.getElementById('detailContent');
        const layout  = document.getElementById('aufgabenLayout');
        const st      = statusLabels[task.status];

        empty.classList.add('hidden');
        content.classList.remove('hidden');
        layout.classList.add('has-detail');

        const assigneesHtml = task.assignees.map(a => `
            <div class="detail-assignee">
                <div class="aufgaben-avatar" style="background:${a.color};width:32px;height:32px;font-size:.75rem">${a.kuerzel}</div>
                <span>${a.name}</span>
            </div>
        `).join('');

        const resHtml = task.ressourcen.map(r => `
            <div class="detail-resource">
                <div class="detail-resource-icon">
                    <i class="material-icons">${r.icon}</i>
                </div>
                <div class="detail-resource-info">
                    <div class="detail-resource-name">${r.name}</div>
                    <div class="detail-resource-size">${r.size}</div>
                </div>
                <button class="detail-resource-dl" title="Herunterladen">
                    <i class="material-icons">download</i>
                </button>
            </div>
        `).join('');

        const abgabeHtml = task.abgabe && task.status !== 'abgegeben' ? `
            <div class="detail-section detail-abgabe-section">
                <div class="detail-section-label">Abgabe</div>
                <div id="detailUploadZone"></div>
            </div>
        ` : task.status === 'abgegeben' ? `
            <div class="detail-abgegeben-badge">
                <i class="material-icons">check_circle</i>
                Bereits abgegeben
            </div>
        ` : '';

        content.innerHTML = `
            <div class="detail-header">
                <div class="detail-status-badge ${st.cls}">${st.label}</div>
                <button class="detail-close-btn" id="detailCloseBtn" title="Schließen">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="detail-title">${task.title}</div>
            <div class="detail-due">
                <i class="material-icons">event</i>
                Fällig am ${task.due}
            </div>

            <div class="detail-section">
                <div class="detail-section-label">Beschreibung</div>
                <p class="detail-beschreibung">${task.beschreibung}</p>
            </div>

            <div class="detail-section">
                <div class="detail-section-label">Zugewiesen an</div>
                <div class="detail-assignees">${assigneesHtml}</div>
                <button class="detail-mir-zuweisen-btn">
                    <i class="material-icons">person_add</i>
                    Mir zuweisen
                </button>
            </div>

            <div class="detail-section">
                <div class="detail-section-label">Ressourcen</div>
                <div class="detail-resources">${resHtml}</div>
            </div>

            ${abgabeHtml}
        `;

        document.getElementById('detailCloseBtn').addEventListener('click', closeDetail);

        if (task.abgabe && task.status !== 'abgegeben') {
            initDetailUpload('detailUploadZone');
        }
    }

    function initDetailUpload(containerId) {
        var container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML =
            '<div class="upload-zone" id="detailUploadZoneEl">' +
                '<input type="file" id="detailUploadInput" class="upload-input" multiple ' +
                '       accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.jpg,.jpeg,.png">' +
                '<div class="upload-zone-inner">' +
                    '<div class="upload-icon-wrap"><i class="material-icons">upload_file</i></div>' +
                    '<div class="upload-zone-text">Dateien hierher ziehen oder <span class="upload-browse-link">durchsuchen</span></div>' +
                    '<div class="upload-zone-hint">PDF, Word, ZIP, Bilder · max. 25 MB</div>' +
                '</div>' +
            '</div>' +
            '<ul class="upload-file-list" id="detailFileList"></ul>' +
            '<button class="detail-abgabe-btn" id="detailAbgabeBtn" style="margin-top:12px" disabled>' +
                '<i class="material-icons">send</i> Aufgabe einreichen' +
            '</button>';

        var zone     = document.getElementById('detailUploadZoneEl');
        var input    = document.getElementById('detailUploadInput');
        var fileList = document.getElementById('detailFileList');
        var submitBtn = document.getElementById('detailAbgabeBtn');
        var files    = [];
        var idCtr    = 0;

        zone.addEventListener('click', function (e) {
            if (e.target.closest('.upload-zone-inner') || e.target === zone) input.click();
        });
        input.addEventListener('change', function () { addFiles(Array.from(this.files)); this.value = ''; });
        zone.addEventListener('dragover', function (e) { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', function (e) { if (!zone.contains(e.relatedTarget)) zone.classList.remove('drag-over'); });
        zone.addEventListener('drop', function (e) { e.preventDefault(); zone.classList.remove('drag-over'); addFiles(Array.from(e.dataTransfer.files)); });

        function addFiles(newFiles) {
            newFiles.forEach(function (f) {
                if (f.size > 25 * 1024 * 1024) return;
                var id = ++idCtr; files.push(id);
                var li = document.createElement('li');
                li.className = 'upload-file-item'; li.id = 'du-' + id;
                li.innerHTML =
                    '<div class="upload-file-icon"><i class="material-icons">' + getFileIcon(f.name) + '</i></div>' +
                    '<div class="upload-file-info">' +
                        '<div class="upload-file-name">' + f.name.replace(/</g,'&lt;') + '</div>' +
                        '<div class="upload-file-size">' + fmtSize(f.size) + '</div>' +
                        '<div class="upload-progress-bar"><div class="upload-progress-fill" id="dprog-' + id + '"></div></div>' +
                    '</div>' +
                    '<button class="upload-file-remove" data-id="' + id + '" type="button"><i class="material-icons">close</i></button>';
                fileList.appendChild(li);
                li.querySelector('.upload-file-remove').addEventListener('click', function () {
                    files = files.filter(function(x){ return x != this.dataset.id; }.bind(this));
                    var el = document.getElementById('du-' + this.dataset.id);
                    if (el) { el.classList.add('upload-removing'); setTimeout(function(){ el.remove(); }, 250); }
                    submitBtn.disabled = files.length === 0;
                });
                simulateProg(id);
                submitBtn.disabled = false;
            });
        }

        function simulateProg(id) {
            var fill = document.getElementById('dprog-' + id); if (!fill) return;
            var p = 0;
            var iv = setInterval(function () {
                p += Math.random() * 18 + 4;
                if (p >= 100) { p = 100; clearInterval(iv); var it = document.getElementById('du-' + id); if (it) it.classList.add('upload-done'); }
                fill.style.width = Math.min(p,100) + '%';
            }, 120);
        }

        submitBtn.addEventListener('click', function () {
            this.innerHTML = '<i class="material-icons">check</i> Eingereicht!';
            this.style.background = '#22c55e';
            this.disabled = true;
        });

        function getFileIcon(n) {
            var e = n.split('.').pop().toLowerCase();
            return {pdf:'picture_as_pdf',doc:'description',docx:'description',ppt:'slideshow',pptx:'slideshow',
                    xls:'table_chart',xlsx:'table_chart',zip:'folder_zip',jpg:'image',jpeg:'image',png:'image'}[e] || 'insert_drive_file';
        }
        function fmtSize(b) { return b < 1024 ? b+' B' : b < 1048576 ? (b/1024).toFixed(1)+' KB' : (b/1048576).toFixed(1)+' MB'; }
    }

    function closeDetail() {
        document.querySelectorAll('.aufgaben-card').forEach(c => c.classList.remove('active'));
        document.getElementById('detailEmpty').classList.remove('hidden');
        document.getElementById('detailContent').classList.add('hidden');
        document.getElementById('aufgabenLayout').classList.remove('has-detail');
        activeCard = null;
    }

    document.querySelectorAll('.aufgaben-assign-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            this.innerHTML = '<i class="material-icons">check</i>';
            this.classList.add('assigned');
            this.disabled = true;
        });
    });

    document.querySelectorAll('.aufgaben-filter-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.aufgaben-filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            filterCards(filter, document.getElementById('aufgabenSearch').value);
        });
    });

    document.getElementById('aufgabenSearch').addEventListener('input', function () {
        const activeFilter = document.querySelector('.aufgaben-filter-tab.active').dataset.filter;
        filterCards(activeFilter, this.value);
    });

    function filterCards(status, query) {
        const q = query.trim().toLowerCase();
        document.querySelectorAll('.aufgaben-card').forEach(card => {
            const matchStatus = status === 'alle' || card.dataset.status === status;
            const matchQuery  = !q || card.querySelector('.aufgaben-card-title').textContent.toLowerCase().includes(q);
            card.style.display = matchStatus && matchQuery ? '' : 'none';
        });
        document.querySelectorAll('.aufgaben-gruppe-label').forEach(label => {
            let next = label.nextElementSibling;
            let hasVisible = false;
            while (next && !next.classList.contains('aufgaben-gruppe-label')) {
                if (next.style.display !== 'none') hasVisible = true;
                next = next.nextElementSibling;
            }
            label.style.display = hasVisible ? '' : 'none';
        });
    }
});
</script>
</body>
</html>