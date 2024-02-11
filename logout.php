<?php
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
if (isset($_SESSION['user']))
  {
    destroySession();
    echo "<div class='main'>Has salido de tu sesión. " ;
    // redirección a la pantalla principal
    header('Location: ./index.php');
  }
  else echo "<div class='main'><br>" .
            "No puedes salir de sesión por que no estas registrado";