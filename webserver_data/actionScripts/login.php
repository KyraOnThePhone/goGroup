<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


$serverName = "sql, 1433"; 
$connectionInfo = array(
    "Database" => "GoGroup",
    "UID" => "sa",
    "PWD" => "BratwurstIN23!",
    "TrustServerCertificate" => true 
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}


$sql = "SELECT 
            u.UserId AS id,
            u.FirstName,
            u.LastName,
            r.Name AS role_name,
            r.RoleId AS role_id,
            l.Username,
            l.UserPassword as password
        FROM dbo.[USER] AS u
        LEFT JOIN dbo.ROLE AS r ON u.RoleId = r.RoleId
        LEFT JOIN dbo.LOGIN AS l ON u.UserId = l.UserId
        WHERE l.Username = ?";
$params = array($_POST['uname']);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $id = $row['id'];
    $password = $row['password'];
    $role_id = $row['role_id'];
    $role_name = $row['role_name']; 


    if (password_verify($_POST['pw'], $password)) {
    
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['uname'];
        $_SESSION['id'] = $id;
        $_SESSION['role_id'] = $role_id;  
        $_SESSION['role_name'] = $role_name;  


        $permission_sql = "SELECT PermissionName FROM PERMISSIONS WHERE RoleId = ?";
        $permission_stmt = sqlsrv_query($conn, $permission_sql, array($role_id));

        if ($permission_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }


        $_SESSION['permissions'] = [];
        while ($permission_row = sqlsrv_fetch_array($permission_stmt, SQLSRV_FETCH_ASSOC)) {
            $_SESSION['permissions'][] = $permission_row['PermissionName'];
        }

        sqlsrv_free_stmt($permission_stmt);


        header('Location: ../index.php');
        exit;
    } else {
        echo 'Passwort stimmt nicht mit dem Username überein';
    }
} else {
    echo 'Username existiert nicht';
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>