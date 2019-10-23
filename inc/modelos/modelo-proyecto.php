<?php

// creo variables que van a ir en $_POST
$accion = $_POST['accion']; // tipo de acción: 'crear'/'login'
$proyecto = $_POST['proyecto']; // nombre del proyecto
//echo json_encode($_POST);

// Abro la conexión a la bd y hago la consulta
if ($accion === 'crear') {
     //importo la conexion
     include '../funciones/conexion.php';

     // verificar la conexión
     try {
          // realizar la consulta a la bd con prepared stametements
          $stmt = $conn->prepare("INSERT INTO proyectos (nombre) VALUES (?) ");
          $stmt->bind_param('s', $proyecto);
          $stmt->execute();
          if ($stmt->affected_rows > 0) {
               $respuesta = array(
                    'respuesta' => 'correcto',
                    'id_proyecto' => $stmt->insert_id,
                    'nombre_proyecto' => $proyecto,
                    'tipo' => $accion
               );
          } else {
               $respuesta = array(
                    'respuesta' => 'error'
               );
          }
          $stmt->close();
          $conn->close();
     } catch (\Exception $e) {
          // en caso de error, tomar la excepción
          $respuesta = array(
               'error' => $e->getMessage()
          );
     }

     echo json_encode($respuesta);
}



 ?>
