const FS = {
    root:       { id:'root',        name:'Ressourcen',  type:'folder', children:['f-mathe','f-deutsch','f-bio','file-prot'] },
    'f-mathe':  { id:'f-mathe',     name:'Mathematik',  type:'folder', children:['f-ana','file-formel','file-klausur'] },
    'f-ana':    { id:'f-ana',       name:'Analysis',    type:'folder', children:['file-diff','file-int'] },
    'f-deutsch':{ id:'f-deutsch',   name:'Deutsch',     type:'folder', children:['file-faust','file-referat'] },
    'f-bio':    { id:'f-bio',       name:'Biologie',    type:'folder', children:['file-zelle'] },
    'file-prot':    { id:'file-prot',    name:'Gruppenprotokoll_01.pdf',       type:'file', ext:'pdf',  size:'124 KB', date:'02.04.2026', uploader:{initials:'LM',name:'Laura Meier'},  history:[{type:'upload',label:'Hochgeladen',user:'Laura Meier',date:'02.04.2026, 10:34'},{type:'edit',label:'Metadaten bearbeitet',user:'Tim Brauer',date:'03.04.2026, 08:12'}] },
    'file-formel':  { id:'file-formel',  name:'Formelsammlung.pdf',            type:'file', ext:'pdf',  size:'340 KB', date:'28.03.2026', uploader:{initials:'TB',name:'Tim Brauer'},   history:[{type:'upload',label:'Hochgeladen',user:'Tim Brauer',date:'28.03.2026, 14:20'}] },
    'file-klausur': { id:'file-klausur', name:'Klausur_2025.docx',             type:'file', ext:'docx', size:'87 KB',  date:'15.01.2026', uploader:{initials:'AK',name:'Anna Klein'},   history:[{type:'upload',label:'Hochgeladen',user:'Anna Klein',date:'15.01.2026, 09:00'},{type:'move',label:'Verschoben nach Mathematik',user:'Tim Brauer',date:'20.01.2026, 11:44'}] },
    'file-diff':    { id:'file-diff',    name:'Differentialrechnung.pptx',     type:'file', ext:'pptx', size:'2.1 MB', date:'10.03.2026', uploader:{initials:'LM',name:'Laura Meier'},  history:[{type:'upload',label:'Hochgeladen',user:'Laura Meier',date:'10.03.2026, 16:05'}] },
    'file-int':     { id:'file-int',     name:'Integralrechnung_Aufgaben.pdf', type:'file', ext:'pdf',  size:'560 KB', date:'11.03.2026', uploader:{initials:'LM',name:'Laura Meier'},  history:[{type:'upload',label:'Hochgeladen',user:'Laura Meier',date:'11.03.2026, 16:12'},{type:'edit',label:'Version 2 hochgeladen',user:'Laura Meier',date:'15.03.2026, 09:33'}] },
    'file-faust':   { id:'file-faust',   name:'Faust_Analyse.docx',            type:'file', ext:'docx', size:'210 KB', date:'20.03.2026', uploader:{initials:'AK',name:'Anna Klein'},   history:[{type:'upload',label:'Hochgeladen',user:'Anna Klein',date:'20.03.2026, 13:00'}] },
    'file-referat': { id:'file-referat', name:'Referat_Kafka.pptx',            type:'file', ext:'pptx', size:'1.4 MB', date:'05.04.2026', uploader:{initials:'TB',name:'Tim Brauer'},   history:[{type:'upload',label:'Hochgeladen',user:'Tim Brauer',date:'05.04.2026, 18:22'},{type:'edit',label:'Folien ergänzt',user:'Tim Brauer',date:'06.04.2026, 10:01'}] },
    'file-zelle':   { id:'file-zelle',   name:'Zellbiologie.pdf',              type:'file', ext:'pdf',  size:'780 KB', date:'01.04.2026', uploader:{initials:'AK',name:'Anna Klein'},   history:[{type:'upload',label:'Hochgeladen',user:'Anna Klein',date:'01.04.2026, 11:55'}] },
};
 
