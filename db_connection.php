<?php
function conectarDB() {
    $host = 'localhost';
    $usuario = 'root';
    $password = '';
    $database = 'google_login';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
        return null;
    }
}
?>
