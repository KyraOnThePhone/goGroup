<?php

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

function GetUserGroups($userId) {
    $sql = "SELECT me.[GroupId], COUNT(m2.[GroupId]) AS MemberCount, gr.[Name] AS GroupName
            FROM [dbo].[MEMBER] as me
            LEFT JOIN [MEMBER] AS m2 ON m2.[GroupId] = me.[GroupId]
            LEFT JOIN [GROUP] AS gr ON gr.[GroupId] = me.[GroupId]
            WHERE me.[UserId] = ? 
            GROUP BY me.[GroupId], gr.[Name]";
            
    return GetMultipleRecords($sql, array($userId)); 
}

function GetGroupDetails($groupId){
    global $conn;

    $sql = 'SELECT
            G."GroupId",
            G.Name,
            (SELECT COUNT(*) FROM "MEMBER" WHERE "GroupId" = G."GroupId") AS MemberCount
        FROM [dbo].[GROUP] AS G
        WHERE G."GroupId" = ?';
    return GetSingleRecord($sql,[$groupId]);
}

?>