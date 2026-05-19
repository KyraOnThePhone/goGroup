document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sidenav');

    let isDirty = false;
    let pendingHref = null;
    function markDirty() { isDirty = true; }
    function markClean() { isDirty = false; }

    const allMembers = INIT_DATA?.currentMembers;

    const pool = INIT_DATA?.userList;
    const groupId = INIT_DATA?.groupId;

    let members = [...allMembers];

    const membersList = document.getElementById('gs_members_list');
    const chipsWrap   = document.getElementById('gs_member_chips');
    const totalBadge  = document.getElementById('gs_total_badge');
    const statMember  = document.getElementById('statMember');

    function renderChips() {
        chipsWrap.innerHTML = '';
        members.forEach(m => {
            const chip = document.createElement('div');
            chip.className = 'gs-chip';
            chip.title = m.name + (m.role === 'admin' ? ' (Admin)' : '');
            chip.innerHTML =
                '<div class="gs-chip-avatar" style="background:' + m.color + '">' + m.kuerzel + '</div>' +
                '<span class="gs-chip-name">' + m.name.split(' ')[0] + '</span>' +
                (m.role !== 'admin' || m.role !== 'lehrer'
                    ? '<button class="gs-chip-remove" data-id="' + m.id + '" title="Entfernen">×</button>': '');
            chipsWrap.appendChild(chip);
        });
        chipsWrap.querySelectorAll('.gs-chip-remove').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeMember(parseInt(this.dataset.id));
            });
        });
    }

    function renderList() {
        membersList.innerHTML = '';
        members.forEach(m => {
            const li = document.createElement('li');
            li.className = 'gs-member-row';
            li.id = 'gs-m-' + m.id;
            li.innerHTML =
                '<div class="gs-member-row-avatar" style="background:' + m.color + '">' + m.kuerzel + '</div>' +
                '<div class="gs-member-row-info">' +
                    '<div class="gs-member-row-name">' + m.name + '</div>' +
                    '<div class="gs-member-row-role">' + (m.roleDisplayName) + '</div>' +
                '</div>' +
                '<div class="gs-member-row-actions">' +
                    (m.role !== 'admin' || m.role !== 'lehrer'
                        ? '<button class="gs-remove-btn" data-id="' + m.id + '" title="Entfernen"><i class="material-icons">person_remove</i></button>'
                        : '<div class="gs-admin-badge" title="Admin"><i class="material-icons">star</i></div>'
                    ) +
                '</div>';
            membersList.appendChild(li);
            requestAnimationFrame(() => li.classList.add('visible'));
        });

        membersList.querySelectorAll('.gs-role-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const m = members.find(x => x.id === parseInt(this.dataset.id));
                if (m) {
                    m.role = m.role === 'admin' ? 'member' : 'admin';
                    renderAll();
                    markDirty();
                    showToast(m.name + (m.role === 'admin' ? ' ist jetzt Administrator' : ' ist jetzt Mitglied'));
                }
            });
        });

        membersList.querySelectorAll('.gs-remove-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeMember(parseInt(this.dataset.id));
            });
        });
    }

    function renderAll() {
        renderList();
        renderChips();
        totalBadge.textContent = members.length;
        if (statMember) statMember.textContent = members.length;
    }

    function removeMember(id) {
        const row = document.getElementById('gs-m-' + id);
        if (row) {
            row.classList.add('gs-member-removing');
            setTimeout(() => { members = members.filter(x => x.id !== id); renderAll(); markDirty(); }, 240);
        } else {
            members = members.filter(x => x.id !== id);
            renderAll();
            markDirty();
        }
    }

    renderAll();

    const addOverlay    = document.getElementById('gs_add_member_overlay');
    const addList       = document.getElementById('gs_add_member_list');
    const addSearch     = document.getElementById('gs_add_member_search');
    const btnAddMember  = document.getElementById('btn_add_member');
    const btnAddClose   = document.getElementById('gs_add_member_close');

    function openAddModal() {
        addSearch.value = '';
        renderAddList('');
        addOverlay.classList.remove('hidden');
        requestAnimationFrame(() => addOverlay.classList.add('visible'));
        setTimeout(() => addSearch.focus(), 100);
    }

    function closeAddModal() {
        addOverlay.classList.remove('visible');
        setTimeout(() => addOverlay.classList.add('hidden'), 220);
    }

    function renderAddList(query) {
        const q = query.toLowerCase();
        
        const rawCombined = [...pool, ...allMembers];
        const combined = Array.from(
            new Map(rawCombined.map(p => [Number(p.id), p])).values()
        );
        
        const candidates = combined.filter(p => {
            // Prüfen, ob die Person bereits Mitglied in der Gruppe ist
            const isAlreadyMember = members.some(m => Number(m.id) === Number(p.id));
            
            // Suchbegriff prüfen
            const matchesQuery = !q || p.name.toLowerCase().includes(q);
            return !isAlreadyMember && matchesQuery;
        });

        addList.innerHTML = '';

        if (!candidates.length) {
            addList.innerHTML = '<div class="gs-add-member-empty">Keine Personen gefunden</div>';
            return;
        }

        candidates.forEach(p => {
            const row = document.createElement('div');
            row.className = 'gs-add-member-row';
            row.innerHTML =
                '<div class="gs-member-row-avatar" style="background:' + p.color + ';width:40px;height:40px;font-size:.85rem;border-radius:50%">' + p.kuerzel + '</div>' +
                '<div class="gs-add-member-info">' +
                    '<div class="gs-add-member-name">' + p.name + '</div>' +
                    `<div class="gs-add-member-sub">${p?.roleDisplayName ?? p?.role}</div>` +
                '</div>' +
                '<button class="gs-add-member-confirm-btn" data-id="' + p.id + '">' +
                    '<i class="material-icons">add</i> Hinzufügen' +
                '</button>';
            addList.appendChild(row);
        });

        addList.querySelectorAll('.gs-add-member-confirm-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = Number(this.dataset.id);
                const freshPool = INIT_DATA?.userList || [];
                const rawAll = [...freshPool, ...allMembers];
                
                // Auch hier bei der Event-Suche die Duplikate entfernen
                const all = Array.from(new Map(rawAll.map(x => [Number(x.id), x])).values());
                
                const p = all.find(x => Number(x.id) === id);
                if (p && !members.some(x => Number(x.id) === id)) {
                    members.push({ ...p, role: 'member' });
                    renderAll();
                    markDirty();
                    showToast(p.name + ' hinzugefügt');
                    const row = this.closest('.gs-add-member-row');
                    row.classList.add('gs-add-member-row--added');
                    
                    // Aktualisiert das Modal sauber nach dem Hinzufügen
                    setTimeout(() => renderAddList(addSearch.value), 350);
                }
            });
        });
    }
    
    btnAddMember.addEventListener('click', openAddModal);
    btnAddClose.addEventListener('click', closeAddModal);
    addOverlay.addEventListener('click', e => { if (e.target === addOverlay) closeAddModal(); });

    addSearch.addEventListener('input', function() {
        renderAddList(this.value.trim());
    });

    const nameInput     = document.getElementById('gs_name');
    const nameCounter   = document.getElementById('gs_name_counter');
    const beschrArea    = document.getElementById('gs_beschreibung');
    const beschrCounter = document.getElementById('gs_beschr_counter');
    const overviewName  = document.getElementById('overviewName');
    const overviewDesc  = document.getElementById('overviewDesc');
    const overviewAvatar= document.getElementById('overviewAvatar');
    const sidebarAvatar = document.querySelector('#gruppenSidebar .sidebar-group-avatar');
    const sidebarName   = document.querySelector('#gruppenSidebar .sidebar-group-name');

    function makeAbbr(val) {
        const w = val.trim().split(/\s+/).filter(Boolean);
        if (w.length >= 2) return (w[0][0] + w[1][0]).toUpperCase();
        if (w.length === 1 && w[0].length >= 2) return w[0].slice(0, 2).toUpperCase();
        return val.slice(0, 2).toUpperCase() || 'GR';
    }

    nameInput.addEventListener('input', function() {
        nameCounter.textContent = this.value.length + ' / 80';
        const val = this.value.trim() || 'Gruppenname';
        if (overviewName)  overviewName.textContent  = val;
        if (sidebarName)   sidebarName.textContent   = val;
        const abbr = makeAbbr(val);
        if (overviewAvatar) overviewAvatar.textContent = abbr;
        if (sidebarAvatar)  sidebarAvatar.textContent  = abbr;
        document.getElementById('err_name').textContent = '';
        this.classList.remove('gs-input-error');
        markDirty();
    });

    beschrArea.addEventListener('input', function() {
        beschrCounter.textContent = this.value.length;
        if (overviewDesc) overviewDesc.textContent = this.value.trim() || 'Keine Beschreibung';
        markDirty();
    });

    beschrCounter.textContent = beschrArea.value.length;

    document.getElementById('btn_save').addEventListener('click', function() {
        const val = nameInput.value.trim();
        if (!val) {
            document.getElementById('err_name').textContent = 'Bitte gib einen Gruppennamen ein.';
            nameInput.classList.add('gs-input-error');
            nameInput.focus();
            return;
        }
        this.disabled = true;
        this.innerHTML = '<i class="material-icons">hourglass_top</i> Wird gespeichert…';

        // #region Save
        const payload = {
            groupId: Number(groupId),
            groupName: val,
            groupDescription: beschrArea?.value?.trim(),
            memberIds: members.map(m => Number(m.id))
        };

        // Absenden
        fetch('actionScripts/saveGroupSettings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(
            response => response.json()
        )
        .then(data => {
            if (data.success) {
                markClean();
                this.innerHTML = '<i class="material-icons">check</i> Gespeichert!';
                this.style.background = '#22c55e';
                showToast('Einstellungen erfolgreich gespeichert');
                
                setTimeout(() => {
                    this.disabled = false;
                    this.style.background = '';
                    this.innerHTML = '<i class="material-icons">save</i> Änderungen speichern';
                }, 2000);
            } else {
                throw new Error(data.message || 'Fehler beim Speichern');
            }
        })
        .catch(error => {
            console.error('Save Error:', error);
            this.disabled = false;
            this.style.background = '';
            this.innerHTML = '<i class="material-icons">report_problem</i> Fehler!';
            showToast('Fehler: ' + error.message);
        });
        // #endregion

        /*
        setTimeout(() => {
            markClean();
            this.innerHTML = '<i class="material-icons">check</i> Gespeichert!';
            this.style.background = '#22c55e';
            setTimeout(() => {
                this.disabled = false;
                this.style.background = '';
                this.innerHTML = '<i class="material-icons">save</i> Änderungen speichern';
                showToast('Einstellungen gespeichert');
            }, 2000);
        }, 700);
        */
    });

    const unsavedOverlay = document.getElementById('gs_unsaved_overlay');
    const btnStay        = document.getElementById('gs_unsaved_stay');
    const btnLeave       = document.getElementById('gs_unsaved_leave');

    function openUnsaved(href) {
        pendingHref = href;
        unsavedOverlay.classList.remove('hidden');
        requestAnimationFrame(() => unsavedOverlay.classList.add('visible'));
    }

    function closeUnsaved() {
        unsavedOverlay.classList.remove('visible');
        setTimeout(() => unsavedOverlay.classList.add('hidden'), 220);
        pendingHref = null;
    }

    btnStay.addEventListener('click', closeUnsaved);
    btnLeave.addEventListener('click', () => { markClean(); if (pendingHref) window.location.href = pendingHref; closeUnsaved(); });
    unsavedOverlay.addEventListener('click', e => { if (e.target === unsavedOverlay) closeUnsaved(); });

    document.addEventListener('click', function(e) {
        if (!isDirty) return;
        const link = e.target.closest('a[href]');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript')) return;
        e.preventDefault();
        openUnsaved(href);
    }, true);

    window.addEventListener('beforeunload', function(e) {
        if (!isDirty) return;
        e.preventDefault();
        e.returnValue = '';
    });

    const confirmOverlay = document.getElementById('gs_confirm_overlay');
    const confirmTitle   = document.getElementById('gs_confirm_title');
    const confirmDesc    = document.getElementById('gs_confirm_desc');
    const confirmOk      = document.getElementById('gs_confirm_ok');
    const confirmCancel  = document.getElementById('gs_confirm_cancel');
    const confirmIcon    = document.getElementById('gs_confirm_icon_name');

    function openConfirm({ title, desc, okLabel, okIcon, onOk }) {
        confirmTitle.textContent = title;
        confirmDesc.textContent  = desc;
        confirmOk.innerHTML      = '<i class="material-icons">' + okIcon + '</i> ' + okLabel;
        confirmIcon.textContent  = okIcon;
        confirmOverlay.classList.remove('hidden');
        requestAnimationFrame(() => confirmOverlay.classList.add('visible'));
        confirmOk.onclick = () => { closeConfirm(); onOk(); };
    }

    function closeConfirm() {
        confirmOverlay.classList.remove('visible');
        setTimeout(() => confirmOverlay.classList.add('hidden'), 220);
    }

    confirmCancel.addEventListener('click', closeConfirm);
    confirmOverlay.addEventListener('click', e => { if (e.target === confirmOverlay) closeConfirm(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeConfirm(); closeUnsaved(); closeAddModal(); }
    });

    document.getElementById('btn_leave').addEventListener('click', () => {
        openConfirm({ title: 'Gruppe verlassen?', desc: 'Du verlässt die Gruppe. Deine bisherigen Inhalte bleiben erhalten.', okLabel: 'Ja, verlassen', okIcon: 'exit_to_app', onOk: () => showToast('Du hast die Gruppe verlassen (Dummy)') });
    });

    document.getElementById('btn_delete').addEventListener('click', () => {
        openConfirm({ title: 'Gruppe wirklich löschen?', desc: 'Alle Aufgaben, Dateien und Inhalte werden dauerhaft entfernt. Diese Aktion ist nicht umkehrbar.', okLabel: 'Ja, löschen', okIcon: 'delete_forever', onOk: () => showToast('Gruppe gelöscht (Dummy)') });
    });

    const anchors  = document.querySelectorAll('.gs-anchor-item');
    const sections = ['section-allgemein', 'section-mitglieder', 'section-danger'];

    window.addEventListener('scroll', () => {
        let current = 'allgemein';
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el && el.getBoundingClientRect().top < 160) current = id.replace('section-', '');
        });
        anchors.forEach(a => a.classList.toggle('active', a.dataset.section === current));
    }, { passive: true });

    let toastTimer;
    function showToast(msg) {
        let t = document.getElementById('gs_toast');
        if (!t) { t = document.createElement('div'); t.id = 'gs_toast'; t.className = 'gs-toast'; document.body.appendChild(t); }
        t.textContent = msg;
        t.className = 'gs-toast';
        clearTimeout(toastTimer);
        requestAnimationFrame(() => t.classList.add('visible'));
        toastTimer = setTimeout(() => t.classList.remove('visible'), 3000);
    }
});