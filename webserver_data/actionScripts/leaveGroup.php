<?php
header('Content-Type: application/json; charset=utf-8');
define('GOGROUP', true);
require_once 'dbConnect.php';
require_once 'sessioncheck.php';

$groupId = isset($_POST['groupId']) ? intval($_POST['groupId']) : 0;

if ($groupId > 0) {
    $sql = "DELETE FROM [dbo].[MEMBER] WHERE [GroupId] = ? AND [UserId] = ?";
    
    if (ExecuteQuery($sql, array($groupId, $_SESSION['user_id']))) {
        echo json_encode(['success' => true, 'message' => 'Gruppe verlassen.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Ungültige Daten übergeben.']);
exit;