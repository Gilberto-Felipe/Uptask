<?php

//mostrar línea de error en php
ini_set("display_errors", "1"); // muestro línea de error php
error_reporting(E_ALL); // muestro línea de error php

// f obtener página actual que se ejecuta - la página que está llamando a está función
function obtenerPaginaActual(){
     $archivo = basename($_SERVER['PHP_SELF']); // guardo el nombre del archivo actual
     $pagina = str_replace(".php", "", $archivo);
     return $pagina;
}
obtenerPaginaActual();

// *** Consultas ***

// Obtener todos los proyectos
function obtenerProyecto() {
     // incluyo la conexión a bd
     include 'conexion.php';

     // valido la conexión y realizo consulta a bd
     try {
          return $conn->query('SELECT id, nombre FROM proyectos');
     } catch (\Exception $e) {
          echo "¡Error!: " . $e->getMessage();
          return false;
     }
}

// Obtener el nombre del proyecto
function obtenerNombreProyecto($id = null) {
     // importo conexión
     include 'conexion.php';

     // valido conexión y ralizo consulta a bd
     try {
          return $conn->query("SELECT nombre FROM proyectos WHERE id = {$id}"); // {$id}: para ingresar var id a la consulta, que sea dinámica.
     } catch (\Exception $e) {
          echo "¡Error!: " . $e->getMessage();
          return false;
     }
}

// Obtener las clases del proyecto
function obtenerTareasProyecto($id = null) {
     // importo conexión
     include 'conexion.php';

     // valido conexión y ralizo consulta a bd
     try {
          return $conn->query("SELECT id, nombre, estado FROM tareas WHERE id_proyecto = {$id}"); // {$id}: para ingresar var id a la consulta, que sea dinámica.
     } catch (\Exception $e) {
          echo "¡Error!: " . $e->getMessage();
          return false;
     }
}
















/* DOCUMENTACIÓN
*basename - devuelve el último componente de nombre una ruta
*$_SERVER - Información del entorno del servidor y de ejecución = un array con cabeceras, url, ubicaciones de script. El servidor proporciona esta info.
*'PHP_SELF' El nombre del archivo de script actual, relativo al directorio raíz de documentos del servidor.

*str_replace() - reemplaza una parte de un string con otra. ("elemareemplazar", "reemplazo", $myarchivo)
*/
