<?php
session_start();
require_once 'config.php';

// Verificar si el usuario está logueado
$usuario = obtenerUsuario();
if (!$usuario) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bienvenido</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if ($usuario['tipo'] === 'google'): ?>
                            <div class="text-center mb-3">
                                <img src="<?php echo htmlspecialchars($usuario['imagen']); ?>" 
                                     class="rounded-circle" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                        
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">
                                <h5 class="mb-1">Información Personal</h5>
                                <p class="mb-1"><strong>Tipo de cuenta:</strong> <?php echo ucfirst($usuario['tipo']); ?></p>
                                <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
                                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></p>
                            </a>
                        </div>
                        <div class="mt-3">
                            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
