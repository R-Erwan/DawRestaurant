<?php
// config/db.php

$host = 'db'; // Nom du service de la DB dans docker-compose
$dbname = 'daw_db';
$user = DB_USER;
$pass = DB_PASS;
$charset = 'utf8';

$dsn = "pgsql:host=$host;port=5432;dbname=$dbname;user=$user;password=$pass";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
