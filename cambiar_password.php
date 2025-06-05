<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($codigo) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos.';
        header('Location: cambiar_password.php');
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Las contraseñas no coinciden.';
        header('Location: cambiar_password.php');
        exit;
    }

    $conn = conectarDB();
    if (!$conn) {
        $_SESSION['error'] = 'Error al conectar con la base de datos.';
        header('Location: cambiar_password.php');
        exit;
    }

    try {
        // Verificar si el código es válido
        $stmt = $conn->prepare("SELECT usuario_id FROM codigos_recuperacion WHERE codigo = :codigo");
        $stmt->execute([':codigo' => $codigo]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            $_SESSION['error'] = 'Código inválido.';
            header('Location: cambiar_password.php');
            exit;
        }

        // Hashear la nueva contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Actualizar la contraseña del usuario
        $stmt = $conn->prepare("UPDATE usuarios_no_g SET contraseña = :password 
                              WHERE id = :usuario_id");
        $stmt->execute([
            ':password' => $hashed_password,
            ':usuario_id' => $resultado['usuario_id']
        ]);

        // Eliminar el código usado
        $stmt = $conn->prepare("DELETE FROM codigos_recuperacion WHERE codigo = :codigo");
        $stmt->execute([':codigo' => $codigo]);

        $_SESSION['success'] = 'Contraseña cambiada exitosamente. Puedes iniciar sesión.';
        header('Location: index.php');
        exit;

    } catch(PDOException $e) {
        $_SESSION['error'] = 'Error al cambiar la contraseña: ' . $e->getMessage();
        header('Location: cambiar_password.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Cambiar Contraseña</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                echo $_SESSION['error']; 
                                unset($_SESSION['error']); 
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="codigo">Código de recuperación:</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Nueva Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirmar Contraseña:</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
