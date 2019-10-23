<?php

function usuario_autenticado() {
     // si el usuario no está logeado, redirigirlo a la página de login
     if (!revisar_usuario()) {
          header('location:login.php');
          exit();
     }
}

function revisar_usuario() {
     // si el usuario está logeado, entonces abre la sesión con el nombre
     return isset($_SESSION['nombre']);
}

session_start(); // inicia 1 sesión
usuario_autenticado(); // revisa que el usuario esté logeado














 ?>
