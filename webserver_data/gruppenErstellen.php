<?php
//include 'actionScripts/dbConnect.php';
include 'actionScripts/sessioncheck.php';
?>
<!DOCTYPE html>
<html lang="de">
<?php include 'components/head.php'; ?>
<body>
<?php include 'components/header.php'; ?>


<main style="width:100%">
    <div class="gruppe-erstellen-page">

        <div class="gruppe-erstellen-form-col">
            <div class="gruppe-erstellen-card">

                <div class="gruppe-erstellen-card-header">
                    <div class="gruppe-erstellen-card-icon">
                        <i class="material-icons">group_add</i>
                    </div>
                    <div>
                        <h1 class="gruppe-erstellen-title">Neue Gruppe erstellen</h1>
                        <p class="gruppe-erstellen-subtitle">Füge Mitglieder hinzu und konfiguriere deine Gruppe.</p>
                    </div>
                </div>

                <form id="gruppeForm" method="post" action="actionScripts/gruppeErstellen.php" novalidate>

                    <div class="ge-form-group">
                        <label class="ge-label" for="ge_name">
                            Gruppenname <span class="ge-required">*</span>
                        </label>
                        <div class="ge-input-wrap">
                            <i class="material-icons ge-input-icon">group</i>
                            <input type="text" id="ge_name" name="name"
                                   class="ge-input ge-input--icon"
                                   placeholder="z.B. Informatik LK 12b"
                                   maxlength="80" autocomplete="off" required>
                            <span class="ge-char-counter" id="ge_name_counter">0 / 80</span>
                        </div>
                        <div class="ge-field-error" id="err_name"></div>
                    </div>

                    <div class="ge-form-group">
                        <label class="ge-label" for="ge_beschreibung">
                            Beschreibung <span class="ge-optional">(optional)</span>
                        </label>
                        <textarea id="ge_beschreibung" name="beschreibung"
                                  class="ge-textarea"
                                  placeholder="Worum geht es in dieser Gruppe? Was ist das Ziel?"
                                  rows="3" maxlength="500"></textarea>
                        <div class="ge-char-counter ge-char-counter--right">
                            <span id="ge_beschr_counter">0</span> / 500
                        </div>
                    </div>

                    <div class="ge-form-group">
                        <label class="ge-label">Sichtbarkeit</label>
                        <div class="ge-radio-group">
                            <label class="ge-radio-card" id="radio_oeffentlich">
                                <input type="radio" name="sichtbarkeit" value="oeffentlich" checked>
                                <div class="ge-radio-card-inner">
                                    <div class="ge-radio-icon"><i class="material-icons">public</i></div>
                                    <div>
                                        <div class="ge-radio-title">Öffentlich</div>
                                        <div class="ge-radio-desc">Jeder kann der Gruppe beitreten</div>
                                    </div>
                                </div>
                                <div class="ge-radio-check"><i class="material-icons">check</i></div>
                            </label>
                            <label class="ge-radio-card" id="radio_privat">
                                <input type="radio" name="sichtbarkeit" value="privat">
                                <div class="ge-radio-card-inner">
                                    <div class="ge-radio-icon"><i class="material-icons">lock</i></div>
                                    <div>
                                        <div class="ge-radio-title">Privat</div>
                                        <div class="ge-radio-desc">Nur per Einladung beitreten</div>
                                    </div>
                                </div>
                                <div class="ge-radio-check"><i class="material-icons">check</i></div>
                            </label>
                        </div>
                    </div>

                    <div class="ge-form-group">
                        <label class="ge-label" for="ge_member_search">Mitglieder hinzufügen</label>
                        <div class="ge-member-search-wrap">
                            <i class="material-icons" style="color:var(--text-muted);font-size:18px;flex-shrink:0">search</i>
                            <input type="text" id="ge_member_search"
                                   class="ge-member-search-input"
                                   placeholder="Name suchen und hinzufügen…"
                                   autocomplete="off">
                        </div>
                        <div class="ge-suggestions hidden" id="ge_suggestions"></div>
                        <div class="ge-field-error" id="err_members"></div>
                    </div>

                </form>

                <div class="gruppe-erstellen-actions">
                    <a href="gruppen.php" class="ge-btn-cancel">
                        <i class="material-icons">arrow_back</i>
                        Abbrechen
                    </a>
                    <button class="ge-btn-submit" id="ge_submit">
                        <i class="material-icons">group_add</i>
                        Gruppe erstellen
                    </button>
                </div>

            </div>
        </div>

        <div class="gruppe-erstellen-preview-col">

            <div class="ge-preview-card">
                <div class="ge-preview-label">
                    <i class="material-icons">preview</i>
                    Vorschau
                </div>
                <div class="ge-preview-group-card">
                    <div class="ge-preview-avatar" id="previewAvatar">GR</div>
                    <div class="ge-preview-info">
                        <div class="ge-preview-name" id="previewName">Gruppenname</div>
                        <div class="ge-preview-desc" id="previewDesc">Keine Beschreibung</div>
                        <div class="ge-preview-meta">
                            <span id="previewCount">
                                <i class="material-icons">person</i> 1 Mitglied
                            </span>
                            <span id="previewVisibility">
                                <i class="material-icons">public</i> Öffentlich
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ge-members-panel">
                <div class="ge-members-panel-header">
                    <span class="ge-members-panel-title">
                        Mitglieder
                    </span>
                    <span class="ge-members-count-badge" id="geMembersCount">1</span>
                </div>
                <ul class="ge-members-list" id="geMembersList">
                    <li class="ge-member-item ge-member-item--owner" id="ge-owner">
                        <div class="aufgaben-avatar" style="background:var(--gold);width:36px;height:36px;font-size:.82rem;border-radius:10px;flex-shrink:0">Du</div>
                        <div class="ge-member-info">
                            <div class="ge-member-name">Du (Ersteller)</div>
                            <div class="ge-member-role">Administrator</div>
                        </div>
                        <div class="ge-member-owner-badge">
                            <i class="material-icons">star</i>
                        </div>
                    </li>
                </ul>
                <div class="ge-members-empty hidden" id="geMembersEmpty">
                    <i class="material-icons">person_add</i>
                    <span>Noch keine Mitglieder hinzugefügt</span>
                </div>
            </div>

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

    const allMembers = [
        { id: 1,  name: 'Max Mustermann',  kuerzel: 'MM', color: '#7a653a' },
        { id: 2,  name: 'Erika Muster',    kuerzel: 'EM', color: '#8b0000' },
        { id: 3,  name: 'Luca Bauer',      kuerzel: 'LB', color: '#4a7c59' },
        { id: 4,  name: 'Jana Klein',      kuerzel: 'JK', color: '#5b4fcf' },
        { id: 5,  name: 'Tim Wolf',        kuerzel: 'TW', color: '#b45309' },
        { id: 6,  name: 'Sara Hoffmann',   kuerzel: 'SH', color: '#c2410c' },
        { id: 7,  name: 'Finn Schreiber',  kuerzel: 'FS', color: '#0369a1' },
        { id: 8,  name: 'Mia Krause',      kuerzel: 'MK', color: '#7c3aed' },
        { id: 9,  name: 'Leon Fischer',    kuerzel: 'LF', color: '#065f46' },
        { id: 10, name: 'Anna Berger',     kuerzel: 'AB', color: '#9d174d' },
        { id: 11, name: 'Noah Wagner',     kuerzel: 'NW', color: '#1e3a8a' },
        { id: 12, name: 'Lena Schmidt',    kuerzel: 'LS', color: '#713f12' },
    ];

    let selected = []; 

    const nameInput     = document.getElementById('ge_name');
    const nameCounter   = document.getElementById('ge_name_counter');
    const beschrArea    = document.getElementById('ge_beschreibung');
    const beschrCounter = document.getElementById('ge_beschr_counter');
    const searchInput   = document.getElementById('ge_member_search');
    const sugBox        = document.getElementById('ge_suggestions');
    const membersList   = document.getElementById('geMembersList');
    const membersCount  = document.getElementById('geMembersCount');
    const membersEmpty  = document.getElementById('geMembersEmpty');
    const previewName   = document.getElementById('previewName');
    const previewDesc   = document.getElementById('previewDesc');
    const previewAvatar = document.getElementById('previewAvatar');
    const previewCount  = document.getElementById('previewCount');
    const previewVis    = document.getElementById('previewVisibility');
    const submitBtn     = document.getElementById('ge_submit');

    nameInput.addEventListener('input', function () {
        const val = this.value;
        nameCounter.textContent = val.length + ' / 80';
        previewName.textContent = val.trim() || 'Gruppenname';
        const words = val.trim().split(/\s+/).filter(Boolean);
        if (words.length >= 2) {
            previewAvatar.textContent = (words[0][0] + words[1][0]).toUpperCase();
        } else if (words.length === 1 && words[0].length >= 2) {
            previewAvatar.textContent = words[0].slice(0, 2).toUpperCase();
        } else {
            previewAvatar.textContent = val.slice(0, 2).toUpperCase() || 'GR';
        }
        document.getElementById('err_name').textContent = '';
        this.classList.remove('ge-input-error');
    });

    beschrArea.addEventListener('input', function () {
        beschrCounter.textContent = this.value.length;
        previewDesc.textContent = this.value.trim() || 'Keine Beschreibung';
        previewDesc.style.opacity = this.value.trim() ? '1' : '0.45';
    });

    document.querySelectorAll('input[name="sichtbarkeit"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.ge-radio-card').forEach(c => c.classList.remove('selected'));
            this.closest('.ge-radio-card').classList.add('selected');
            if (this.value === 'privat') {
                previewVis.innerHTML = '<i class="material-icons">lock</i> Privat';
            } else {
                previewVis.innerHTML = '<i class="material-icons">public</i> Öffentlich';
            }
        });
    });

    document.querySelector('.ge-radio-card:has(input:checked)').classList.add('selected');

    searchInput.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        if (!q) { hideSuggestions(); return; }
        const hits = allMembers.filter(m =>
            m.name.toLowerCase().includes(q) &&
            !selected.find(s => s.id === m.id)
        );
        renderSuggestions(hits);
    });

    searchInput.addEventListener('blur', () => {
        setTimeout(hideSuggestions, 160);
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const first = sugBox.querySelector('.ge-suggestion');
            if (first) first.click();
        }
    });

    function renderSuggestions(hits) {
        if (!hits.length) { hideSuggestions(); return; }
        sugBox.innerHTML = hits.map(m => `
            <div class="ge-suggestion" data-id="${m.id}">
                <div class="aufgaben-avatar"
                     style="background:${m.color};width:32px;height:32px;font-size:.75rem;border-radius:50%;flex-shrink:0">
                    ${m.kuerzel}
                </div>
                <div class="ge-suggestion-info">
                    <div class="ge-suggestion-name">${m.name}</div>
                    <div class="ge-suggestion-role">Mitglied</div>
                </div>
                <div class="ge-suggestion-add">
                    <i class="material-icons">add</i>
                </div>
            </div>
        `).join('');
        sugBox.classList.remove('hidden');

        sugBox.querySelectorAll('.ge-suggestion').forEach(el => {
            el.addEventListener('mousedown', function (e) {
                e.preventDefault();
                const id = parseInt(this.dataset.id);
                const member = allMembers.find(m => m.id === id);
                if (member) addMember(member);
                searchInput.value = '';
                hideSuggestions();
            });
        });
    }

    function hideSuggestions() {
        sugBox.classList.add('hidden');
        sugBox.innerHTML = '';
    }

    function addMember(m) {
        if (selected.find(s => s.id === m.id)) return;
        selected.push(m);
        renderMembers();
        updatePreviewCount();
    }

    function removeMember(id) {
        const item = document.getElementById('ge-member-' + id);
        if (item) {
            item.classList.add('ge-member-removing');
            setTimeout(() => {
                selected = selected.filter(s => s.id !== id);
                renderMembers();
                updatePreviewCount();
            }, 240);
        }
    }

    function renderMembers() {
        membersList.querySelectorAll('.ge-member-item:not(.ge-member-item--owner)').forEach(el => el.remove());

        selected.forEach(m => {
            const li = document.createElement('li');
            li.className = 'ge-member-item';
            li.id = 'ge-member-' + m.id;
            li.innerHTML = `
                <div class="aufgaben-avatar"
                     style="background:${m.color};width:36px;height:36px;font-size:.82rem;border-radius:10px;flex-shrink:0">
                    ${m.kuerzel}
                </div>
                <div class="ge-member-info">
                    <div class="ge-member-name">${m.name}</div>
                    <div class="ge-member-role">Mitglied</div>
                </div>
                <button class="ge-member-remove" data-id="${m.id}" title="Entfernen">
                    <i class="material-icons">close</i>
                </button>
                <input type="hidden" name="members[]" value="${m.id}">
            `;
            membersList.appendChild(li);

            li.querySelector('.ge-member-remove').addEventListener('click', function () {
                removeMember(parseInt(this.dataset.id));
            });
        });

        membersList.querySelectorAll('.ge-member-item:not(.ge-member-item--owner)').forEach(el => {
            requestAnimationFrame(() => el.classList.add('visible'));
        });

        membersCount.textContent = selected.length + 1;
    }

    function updatePreviewCount() {
        const total = selected.length + 1;
        previewCount.innerHTML = `<i class="material-icons">person</i> ${total} Mitglied${total !== 1 ? 'er' : ''}`;
    }

    submitBtn.addEventListener('click', () => {
        let valid = true;

        const nameErr = document.getElementById('err_name');
        if (!nameInput.value.trim()) {
            nameErr.textContent = 'Bitte gib einen Gruppennamen ein.';
            nameInput.classList.add('ge-input-error');
            nameInput.focus();
            valid = false;
        } else {
            nameErr.textContent = '';
            nameInput.classList.remove('ge-input-error');
        }

        if (!valid) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="material-icons">hourglass_top</i> Wird erstellt…';

        setTimeout(() => {
            submitBtn.innerHTML = '<i class="material-icons">check</i> Gruppe erstellt!';
            submitBtn.style.background = '#22c55e';
        }, 900);
    });
});
</script>
</body>
</html>