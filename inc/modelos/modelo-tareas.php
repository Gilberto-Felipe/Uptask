<?php

// creo variables que van a ir en $_POST
$accion = $_POST['accion'];
$id_proyecto = (int) $_POST['id_proyecto'];
$tarea = $_POST['tarea'];
$estado = $_POST['estado'];
$id_tarea = (int) $_POST['id'];
//echo json_encode($_POST);

// Si la acción es de tipo crear: Abro la conexión a la bd y hago la consulta
if ($accion === 'crear') {
     //importo la conexion
     include '../funciones/conexion.php';

     // verificar la conexión
     try {
          // realizar la consulta a la bd con prepared stametements
          $stmt = $conn->prepare("INSERT INTO tareas (nombre, id_proyecto) VALUES (?, ?) ");
          $stmt->bind_param('si', $tarea, $id_proyecto);
          $stmt->execute();
          if ($stmt->affected_rows > 0) {
               $respuesta = array(
                    'respuesta' => 'correcto',
                    'id_proyecto' => $stmt->insert_id,
                    'tipo' => $accion,
                    'tarea' => $tarea
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

// Si la acción es de tipo 'actualizar':
if ($accion === 'actualizar') {
     //echo json_encode($_POST);

     //importo la conexion
     include '../funciones/conexion.php';

     // verificar la conexión
     try {
          // realizar la consulta a la bd con prepared stametements
          $stmt = $conn->prepare("UPDATE tareas set estado = ? WHERE id = ? ");
          $stmt->bind_param('ii', $estado, $id_tarea);
          $stmt->execute();
          if ($stmt->affected_rows > 0) {
               $respuesta = array(
                    'respuesta' => 'correcto'
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


// Si la acción es de tipo 'eliminar' de BD:
if ($accion === 'eliminar') {
     //echo json_encode($_POST);

     //importo la conexion
     include '../funciones/conexion.php';

     // verificar la conexión
     try {
          // realizar la consulta a la bd con prepared stametements
          $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ? ");
          $stmt->bind_param('i', $id_tarea);
          $stmt->execute();
          if ($stmt->affected_rows > 0) {
               $respuesta = array(
                    'respuesta' => 'correcto'
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

















/* Documentación
*(int) convierte string "1" a número: 1. Con esto te aseguras que un valor es numérico.


*/
