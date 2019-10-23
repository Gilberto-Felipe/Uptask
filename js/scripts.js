//console.log('funciona!!');

eventListeners();
// var lista de proyectos
var listaProyectos = document.querySelector('ul#proyectos');

function eventListeners() {
     // Botón para crear proyecto
     document.querySelector('.crear-proyecto a').addEventListener('click', nuevoProyecto);

     // Botón para crear una nueva tarea
     document.querySelector('.nueva-tarea').addEventListener('click', agregarTarea);

     // Botones para las acciones de las tareas usando Delegation en js
     document.querySelector('.listado-pendientes').addEventListener('click', accionesTareas);


}

function nuevoProyecto(e) {
     e.preventDefault();
     //console.log('Presionaste nuevo proyecto');

     // crea un <input> para el nombre del nuevo proyecto
     let nuevoProyecto = document.createElement('li');
     nuevoProyecto.innerHTML = '<input type="text" id="nuevo-proyecto">';
     listaProyectos.appendChild(nuevoProyecto);

     // seleccionar id del nuevoProyecto
     let inputNuevoProyecto = document.querySelector('#nuevo-proyecto');

     // al presionar enter crear el proyecto
     inputNuevoProyecto.addEventListener('keypress', function(e) {
          //console.log(e);
          // reaccionar en base a un tecla específica
          let tecla = e.witch || e.keyCode;

          if (tecla === 13) {
               //console.log('Presionaste la tecla enter');
               guardarProyectoDB(inputNuevoProyecto.value);
               listaProyectos.removeChild(nuevoProyecto);
          }
     });
}

// funcion para mostar el nombre del nuevo proyecto en la lista y guardarlo en la bd
function guardarProyectoDB(nombreProyecto) {
     // 1- creo llamado ajax
     var xhr = new XMLHttpRequest();

     // 2- Formatear datos con FormData. Hace las cosas más fáciles
     var datos = new FormData();
     datos.append('proyecto', nombreProyecto);
     datos.append('accion', 'crear');

     // 3- abro la conexión
     xhr.open('POST', 'inc/modelos/modelo-proyecto.php', true);

     // 4- compruebo al cargar
     xhr.onload = function() {
          if (this.status === 200) {
               console.log(JSON.parse(xhr.responseText));

               // leer/obtener datos de la respuesta
               var respuesta = JSON.parse(xhr.responseText);
               var proyecto = respuesta.nombre_proyecto,
                    id_proyecto = respuesta.id_proyecto,
                    tipo = respuesta.tipo,
                    resultado = respuesta.respuesta;

               // compruebo la inserción
               if (resultado === 'correcto') {
                    // fue existoso
                    // valido si fue del tipo crear o no lo fue
                    if (tipo === 'crear') {
                         // se creó un nuevo proyecto
                         // inyecto el html
                         var nuevoProyecto = document.createElement('li');
                         nuevoProyecto.innerHTML = `
                              <a href="index.php?id_proyecto=${id_proyecto}" id="proyecto:${id_proyecto}">
                                   ${proyecto}
                              </a>
                         `;
                         // agrego al html
                         listaProyectos.appendChild(nuevoProyecto);

                         // envío alerta
                         swal({
                              title: '¡Proyecto creado!',
                              text: '¡El proyecto: ' + proyecto + ' se creó correctamente!',
                              type: 'success'
                         })
                         .then(resultado => {
                              //console.log(resultado); // value: true
                              // redirecciono a la nueva URL
                              if (resultado.value) {
                                   window.location.href = 'index.php?id_proyecto=' + id_proyecto; // para redireccionar la página
                              }
                         })
                    } else {
                         // se actualizó o eliminó un proyecto
                    }

               } else {
                    // hubo un error
                    swal({
                       title: '¡Error!',
                       text: '¡Hubo un error!',
                       type: 'error'
                    })
               }
          }
     }

     // 5- enviar el reques
     xhr.send(datos);
}

// Agregar una nueva tarea al proyecto actual
function agregarTarea(e) {
     e.preventDefault();
     //console.log('Diste click en agregar!');
     // entrada de datos
     let nombreTarea = document.querySelector('.nombre-tarea').value;

     // valido que el campo no esté vacío"
     if (nombreTarea === "") {
          swal({
               title: 'Error',
               text: 'Escribe una tarea',
               type: 'error'
          });
     } else {
          // la tarea existe, insertar PHP

          // 1. Creo llamado a ajax
          let xhr = new XMLHttpRequest();

          // 2. Abro la conexión
          xhr.open('POST', 'inc/modelos/modelo-tareas.php', true);

          // 3. Creo FormData
          let datos = new FormData();
          datos.append('tarea', nombreTarea);
          datos.append('accion', 'crear');
          datos.append('id_proyecto', document.querySelector('#id_proyecto').value);

          // Ejecuto y obtengo respuesta de la conexión
          xhr.onload = function() {
               if (this.status === 200) {
                    // todo correcto e imprimo respuesta
                    let respuesta = JSON.parse(xhr.responseText);
                    //console.log(respuesta);

                    // asigno variables a las keys de la respuesta
                    let resultado = respuesta.respuesta,
                         tarea = respuesta.tarea,
                         id_proyecto = respuesta.id_proyecto,
                         tipo = respuesta.tipo;

                    if (resultado === 'correcto') {
                         // se agregó correctamente
                         if (tipo === 'crear') {
                              // lanzar alerta
                              swal({
                                 title: 'Tarea creada',
                                 text: '¡La tarea: ' + tarea + ' se creó exitosamente!',
                                 type: 'success'
                              })

                              // Seleccionar párrafo con la lista vacía
                              let parrrafoListaVacia = document.querySelectorAll('.lista-vacia');

                              // comprobamos que haya un elemento de clase lista-vacía
                              if (parrrafoListaVacia.length > 0) {
                                   document.querySelector('.lista-vacia').remove();
                              }

                              // contruir template html
                              let nuevaTarea = document.createElement('li');

                              // agregar el id_tarea
                              nuevaTarea.id = 'tarea: ' + id_proyecto

                              // agregar clase tarea
                              nuevaTarea.classList.add('tarea');

                              // construir el html para tarea
                              nuevaTarea.innerHTML = `
                                   <p>${tarea}</p>
                                   <div class="acciones">
                                        <i class="far fa-check-circle"></i>
                                        <i class="fas fa-trash"></i>
                                   </div>
                              `;

                              // agregar tarea al html/DOM
                              let listado = document.querySelector('.listado-pendientes ul');
                              listado.appendChild(nuevaTarea);

                              // limpiar el formulario
                              document.querySelector('.agregar-tarea').reset();
                         }

                    } else {
                         // hubo un error
                         swal({
                            title: 'Error',
                            text: 'Hubo un error',
                            type: 'error'
                         })
                    }
               }
          }
          // Envío la consulta
          xhr.send(datos);
     }
}

