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

 <main class="gruppen-chat-container">

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

        <!-- <div class="groupchat"> -->
            <div>
            <div class="groupmessages" id="chatMessages">
            <div class="message-group other">
                
                <div class="message other">Hey, hast du die Aufgabe schon erledigt? 😊</div>
                <div class="message-time">14:32 Max</div>
            </div>
            <div class="message-group me">
                <div class="message me">Ja, fast fertig! Schicke dir das gleich.</div>
                <div class="message-time">14:35 Du</div>  
            </div>
            <div class="group other">
                <div class="message other">Super, danke! 🙌</div>
                <div class="message-time">14:36 Max</div>
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

    <!-- <div class= "gruppen-mitglieder-container">
        <h1 class="gruppen-content-title">Mitglieder</h1>
        <li>Max Mustermann</li>
        <li>Anna Schmidt</li>
        <li>Tobias Weber</li>
    </div> -->
</div>
</body>
    </html>
   