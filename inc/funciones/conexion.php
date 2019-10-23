<?php
//conexión a base de datos (servidor, usuario, password, tabla)
$conn = new mysqli('localhost', 'root', '', 'uptask');
// mostrar error
if ($conn->connect_error) {
     echo $error = $conn->connect_error; // ->f de mysqli que muestra errores
}

//configurar caracteres especiales del español
$conn->set_charset('utf8');

// $conn: hace la conexión y guarda errores. Es un array con info sobre la conexión.
// ->connect_error: f que muestra los errores en la conexión.
/* para ver $conn de forma ordenada:
echo "<pre>";
     var_dump($conn); | -> NULL significa que no hay errores
echo "</pre>";

**Otra forma con enviando un ping
echo "<pre>";
     var_dump($conn->ping()); | -> bool true (hay conexión) / bool false (no hay conexión)
echo "</pre>";
*/
?>
