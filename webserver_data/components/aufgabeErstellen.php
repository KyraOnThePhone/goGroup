<div class="aufgabe-modal-overlay hidden" id="aufgabeModalOverlay">
    <div class="aufgabe-modal" id="aufgabeModal" role="dialog" aria-modal="true" aria-labelledby="aufgabeModalTitle">

        <div class="aufgabe-modal-header">
            <div class="aufgabe-modal-header-left">
                <div class="aufgabe-modal-icon">
                    <i class="material-icons">assignment_add</i>
                </div>
                <h2 class="aufgabe-modal-title" id="aufgabeModalTitle">Neue Aufgabe erstellen</h2>
            </div>
            <button class="aufgabe-modal-close" id="aufgabeModalClose" title="Schließen">
                <i class="material-icons">close</i>
            </button>
        </div>

        <div class="aufgabe-modal-body">
            <form id="aufgabeForm" method="post" action="actionScripts/aufgabeErstellen.php" enctype="multipart/form-data" novalidate>

                <div class="aufgabe-form-group">
                    <label class="aufgabe-form-label" for="af_titel">
                        Titel <span class="aufgabe-required">*</span>
                    </label>
                    <input type="text" id="af_titel" name="titel" class="aufgabe-form-input"
                           placeholder="z.B. UML-Klassendiagramm" maxlength="120" required autocomplete="off">
                    <div class="aufgabe-field-error" id="err_titel"></div>
                </div>

                <div class="aufgabe-form-group">
                    <label class="aufgabe-form-label" for="af_beschreibung">Beschreibung</label>
                    <textarea id="af_beschreibung" name="beschreibung" class="aufgabe-form-textarea"
                              placeholder="Aufgabenbeschreibung, Anforderungen, Hinweise…" rows="4" maxlength="2000"></textarea>
                    <div class="aufgabe-char-count"><span id="af_beschr_count">0</span> / 2000</div>
                </div>

                <div class="aufgabe-form-row">
                    <div class="aufgabe-form-group">
                        <label class="aufgabe-form-label" for="af_fach">Fach / Kategorie</label>
                        <div class="aufgabe-select-wrap">
                            <select id="af_fach" name="fach" class="aufgabe-form-select">
                                <option value="">— Kein Fach —</option>
                                <option value="Informatik">Informatik</option>
                                <option value="Mathematik">Mathematik</option>
                                <option value="Deutsch">Deutsch</option>
                                <option value="Englisch">Englisch</option>
                                <option value="Physik">Physik</option>
                                <option value="Projekt">Projekt</option>
                                <option value="Sonstiges">Sonstiges</option>
                            </select>
                            <i class="material-icons aufgabe-select-arrow">expand_more</i>
                        </div>
                    </div>

                    <div class="aufgabe-form-group">
                        <label class="aufgabe-form-label" for="af_status">Status</label>
                        <div class="aufgabe-select-wrap">
                            <select id="af_status" name="status" class="aufgabe-form-select">
                                <option value="offen">Offen</option>
                                <option value="in_bearbeitung">In Bearbeitung</option>
                            </select>
                            <i class="material-icons aufgabe-select-arrow">expand_more</i>
                        </div>
                    </div>
                </div>

                <div class="aufgabe-form-row">
                    <div class="aufgabe-form-group">
                        <label class="aufgabe-form-label" for="af_datum">
                            Abgabedatum <span class="aufgabe-required">*</span>
                        </label>
                        <div class="aufgabe-input-icon-wrap">
                            <i class="material-icons aufgabe-input-icon">event</i>
                            <input type="date" id="af_datum" name="abgabedatum"
                                   class="aufgabe-form-input aufgabe-form-input--icon" required>
                        </div>
                        <div class="aufgabe-field-error" id="err_datum"></div>
                    </div>

                    <div class="aufgabe-form-group aufgabe-form-group--center">
                        <label class="aufgabe-form-label">Datei-Abgabe</label>
                        <label class="aufgabe-toggle" for="af_abgabe">
                            <input type="checkbox" id="af_abgabe" name="abgabe" value="1" checked>
                            <span class="aufgabe-toggle-track">
                                <span class="aufgabe-toggle-thumb"></span>
                            </span>
                            <span class="aufgabe-toggle-label">Dateien einreichen erlauben</span>
                        </label>
                    </div>
                </div>

                <div class="aufgabe-form-group">
                    <label class="aufgabe-form-label">Personen zuweisen</label>
                    <div class="aufgabe-member-search-wrap">
                        <i class="material-icons" style="color:var(--text-muted);font-size:18px;flex-shrink:0">search</i>
                        <input type="text" id="af_member_search" placeholder="Mitglied suchen…"
                               autocomplete="off" class="aufgabe-member-search-input">
                    </div>
                    <div class="aufgabe-member-suggestions hidden" id="af_member_suggestions"></div>
                    <div class="aufgabe-selected-members" id="af_selected_members"></div>
                </div>

                <div class="aufgabe-form-group">
                    <label class="aufgabe-form-label">Ressourcen / Anhänge</label>
                    <div class="aufgabe-form-upload-wrap">
                        <?php include 'aufgabeUpload.php'; ?>
                    </div>
                </div>

            </form>
        </div>

        <div class="aufgabe-modal-footer">
            <button class="aufgabe-btn-cancel" id="aufgabeBtnCancel" type="button">Abbrechen</button>
            <button class="aufgabe-btn-submit" id="aufgabeBtnSubmit" type="button">
                <i class="material-icons">add_task</i>
                Aufgabe erstellen
            </button>
        </div>

    </div>
