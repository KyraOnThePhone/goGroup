<?php


function GenerateAvatarByName($groupName) {
    if (empty($groupName)) {
        return "??";
    }
    
    return htmlspecialchars(mb_strtoupper(mb_substr($groupName, 0, 2)));
}

function GenerateAvatarColor($groupName){
    return '#' . substr(md5($groupName), 0, 6);
}

function LoadGroupIdFromParam(){
    global $_GET;
    $id = $_GET['groupId'] ?? $_GET['id'] ?? null;

    if ($id === null || empty($id)) {
        trigger_error("GruppenID nicht geladen!");
        exit;
    }

    return $id;
}
?>