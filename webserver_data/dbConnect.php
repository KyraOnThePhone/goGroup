<?php
include 'actionScripts/sessioncheck.php';

if (!isset($_SESSION['loggedin'])) {
    echo "Nicht autorisiert.";
    exit;
}

// Verbindung zur MSSQL-Datenbank herstellen
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