</div>

<script>
(function () {
    var members = [
        { id: 1, name: 'Max Mustermann',    kuerzel: 'MM', color: '#7a653a' },
        { id: 2, name: 'Erika Muster',      kuerzel: 'EM', color: '#8b0000' },
        { id: 3, name: 'Luca Bauer',        kuerzel: 'LB', color: '#4a7c59' },
        { id: 4, name: 'Jana Klein',        kuerzel: 'JK', color: '#5b4fcf' },
        { id: 5, name: 'Tim Wolf',          kuerzel: 'TW', color: '#7a653a' },
        { id: 6, name: 'Sara Hoffmann',     kuerzel: 'SH', color: '#c2410c' },
        { id: 7, name: 'Finn Schreiber',    kuerzel: 'FS', color: '#0369a1' },
        { id: 8, name: 'Mia Krause',        kuerzel: 'MK', color: '#7c3aed' },
    ];

    var selected = [];

    function init() {
        var overlay   = document.getElementById('aufgabeModalOverlay');
        var modal     = document.getElementById('aufgabeModal');
        var closeBtn  = document.getElementById('aufgabeModalClose');
        var cancelBtn = document.getElementById('aufgabeBtnCancel');
        var submitBtn = document.getElementById('aufgabeBtnSubmit');
        var form      = document.getElementById('aufgabeForm');
        var textarea  = document.getElementById('af_beschreibung');
        var countEl   = document.getElementById('af_beschr_count');
        var searchIn  = document.getElementById('af_member_search');
        var sugBox    = document.getElementById('af_member_suggestions');
        var selBox    = document.getElementById('af_selected_members');
        var openBtn   = document.getElementById('newTaskBtn');

        if (!overlay) return;

        if (openBtn) openBtn.addEventListener('click', openModal);

        function openModal() {
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(function () { overlay.classList.add('visible'); }, 10);
            var titleInput = document.getElementById('af_titel');
            if (titleInput) titleInput.focus();
        }

        function closeModal() {
            overlay.classList.remove('visible');
            document.body.style.overflow = '';
            setTimeout(function () { overlay.classList.add('hidden'); }, 280);
        }

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !overlay.classList.contains('hidden')) closeModal();
        });

        if (textarea && countEl) {
            textarea.addEventListener('input', function () {
                countEl.textContent = this.value.length;
            });
        }

        if (searchIn) {
            searchIn.addEventListener('input', function () {
                var q = this.value.trim().toLowerCase();
                if (!q) { hideSuggestions(); return; }
                var hits = members.filter(function (m) {
                    return m.name.toLowerCase().includes(q) &&
                           !selected.find(function (s) { return s.id === m.id; });
                });
                renderSuggestions(hits);
            });

            searchIn.addEventListener('blur', function () {
                setTimeout(hideSuggestions, 180);
            });
        }

        function renderSuggestions(hits) {
            if (!hits.length) { hideSuggestions(); return; }
            sugBox.innerHTML = hits.map(function (m) {
                return '<div class="af-suggestion" data-id="' + m.id + '">' +
                    '<div class="aufgaben-avatar" style="background:' + m.color + ';width:28px;height:28px;font-size:.7rem;flex-shrink:0">' + m.kuerzel + '</div>' +
                    '<span>' + m.name + '</span>' +
                    '</div>';
            }).join('');
            sugBox.classList.remove('hidden');
            sugBox.querySelectorAll('.af-suggestion').forEach(function (el) {
                el.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    var id = parseInt(this.dataset.id);
                    var member = members.find(function (m) { return m.id === id; });
                    if (member) addMember(member);
                    searchIn.value = '';
                    hideSuggestions();
                });
            });
        }

        function hideSuggestions() {
            sugBox.classList.add('hidden');
            sugBox.innerHTML = '';
        }

        function addMember(m) {
            if (selected.find(function (s) { return s.id === m.id; })) return;
            selected.push(m);
            renderSelected();
        }

        function removeMember(id) {
            selected = selected.filter(function (s) { return s.id !== id; });
            renderSelected();
        }

        function renderSelected() {
            selBox.innerHTML = selected.map(function (m) {
                return '<div class="af-selected-chip">' +
                    '<div class="aufgaben-avatar" style="background:' + m.color + ';width:24px;height:24px;font-size:.65rem;flex-shrink:0">' + m.kuerzel + '</div>' +
                    '<span>' + m.name + '</span>' +
                    '<button class="af-chip-remove" data-id="' + m.id + '" type="button" title="Entfernen">' +
                        '<i class="material-icons">close</i>' +
                    '</button>' +
                    '<input type="hidden" name="assignees[]" value="' + m.id + '">' +
                    '</div>';
            }).join('');
            selBox.querySelectorAll('.af-chip-remove').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    removeMember(parseInt(this.dataset.id));
                });
            });
        }

        submitBtn.addEventListener('click', function () {
            var valid = true;

            var titel = document.getElementById('af_titel');
            var errTitel = document.getElementById('err_titel');
            if (!titel.value.trim()) {
                errTitel.textContent = 'Bitte gib einen Titel ein.';
                titel.classList.add('aufgabe-input-error');
                valid = false;
            } else {
                errTitel.textContent = '';
                titel.classList.remove('aufgabe-input-error');
            }

            var datum = document.getElementById('af_datum');
            var errDatum = document.getElementById('err_datum');
            if (!datum.value) {
                errDatum.textContent = 'Bitte wähle ein Abgabedatum.';
                datum.classList.add('aufgabe-input-error');
                valid = false;
            } else {
                errDatum.textContent = '';
                datum.classList.remove('aufgabe-input-error');
            }

            if (valid) {

                submitBtn.innerHTML = '<i class="material-icons">check</i> Erstellt!';
                submitBtn.style.background = '#22c55e';
                setTimeout(function () {
                    submitBtn.innerHTML = '<i class="material-icons">add_task</i> Aufgabe erstellen';
                    submitBtn.style.background = '';
                    closeModal();
                }, 1200);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>