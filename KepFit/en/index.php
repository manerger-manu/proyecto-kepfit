<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
session_start([
    'cookie_lifetime' => 86400,
]);

// Realizando la llamada al script functions establezco la conexión con base de datos
require_once '../utils/functions.php';
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
      $error = "You should complete all the fields<br>";
  else
  {
      // TODO Cambiada la variable $result a $result para evitar errores
      $result = queryMysql("SELECT * FROM usuarios WHERE user='$user' AND pass='$pass'" );
      
      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>Email/Password Invalid</span><br><br>";    
        
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
       header('Location: ../intranet.php');
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
                  <a class="nav-link active events tran" aria-current="page" href="#productos">Products/Services</a>
                </li>
                <li class="nav-item ms-4">
                  <a class="nav-link active tran" aria-current="page" href="#somos">About Us</a>
                </li>
                <li class="nav-item ms-4">
                  <a class="nav-link active tran" aria-current="page" href="#contacto">Contact</a>
                </li>
                <li class="nav-item ms-4">
                  <a class="nav-link active tran" aria-current="page" href="#trabaja">Join Us</a>
                </li>
                <li class="nav-item dropdown ms-4">
                  <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Access</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item tran" href="#registro">Register</a></li>
                    <li><a class="dropdown-item tran" href="#sesion">Login</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item tran" href="#intranet">Internal Portal</a></li>
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
                        <input class="form-check-input" type="checkbox" id="formSwitchCheckChecked" checked="">
                        <label class="form-check-label" for="formSwitchCheckChecked">English</label>
                    </div>
                    <h1 class="display-3 text-center">KepFit - English</h1>
                    <p class="lead">Welcome to KepFit, we are a young company with the idea of creating a much more united fitness community through a social network with posts, trainers, different products, AND A THOUSAND POSSIBILITIES.</p>
                    <hr class="my-4">
                    <p class="display-6">TRANSFORM YOUR LIFE, SHARE YOUR ENERGY.</p>
                    <a class="btn btn-primary btn-lg"
                        href="#myModal2"
                        role="button" data-toggle="modal">
                        Video-Presentation
                    </a>
                    <div id="myModal2" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Presentation</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="embed-responsive embed-responsive-16by9 text-center">
                                        <iframe id="cartoonVideo2" class="embed-responsive-item" width="440" height="315" src="./assets/vid/VIDEO PRESENTACIÓN KEPFIT.mp4" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="./assets/img/app.png" alt="car" class="mx-auto d-block img-fluid">
            </div>
        </div>
        <div class="row d-flex">
            <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
                <h3>Our Purpose Is:</h3>
                <p>
                    To increase the fitness community and further interconnect it through routines, challenges, and interactions in our mobile application. We have personal trainers, experienced individuals, and enthusiasts ready to train.
                </p>
                <p>
                    We aim to provide all users with products of all kinds, from supplements to fitness machinery.
                </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <img src="./assets/img/monigotes.png" alt="characters" class="mx-auto d-block img-fluid" height="400" width="400">
            </div>
        </div>
        <div class="row d-flex justify-content-between bd-highlight">
            <div class="col bd-highlight">
                <img src="./assets/img/equipo.png" alt="team" class="mx-auto d-block img-fluid" height="400" width="400">
            </div>
            <div class="col bd-highlight align-self-center">
                <h3>We Have:</h3>
                <p>
                    Great suppliers and advisors to enhance the user experience. We also have skilled developers and engineers on our team who will help us achieve a top-notch application.
                </p>
            </div>
        </div>
    </div>
    </div>
    <div id="productos" class="pagina">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="d-flex justify-content-center">
            <p>Information about the different services/products we offer</p>
          </div>
        </div>
      </div>
      <div class="row d-flex align-self-center">
        <div class="col-lg-4 col-md-6 col-sm-12 justify-content-center center-block">
          <div class="card" style="width: 18rem;">
            <img src="./assets/img/premium.png" class="card-img-top" alt="CARS">
            <div class="card-body">
              <h5 class="card-title">Fit Plans</h5>
              <p class="card-text">
                Currently, we have different modes in our application, which offer various possibilities in the application.
              </p>
              <a href="#coches-section" class="btn btn-primary" id="verTarifas">View Prices</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 justify-content-center">
          <div class="card" style="width: 18rem;">
            <img src="./assets/img/suple.jpg" class="card-img-top" alt="MOTOS">
            <div class="card-body">
              <h5 class="card-title">Supplementation</h5>
              <p class="card-text">
                Currently in our catalog, you can find different supplements to accompany your diet.
              </p>
              <a href="#motos-section" class="btn btn-primary" id="verSuple">View Products</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 justify-content-center">
          <div class="card" style="width: 18rem;">
            <img src="./assets/img/maquinaria.jpg" class="card-img-top" alt="CHOFER">
            <div class="card-body">
              <h5 class="card-title">Fitness Equipment</h5>
              <p class="card-text">
                Nowadays, it is very common to exercise at home, and therefore, we have different machines that can help maintain your physique.
              </p>
              <a href="#personal-section" class="btn btn-primary" id="verMaq">View Equipment</a>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row vehiculos-section" id="coches-section">
        <table class="table mx-auto" style="width: 80%;">
          <thead>
            <tr class="table-light">
              <th scope="col">Plans</th>
              <th scope="col">Standard(Free)</th>
              <th scope="col">Premium(20€/month)</th>
            </tr>
          </thead>
          <tbody>
            <tr class="table-light">
              <th scope="row">Shop Access</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">24/7 Access</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Routine Publication</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Customizable</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Public Trainers</th>
              <td><i class="bi bi-check"></i></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Special Challenges</th>
              <td></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Ad-Free</th>
              <td></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Special Offers</th>
              <td></td>
              <td><i class="bi bi-check"></i></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Personal Trainers</th>
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
                <h4>10 points</h4>
              </div>
            </div>
            <div class="carousel-item">
              <img src="./assets/img/aminoacidos.jpg" class="mx-auto d-block w-50 img-fluid" alt="..." style="height: 350px; width: 300px;">
              <div class="carousel-caption d-none d-md-block">
                <h4>15 points</h4>
              </div>
            </div>
            <div class="carousel-item">
              <img src="./assets/img/cafeina.jpg" class="mx-auto d-block w-50 img-fluid" alt="..." style="height: 350px; width: 300px;">
              <div class="carousel-caption d-none d-md-block">
                <h4>20 points</h4>
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
          <h2>Adjustable Dumbbell RockPull</h2>
          <img src="./assets/img/mancuerna.jpg" alt="dumbbell" class="img-fluid">
          <h3>15 Points</h3>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <h2>Muscle Bench HomCom</h2>
          <img src="./assets/img/banco.jpg" alt="bench" class="img-fluid">
          <h3>12 points</h3>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <h2>Ultimate Multi-purpose Mat</h2>
          <img src="./assets/img/alfombra.jpeg" alt="mat" class="img-fluid">
          <h3>10 points</h3>
        </div>
      </div>
    </div>
    </div>
    <div id="somos" class="pagina">
      <div class="container">
        <div class="row">
          <h1 class="d-flex justify-content-center">Who Are We?</h1>
        </div>
        <div class="row d-flex">
          <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
            <h3>Our Story:</h3>
            <p>
              We are 5 young administrators and developers passionate about exercise, sharing the same idea: TO CREATE A SPORTS COMMUNITY.
            </p>
            <p>
              We aim to provide all users with products of all kinds, from supplements to fitness equipment.
            </p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <img src="./assets/img/monigotes.png" alt="monigotes" class="mx-auto d-block img-fluid" height="400" width="400">
          </div>
        </div>
        <div class="row d-flex justify-content-between bd-highlight">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <img src="./assets/img/misionp.jpg" alt="mission" class="mx-auto d-block img-fluid" height="400" width="400">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
            <H3 class="d-flex card-title">Our mission</H3>
            <p class="card-body">We want to <strong>help and personalize</strong> the exercises of our community by promoting exercise with the exchange of points for products.</p>
          </div>
        </div>
        <div class="row d-flex">
          <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
            <H3 class="d-flex card-title">Our vision</H3>
            <p class="card-body">We want to improve and help the community <strong>achieve their sports goals</strong>.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <img src="./assets/img/vision.avif" alt="vision" class="mx-auto d-block img-fluid" height="400" width="400">
          </div>
        </div>
        <div class="row d-flex justify-content-between bd-highlight">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <img src="./assets/img/valores.png" alt="values" class="mx-auto d-block img-fluid" height="400" width="400">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
            <H3 class="d-flex card-title">Our values</H3>
            <p class="card-body">We are a community that <strong>believes in health</strong> and in helping through the <strong>exchange of knowledge</strong>.</p>
          </div>
        </div>
        <div class="row">
          <img src="" alt="">
        </div>
        <br>
        <div class="row">
          <div class="col-lg-4">
            <div class="list-group" id="list-tab" role="tablist">
              <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="home">Our Beginnings</a>
              <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Our Progression</a>
              <a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">The Obstacles</a>
              <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Current Situation</a>
              <a class="list-group-item list-group-item-action" id="list-group-list" data-bs-toggle="list" href="#list-group" role="tab" aria-controls="group">Our Team</a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="tab-content" id="list-nav-tabContent">
              <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                We started in 2022, just being novice students who completed our respective internships in a company. Even with our experience, we decided to embark on this project.
              </div>
              <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                Despite not having experience and having several doubts, we managed our start quite well because we had very fresh knowledge, such as web development, networks, services, etc.
              </div>
              <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
                But like in any company or project, we faced challenges, such as forgetting certain backups, lack of cybersecurity in the company, and even problems with establishing IPs in our network.
              </div>
              <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
                Currently, we are still making progress in improving our customer service and pushing ourselves every day by adding more technologies for new possibilities in the market.
                <br>
                Our location: 31620, Navarra Polígono Areta-2
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2069.19843855853!2d-1.6000852816454763!3d42.82451303436156!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd509362af36dc59%3A0xa44c055d0be1decc!2sPol%C3%ADgono%20Areta-2%2C%2031620%2C%20Navarra!5e0!3m2!1ses!2ses!4v1702905439945!5m2!1ses!2ses" width="350" height="250" style="border:burlywood;border-radius: 5px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
        <div class="tab-pane fade" id="list-group" role="tabpanel" aria-labelledby="list-group-list">
          <div class="row d-flex">
            <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
              <h3>Manu:</h3>
              <p>
                At 20, he completed professional training, specifically ASIR at the Cuatrovientos Institute. He decided to enter the workforce and start a music school, where he applied all his computer knowledge.
              </p>
              <p>
                After some time, he discovered his new hobby, the gym, and from that moment on, he embraced this idea without hesitation.
              </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
              <h3>Rubén</h3>
              <p>
                At the age of 21, he started working at Cistec, an important company in Pamplona. Thanks to his great knowledge of electronics, he became the head of the IT department in his company.
              </p>
              <p>
                After a few months, he realized that this was not the path he wanted to follow; he wanted to start a business and decided to join this project.
              </p>
            </div>
          </div>
          <div class="row d-flex">
            <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
              <h3>Asier Lizarraga:</h3>
              <p>
                At 19, he completed the ASIR degree at the Cuatrovientos Institute, a classmate of Manu and Asier Mañeru. After finishing it, he discovered that this computer thing was his passion and decided to pursue a computer engineering degree at UPNA (Public University of Navarra).
              </p>
              <p>
                Upon completing the degree, he heard about the KepFit project and wanted to fully engage in it.
              </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
              <h3>Asier Mañeru:</h3>
              <p>
                He finished his studies at the age of 20 at the same institute as Asier Lizarraga and Manu. He entered the workforce thanks to internships provided by his center.
              </p>
              <p>
                Later on, Asier and Manu, during a gym session, discussed creating KepFit and the idea of involving Asier Lizarraga and Rubén in the project. The rest is history.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>    
    <div id="contacto" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">CONTACT US FOR ANY QUESTIONS OR ISSUES!!</h1>
        <H3 class="d-flex justify-content-center">WE WILL GET BACK TO YOU AS SOON AS POSSIBLE</H3>
        <form action="mailto:bartoni8890@gmail.com">
          <div class="form-group">
            <label for="exampleFormControlInput1">Email address</label>
            <input type="email" class="form-control" id="Email" placeholder="name@example.com">
          </div>
          <div class="form-group">
            <label for="exampleFormControlSelect1">Reason</label>
            <select class="form-control" id="exampleFormControlSelect1">
              <option>Question</option>
              <option>Report a Problem</option>
              <option>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="exampleFormControlSelect2">Customer Type</label>
            <select multiple class="form-control" id="exampleFormControlSelect2">
              <option>Already a Customer</option>
              <option>Not a Customer</option>
              <option>Undecided about becoming a customer</option>
            </select>
          </div>
          <div class="form-group">
            <label for="exampleFormControlTextarea1">Description of the question/problem</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleFormControlFile1">Attach additional file</label>
            <input type="file" class="form-control-file" id="exampleFormControlFile1">
          </div>
          <button type="submit" class="btn btn-primary">Send</button>
        </form>
      </div>
    </div>
    </div>
    <div id="trabaja" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">Registration</h1>
        <form>
          <div class="mb-3">
            <label for="examplename" class="form-label">Name</label>
            <input type="name" class="form-control" id="name" aria-describedby="nameHelp">
          </div>
          <div class="mb-3">
            <label for="exampleapellidos" class="form-label">Last Name</label>
            <input type="apellidos" class="form-control" id="apellidos" aria-describedby="apellidosHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Accept terms and conditions*</label>
          </div>
          <div class="form-group">
            <label for="exampleFormControlFile1">Attach Resume</label>
            <input type="file" class="form-control-file" id="exampleFormControlFile1">
          </div>
          <button type="submit" class="btn btn-primary">SAVE</button>
        </form>
      </div>
    </div>
    </div>
    <div id="registro" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">Register at KepFit</h1>
        <form>
          <div class="mb-3">
            <label for="examplename" class="form-label">Name</label>
            <input type="name" class="form-control" id="name" aria-describedby="nameHelp">
          </div>
          <div class="mb-3">
            <label for="exampleapellidos" class="form-label">Last Name</label>
            <input type="apellidos" class="form-control" id="apellidos" aria-describedby="apellidosHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1">
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Accept terms and conditions*</label>
          </div>
          <button type="submit" class="btn btn-primary">REGISTER</button>
        </form>
      </div>
    </div>
    </div>
    <div id="sesion" class="pagina">
    <div class="container">
      <div class="row">
        <h1 class="d-flex justify-content-center">Sign In to KepFit</h1>
        <!--<div class="col-8">-->
        <form>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Username/Email</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1">
          </div>
          <button type="submit" class="btn btn-primary">Sign In</button>
          <button type="submit" class="btn btn-primary">Forgot Password</button>
        </form>
        </div>
      </div>
    </div>
    <div id="intranet" class="pagina">
        <div class="container">
          <div class="row">
            <h1 class="d-flex justify-content-center">Access as Employee</h1>
            <form method="POST">
              <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label" for="email">Email</label>
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
                <label for="exampleInputPassword1" class="form-label" for="pass">Password</label>
                <input type="password" name="pass" class="form-control" id="exampleInputPassword1">
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
             <button type="submit" class="btn btn-primary">Log In</button>
            </form>
          </div>
        </div>
    </div>  
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="./assets/JQuery/script.js"></script>
</body>
<br>
<footer class="footer bg-light text-lg-start">
  <div class="text-center">
      <p>Rubén, Asier Lizarraga, Asier Mañeru, Jon and Enmanuel Holgado. 2nd ASIR</p>
      <p class="mb-0">
          Our social networks:
      </p>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="collapse navbar-collapse d-flex justify-content-center align-self-center" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item ms-4">
                      <strong>Email</strong>
                      <a class="nav-link active" aria-current="page" href="#">
                          <img src="./assets/img/gmail.png" alt="gmail" class="img-fluid imgs">
                      </a>
                  </li>
                  <li class="nav-item ms-4">
                      <strong>Instagram</strong>
                      <a class="nav-link active" aria-current="page" href="#">
                          <img src="./assets/img/ig.png" alt="instagram" class="img-fluid imgs">
                      </a>
                  </li>
                  <li class="nav-item ms-4">
                      <strong>Infojobs</strong>
                      <a class="nav-link active" aria-current="page" href="#">
                          <img src="./assets/img/info.png" alt="infojobs" class="img-fluid imgs">
                      </a>
                  </li>
                  <li class="nav-item ms-4">
                      <strong>LinkedIn</strong>
                      <a class="nav-link active" aria-current="page" href="#">
                          <img src="./assets/img/link.png" alt="linkedin" class="img-fluid imgs">
                      </a>
                  </li>
                  <li class="nav-item ms-4">
                      <strong>Facebook</strong>
                      <a class="nav-link active" aria-current="page" href="#">
                          <img src="./assets/img/face.png" alt="facebook" class="img-fluid imgs">
                      </a>
                  </li>
                  <li class="nav-item ms-4">
                      <strong>Twitter</strong>
                      <a class="nav-link active" aria-current="page" href="#">
                          <img src="./assets/img/twitter.png" alt="twitter" class="img-fluid imgs">
                      </a>
                  </li>
              </ul>
          </div>
      </nav>
      <!--
      <nav class="navbar collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav d-flex">
              <li class="nav-item ms-4">
                  <p class="nav-link">ig</p>  ig
              </li>
              <li class="nav-item ms-4">Facebook</li>
              <li class="nav-item ms-4">LinkedIn</li>
              <li class="nav-item ms-4">Twitter</li>
              <li class="nav-item ms-4">Infojobs</li>
          </ul>
      </nav>
      -->
  </div>
</footer>
</html>