<?php
define('GOGROUP', true);
include 'ajaxCheck.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'dbConnect.php';

$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "
    SELECT
        l.[LoginId],
        l.[UserPassword],
        u.[UserId],
        u.[FirstName],
        u.[LastName],
        r.[RoleId],
        r.[Name] AS RoleName
    FROM [dbo].[LOGIN] l
    JOIN [dbo].[USER]  u ON l.[UserId]  = u.[UserId]
    JOIN [dbo].[ROLE]  r ON u.[RoleId]  = r.[RoleId]
    WHERE l.[Username] = ?
";

$params = array($_POST['uname']);
$stmt   = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if (password_verify($_POST['pw'], $row['UserPassword'])) {

        session_regenerate_id(true);

        $_SESSION['loggedin']   = true;
        $_SESSION['username']   = $_POST['uname'];
        $_SESSION['user_id']    = $row['UserId'];
        $_SESSION['first_name'] = $row['FirstName'];
        $_SESSION['last_name']  = $row['LastName'];
        $_SESSION['role_id']    = $row['RoleId'];
        $_SESSION['role_name']  = $row['RoleName'];

        $perm_sql  = "SELECT [PermissionName] FROM [dbo].[PERMISSIONS] WHERE [RoleId] = ?";
        $perm_stmt = sqlsrv_query($conn, $perm_sql, array($row['RoleId']));

        if ($perm_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $_SESSION['permissions'] = [];
        while ($perm_row = sqlsrv_fetch_array($perm_stmt, SQLSRV_FETCH_ASSOC)) {
            $_SESSION['permissions'][] = $perm_row['PermissionName'];
        }
        sqlsrv_free_stmt($perm_stmt);
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

        header('Location: ../index.php');
        exit;
    }
    else {
        header("Location: ../login.php?error=failed&message=Benutzername oder Passwort ist falsch!");
        exit(); 
    }
} 
else {
    header("Location: ../login.php?error=failed&message=Benutzername exsistiert nicht!");
    exit();
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>