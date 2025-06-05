<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'db_connection.php';

$google_client = new Google_Client();
$google_client->setClientId('.apps.googleusercontent.com');
$google_client->setClientSecret('');
$google_client->setRedirectUri('http://localhost/index.php');

$google_client->addScope('email');
$google_client->addScope('profile');

// Función para guardar o actualizar usuario en la base de datos
function guardarUsuario($usuario) {
    $conn = conectarDB();
    if (!$conn) return false;
    
    try {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo) 
                              VALUES (:nombre, :correo)
                              ON DUPLICATE KEY UPDATE 
                              nombre = :nombre");
        
        $stmt->execute([
            ':nombre' => $usuario['name'],
            ':correo' => $usuario['email']
        ]);
        
        return true;
    } catch(PDOException $e) {
        echo "Error al guardar usuario: " . $e->getMessage();
        return false;
    }
}

// Función para obtener información del usuario
function obtenerUsuario() {
    if (isset($_SESSION['user_id'])) {
        // Usuario registrado
        return [
            'nombre' => $_SESSION['user_nombre'],
            'correo' => $_SESSION['user_correo'],
            'tipo' => 'registrado'
        ];
    } elseif (isset($_SESSION['user_email_address'])) {
        // Usuario de Google
        return [
            'nombre' => $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'],
            'correo' => $_SESSION['user_email_address'],
            'tipo' => 'google',
            'imagen' => $_SESSION['user_image']
        ];
    }
    return null;
}
?>