const get = id => FS[id];
const findParent = childId => Object.values(FS).find(e => e.type==='folder' && (e.children||[]).includes(childId));
const fileIcon = ext => ({pdf:'picture_as_pdf',docx:'description',doc:'description',pptx:'slideshow',ppt:'slideshow',xlsx:'table_chart',xls:'table_chart',jpg:'image',jpeg:'image',png:'image',gif:'image',zip:'folder_zip',mp4:'movie',mp3:'audio_file'}[ext]||'insert_drive_file');
const iconColor = ext => ({pdf:'#dc2626',docx:'#2563eb',doc:'#2563eb',pptx:'#ea580c',ppt:'#ea580c',xlsx:'#16a34a',xls:'#16a34a'}[ext]||'var(--gold)');
const escHtml = s => s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
const formatBytes = b => b<1024?b+' B':b<1048576?(b/1024).toFixed(0)+' KB':(b/1048576).toFixed(1)+' MB';
const nowStr = () => new Date().toLocaleDateString('de-DE')+', '+new Date().toLocaleTimeString('de-DE',{hour:'2-digit',minute:'2-digit'});
function foldersFlat(excludeId){
    const list=[];
    function walk(id,depth){ const e=get(id); if(!e||e.type!=='folder'||e.id===excludeId)return; list.push({id:e.id,name:e.name,depth}); (e.children||[]).forEach(c=>walk(c,depth+1)); }
    walk('root',0); return list;
}
 
let currentView='list', currentPath=['root'], openFolders=new Set(), searchTerm='';
let activeFileId=null, activeDetailTab='info', moveItemId=null, moveTargetId=null, dragItemId=null;
 
function setView(v,btn){ currentView=v; document.querySelectorAll('.aufgaben-filter-tab').forEach(b=>b.classList.remove('active')); btn.classList.add('active'); renderExplorer(); }
 
function navigateTo(id){ const idx=currentPath.indexOf(id); if(idx!==-1)currentPath=currentPath.slice(0,idx+1); else currentPath.push(id); renderBreadcrumb(); renderExplorer(); }
function renderBreadcrumb(){
    const bc=document.getElementById('resBreadcrumb'); bc.innerHTML='';
    currentPath.forEach((id,i)=>{
        const e=get(id),isLast=i===currentPath.length-1;
        if(i>0){const sep=document.createElement('span');sep.className='res-bc-sep';sep.innerHTML='<i class="material-icons" style="font-size:18px;">chevron_right</i>';bc.appendChild(sep);}
        const a=document.createElement('a');a.href='#';a.className='res-bc-item'+(isLast?' active':'');a.textContent=e.name;
        if(!isLast)a.onclick=ev=>{ev.preventDefault();navigateTo(id);};bc.appendChild(a);
    });
    const cur=get(currentPath[currentPath.length-1]),kids=(cur.children||[]).map(id=>get(id)).filter(Boolean);
    document.getElementById('resCurrentTitle').textContent=cur.name;
    const fd=kids.filter(k=>k.type==='folder').length,fi=kids.filter(k=>k.type==='file').length;
    document.getElementById('resCurrentMeta').textContent=`${fd} Ordner · ${fi} Datei${fi!==1?'en':''}`;
}
 
function renderExplorer(){
    const c=document.getElementById('resExplorer');
    const cur=get(currentPath[currentPath.length-1]);
    const kids=(cur.children||[]).map(id=>get(id)).filter(Boolean);
    const filtered=searchTerm?kids.filter(e=>e.name.toLowerCase().includes(searchTerm.toLowerCase())):kids;
    c.className='res-explorer'+(currentView==='grid'?' grid-view':'');
    c.innerHTML='';
    if(!filtered.length){c.innerHTML=`<div class="res-empty"><i class="material-icons">folder_open</i><p>${searchTerm?'Keine Einträge gefunden.':'Dieser Ordner ist noch leer.'}</p></div>`;return;}
    const folders=filtered.filter(e=>e.type==='folder'),files=filtered.filter(e=>e.type==='file');
    if(currentView==='list'){ folders.forEach(f=>c.appendChild(buildFolderRow(f))); files.forEach(f=>c.appendChild(buildFileRow(f))); }
    else { folders.forEach(f=>c.appendChild(buildTile(f,true))); files.forEach(f=>c.appendChild(buildTile(f,false))); }
}
 