// Cambia el estado de las tareas o las elimina
function accionesTareas(e) {
     e.preventDefault();
     //console.log('Diste click en listado!!');
     //console.log(e.target); accede al elemento seleccionado por el usuario
     if (e.target.classList.contains('fa-check-circle')) {
          //console.log('Diste click en el círculo!');
          if (e.target.classList.contains('completo')) {
               e.target.classList.remove('completo');
               cambiarEstadoTarea(e.target, 0);
          } else {
               e.target.classList.add('completo');
               cambiarEstadoTarea(e.target, 1);
          }
     }

     if (e.target.classList.contains('fa-trash')) {
          //console.log('Diste click en el cesto!');
          swal({
               title: '¿Seguro?',
               text: "¡Esta acción no se puede deshacer!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: "¡Sí, borrar!",
               cancelButtonText: "Cancelar"
          }) .then((result) => {
               if (result.value) {
                    let tareaEliminar = e.target.parentElement.parentElement;
                    // Borrar de la BD
                    eliminarTareaBD(tareaEliminar);

                    // Borrar del HTML/DOM
                    //console.log(tareaEliminar);
                    tareaEliminar.remove();


                    swal(
                         'Eliminado',
                         'La tarea fue eliminada',
                         'success'
                    )
               }
          })
     }
}

// completa o descompleta una tarea
function cambiarEstadoTarea(tarea, estado) {
     let idTarea = tarea.parentElement.parentElement.id.split(':'); // traversing o recorrer el DOM / split para separar el id='tarea:i', y quedarte solo con la posición.
     //console.log(idTarea[1]);

     // 1. crear llamado a ajax
     let xhr = new XMLHttpRequest();

     // 2. Formateo los datos con new FormData
     let datos = new FormData();
     datos.append('id', idTarea[1]);
     datos.append('accion', 'actualizar');
     datos.append('estado', estado);
     //console.log(estado);

     // 3. Abro la conexión
     xhr.open('POST', 'inc/modelos/modelo-tareas.php', true);

     // 4. Ejecuto y obtengo la respuesta
     xhr.onload = function() {
          if (this.status === 200) {
               console.log(JSON.parse(xhr.responseText));
          }
     }

     // 5. Envío la consulta/datos
     xhr.send(datos);
}

// f elimina las tareas de la bd
function eliminarTareaBD(tarea) {
     //console.log(tarea);
     let idTarea = tarea.id.split(':');

     // 1. crear llamado a ajax
     let xhr = new XMLHttpRequest();

     // 2. Formateo los datos con new FormData
     let datos = new FormData();
     datos.append('id', idTarea[1]);
     datos.append('accion', 'eliminar');

     // 3. Abro la conexión
     xhr.open('POST', 'inc/modelos/modelo-tareas.php', true);

     // 4. Ejecuto y obtengo la respuesta
     xhr.onload = function() {
          if (this.status === 200) {
               console.log(JSON.parse(xhr.responseText));

               // comprobar que haya tareas restantes
               let listaTareasRestantes = document.querySelectorAll('li.tarea');

               if (listaTareasRestantes.length === 0) {
                    document.querySelector('.listado-pendientes ul').innerHTML = "<p class='lista-vacia'>No hay tareas en este proyecto</p>";
               }
          }
     }

     // 5. Envío la consulta/datos
     xhr.send(datos);
}








//console.log(nombreProyecto);

/*
*new FormData(); Permite almacenar en formato llave valor y poderlo enviar por ajax mucho más fácil.
Te hace las cosas mucho más fáciles.

*xhr.responseText devuelve un string
JSON.parse(xhr.responseText) convierte xhr.responseText en un json.

*.reset(): método de js para limpiar campos de un formulario

*Dlegation in js: Es poner un evento a una clase padre, en lugar de atarlo a cada uno de sus hijos. Esto es más dinámico y automático. Te permite crear y eliminar elementos ligados al evento de la clase padre.
Con e.target accedes al elemento seleccionado por el usuario.
Con un if controlas qué acciones se liguen a cierto elemento seleccionado.
*/




//
