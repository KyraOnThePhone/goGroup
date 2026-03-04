<?php
$serverName = "sql, 1433"; 
$connectionInfo = array(
    "Database" => "GoGroup",
    "UID" => "sa",
    "PWD" => "BratwurstIN23!",
    "TrustServerCertificate" => true 
);

$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}