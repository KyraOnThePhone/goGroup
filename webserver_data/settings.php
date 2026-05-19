<?php
define('GOGROUP', true);
include 'actionScripts/dbConnect.php';
include 'actionScripts/sessioncheck.php';

// Daten laden
include 'actionScripts/groupDataLoader.php';
$activeNav = "einstellungen";
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
                <div class="gruppen-content-avatar"><? echo $gruppenAvatar ?></div>
                <div>
                    <h1 class="gruppen-content-title">Einstellungen</h1>
                    <div class="gruppen-content-meta">
                        <i class="material-icons">settings</i>
                        Gruppeneinstellungen verwalten
                    </div>
                </div>
            </div>
        </div>

        <div class="gs-layout">

            <div class="gs-main-col">

                <section class="gs-card" id="section-allgemein">
                    <div class="gs-card-header">
                        <div class="gs-card-header-icon">
                            <i class="material-icons">edit</i>
                        </div>
                        <div>
                            <div class="gs-card-title">Allgemein</div>
                            <div class="gs-card-subtitle">Gruppenname und Beschreibung anpassen</div>
                        </div>
                    </div>

                    <div class="gs-card-body">
                        <div class="gs-form-group">
                            <label class="gs-label" for="gs_name">
                                Gruppenname <span class="gs-required">*</span>
                            </label>
                            <div class="gs-input-wrap">
                                <input type="text" id="gs_name" class="gs-input"
                                       value="<? echo $gruppenName ?>" maxlength="80" autocomplete="off">
                                <span class="gs-char-counter" id="gs_name_counter">18 / 80</span>
                            </div>
                            <div class="gs-field-error" id="err_name"></div>
                        </div>

                        <div class="gs-form-group" style="margin-bottom:0">
                            <label class="gs-label" for="gs_beschreibung">
                                Beschreibung <span class="gs-optional">(optional)</span>
                            </label>
                            <textarea id="gs_beschreibung" class="gs-textarea" rows="3"
                                      maxlength="500" placeholder="Worum geht es in dieser Gruppe?"><? echo $groupDescription ?></textarea>
                            <div class="gs-char-counter gs-char-counter--right">
                                <span id="gs_beschr_counter">80</span> / 500
                            </div>
                        </div>
                    </div>
                </section>

                <section class="gs-card" id="section-mitglieder">
                    <div class="gs-card-header">
                        <div class="gs-card-header-icon">
                            <i class="material-icons">people</i>
                        </div>
                        <div>
                            <div class="gs-card-title">Mitglieder</div>
                            <div class="gs-card-subtitle">Personen hinzufügen und verwalten</div>
                        </div>
                        <span class="gs-member-total-badge" id="gs_total_badge">8</span>
                    </div>

                    <div class="gs-card-body">
                        <div class="gs-form-group" style="margin-bottom:0">
                            <div class="gs-member-add-layout">

                                <div class="gs-member-search-col">
                                    <div class="gs-label" style="margin-bottom:8px">Person suchen</div>
                                    <div class="gs-member-search-wrap">
                                        <i class="material-icons" style="color:var(--text-muted);font-size:18px;flex-shrink:0">search</i>
                                        <input type="text" id="gs_member_filter"
                                               class="gs-member-search-input"
                                               placeholder="Mitglieder filtern…"
                                               autocomplete="off">
                                    </div>

                                    <div style="margin-top:16px">
                                        <div class="gs-label" style="margin-bottom:8px">Aktuelle Mitglieder</div>
                                        <ul class="gs-members-list" id="gs_members_list"></ul>
                                    </div>

                                    <button class="gs-add-member-btn" id="btn_add_member">
                                        <i class="material-icons">person_add</i>
                                        Mitglied hinzufügen
                                    </button>
                                </div>

                                <div class="gs-member-chips-col">
                                    <div class="gs-label" style="margin-bottom:10px">In der Gruppe</div>
                                    <div class="gs-member-chips" id="gs_member_chips"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                <section class="gs-card gs-card--danger" id="section-danger">
                    <div class="gs-card-header">
                        <div class="gs-card-header-icon gs-card-header-icon--danger">
                            <i class="material-icons">warning</i>
                        </div>
                        <div>
                            <div class="gs-card-title gs-card-title--danger">Gefahrenzone</div>
                            <div class="gs-card-subtitle">Irreversible Aktionen</div>
                        </div>
                    </div>

                    <div class="gs-card-body">
                        <div class="gs-danger-row">
                            <div>
                                <div class="gs-danger-label">Gruppe verlassen</div>
                                <div class="gs-danger-desc">Du verlässt die Gruppe. Deine Inhalte bleiben erhalten.</div>
                            </div>
                            <button class="gs-btn-danger gs-btn-danger--outline" id="btn_leave">
                                <i class="material-icons">exit_to_app</i>
                                Verlassen
                            </button>
                        </div>
                        <div class="gs-danger-divider"></div>
                        <div class="gs-danger-row">
                            <div>
                                <div class="gs-danger-label">Gruppe löschen</div>
                                <div class="gs-danger-desc">Die Gruppe und alle Inhalte werden dauerhaft gelöscht.</div>
                            </div>
                            <button class="gs-btn-danger" id="btn_delete">
                                <i class="material-icons">delete_forever</i>
                                Löschen
                            </button>
                        </div>
                    </div>
                </section>

            </div>

            <div class="gs-side-col">
                <div class="gs-overview-card">
                    <div class="gs-overview-header">
                        <div class="gs-overview-avatar" id="overviewAvatar"><? echo $gruppenAvatar ?></div>
                        <div class="gs-overview-info">
                            <div class="gs-overview-name" id="overviewName"><? echo $groupName ?></div>
                            <div class="gs-overview-desc" id="overviewDesc">Leistungskurs Informatik, Jahrgang 12b.</div>
                        </div>
                    </div>
                    <div class="gs-overview-stats">
                        <div class="gs-stat">
                            <div class="gs-stat-value" id="statMember"><? echo $groupCount ?></div>
                            <div class="gs-stat-label">Mitglieder</div>
                        </div>
                        <div class="gs-stat-divider"></div>
                        <div class="gs-stat">
                            <div class="gs-stat-value">X</div>
                            <div class="gs-stat-label">Aufgaben</div>
                        </div>
                    </div>
                </div>

                <div class="gs-anchor-nav">
                    <a href="#section-allgemein" class="gs-anchor-item active" data-section="allgemein">
                        <i class="material-icons">edit</i>
                        Allgemein
                    </a>
                    <a href="#section-mitglieder" class="gs-anchor-item" data-section="mitglieder">
                        <i class="material-icons">people</i>
                        Mitglieder
                    </a>
                    <a href="#section-danger" class="gs-anchor-item gs-anchor-item--danger" data-section="danger">
                        <i class="material-icons">warning</i>
                        Gefahrenzone
                    </a>
                </div>

                <button class="gs-btn-save gs-btn-save--full" id="btn_save">
                    <i class="material-icons">save</i>
                    Änderungen speichern
                </button>
            </div>

        </div>

    </main>
