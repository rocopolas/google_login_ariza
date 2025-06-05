<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $nombre = $_POST['nombre'] ?? '';

    // Validar campos requeridos
    if (empty($email) || empty($password) || empty($nombre)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos.';
        header('Location: index.php');
        exit;
    }

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Por favor, ingrese un email válido.';
        header('Location: index.php');
        exit;
    }

    // Conectar a la base de datos
    $conn = conectarDB();
    if (!$conn) {
        $_SESSION['error'] = 'Error al conectar con la base de datos.';
        header('Location: index.php');
        exit;
    }

    try {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios_no_g WHERE correo = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Este email ya está registrado.';
            header('Location: index.php');
            exit;
        }

        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $stmt = $conn->prepare("INSERT INTO usuarios_no_g (nombre, correo, contraseña) VALUES (:nombre, :email, :password)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $hashed_password
        ]);

        $_SESSION['success'] = 'Registro exitoso. Por favor, inicie sesión.';
        header('Location: index.php');
        exit;

    } catch(PDOException $e) {
        $_SESSION['error'] = 'Error al registrar el usuario: ' . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}
?>