function buildFolderRow(folder){
    const isOpen=openFolders.has(folder.id);
    const kids=(folder.children||[]).map(id=>get(id)).filter(Boolean);
    const wrap=document.createElement('div');wrap.className='res-folder-row';wrap.dataset.id=folder.id;
    setupFolderDrop(wrap,folder.id);
    const header=document.createElement('div');header.className='res-folder-header';
    header.innerHTML=`<i class="material-icons res-folder-icon">${isOpen?'folder_open':'folder'}</i>
        <span class="res-folder-name">${escHtml(folder.name)}</span>
        <span class="res-folder-meta">${kids.filter(k=>k.type==='folder').length} Ordner · ${kids.filter(k=>k.type==='file').length} Dateien</span>
        <div class="res-folder-actions">
            <button class="res-action-btn" title="Datei hier hochladen" onclick="event.stopPropagation();openUploadInFolder('${folder.id}')"><i class="material-icons">upload_file</i></button>
            <button class="res-action-btn danger" title="Löschen" onclick="event.stopPropagation();deleteEntry('${folder.id}')"><i class="material-icons">delete_outline</i></button>
        </div>
        <i class="material-icons res-folder-chevron${isOpen?' open':''}">chevron_right</i>`;
    header.onclick=()=>toggleFolder(folder.id,wrap);
    wrap.appendChild(header);
    const childDiv=document.createElement('div');childDiv.className='res-folder-children'+(isOpen?' open':'');
    if(kids.length){
        kids.filter(k=>k.type==='folder').forEach(sf=>childDiv.appendChild(buildFolderRow(sf)));
        kids.filter(k=>k.type==='file').forEach(sf=>childDiv.appendChild(buildFileRow(sf)));
    } else {
        childDiv.innerHTML=`<div style="font-size:.8rem;color:var(--text-muted);padding:10px 4px;opacity:.55;font-style:italic;">Leerer Ordner – Dateien hierher ziehen</div>`;
    }
    wrap.appendChild(childDiv);
    return wrap;
}
function toggleFolder(id,wrap){
    const isOpen=openFolders.has(id);
    if(isOpen)openFolders.delete(id);else openFolders.add(id);
    wrap.querySelector('.res-folder-chevron').classList.toggle('open',!isOpen);
    wrap.querySelector('.res-folder-icon').textContent=isOpen?'folder':'folder_open';
    wrap.querySelector('.res-folder-children').classList.toggle('open',!isOpen);
}
 
function buildFileRow(file){
    const row=document.createElement('div');row.className='res-file-row'+(file.id===activeFileId?' active':'');
    row.dataset.id=file.id;row.setAttribute('draggable','true');
    row.innerHTML=`<div class="res-file-icon-wrap" style="background:${iconColor(file.ext)}"><i class="material-icons">${fileIcon(file.ext)}</i></div>
        <div class="res-file-info"><div class="res-file-name">${escHtml(file.name)}</div><div class="res-file-meta">${file.size} · ${file.date}</div></div>
        <div class="res-file-actions">
            <button class="res-action-btn" title="Verschieben" onclick="event.stopPropagation();openMoveModal('${file.id}')"><i class="material-icons">drive_file_move</i></button>
            <button class="res-action-btn" title="Herunterladen" onclick="event.stopPropagation();downloadFile('${file.id}')"><i class="material-icons">download</i></button>
            <button class="res-action-btn danger" title="Löschen" onclick="event.stopPropagation();deleteEntry('${file.id}')"><i class="material-icons">delete_outline</i></button>
        </div>`;
    row.onclick=()=>openDetailPanel(file.id);
    setupFileDrag(row,file.id);
    return row;
}
 
function buildTile(entry,isFolder){
    const tile=document.createElement('div');tile.className='res-tile'+(entry.id===activeFileId?' active':'');tile.dataset.id=entry.id;
    if(isFolder){
        tile.innerHTML=`<i class="material-icons res-tile-icon" style="color:var(--gold)">folder</i><span class="res-tile-name">${escHtml(entry.name)}</span><span class="res-tile-meta">${(entry.children||[]).length} Einträge</span>`;
        tile.onclick=()=>navigateTo(entry.id);
        setupFolderDrop(tile,entry.id);
    } else {
        tile.setAttribute('draggable','true');
        tile.innerHTML=`<i class="material-icons res-tile-icon" style="color:${iconColor(entry.ext)}">${fileIcon(entry.ext)}</i><span class="res-tile-name">${escHtml(entry.name)}</span><span class="res-tile-meta">${entry.size}</span>`;
        tile.onclick=()=>openDetailPanel(entry.id);
        setupFileDrag(tile,entry.id);
    }
    return tile;
}
 
