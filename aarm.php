<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KepFit</title>
  <link rel="icon" type="image/x-icon" href="./assets/img/KepFit-Icon.png">
  <!-- Agrega la referencia al archivo CSS de Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inclusive+Sans&family=Roboto:wght@100&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inclusive+Sans&family=Martian+Mono&family=Roboto:wght@100&display=swap">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<?php
/**
 * @title: Proyecto integrador Ev01 - Registro en el sistema.
 * @description:  Script PHP para almacenar un nuevo usuario en la base de datos
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */
session_start([
    'cookie_lifetime' => 86400,
]);

// Realizando la llamada al script functions establezco la conexión con base de datos
require_once 'utils/functions.php';
$userstr = ' (Invitado)';
// Gestión de la sesión de usuario
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr = " ($user)";
} else{
    $loggedin = FALSE;
}

$error = $user = $pass = "";

if (isset($_POST['email'])) {

  // TODO Realiza la lectura de los campos del formulario en $user y $pass
  $user = $_POST['email'];
  $pass = $_POST['password'];

  if ($user == "" || $pass == "") {
    $error = "Debes completar todos los campos";
  }
  else {
    $result = queryMysql("SELECT * FROM usuarios WHERE user='$user'");

    if ($result->num_rows) {
      $error = "El usuario ya existe";
    }
    else {
      queryMysql("INSERT INTO usuarios(user,pass) VALUES('$user', '$pass')");

      // TODO
      // Establecer el almacenamiento de usuario en una variable "user" almacenada en sesión
      // para que al pulsar sobre el menú de Acceder no se le vuelva a preguntar por usuario/contraseña
      $_SESSION['user'] = $user;

      //header('Location: login.php?');
    }
  }
}
?>
</head>
<body>
<div class="container">
    <form class="form-horizontal" role="form" method="POST" action="aarm.php">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h2>Registro de Un Nuevo Usuario</h2>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="form-group has-danger">
                    <label class="sr-only" for="email">Email:</label>
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i
                                    class="fa fa-at"></i></div>
                        <input type="text" name="email" class="form-control"
                               id="email"
                               placeholder="vivayo@correo.com" required
                               autofocus>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-control-feedback">
                    <span class="text-danger align-middle">
                        <?php echo $error; // Muestra mensaje de error ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="sr-only" for="password">Contraseña:</label>
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i
                                    class="fa fa-key"></i></div>
                        <input type="password" name="password"
                               class="form-control" id="password"
                               placeholder="Password" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-control-feedback">
                    <span class="text-danger align-middle">
                        <?php echo $error; // Muestra mensaje de error ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="row" style="padding-top: 1rem">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-success"><i
                            class="fa fa-sign-in"></i> Registrar
                </button>
            </div>
        </div>
    </form>
</div>
</body>
<footer class="footer bg-light text-lg-start ">
  <div class="text-center">
    <p>Rubén, Asier Lizarraga, Asier Mañeru, Jon y Enmanuel Holgado. 2ºASIR
    </p>
    <p class="mb-0">
      Nuestras redes sociales:
    </p>
  </div>
</footer>
</html>