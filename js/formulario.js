
eventListeners();

function eventListeners() {
     document.querySelector('#formulario').addEventListener('submit', validarRegistro);
}

function validarRegistro(e) {
     e.preventDefault();
     //console.log('Aquí vamos!!');

     var usuario = document.querySelector('#usuario').value,
          password = document.querySelector('#password').value,
          tipo = document.querySelector('#tipo').value;
     //console.log(`${usuario} \n${password}`);

     if (usuario === "" || password === "") {
          // a validación falló
          swal({
               type: 'error',
               title: 'Ups...',
               text: '¡Ambos campos son obligatorios!'
          })
     } else {
          //Ambos campos son correctos, ejecutar Ajax

          // datos que se envían al servidor
          var datos = new FormData();
          datos.append('usuario', usuario);
          datos.append('password', password);
          datos.append('accion', tipo);
          //console.log(datos.get('usuario'));

          // 1 crear el llamado a Ajax
          var xhr = new XMLHttpRequest();

          // 2 abrir conexión
          xhr.open('POST', 'inc/modelos/modelo-admin.php', true);

          // 3 retorno de datos o xhr.onload
          xhr.onload = function() {
               if (this.status === 200) {
                    //console.log(JSON.parse(xhr.responseText));
                    var respuesta = JSON.parse(xhr.responseText);

                    console.log(respuesta);
                    // si la respuesta es correcta
                    if (respuesta.respuesta === 'correcto') {
                         // si es nuevo usuario = crear cuenta
                         if (respuesta.tipo === 'crear') {
                              swal({
                                   title: 'Usuario creado',
                                   text: '¡El usuario se creó correctamente!',
                                   type: 'success'
                              });
                         } else if (respuesta.tipo === 'login') {
                              swal({
                                   title: 'Login correcto',
                                   text: 'Presiona OK para abrir el dashboard',
                                   type: 'success'
                              })
                              .then(resultado => {
                                   //console.log(resultado); // value: true
                                   if (resultado.value) {
                                        window.location.href = 'index.php'; // para redireccionar la página
                                   }
                              })
                         }
                    } else {
                         // Hubo un error
                         swal({
                            title: 'Error',
                            text: 'Hubo un error',
                            type: 'error'
                         })
                    }
               }
          }
          // 4 enviar petición
          xhr.send(datos); // se envía el FormData
     }
}

/* DOCUMENTACIÓN
Ajax
* responseText es la respuesta que viene del servidor
*JSON.parse() convierte json a objeto js. Así puedo acceder a los elementos del objeto.
*si sólo fuera xhr.responseText sería un string en formato json, xtanto, no se puede acceder a sus elementos
*.then es una promise: usado en computaciones asíncronas. Es un objeto que representa el eventual cumplimiento o fallo de una pperación asíncrona.
*/



























//
