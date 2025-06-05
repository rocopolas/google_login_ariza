<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar campos requeridos
    if (empty($email) || empty($password)) {
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
        // Buscar el usuario por email
        $stmt = $conn->prepare("SELECT id, nombre, correo, contraseña FROM usuarios_no_g WHERE correo = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            // Si las credenciales son correctas, iniciar sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['user_correo'] = $usuario['correo'];
            
            // Redirigir al dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = 'Email o contraseña incorrectos.';
            header('Location: index.php');
            exit;
        }

    } catch(PDOException $e) {
        $_SESSION['error'] = 'Error al procesar el inicio de sesión: ' . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}
?>
