<!DOCTYPE html>
<html lang="de">
<?php 
include 'components/head.php';
?>
<body>
<?php
include 'components/header.php';

?>

<div class="gruppen-page-wrapper">
    <?php 
    include 'components/groupSidebar.php';?>

    <div class="gruppen-page-content" id="gruppenPageContent">
        <div class="res-breadcrumb-bar">
            <nav class="res-breadcrumb" id="resBreadcrumb"></nav>
        </div>
 
        <div class="gruppen-content-header">
            <div class="gruppen-content-header-left">
                <div class="gruppen-content-avatar">
                    <i class="material-icons" style="font-size:24px;">folder_open</i>
                </div>
                <div>
                    <h1 class="gruppen-content-title" id="resCurrentTitle">Ressourcen</h1>
                    <div class="gruppen-content-meta">
                        <i class="material-icons">info_outline</i>
                        <span id="resCurrentMeta">Gruppenordner</span>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:10px;align-items:center;">
                <button class="aufgaben-new-btn" style="background:none;border:1.5px solid var(--gold-light);color:var(--gold);box-shadow:none;" onclick="openUploadModal()">
                    <i class="material-icons">upload_file</i>
                    <span>Hochladen</span>
                </button>
                <button class="aufgaben-new-btn" onclick="openNewFolderModal()">
                    <i class="material-icons">create_new_folder</i>
                    <span>Neuer Ordner</span>
                </button>
            </div>
        </div>
 
        <div class="aufgaben-filterbar" style="margin-bottom:16px;">
            <div class="aufgaben-filter-tabs">
                <button class="aufgaben-filter-tab active" onclick="setView('list',this)">
                    <i class="material-icons" style="font-size:16px;vertical-align:middle;">view_list</i> Liste
                </button>
                <button class="aufgaben-filter-tab" onclick="setView('grid',this)">
                    <i class="material-icons" style="font-size:16px;vertical-align:middle;">grid_view</i> Kacheln
                </button>
            </div>
            <div class="aufgaben-search-wrap">
                <i class="material-icons" style="color:var(--text-muted);font-size:18px;">search</i>
                <input type="text" placeholder="Dateien und Ordner suchen…" id="resSearch" oninput="filterEntries(this.value)">
            </div>
        </div>
         <div class="res-main-layout" id="resMainLayout">
            <div class="res-explorer" id="resExplorer"></div>
            <div class="res-detail-panel hidden" id="resDetailPanel">
                <div class="res-detail-header">
                    <span class="res-detail-filename" id="detailFilename">-</span>
                    <button class="detail-close-btn" onclick="closeDetailPanel()"><i class="material-icons">close</i></button>
                </div>
                <div class="res-preview-area" id="resPreviewArea"></div>
                <div class="res-detail-tabs">
                    <button class="res-detail-tab active" onclick="switchDetailTab('info',this)">Info</button>
                    <button class="res-detail-tab" onclick="switchDetailTab('history',this)">Verlauf</button>
                </div>
                <div class="res-detail-body" id="detailTabInfo"></div>
                <div class="res-detail-body hidden" id="detailTabHistory"></div>
            </div>
        </div>
    </div>
