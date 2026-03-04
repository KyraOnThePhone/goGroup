<?php
include 'actionScripts/sessioncheck.php';
$gruppenName   = "Informatik LK";
$gruppenMeta   = "12 Mitglieder";
$gruppenAvatar = "IL";
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
        </div>

        <div class="groupchat">
            <div class="groupmessages" id="chatMessages">
            <div class="groupmessage-group other">
                <div class="message other">Hey, hast du die Aufgabe schon erledigt? 😊</div>
                <div class="message-time-user">14:32 Max</div>
              
            </div>
            <div class="groupmessage-group me">
                <div class="message me">Ja, fast fertig! Schicke dir das gleich.</div>
                <div class="message-time-user">14:35 Du</div>
               
            </div>
            <div class="groupmessage-group other">
                <div class="message other">Super, danke! 🙌</div>
                <div class="message-time-user">14:36 Max</div>
            </div>
        </div>
        <div class="group-chat-input-bar">
            <input type="text" id="chatMsgInput" placeholder="Schreibe eine Nachricht…" autocomplete="off" />
            <button class="chat-send-btn" id="chatSendBtn" type="button" title="Senden">
                <i class="material-icons" style="font-size:18px">send</i>
            </button>
        </div>
        </div>
    </main>
</div>
</body>
    </html>
   