function setupFileDrag(el,id){
    el.addEventListener('dragstart',e=>{
        dragItemId=id;el.classList.add('dragging');
        const ghost=document.getElementById('dragGhost');
        document.getElementById('dragGhostLabel').textContent=get(id).name;
        ghost.style.display='flex';
        try{e.dataTransfer.setDragImage(ghost,0,0);}catch(err){}
        e.dataTransfer.effectAllowed='move';
    });
    el.addEventListener('dragend',()=>{el.classList.remove('dragging');document.getElementById('dragGhost').style.display='none';dragItemId=null;});
}
function setupFolderDrop(el,folderId){
    el.addEventListener('dragover',e=>{if(!dragItemId)return;e.preventDefault();e.dataTransfer.dropEffect='move';el.classList.add('drag-over');});
    el.addEventListener('dragleave',e=>{if(!el.contains(e.relatedTarget))el.classList.remove('drag-over');});
    el.addEventListener('drop',e=>{e.preventDefault();el.classList.remove('drag-over');if(!dragItemId||dragItemId===folderId)return;performMove(dragItemId,folderId);});
}
 
function performMove(itemId,targetId){
    const item=get(itemId),target=get(targetId);
    if(!item||!target||target.type!=='folder')return;
    if((target.children||[]).includes(itemId)){showToast('Datei ist bereits in diesem Ordner.');return;}
    const parent=findParent(itemId);
    if(parent)parent.children=parent.children.filter(c=>c!==itemId);
    target.children.push(itemId);
    if(!item.history)item.history=[];
    item.history.unshift({type:'move',label:`Verschoben nach „${target.name}"`,user:'Du',date:nowStr()});
    renderExplorer();renderBreadcrumb();
    showToast(`„${item.name}" → ${target.name}`);
    if(activeFileId===itemId)renderDetailPanel(itemId);
}
function openMoveModal(id){
    moveItemId=id;moveTargetId=null;
    document.getElementById('moveItemLabel').textContent=get(id).name;
    const picker=document.getElementById('folderTreePicker');picker.innerHTML='';
    foldersFlat(id).forEach(f=>{
        const row=document.createElement('div');row.className='res-tree-item';row.dataset.id=f.id;
        row.innerHTML=`<span style="display:inline-block;width:${f.depth*18}px;flex-shrink:0;"></span><i class="material-icons">folder</i><span>${escHtml(f.name)}</span>`;
        row.onclick=()=>{document.querySelectorAll('.res-tree-item').forEach(r=>r.classList.remove('selected'));row.classList.add('selected');moveTargetId=f.id;};
        picker.appendChild(row);
    });
    showModal('moveModal');
}
function confirmMove(){if(!moveTargetId){showToast('Bitte einen Ordner wählen.',true);return;}performMove(moveItemId,moveTargetId);closeModal('moveModal');}
 
