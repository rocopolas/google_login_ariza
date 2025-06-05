<?php
include('config.php');

$login_button = '';

if (isset($_GET["code"])) {

    try {
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];
        
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        $_SESSION['user_email_address'] = $data['email'];
        $_SESSION['user_first_name'] = isset($data['given_name']) ? $data['given_name'] : '';
        $_SESSION['user_last_name'] = isset($data['family_name']) ? $data['family_name'] : '';
        $_SESSION['user_image'] = isset($data['picture']) ? $data['picture'] : 'https://via.placeholder.com/100';
        
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        error_log('Error en la autenticación: ' . $e->getMessage());
        session_destroy();
        header('Location: index.php');
        exit;
    }
}

//Ancla para iniciar sesión
if (!isset($_SESSION['access_token'])) {
    $login_button = '<a href="' . $google_client->createAuthUrl() . '" style=" background: #dd4b39; border-radius: 5px; color: white; display: block; font-weight: bold; padding: 20px; text-align: center; text-decoration: none; width: 200px;">Login With Google</a>';
}
?>

<html>
<head>
    <title>Login google</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

</head>

<body>
    <div class="container">
        <br />
        <h2 align="center" style="text-align: center;"> Inicio de sesión</h2>
        <br />
        <!-- Mensajes de éxito/error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']); 
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); 
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
        <p class="ml-5">o</p>
        <div class="mt-4">
            <a href="registro.html" class="btn btn-success">Registrarse</a>
        </div>
        
        <div class="mt-4 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">¿Olvidaste tu contraseña?</h5>
                </div>
                <div class="card-body" style="display: none;">
                    <p class="mb-3">Si olvidaste tu contraseña, ingresa tu email y te enviaremos un código para recuperarla.</p>
                    <form action="recuperar.php" method="POST" class="form-inline">
                        <div class="form-group mx-sm-3">
                            <input type="email" class="form-control" name="email" placeholder="Tu email" required>
                        </div>
                        <button type="submit" class="btn btn-light">Recuperar contraseña</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
        document.querySelector('.card-header').addEventListener('click', function() {
            const body = this.parentElement.querySelector('.card-body');
            body.style.display = body.style.display === 'block' ? 'none' : 'block';
        });
        </script>

        
        
        <div>
            <?php
                    if ($login_button == '') {
                        // Si ya está logueado, redirigir al dashboard
                        header('Location: dashboard.php');
                        exit;
                    } else {
                        echo '<div align="center">' . $login_button . '</div>';
                    }
            ?>
        </div>
    </div>
</body>
</html>