</div>
 <div class="aufgabe-modal-overlay hidden" id="uploadModal">
    <div class="aufgabe-modal" style="max-width:520px;">
        <div class="aufgabe-modal-header">
            <div class="aufgabe-modal-header-left">
                <div class="aufgabe-modal-icon"><i class="material-icons">upload_file</i></div>
                <h2 class="aufgabe-modal-title">Datei hochladen</h2>
            </div>
            <button class="aufgabe-modal-close" onclick="closeModal('uploadModal')"><i class="material-icons">close</i></button>
        </div>
        <div class="aufgabe-modal-body">
            <div class="aufgabe-form-group">
                <label class="aufgabe-form-label">In Ordner ablegen</label>
                <div class="aufgabe-select-wrap">
                    <select class="aufgabe-form-select" id="uploadFolderSelect"></select>
                    <i class="material-icons aufgabe-select-arrow">expand_more</i>
                </div>
            </div>
            <div class="upload-zone" id="uploadZone">
                <input type="file" class="upload-input" id="uploadInput" multiple onchange="handleFileSelect(this.files)">
                <div class="upload-zone-inner">
                    <div class="upload-icon-wrap"><i class="material-icons">cloud_upload</i></div>
                    <span class="upload-zone-text">Dateien hierher ziehen oder <span class="upload-browse-link" onclick="document.getElementById('uploadInput').click()">durchsuchen</span></span>
                    <span class="upload-zone-hint">Alle Dateitypen · Max. 50 MB</span>
                </div>
            </div>
            <ul class="upload-file-list" id="uploadFileList"></ul>
        </div>
        <div class="aufgabe-modal-footer">
            <button class="aufgabe-btn-cancel" onclick="closeModal('uploadModal')">Abbrechen</button>
            <button class="aufgabe-btn-submit" onclick="confirmUpload()">
                <i class="material-icons">cloud_upload</i> Hochladen
            </button>
        </div>
    </div>
</div>
 
<div class="aufgabe-modal-overlay hidden" id="newFolderModal">
    <div class="aufgabe-modal" style="max-width:400px;">
        <div class="aufgabe-modal-header">
            <div class="aufgabe-modal-header-left">
                <div class="aufgabe-modal-icon"><i class="material-icons">create_new_folder</i></div>
                <h2 class="aufgabe-modal-title">Neuer Ordner</h2>
            </div>
            <button class="aufgabe-modal-close" onclick="closeModal('newFolderModal')"><i class="material-icons">close</i></button>
        </div>
        <div class="aufgabe-modal-body">
            <div class="aufgabe-form-group">
                <label class="aufgabe-form-label">Name <span class="aufgabe-required">*</span></label>
                <input type="text" class="aufgabe-form-input" id="newFolderName" placeholder="z. B. Mathematik" maxlength="60">
                <div class="aufgabe-field-error" id="newFolderError"></div>
            </div>
        </div>
        <div class="aufgabe-modal-footer">
            <button class="aufgabe-btn-cancel" onclick="closeModal('newFolderModal')">Abbrechen</button>
            <button class="aufgabe-btn-submit" onclick="confirmNewFolder()">
                <i class="material-icons">check</i> Erstellen
            </button>
        </div>
    </div>
</div>
 
<div class="aufgabe-modal-overlay hidden" id="moveModal">
    <div class="aufgabe-modal" style="max-width:420px;">
        <div class="aufgabe-modal-header">
            <div class="aufgabe-modal-header-left">
                <div class="aufgabe-modal-icon"><i class="material-icons">drive_file_move</i></div>
                <h2 class="aufgabe-modal-title">Verschieben</h2>
            </div>
            <button class="aufgabe-modal-close" onclick="closeModal('moveModal')"><i class="material-icons">close</i></button>
        </div>
        <div class="aufgabe-modal-body">
            <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:14px;">
                Zielordner für <strong id="moveItemLabel">-</strong>:
            </p>
            <div class="res-folder-tree-picker" id="folderTreePicker"></div>
        </div>
        <div class="aufgabe-modal-footer">
            <button class="aufgabe-btn-cancel" onclick="closeModal('moveModal')">Abbrechen</button>
            <button class="aufgabe-btn-submit" onclick="confirmMove()">
                <i class="material-icons">drive_file_move</i> Verschieben
            </button>
        </div>
    </div>
</div>
 
<div class="upload-toast" id="resToast"></div>
<div id="dragGhost" style="position:fixed;top:-100px;left:-100px;background:var(--gold);color:#fff;padding:6px 12px;border-radius:var(--radius-sm);font-size:.82rem;font-weight:600;pointer-events:none;z-index:9999;display:none;align-items:center;gap:6px;box-shadow:var(--shadow-md);">
    <i class="material-icons" style="font-size:16px;">insert_drive_file</i>
    <span id="dragGhostLabel">Datei</span>
</div>
 

<footer>
    <?php include 'components/footer.php'; ?>
</footer>
<script src="js/ressourcen.js"></script>
</body>
</html>