function openDetailPanel(id){
    activeFileId=id;
    document.getElementById('resDetailPanel').classList.remove('hidden');
    document.getElementById('resMainLayout').classList.add('panel-open');
    renderDetailPanel(id);
    document.querySelectorAll('.res-file-row,.res-tile').forEach(el=>el.classList.toggle('active',el.dataset.id===id));
}
function closeDetailPanel(){
    activeFileId=null;
    document.getElementById('resDetailPanel').classList.add('hidden');
    document.getElementById('resMainLayout').classList.remove('panel-open');
    document.querySelectorAll('.res-file-row,.res-tile').forEach(el=>el.classList.remove('active'));
}
function switchDetailTab(tab,btn){
    activeDetailTab=tab;
    document.querySelectorAll('.res-detail-tab').forEach(b=>b.classList.remove('active'));btn.classList.add('active');
    document.getElementById('detailTabInfo').classList.toggle('hidden',tab!=='info');
    document.getElementById('detailTabHistory').classList.toggle('hidden',tab!=='history');
}
function renderDetailPanel(id){
    const f=get(id);if(!f)return;
    document.getElementById('detailFilename').textContent=f.name;

    const prev=document.getElementById('resPreviewArea');prev.innerHTML='';
    if(['jpg','jpeg','png','gif'].includes(f.ext)){
        const img=document.createElement('img');img.src='/uploads/'+f.name;img.alt=f.name;
        img.onerror=()=>{prev.innerHTML=`<div class="res-preview-placeholder"><i class="material-icons">broken_image</i><span>Vorschau nicht verfügbar</span></div>`;};
        prev.appendChild(img);
    } else if(f.ext==='pdf'){
        prev.innerHTML=`<iframe src="/uploads/${escHtml(f.name)}" title="${escHtml(f.name)}"></iframe>`;
    } else if(f.ext==='mp4'){
        prev.innerHTML=`<video controls><source src="/uploads/${escHtml(f.name)}"></video>`;
    } else if(f.ext==='mp3'){
        prev.innerHTML=`<audio controls><source src="/uploads/${escHtml(f.name)}"></audio>`;
    } else {
        prev.innerHTML=`<div class="res-preview-placeholder"><i class="material-icons">${fileIcon(f.ext)}</i><span>Keine Vorschau für .${f.ext.toUpperCase()}</span></div>`;
    }

    const parent=findParent(id);
    document.getElementById('detailTabInfo').innerHTML=`
        <div class="res-info-row"><span class="res-info-label">Dateiname</span><span class="res-info-value">${escHtml(f.name)}</span></div>
        <div class="res-info-row"><span class="res-info-label">Typ</span><span class="res-info-value">.${f.ext.toUpperCase()}</span></div>
        <div class="res-info-row"><span class="res-info-label">Größe</span><span class="res-info-value">${f.size}</span></div>
        <div class="res-info-row"><span class="res-info-label">Geändert</span><span class="res-info-value">${f.date}</span></div>
        <div class="res-info-row"><span class="res-info-label">Ordner</span><span class="res-info-value">${parent?escHtml(parent.name):'–'}</span></div>
        <div class="res-info-row" style="border-bottom:none;"><span class="res-info-label">Hochgeladen von</span><span class="res-info-value"><span class="res-uploader-chip"><span class="res-uploader-avatar">${f.uploader?f.uploader.initials:'?'}</span>${f.uploader?escHtml(f.uploader.name):'Unbekannt'}</span></span></div>
        <div style="display:flex;gap:8px;margin-top:18px;padding-top:14px;border-top:1px solid var(--gold-pale);">
            <button class="aufgaben-new-btn" style="flex:1;justify-content:center;font-size:.85rem;padding:9px 12px;" onclick="downloadFile('${id}')"><i class="material-icons">download</i><span>Download</span></button>
            <button class="aufgaben-new-btn" style="flex:1;justify-content:center;font-size:.85rem;padding:9px 12px;background:none;border:1.5px solid var(--gold-light);color:var(--gold);box-shadow:none;" onclick="openMoveModal('${id}')"><i class="material-icons">drive_file_move</i><span>Verschieben</span></button>
        </div>`;
    const hist=f.history||[];
    document.getElementById('detailTabHistory').innerHTML=hist.length
        ?hist.map(h=>`<div class="res-history-item"><div class="res-history-dot ${h.type}"></div><div><div class="res-history-action">${escHtml(h.label)}</div><div class="res-history-meta">${escHtml(h.user)} · ${escHtml(h.date)}</div></div></div>`).join('')
        :`<div style="text-align:center;padding:32px;font-size:.85rem;color:var(--text-muted);opacity:.5;">Kein Verlauf vorhanden.</div>`;
    document.getElementById('detailTabInfo').classList.toggle('hidden',activeDetailTab!=='info');
    document.getElementById('detailTabHistory').classList.toggle('hidden',activeDetailTab!=='history');
}
 
