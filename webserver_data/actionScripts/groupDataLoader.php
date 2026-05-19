<?php
$currentGroupId = LoadGroupIdFromParam();
$groupDetails = GetGroupDetails($currentGroupId);

$gruppenName = $groupDetails["Name"];
$groupDescription = $groupDetails["Description"];
$gruppenMeta = "{$groupDetails["MemberCount"]} Mitglieder";
$groupCount = $groupDetails["MemberCount"];
$gruppenAvatar = GenerateAvatarByName($gruppenName) ?? "??";
?>