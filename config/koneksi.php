<?php
// filepath: /opt/lampp/htdocs/SmartSMANSABAYApt2/config/koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "esmart_db";

// MySQLi connection (existing)
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// PDO connection (tambahan untuk compatibility)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}
?>