function populateFolderSelect(preselect){
    const sel=document.getElementById('uploadFolderSelect');sel.innerHTML='';
    foldersFlat(null).forEach(f=>{
        const opt=document.createElement('option');opt.value=f.id;
        opt.textContent='\u00A0'.repeat(f.depth*3)+f.name;
        if(f.id===preselect)opt.selected=true;
        sel.appendChild(opt);
    });
}
function openUploadModal(){populateFolderSelect(currentPath[currentPath.length-1]);document.getElementById('uploadFileList').innerHTML='';showModal('uploadModal');}
function openUploadInFolder(id){populateFolderSelect(id);document.getElementById('uploadFileList').innerHTML='';showModal('uploadModal');}
function handleFileSelect(files){
    const list=document.getElementById('uploadFileList');
    Array.from(files).forEach(file=>{
        const li=document.createElement('li');li.className='upload-file-item';
        li.innerHTML=`<div class="upload-file-icon"><i class="material-icons">${fileIcon(file.name.split('.').pop().toLowerCase())}</i></div><div class="upload-file-info"><div class="upload-file-name">${escHtml(file.name)}</div><div class="upload-file-size">${formatBytes(file.size)}</div><div class="upload-progress-bar"><div class="upload-progress-fill"></div></div></div>`;
        list.appendChild(li);
        let pct=0;const fill=li.querySelector('.upload-progress-fill');
        const iv=setInterval(()=>{pct=Math.min(pct+Math.random()*22+5,100);fill.style.width=pct+'%';if(pct>=100){clearInterval(iv);li.classList.add('upload-done');}},80);
    });
}
function confirmUpload(){
    const files=document.getElementById('uploadInput').files;
    if(!files.length){showToast('Keine Dateien ausgewählt.',true);return;}
    const targetId=document.getElementById('uploadFolderSelect').value,parent=get(targetId);
    Array.from(files).forEach(file=>{
        const ext=file.name.split('.').pop().toLowerCase(),newId='file-'+Date.now()+'-'+Math.random().toString(36).slice(2);
        FS[newId]={id:newId,name:file.name,type:'file',ext,size:formatBytes(file.size),date:new Date().toLocaleDateString('de-DE'),
            uploader:{initials:'Du',name:'Du'},history:[{type:'upload',label:'Hochgeladen',user:'Du',date:nowStr()}]};
        parent.children.push(newId);
    });
    closeModal('uploadModal');renderExplorer();renderBreadcrumb();
    showToast(`${files.length} Datei${files.length>1?'en':''} in „${get(targetId).name}" hochgeladen.`);
}
 
function openNewFolderModal(){document.getElementById('newFolderName').value='';document.getElementById('newFolderError').textContent='';showModal('newFolderModal');}
function confirmNewFolder(){
    const name=document.getElementById('newFolderName').value.trim();
    if(!name){document.getElementById('newFolderError').textContent='Bitte einen Namen eingeben.';return;}
    const newId='f-'+Date.now();FS[newId]={id:newId,name,type:'folder',children:[]};
    get(currentPath[currentPath.length-1]).children.unshift(newId);
    closeModal('newFolderModal');renderExplorer();renderBreadcrumb();showToast(`Ordner „${name}" erstellt.`);
}
 
function deleteEntry(id){
    const e=get(id);if(!confirm(`„${e.name}" wirklich löschen?`))return;
    const parent=findParent(id);if(parent)parent.children=parent.children.filter(c=>c!==id);
    delete FS[id];if(activeFileId===id)closeDetailPanel();
    renderExplorer();renderBreadcrumb();showToast(`„${e.name}" gelöscht.`);
}
 
function downloadFile(id){ showToast(`Download gestartet: ${get(id).name}`);  }
 
function filterEntries(v){searchTerm=v.trim();renderExplorer();}
 
function showModal(id){const m=document.getElementById(id);m.classList.remove('hidden');requestAnimationFrame(()=>m.classList.add('visible'));}
function closeModal(id){const m=document.getElementById(id);m.classList.remove('visible');setTimeout(()=>m.classList.add('hidden'),280);}
 
function showToast(msg,err=false){const t=document.getElementById('resToast');t.textContent=msg;t.className='upload-toast visible'+(err?' upload-toast--error':'');setTimeout(()=>{t.className='upload-toast';},3200);}
 
document.addEventListener('DOMContentLoaded',()=>{renderBreadcrumb();renderExplorer();});