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
// Al pulsar el botón del formulario se recarga la misma página, volviendo a ejecutar este script.
// En caso de que se haya completado los valores del formulario se verifica la existencia de usuarios en la base de datos
// para los valores introducidos.
if (isset($_POST['email']))
{
  $user = $_POST['email']; // Cambiado de 'user' a 'email'
  $pass = $_POST['pass'];
  if ($user == "" || $pass == "")
      $error = "Debes completar todos los campos<br>";
  else
  {
      // TODO Cambiada la variable $result a $result para evitar errores
      $result = queryMysql("SELECT * FROM usuarios WHERE user='$user' AND pass='$pass'" );
      
      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>Email/Contraseña invalida</span><br><br>";    
        
        // header('Location: index.php');
      }
      else
      {
        // TODO Realiza la gestión de la sesión de usuario:
        // Almacena en la variable de sesión 'user' el valor de $user
        $_SESSION['user'] = $user;
        // Control de vida de la sesión antes de que expire
        if (!isset($_SESSION['CREATED'])) {
          $_SESSION['CREATED'] = time();
        } else if (time() - $_SESSION['CREATED'] > 1800) {
          // session started more than 30 minutes ago
          session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
          $_SESSION['CREATED'] = time();  // update creation time
        }

        // En caso de un acceso exitoso 
        // La gestión de usuario en la página principal se hace a través de la variable de sesión
       header('Location: intranet.php');
      }
  }
}
// En caso de que no se haya completado el formulario,
// analizamos si hay variable de sesión almacenada.
else if (isset($_SESSION['user'])){
    // En caso de que exista variable de sesión redireccionamos a la página principal
   //header('Location: index.php'); 
}
?>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#index">
          <img src="./assets/img/KepFit.png" alt="Logo" width="120" height="90">
          KepFit
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav d-flex">
            <li class="nav-item ms-4">
              <a class="nav-link active events tran" aria-current="page" href="#productos">Productos/Servicios</a>
            </li>
            <li class="nav-item ms-4">
              <a class="nav-link active tran" aria-current="page" href="#somos">¿Quienes Somos?</a>
            </li>
            <li class="nav-item ms-4">
              <a class="nav-link active tran"  aria-current="page" href="#contacto">Contacto</a>
            </li>
            <li class="nav-item ms-4">
              <a class="nav-link active tran" aria-current="page" href="#trabaja">Trabaja Con Nosotros</a>
            </li>
            <li class="nav-item dropdown ms-4">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Acceso</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item tran" href="#registro">Registrarse</a></li>
                <li><a class="dropdown-item tran" href="#sesion">Inicio De Sesion</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item tran" href="#intranet">Portal Interno</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <br>
  <div id="index" class="pagina">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="jumbotron">
            <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="formSwitchCheckDefault">
            <label class="form-check-label" for="formSwitchCheckDefault">Ingles</label>
            </div>
            <h1 class="display-3 text-center">KepFit</h1>
            <p class="lead">Bienvenidos KepFit, somos una joven empresa con la idea de crear una sociedad mucho mas unida del mundo del fitnes mediante una red social con publicaciones, entrenadores, diferentes productos Y MIL POSIBILIDADES</p>
            <hr class="my-4">
            <p class="display-6">TRANSFORMA TU VIDA, COMPARTE TU ENERGIA.</p>
            <a class="btn btn-primary btn-lg"
              href="#myModal"
              role="button" data-toggle="modal">
              Video-Presentación
            </a>
              <div id="myModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Presentación</h5>
                            <button type="button" class="btn-close" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="embed-responsive embed-responsive-16by9 text-center">
                            <iframe id="cartoonVideo" class="embed-responsive-item" width="440" height="315" src="./assets/vid/VIDEO PRESENTACIÓN KEPFIT.mp4" allowfullscreen></iframe>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <img src="./assets/img/app.png" alt="coche" class="mx-auto d-block img-fluid">
        </div>
      </div>
      <div class="row d-flex">
        <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
          <h3>Nuestro Proposito Es:</h3>
          <p>
          Aumentar la comunidad fitness e interconectarla aún más mediante rutinas, retos e interaciones en nuestra aplicación móvil.
          Contando con entrenadores personales, personas con experiencia y ganas de entrenar.
          </p>
          <p>
            Buscamos proveer a todos los usuarios de productos, de todo tipo, desde suplementación hasta maquinaria fitness.
          </p>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <img src="./assets/img/monigotes.png" alt="monigotes" class="mx-auto d-block img-fluid" height="400" width="400">
        </div>
      </div>
      <div class="row d-flex justify-content-between bd-highlight">
        <div class="col bd-highlight">
          <img src="./assets/img/equipo.png" alt="equipo" class="mx-auto d-block img-fluid" height="400" width="400">
        </div>
        <div class="col bd-highlight align-self-center">
          <h3>Contamos Con:</h3>
          <p>
            Grandes proveedores y consejeros para mejorar la experiencia del usuario. 
            También tenemos en nuestro equipo grandes desarrolladores e ingenieros que nos ayudaran a conseguir una aplicación de gran calibre.
          </p>
        </div>
        <!--<div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
        -->
      </div>
    </div>
  </div>
  <div id="productos" class="pagina">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="d-flex justify-content-center">
            <p>Información sobre los diferentes servicios/productos que ofrecemos</p>
          </div>
        </div>
      </div>
      <div class="row d-flex align-self-center">
        <div class="col-lg-4 col-md-6 col-sm-12 justify-content-center center-block">
          <div class="card" style="width: 18rem;">
            <img src="./assets/img/premium.png"
              class="card-img-top" alt="COCHES">
            <div class="card-body">
              <h5 class="card-title">Tarifas Fit</h5>
              <p class="card-text">
                Actualmente tenemos distintos modos en nuestra aplicación, las cuales ofrecen distintas posibilidades en la aplicación.
              </p>
              <a href="#coches-section" class="btn btn-primary" id="verTarifas">Ver Precios</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 justify-content-center">
          <div class="card" style="width: 18rem;">
            <img src="./assets/img/suple.jpg" class="card-img-top" alt="MOTOS">
            <div class="card-body">
              <h5 class="card-title">Suplementación</h5>
              <p class="card-text">
                Actualmente en nuestro catalogo puedes encontrar diferentes 
                complementos para acompañar a tu dieta.
              </p>
              <a href="#motos-section" class="btn btn-primary" id="verSuple">Ver Productos</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 justify-content-center">
          <div class="card" style="width: 18rem;">
            <img src="./assets/img/maquinaria.jpg" class="card-img-top" alt="CHOFER">
            <div class="card-body">
              <h5 class="card-title">Maquinaria Fitness</h5>
              <p class="card-text">
                Hoy en día es muy común hacer ejercicio en casa, y por ello tenemos distintas
                máquinas que podrán ayudar a mantener tu físico.
              </p>
              <a href="#personal-section" class="btn btn-primary" id="verMaq">Ver Maquinaria</a>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row vehiculos-section" id="coches-section">
        <!-- .table-{color} can be use with .table, thead, tbody, tr, th and td -->
        <table class="table mx-auto" style="width: 80%;">
          <thead>
            <tr class="table-light">
              <th scope="col">Tarifas</th>
              <th scope="col">Normal(Gratuito)</th>
              <th scope="col">Premium(20€/mes)</th>
            </tr>
          </thead>
          <tbody>
            <tr class="table-light">
              <th scope="row">Acceso a tienda</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Acceso 24/H</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Publicación de Rutinas</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Personalizable</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Entrenador Públicos</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Retos Especiales</th>
              <td></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Sin Anuncios</th>
              <td></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Ofertones</th>
              <td></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Entrenador Personales</th>
              <td></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="row vehiculos-section" id="motos-section">
        <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="./assets/img/prote.jpg" class="mx-auto d-block w-50 img-fluid" alt="..." style="height: 350px; width: 300px;">
              <div class="carousel-caption d-none d-md-block">
                <h4>10 puntos</h4>
              </div>
            </div>
            <div class="carousel-item">
              <img src="./assets/img/aminoacidos.jpg" class="mx-auto d-block w-50 img-fluid" alt="..." style="height: 350px; width: 300px;">
              <div class="carousel-caption d-none d-md-block">
                <h4>15 puntos</h4>
              </div>
            </div>
            <div class="carousel-item">
              <img src="./assets/img/cafeina.jpg" class="mx-auto d-block w-50 img-fluid" alt="..." style="height: 350px; width: 300px;">
              <div class="carousel-caption d-none d-md-block">
                <h4>20 puntos</h4>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
      <div class="row vehiculos-section" id="personal-section">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <h2>Mancuerna Ajustable RockPull</h2>
          <img src="./assets/img/mancuerna.jpg" alt="mancuerna" class="img-fluid">
          <h3>15 Puntos</h3>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <h2>Banco De Musculación HomCom</h2>
          <img src="./assets/img/banco.jpg" alt="banco" class="img-fluid">
          <h3>12 puntos</h3>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <h2>Alfombrilla Multiusos Ultimate</h2>
          <img src="./assets/img/alfombra.jpeg" alt="alfombrilla" class="img-fluid">
          <h3>10 puntos</h3>
        </div>
      </div>
    </div>
  </div>
  <div id="somos" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">¿Quienes Somos?</h1>
      </div>
      <div class="row d-flex">
        <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
          <h3>Nuestra Historia:</h3>
          <p>
          Somos 5 jovenes administradores y desarolladores aficionados al ejercicio, teniamos la misma idea: CREAR UNA COMUNIDAD DEPORTIVA.
          </p>
          <p>
            Buscamos proveer a todos los usuarios de productos, de todo tipo, desde suplementación hasta maquinaria fitness.
          </p>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <img src="./assets/img/monigotes.png" alt="monigotes" class="mx-auto d-block img-fluid" height="400" width="400">
        </div>
      </div>
    <div class="row d-flex justify-content-between bd-highlight">
      <div class="col-lg-6 col-md-6 col-sm-12">
          <img src="./assets/img/misionp.jpg" alt="mision" class="mx-auto d-block img-fluid" height="400" width="400">
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
        <H3 class="d-flex card-title">Nuestra misión</H3>
        <p class="card-body">Queremos <strong>ayudar y personalizar</strong> los ejercicios de nuestra comunidad fomentando la realización del ejercicio con intercambio de puntos por productos</p>
      </div>
      
    </div>
    <div class="row d-flex">
      <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
        <H3 class="d-flex card-title">Nuestra visión</H3>
        <p class="card-body">Queremos mejorar y ayudar a la comunidad a <strong>lograr sus objetivos deportivos</strong></p>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12">
        <img src="./assets/img/vision.avif" alt="mision" class="mx-auto d-block img-fluid" height="400" width="400">
      </div>
    </div>  
    <div class="row d-flex justify-content-between bd-highlight">
      <div class="col-lg-6 col-md-6 col-sm-12">
        <img src="./assets/img/valores.png" alt="mision" class="mx-auto d-block img-fluid" height="400" width="400">
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
        <H3 class="d-flex card-title">Nuestros valores</H3>
        <p class="card-body">Somos una comunidad en la que <strong>creemos en la salud</strong>  y en la ayuda mediante el <strong>intercambio de conocimientos</strong></p>
      </div>
      
    </div>  
      <div class="row">
        <img src="" alt="">
      </div>
      <br>
      <div class="row">
        <div class="col-lg-4">
          <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="home">Nuestros Inicios</a>
            <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Nuestra Progresión</a>
            <a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Los Obstáculos</a>
            <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Situación Actual</a>
            <a class="list-group-item list-group-item-action" id="list-group-list" data-bs-toggle="list" href="#list-group" role="tab" aria-controls="group">Nuestro equipo</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="tab-content" id="list-nav-tabContent">
            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
              Empezamos en 2022, apenas siendo unos estudiantes novatos acabamos nuestras respectivas prácticas en empresa. Y aun con nuestra con experiencia decidimos lanzarlos a la aventura con este proyecto.
            </div>
            <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
              A pesar de no tener experiencia y teniendo varias dudas, supimos getionar nuestro inicio bastante bien, debido a que teniamos los conocimientos bastante frescos, como por ejemplo tema web, red, servicios, etc.
            </div>
            <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
              Pero como en todas empresa y/o proyecto tuvimos problemas, como olvidarnos ciertas copias de seguridad, falta se seguridad informática en la empresa. Incluso tuvimos problemas con la disponibilidad de establecer IPs, en nuestra red.  
            </div>
            <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
              Actualmente seguimos en progreso en busca de mejorar nuestro servicio al cliente y superarnos cada día añadiendo más tecnologías para nuevas posibilidades en el mercado. 
              <br>
              Nos ubicamos en: 31620, Navarra Polígono Areta-2
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2069.19843855853!2d-1.6000852816454763!3d42.82451303436156!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd509362af36dc59%3A0xa44c055d0be1decc!2sPol%C3%ADgono%20Areta-2%2C%2031620%2C%20Navarra!5e0!3m2!1ses!2ses!4v1702905439945!5m2!1ses!2ses" width="350" height="250" style="border:burlywood;border-radius: 5px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="tab-pane fade" id="list-group" role="tabpanel" aria-labelledby="list-group-list">
          <div class="row d-flex">
          <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
            <h3>Manu:</h3>
            <p>
            Con 20 años terminó la formación profeional, concretamente ASIR en el instituto Cuatrovientos. Decidío meterse en el mundo laboral y emprender en una escuala de música, donde puso todos sus conocimientos informáticos. 
            </p>
            <p>
              Pasado un tiempo descubrió su nuevo hobby, el gimnasio y a raiz de ese momento descrubrio esta idea y no se lo penso 2 veces en meterse de cabeza.
            </p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
            <h3>Rubén</h3>
            <p>
              A los 21 años comenzo a trabajar en Cistec, una empersa importante de Pamplona, Gracias a sus grandes conocimentos de la electrónica consiguió ser Jefe del departamento IT de su empresa. 
             </p>
             <p>
              A los pocos meses vió que ese no era el camino que quería seguir, el quería emprender y decidió meterse en este proyecto.</p>
          </div>
          </div>
          <div class="row d-flex">
         <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
          <h3>Asier Lizarraga:</h3>
          <p>
          Con 19 se sacó el grado de ASIR en el instituto Cuatrovientos, compañero de clae de Manu y Asier Mañeru, al terminarló descubrió que esto de la informatica era su pasión y decidió hacer la carerra de ingenieria informatica en la UPNA (Universidad Publica de Navarra).
          </p>
          <p>
            Al terminar la carrera se enteró del proyecto KepFit y quiso meterse de lleno en el.
          </p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
          <h3>Asier Mañeru:</h3>
          <p>
            Terminó sus estudios a los 20 años en el mismo instituto que Asier Lizarraga y Manu, entró al mundo laboral gracias a las practicas realizadas gracias a su centro.  
           </p>
           <p>
            Tiempo despues, Asier y Manu en una sesión de gimnasio hablaron de crear KepFit y la idea de meter a Asier Lizarraga y Rubén en el proyecto, el resto es hitoria.</p>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="contacto" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">CONTACTA CON NOSOTROS ANTE CUALQUIER DUDA O PROBLEMA!!</h1>
        <H3 class="d-flex justify-content-center">TE CONTESTAREMOS LO ANTES POSIBLE</H3>
        <form action="mailto:bartoni8890@gmail.com">
        <div class="form-group">
          <label for="exampleFormControlInput1">Correo electrónico</label>
          <input type="email" class="form-control" id="Correo" placeholder="name@example.com">
        </div>
        <div class="form-group">
          <label for="exampleFormControlSelect1">Motivo</label>
          <select class="form-control" id="exampleFormControlSelect1">
            <option>Duda</option>
            <option>Informar sobre algún problema</option>
            <option>Otro</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleFormControlSelect2">Tipo de cliente</label>
          <select multiple class="form-control" id="exampleFormControlSelect2">
            <option>Ya soy cliente</option>
            <option>No soy cliente</option>
            <option>Tengo duda de hacerme cliente o no</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Descripción de la duda/problema</label>
          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label for="exampleFormControlFile1">Adjuntar archivo adicional</label>
          <input type="file" class="form-control-file" id="exampleFormControlFile1">
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
      </div>
    </div>
  </div>
  <div id="trabaja" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">Inscripción</h1>
        <form>
          <div class="mb-3">
            <label for="examplename" class="form-label">Nombre</label>
            <input type="name" class="form-control" id="name" aria-describedby="nameHelp">
          </div>
          <div class="mb-3">
            <label for="exampleapellidos" class="form-label">Apellidos</label>
            <input type="apellidos" class="form-control" id="apellidos" aria-describedby="apellidosHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">Nunca compartiremos su correo electrónico con nadie más.</div>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Aceptar términos y condiciones*</label>
          </div>
          <div class="form-group">
            <label for="exampleFormControlFile1">Adjuntar Curriculum</label>
            <input type="file" class="form-control-file" id="exampleFormControlFile1">
          </div>
          <button type="submit" class="btn btn-primary">GUARDAR</button>
        </form>
      </div>
    </div>
  </div>
  <div id="registro" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">Registro A KepFit</h1>
        <form>
          <div class="mb-3">
            <label for="examplename" class="form-label">Nombre</label>
            <input type="name" class="form-control" id="name" aria-describedby="nameHelp">
          </div>
          <div class="mb-3">
            <label for="exampleapellidos" class="form-label">Apellidos</label>
            <input type="apellidos" class="form-control" id="apellidos" aria-describedby="apellidosHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">Nunca compartiremos su correo electrónico con nadie más.</div>
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="exampleInputPassword1">
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Aceptar términos y condiciones*</label>
          </div>
          <button type="submit" class="btn btn-primary">REGISTRARSE</button>
        </form>
      </div>
    </div>
  </div>
  <div id="sesion" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">Inicio De Sesion En KepFit</h1>
        <!--<div class="col-8">-->
        <form>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Usuario/Correo</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="exampleInputPassword1">
          </div>
          <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
          <button type="submit" class="btn btn-primary">Olvido de Contraseña</button>
        </form>
        </div>
      </div>
  </div>
  <div id="intranet" class="pagina">
      <div class="container">
        <div class="row">
          <h1 class="d-flex justify-content-center">Acesso Como Trabajador</h1>
          <form method="POST">
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label" for="email">Correo electrónico</label>
              <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="col-md-3">
                  <div class="form-control-feedback">
                      <span class="text-danger align-middle">
                          <?php 
                           echo $error; 
                        // Muestra el mensaje de error 
                          ?>
                      </span>
                  </div>
              </div>
              <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label" for="pass">Contraseña</label>
              <input type="password" name="pass" class="form-control" id="exampleInputPassword1">
            </div>
              <div class="col-md-3">
                  <div class="form-control-feedback">
                      <span class="text-danger align-middle">
                          <?php
                 echo $error; 
              ?>
                      </span>
                  </div>
              </div>
            <button type="submit" class="btn btn-primary">
                        Ingresar
            </button>
              <?php
               if($error != "") {
                        echo "<div class='alert'></div>";   
               }
              ?>
          </form>
        </div>
      </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="./assets/jquery/script.js"></script>
