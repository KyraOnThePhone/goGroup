<div class="upload-zone" id="uploadZone">
    <input type="file" id="uploadInput" class="upload-input" multiple
           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.jpg,.jpeg,.png">
    <div class="upload-zone-inner" id="uploadZoneInner">
        <div class="upload-icon-wrap">
            <i class="material-icons">upload_file</i>
        </div>
        <div class="upload-zone-text">
            Dateien hierher ziehen oder <span class="upload-browse-link">durchsuchen</span>
        </div>
        <div class="upload-zone-hint">PDF, Word, PowerPoint, ZIP, Bilder · max. 25 MB pro Datei</div>
    </div>
</div>

<ul class="upload-file-list" id="uploadFileList"></ul>

<script>
(function () {
    function initUpload() {
        var zone      = document.getElementById('uploadZone');
        var input     = document.getElementById('uploadInput');
        var list      = document.getElementById('uploadFileList');
        var browseLink = zone ? zone.querySelector('.upload-browse-link') : null;

        if (!zone || !input || !list) return;

        var files = [];
        var idCounter = 0;

        zone.addEventListener('click', function (e) {
            if (e.target === browseLink || e.target === zone ||
                e.target.closest('.upload-zone-inner')) {
                input.click();
            }
        });

        input.addEventListener('change', function () {
            addFiles(Array.from(this.files));
            this.value = '';
        });

        zone.addEventListener('dragover', function (e) {
            e.preventDefault();
            zone.classList.add('drag-over');
        });

        zone.addEventListener('dragleave', function (e) {
            if (!zone.contains(e.relatedTarget)) zone.classList.remove('drag-over');
        });

        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            zone.classList.remove('drag-over');
            addFiles(Array.from(e.dataTransfer.files));
        });

        function addFiles(newFiles) {
            newFiles.forEach(function (f) {
                if (f.size > 25 * 1024 * 1024) {
                    showToast(f.name + ' ist zu groß (max. 25 MB)', 'error');
                    return;
                }
                var id = ++idCounter;
                files.push({ id: id, file: f, name: f.name, size: f.size, status: 'pending' });
                renderItem(id, f);
            });
        }

        function renderItem(id, f) {
            var li = document.createElement('li');
            li.className = 'upload-file-item';
            li.id = 'upload-item-' + id;
            li.innerHTML =
                '<div class="upload-file-icon">' +
                    '<i class="material-icons">' + getIcon(f.name) + '</i>' +
                '</div>' +
                '<div class="upload-file-info">' +
                    '<div class="upload-file-name">' + escHtml(f.name) + '</div>' +
                    '<div class="upload-file-size">' + formatSize(f.size) + '</div>' +
                    '<div class="upload-progress-bar"><div class="upload-progress-fill" id="prog-' + id + '"></div></div>' +
                '</div>' +
                '<button class="upload-file-remove" data-id="' + id + '" title="Entfernen">' +
                    '<i class="material-icons">close</i>' +
                '</button>';
            list.appendChild(li);

            li.querySelector('.upload-file-remove').addEventListener('click', function () {
                removeFile(this.dataset.id);
            });

            simulateProgress(id);
        }

        function simulateProgress(id) {
            var fill = document.getElementById('prog-' + id);
            if (!fill) return;
            var pct = 0;
            var interval = setInterval(function () {
                pct += Math.random() * 18 + 4;
                if (pct >= 100) {
                    pct = 100;
                    clearInterval(interval);
                    var item = document.getElementById('upload-item-' + id);
                    if (item) item.classList.add('upload-done');
                }
                fill.style.width = Math.min(pct, 100) + '%';
            }, 120);
        }

        function removeFile(id) {
            files = files.filter(function (f) { return f.id != id; });
            var el = document.getElementById('upload-item-' + id);
            if (el) {
                el.classList.add('upload-removing');
                setTimeout(function () { el.remove(); }, 250);
            }
        }

        function getIcon(name) {
            var ext = name.split('.').pop().toLowerCase();
            var map = {
                pdf: 'picture_as_pdf',
                doc: 'description', docx: 'description',
                ppt: 'slideshow',   pptx: 'slideshow',
                xls: 'table_chart', xlsx: 'table_chart',
                zip: 'folder_zip',
                jpg: 'image', jpeg: 'image', png: 'image'
            };
            return map[ext] || 'insert_drive_file';
        }

        function formatSize(bytes) {
            if (bytes < 1024)       return bytes + ' B';
            if (bytes < 1024*1024)  return (bytes/1024).toFixed(1) + ' KB';
            return (bytes/(1024*1024)).toFixed(1) + ' MB';
        }

        function escHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        function showToast(msg, type) {
            var t = document.createElement('div');
            t.className = 'upload-toast upload-toast--' + (type||'info');
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(function () { t.classList.add('visible'); }, 10);
            setTimeout(function () { t.classList.remove('visible'); setTimeout(function(){ t.remove(); }, 300); }, 3000);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initUpload);
    } else {
        initUpload();
    }
})();
</script>