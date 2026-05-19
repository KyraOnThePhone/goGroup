<?php

#region Helper für die DB
function GetMultipleRecords($sql, $params = array()) {
    global $conn;

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $rows = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $row;
    }
    
    sqlsrv_free_stmt($stmt);
    return $rows;
}

function GetSingleRecord($sql, $params = array()) {
    global $conn;

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
    sqlsrv_free_stmt($stmt);
    return $row ? $row : null;
}

function ExecuteQuery($sql, $params = array()) {
    global $conn;

    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    return true;
}
#endregion

function GetUserGroups($userId) {
    $sql = "SELECT 
                me.[GroupId],
                COUNT(m2.[GroupId]) AS MemberCount,
                gr.[Name] AS GroupName
            FROM [dbo].[MEMBER] as me
            LEFT JOIN [MEMBER] AS m2 ON m2.[GroupId] = me.[GroupId]
            LEFT JOIN [GROUP] AS gr ON gr.[GroupId] = me.[GroupId]
            WHERE me.[UserId] = ? 
            GROUP BY me.[GroupId], gr.[Name]";
            
    return GetMultipleRecords($sql, array($userId)); 
}

function GetGroupDetails($groupId){
    $sql = 'SELECT
            G."GroupId",
            G.Name,
            G.Description,
            (SELECT COUNT(*) FROM "MEMBER" WHERE "GroupId" = G."GroupId") AS MemberCount
        FROM [dbo].[GROUP] AS G
        WHERE G."GroupId" = ?';
    return GetSingleRecord($sql,[$groupId]);
}

function GetGroupMembers($groupId){
    $sql = 'SELECT * FROM [dbo].[MEMBER] WHERE GroupId = ?';
    return GetMultipleRecords($sql,$groupId);
}

function GetGroupMembersFormatted($groupId) {
    global $conn;

    $sql = 'SELECT 
                m.[MemberId], 
                m.[UserId], 
                u.[FirstName],
                u.[LastName],
                R."Name" AS RoleName
            FROM [dbo].[MEMBER] AS m
            INNER JOIN [dbo].[USER] AS u ON u.[UserId] = m.[UserId]
            JOIN "ROLE" AS R ON r."RoleId" = u."RoleId"
            WHERE m.[GroupId] = ?';
            
    $members = GetMultipleRecords($sql, array($groupId));
    $formattedMembers = [];
    
    foreach ($members as $member) {
        $firstName = $member['FirstName'] ?? '';
        $lastName  = $member['LastName'] ?? '';
        $fullName  = trim($firstName . ' ' . $lastName);
        $role = $member['RoleName'];
        
        if (empty($fullName)) {
            $fullName = 'Unbekanntes Mitglied';
        }

        $formattedMembers[] = [
            'id'      => $member['UserId'],
            'name'    => $fullName,
            'kuerzel' => GenerateAvatarByName($fullName), 
            'color'   => GenerateAvatarColor($fullName), 
            'role'    => strtolower($role),
            'roleDisplayName' => $member["RoleName"]
        ];
    }
    
    return $formattedMembers;
}

function GetAllUsers(){
    $sql = 'SELECT
            U."UserId",
            U."FirstName",
            U."LastName",
            R."Name" AS RoleName

        FROM "USER" AS U
        JOIN "ROLE" R ON R."RoleId" = U."RoleId"';
    return GetMultipleRecords($sql);
}

function GetAllUsersFormatted() {
    $users = GetAllUsers();
    
    $formattedUsers = [];
    
    foreach ($users as $user) {
        $userId   = $user['UserId'] ?? $user['id'];
        $fullName = $user["FirstName"];

        if ($user["LastName"]){
            $fullName = $fullName . " " . $user["LastName"];
        }
        
        $formattedUsers[] = [
            'id'      => intval($userId),
            'name'    => $fullName,
            'kuerzel' => GenerateAvatarByName($fullName),
            'color'   => GenerateAvatarColor($fullName),
            'role'    => strtolower($user['RoleName']),
            'roleDisplayName' => $user["RoleName"]
        ];
    }
    
    return $formattedUsers;
}
?>