</body>
<br>
<footer class="footer bg-light text-lg-start ">
  <div class="text-center">
    <p>Rubén, Asier Lizarraga, Asier Mañeru, Jon y Enmanuel Holgado. 2ºASIR
    </p>
    <p class="mb-0">
      Nuestras redes sociales:
    </p>
    <nav class="navbar navbar-expand-lg navbar-light bg-light ">
        <div class="collapse navbar-collapse d-flex justify-content-center align-self-center" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item ms-4">
              <strong>Correo</strong> 
              <a class="nav-link active" aria-current="page" href="#">
                <img src="./assets/img/gmail.png" alt="gmail" class="img-fluid imgs">
              </a>
            </li>
            <li class="nav-item ms-4">
              <strong>Instagram</strong>
              <a class="nav-link active" aria-current="page" href="#">
              <img src="./assets/img/ig.png" alt="gmail" class="img-fluid imgs">
              </a>
            </li>
            <li class="nav-item ms-4">
            <strong>Infojobs</strong>
              <a class="nav-link active" aria-current="page" href="#">
                <img src="./assets/img/info.png" alt="gmail" class="img-fluid imgs">                
              </a>
            </li>
            <li class="nav-item ms-4">
              <strong>Linkdkelin</strong>
              <a class="nav-link active"  aria-current="page" href="#">
              <img src="./assets/img/link.png" alt="gmail" class="img-fluid imgs">
            </a>
            </li>
            <li class="nav-item ms-4">
              <strong>Facebook</strong>
              <a class="nav-link active" aria-current="page" href="#">
              <img src="./assets/img/face.png" alt="gmail" class="img-fluid imgs">
            </a>
            </li>
            <li class="nav-item ms-4">
              <strong>Twitter</strong>
              <a class="nav-link active" aria-current="page" href="#">
              <img src="./assets/img/twitter.png" alt="gmail" class="img-fluid imgs">
            </a>
            </li>
          </ul>
        </div>
    </nav>
  </div>
</footer>
</html>