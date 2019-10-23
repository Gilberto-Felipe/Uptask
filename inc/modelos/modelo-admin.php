<?php
// Es el archivo que recibe los datos de php, hace conexión a la bd y las consultas, y devolver al usuario una respuesta: se logeo o no.
// compruebo que hay conexión formulario.js -> modelo-admin.php
//die(json_encode($_POST)); // convierte un array a string en formato json

// traigo los valores de crear-cuenta / login
$accion = $_POST['accion'];
$password = $_POST['password'];
$usuario = $_POST['usuario'];

// diferencío que archivo se va a conectar: crear-cuenta.php
if ($accion === 'crear') {
     // code para crear los administradores

     // hashear passwords
     $opciones = array(
          'cost' => 10
     );
     $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);
     /*$respuesta = array(
          'pass' => $hash_password
          );
     echo json_encode($respuesta); //muestra cómo es el hash*/

     //importar la conexion
     include '../funciones/conexion.php';

     // verificar la conexión
     try {
          // realizar la consulta a la bd con prepared stametements
          $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?) ");
          $stmt->bind_param('ss', $usuario, $hash_password);
          $stmt->execute();
          if ($stmt->affected_rows > 0) {
               $respuesta = array(
                    'respuesta' => 'correcto',
                    'id_insertado' => $stmt->insert_id,
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

// $accion distingue el tipo de conexión (qué archivo se va a conectar), en este caso, para logear al usuario: login.php 
if ($accion === 'login') {
     // code para logear a los administradores

     include '../funciones/conexion.php';

     try {
          // seleccionar ABD (admin bd)
          $stmt = $conn->prepare("SELECT usuario, id, password FROM usuarios WHERE usuario = ?");
          $stmt->bind_param('s', $usuario);
          $stmt->execute();
          //loguear el usuario
          $stmt->bind_result($nombre_usuario, $id_usuario, $pass_usuario);
          $stmt->fetch();
          if ($nombre_usuario) {
               // el usuario existe, verificar el password
               if (password_verify($password, $pass_usuario)) {
                    // iniciar sesión
                    session_start();
                    $_SESSION['nombre'] = $usuario;
                    $_SESSION['id'] = $id_usuario;
                    $_SESSION['login'] = true;
                    // Login correcto
                    $respuesta = array(
                         'respuesta' => 'correcto',
                         'nombre' => $nombre_usuario,
                         'tipo' => $accion
                    );
               } else {
                    //Login incorrecto
                    $respuesta = array(
                         'error' => '¡Password incorrecto!'
                    );
               }

          } else {
               $respuesta = array(
                    'error' => '¡El usuario no existe!'
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



/* DOCUMENTACIÓN
*json_encode: convierte js a json. php no acepta js, pero sí json. Comunica js con php. Es un formulario de transporte.
*die: es un echo
* forma pedagógica de comprobar conexión ajax del FormData de js, con modelo-admin.php
     $arreglo = array(
          'respuesta' => 'Desde MODELO!!'
     );

     die(json_encode($arreglo));

*Hashear
hash 12 es más seguro, pero consume más recursos.
hash 10, está bien.
password_hash: fun de php para crear hashes ($var, algoritmo, $opciones del arreglo)

*hashear passwords
$opciones = array(
     'cost' => 10
);
$hash_password = password_hash($varaEncriptar, método, $Opcionesde seguridad);
$respuesta = array(
     'pass' => $hash_password
     );
echo json_encode($respuesta); //muestra cómo es el hash

*Passwords en bd nunca se guardan en texto plano. Siempre hay que hashearlos por seguridad.

*try / catch muestra si hay error en conexión. Si hay error, sigue funcionando, y muestra el mensaje de error.

*Prepared stametements previenen la inyección de código sql, ciberseguridad.

+$stmt->bind_result(); regresa mi consulta en el orden que la hice
*/
 ?>
