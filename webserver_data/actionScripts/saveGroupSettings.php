<?php
header('Content-Type: application/json; charset=utf-8');
define('GOGROUP', true);
require_once 'dbConnect.php';
require_once 'sessioncheck.php';

$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput, true);

// Formatierte Daten
$groupId     = intval($data['groupId']);
$groupName   = trim($data['groupName']);
$groupDesc   = trim($data['groupDescription'] ?? '');
$memberIds = array_map('intval', $data['memberIds'] ?? []);

// Transaktion Starten
if (sqlsrv_begin_transaction($conn) === false) {
    echo json_encode(['success' => false, 'message' => 'Transaktionsfehler im System.']);
    exit;
}

try{
    #region Gruppe Updaten
    $sql = 'UPDATE "GROUP"
        SET
            Name = ?,
            Description = ?
        WHERE GroupId = ?';
    ExecuteQuery($sql, array($groupName, $groupDesc, $groupId));
    #endregion

    #region Mitglieder Updaten
    // Alle Mitglieder der Gruppe holen
    $dbMembers = GetMultipleRecords('SELECT [UserId] FROM [dbo].[MEMBER] WHERE [GroupId] = ?', [$groupId]);

    $currentUserIds = array_map(function($row) {
        return intval($row['UserId']);
    }, $dbMembers);

    $frontendUserIds = array_map('intval', $memberIds ?? []);


    // NEUE MITGLIEDER: Welche IDs sind im Frontend, aber noch NICHT in der DB?
    $idsToInsert = array_diff($frontendUserIds, $currentUserIds);

    // ENTFERNTE MITGLIEDER: Welche IDs sind in der DB, aber NICHT mehr im Frontend?
    $idsToDelete = array_diff($currentUserIds, $frontendUserIds);


    // Neue Mitglieder hinzufügen
    if (!empty($idsToInsert)) {
        $sqlInsert = 'INSERT INTO [dbo].[MEMBER] ([GroupId], [UserId]) VALUES (?, ?)';
        foreach ($idsToInsert as $newUserId) {
            ExecuteQuery($sqlInsert, [$groupId, $newUserId]);
        }
    }

    // Gelöschte Mitglieder entfernen
    if (!empty($idsToDelete)) {
        $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
        $sqlDelete = "DELETE FROM [dbo].[MEMBER] WHERE [GroupId] = ? AND [UserId] IN ($placeholders)";
        
        $paramsDelete = array_merge([$groupId], array_values($idsToDelete));
        ExecuteQuery($sqlDelete, $paramsDelete);
    }
    #endregion
}
catch (Exception $ex)
{
    sqlsrv_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Transaktion abschließen
sqlsrv_commit($conn);
echo json_encode(['success' => true, 'message' => 'Einstellungen erfolgreich gespeichert!']);
exit;
?>