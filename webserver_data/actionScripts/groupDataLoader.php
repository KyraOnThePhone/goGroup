<?php
$currentGroupId = LoadGroupIdFromParam();
$groupDetails = GetGroupDetails($currentGroupId);

$gruppenName = $groupDetails["Name"];
$groupDescription = $groupDetails["Description"];
$gruppenMeta = "{$groupDetails["MemberCount"]} Mitglieder";
$groupCount = $groupDetails["MemberCount"];
$gruppenAvatar = GenerateAvatarByName($gruppenName) ?? "??";


// Prüfen ob der Nutzer zugriff auf die Gruppe haben sollte
if (!GetSingleRecord('SELECT "MemberId", "GroupId" FROM "MEMBER" WHERE "UserId" = ? AND "GroupId" = ?',[$_SESSION['user_id'],$currentGroupId])){
    header('Location: ../index.php');
}
?>