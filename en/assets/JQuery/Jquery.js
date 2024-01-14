$(document).ready(function () {
    // Muestra la página inicial por defecto
    $("#index").show();
  
    // Maneja los clics en los enlaces de navegación
    $("nav a").click(function (e) {
      e.preventDefault();
      var targetPageID = $(this).attr("href");
     
      // Oculta todas las páginas excepto la seleccionada
      $(".pagina").not(targetPageID).hide();
     
      // Muestra el div de la página correspondiente
      $(targetPageID).show();
    });

    
    // Ocultar todas las secciones de vehículos al cargar la página
    $('.vehiculos-section').hide();

    // Manejar el clic en el enlace "Ver coches"
    $('#verTarifas').click(function() {
      $('.vehiculos-section').hide(); // Ocultar todas las secciones de vehículos
      $('#coches-section').show(); // Mostrar la sección de coches
    });

    // Manejar el clic en el enlace "Ver motos"
    $('#verSuple').click(function() {
      $('.vehiculos-section').hide(); // Ocultar todas las secciones de vehículos
      $('#motos-section').show(); // Mostrar la sección de motos
    });

    // Manejar el clic en el enlace "Ver vehículos de movilidad personal"
    $('#verMaq').click(function() {
      $('.vehiculos-section').hide(); // Ocultar todas las secciones de vehículos
      $('#personal-section').show(); // Mostrar la sección de vehículos de movilidad personal
    });
    var url = $("#cartoonVideo2").attr('src');
    /* Asigna un valor de URL vacío al atributo src del iframe cuando
      se oculta el modal, lo cual detiene la reproducción del video */
    $("#myModal2").on('hide.bs.modal', function(){
        $("#cartoonVideo2").attr('src', '');
    });
    /* Asigna nuevamente la URL almacenada inicialmente al atributo src
      del iframe cuando el modal se muestra nuevamente */
    $("#myModal2").on('show.bs.modal', function(){
        $("#cartoonVideo2").attr('src', url);
    });
  });
var check=document.querySelector(".form-check-input");
check.addEventListener('click',idioma);
function idioma(){
    let id=(check.checked);
    if (id==true){
      location.href="./en/index.html";
    }else{
      location.href="../index.html"
    }
}