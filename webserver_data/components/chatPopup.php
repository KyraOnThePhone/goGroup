<button id="chatFab" class="chat-fab" title="Nachrichten öffnen" type="button">
    <i class="material-icons">chat</i>
</button>

<!-- ── Chat Popup ──────────────────────────────────────────── -->
<div id="chatPopup" class="chat-popup hidden">

    <div class="chat-header">
        <div class="chat-header-icon">
            <i class="material-icons" style="font-size:20px">forum</i>
        </div>
        <div class="chat-header-text">
            <div class="chat-title-main" id="chatTitle">Nachrichten</div>
            <div class="chat-title-sub" id="chatSubtitle">3 Gespräche</div>
        </div>
        <div class="chat-header-actions">
            <button id="chatBackBtn" type="button" title="Zurück" style="display:none">
                <i class="material-icons" style="font-size:20px">arrow_back</i>
            </button>
            <button id="chatCloseBtn" type="button" title="Schließen">
                <i class="material-icons" style="font-size:20px">close</i>
            </button>
        </div>
    </div>

    <!-- Kontaktliste -->
    <div id="chatList" class="chat-list">
        <div class="chat-list-item" data-name="Max Mustermann">
            <div class="chat-avatar">MM</div>
            <div class="chat-info">
                <div class="chat-name">Max Mustermann</div>
                <div class="chat-preview">Hey, hast du die Aufgabe schon?</div>
            </div>
            <span class="chat-badge">2</span>
        </div>
        <div class="chat-list-item" data-name="Erika Muster">
            <div class="chat-avatar" style="background:#8b0000">EM</div>
            <div class="chat-info">
                <div class="chat-name">Erika Muster</div>
                <div class="chat-preview">Wann ist der nächste Termin?</div>
            </div>
        </div>
        <div class="chat-list-item" data-name="Gruppe: Informatik">
            <div class="chat-avatar" style="background:#4a7c59">GI</div>
            <div class="chat-info">
                <div class="chat-name">Gruppe: Informatik</div>
                <div class="chat-preview">Luca: Ich habe das Dokument...</div>
            </div>
            <span class="chat-badge">5</span>
        </div>
    </div>

    <!-- Chat Fenster -->
    <div id="chatWindow" class="chat-window hidden">
        <div class="messages" id="chatMessages">
            <div class="message-group other">
                <div class="message other">Hey, hast du die Aufgabe schon erledigt? 😊</div>
                <div class="message-time">14:32</div>
            </div>
            <div class="message-group me">
                <div class="message me">Ja, fast fertig! Schicke dir das gleich.</div>
                <div class="message-time">14:35</div>
            </div>
            <div class="message-group other">
                <div class="message other">Super, danke! 🙌</div>
                <div class="message-time">14:36</div>
            </div>
        </div>
        <div class="chat-input-bar">
            <input type="text" id="chatMsgInput" placeholder="Schreibe eine Nachricht…" autocomplete="off" />
            <button class="chat-send-btn" id="chatSendBtn" type="button" title="Senden">
                <i class="material-icons" style="font-size:18px">send</i>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    function init() {
        var fab      = document.getElementById('chatFab');
        var popup    = document.getElementById('chatPopup');
        var closeBtn = document.getElementById('chatCloseBtn');
        var backBtn  = document.getElementById('chatBackBtn');
        var chatList = document.getElementById('chatList');
        var chatWin  = document.getElementById('chatWindow');
        var titleEl  = document.getElementById('chatTitle');
        var subEl    = document.getElementById('chatSubtitle');
        var msgCont  = document.getElementById('chatMessages');
        var input    = document.getElementById('chatMsgInput');
        var sendBtn  = document.getElementById('chatSendBtn');
        var navBtn   = document.getElementById('openChatPopup');

        if (!fab || !popup) return;

        function openPopup() {
            popup.classList.remove('hidden');
            fab.classList.add('hidden');
        }

        function closePopup() {
            popup.classList.add('hidden');
            fab.classList.remove('hidden');
        }

        function showList() {
            chatWin.classList.add('hidden');
            chatList.classList.remove('hidden');
            backBtn.style.display = 'none';
            titleEl.textContent = 'Nachrichten';
            subEl.textContent   = '3 Gespräche';
        }

        function openChat(name) {
            chatList.classList.add('hidden');
            chatWin.classList.remove('hidden');
            backBtn.style.display = 'flex';
            titleEl.textContent = name;
            subEl.textContent   = 'Online';
            input.focus();
        }

        function pad(n) { return n < 10 ? '0' + n : n; }

        function sendMessage() {
            var text = input.value.trim();
            if (!text) return;
            var now  = new Date();
            var time = now.getHours() + ':' + pad(now.getMinutes());

            var g = document.createElement('div');
            g.className = 'message-group me';
            g.innerHTML =
                '<div class="message me">' + text.replace(/</g, '&lt;') + '</div>' +
                '<div class="message-time">' + time + '</div>';
            msgCont.appendChild(g);
            msgCont.scrollTop = msgCont.scrollHeight;
            input.value = '';

            setTimeout(function () {
                var r = document.createElement('div');
                r.className = 'message-group other';
                r.innerHTML =
                    '<div class="message other">Alles klar, danke! 👍</div>' +
                    '<div class="message-time">' + time + '</div>';
                msgCont.appendChild(r);
                msgCont.scrollTop = msgCont.scrollHeight;
            }, 1000);
        }

        fab.addEventListener('click', openPopup);
        closeBtn.addEventListener('click', closePopup);
        backBtn.addEventListener('click', showList);
        sendBtn.addEventListener('click', sendMessage);
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') sendMessage();
        });

        if (navBtn) {
            navBtn.addEventListener('click', function (e) {
                e.preventDefault();
                openPopup();
            });
        }

        var items = chatList.querySelectorAll('.chat-list-item');
        for (var i = 0; i < items.length; i++) {
            (function (item) {
                item.addEventListener('click', function () {
                    openChat(item.getAttribute('data-name'));
                });
            })(items[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
