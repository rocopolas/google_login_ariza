<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        $_SESSION['error'] = 'Por favor, ingrese su email.';
        header('Location: index.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Por favor, ingrese un email válido.';
        header('Location: index.php');
        exit;
    }

    $conn = conectarDB();
    if (!$conn) {
        $_SESSION['error'] = 'Error al conectar con la base de datos.';
        header('Location: index.php');
        exit;
    }

    try {
        // Verificar si el email existe
        $stmt = $conn->prepare("SELECT id, nombre FROM usuarios_no_g WHERE correo = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['error'] = 'No se encontró una cuenta con este email.';
            header('Location: index.php');
            exit;
        }

        // Generar un código numérico simple
        $codigo = rand(100000, 999999);

        // Insertar el código en la base de datos
        $stmt = $conn->prepare("INSERT INTO codigos_recuperacion (usuario_id, codigo) 
                              VALUES (:usuario_id, :codigo)");
        $stmt->execute([
            ':usuario_id' => $usuario['id'],
            ':codigo' => $codigo
        ]);

        // Mostrar el código al usuario
        $_SESSION['success'] = "
        <div class='alert alert-info'>
            <h4 class='alert-heading'>Código de recuperación generado</h4>
            <p>Se ha generado un código de recuperación para tu cuenta. Para cambiar tu contraseña, ingresa este código en el formulario de cambio de contraseña.</p>
            <p class='mb-0'><strong>Código:</strong> $codigo</p>
        </div>
        <div class='mt-3'>
            <a href='cambiar_password.php' class='btn btn-primary'>Cambiar Contraseña</a>
        </div>
        ";
        header('Location: index.php');
        exit;

    } catch(PDOException $e) {
        $_SESSION['error'] = 'Error al procesar la solicitud: ' . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}
?>
