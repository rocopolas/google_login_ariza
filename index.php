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
        <h2 align="center" style="text-align: center;"> Inicio de sesión con google</h2>
        <br />
        <div>
            <?php
                    if ($login_button == '') {
                        echo '<div class="card-header">Welcome User</div><div class="card-body">';
                        echo '<img src="' . $_SESSION["user_image"] . '" class="rounded-circle container"/>';
                        echo '<h3><b>Name :</b> ' . $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'] . '</h3>';
                        echo '<h3><b>Email :</b> ' . $_SESSION['user_email_address'] . '</h3>';
                        echo '<h3><a href="logout.php">Logout</h3></div>';
                    } else {
                        echo '<div align="center">' . $login_button . '</div>';
                    }
            ?>
        </div>
    </div>
</body>
</html>