</div>

<div class="gs-confirm-overlay hidden" id="gs_confirm_overlay">
    <div class="gs-confirm-modal" id="gs_confirm_modal">
        <div class="gs-confirm-icon" id="gs_confirm_icon">
            <i class="material-icons" id="gs_confirm_icon_name">delete_forever</i>
        </div>
        <div class="gs-confirm-title" id="gs_confirm_title">Gruppe löschen?</div>
        <div class="gs-confirm-desc" id="gs_confirm_desc">
            Diese Aktion kann nicht rückgängig gemacht werden. Alle Inhalte werden dauerhaft entfernt.
        </div>
        <div class="gs-confirm-actions">
            <button class="gs-confirm-cancel" id="gs_confirm_cancel">Abbrechen</button>
            <button class="gs-confirm-ok gs-confirm-ok--danger" id="gs_confirm_ok">
                <i class="material-icons">delete_forever</i>
                Ja, löschen
            </button>
        </div>
    </div>
</div>

<div class="gs-confirm-overlay hidden" id="gs_unsaved_overlay">
    <div class="gs-confirm-modal">
        <div class="gs-confirm-icon" style="background:rgba(245,158,11,.12)">
            <i class="material-icons" style="color:#b45309">warning_amber</i>
        </div>
        <div class="gs-confirm-title">Ungespeicherte Änderungen</div>
        <div class="gs-confirm-desc">
            Du hast Änderungen vorgenommen, die noch nicht gespeichert wurden. Wenn du die Seite jetzt verlässt, gehen sie verloren.
        </div>
        <div class="gs-confirm-actions">
            <button class="gs-confirm-cancel" id="gs_unsaved_stay">Hier bleiben</button>
            <button class="gs-confirm-ok" id="gs_unsaved_leave" style="background:var(--gold)">
                <i class="material-icons">logout</i>
                Trotzdem verlassen
            </button>
        </div>
    </div>
</div>

<div class="gs-confirm-overlay hidden" id="gs_add_member_overlay">
    <div class="gs-confirm-modal gs-add-member-modal">
        <div class="gs-add-member-modal-header">
            <div class="gs-add-member-modal-title">
                <i class="material-icons">person_add</i>
                Mitglied hinzufügen
            </div>
            <button class="gs-confirm-cancel" id="gs_add_member_close" style="padding:6px 14px">✕</button>
        </div>
        <div class="gs-add-member-search-wrap">
            <i class="material-icons" style="color:var(--text-muted);font-size:18px;flex-shrink:0">search</i>
            <input type="text" id="gs_add_member_search" class="gs-member-search-input" placeholder="Name suchen…" autocomplete="off">
        </div>
        <div class="gs-add-member-list" id="gs_add_member_list"></div>
    </div>
</div>

<?php include 'components/footer.php'; ?>
<script src="js/settings.js"></script>
</body>
</html>