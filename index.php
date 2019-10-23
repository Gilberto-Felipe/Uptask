<?php
     include 'inc/funciones/sesiones.php';
     include 'inc/funciones/funciones.php';
     include 'inc/templates/header.php';
     include 'inc/templates/barra.php';

     /*//probar sesión
     echo "<pre>";
          var_dump($_SESSION);
     echo "</pre>";*/

     // Obtener id de la URL
     if (isset($_GET['id_proyecto'])) {
          $id_proyecto = $_GET['id_proyecto'];
          //echo $id_proyecto;
     } else  {
         $id_proyecto = '';
     }
 ?>

<div class="contenedor">
     <?php
          include 'inc/templates/sidebar.php';
      ?>
     <main class="contenido-principal">

     <?php
     // paso el id del proyecto a la f obtenerNombreProyecto que regresa el nombre del proyecto seleccionado
     $proyecto = obtenerNombreProyecto($id_proyecto); //
     if($proyecto): ?>
          <h1> Proyecto actual:
               <?php // Obtengo el nombre del proyecto seleccionado
               foreach ($proyecto as $nombre): // $nombre va a a ser un array que recibe el id y el nombre seleccionado ?>
                    <span><?php echo $nombre['nombre']; // imprimo el nombre ?></span>
               <?php endforeach; ?>
          </h1>

        <form action="#" class="agregar-tarea">
            <div class="campo">
                <label for="tarea">Tarea:</label>
                <input type="text" placeholder="Nombre Tarea" class="nombre-tarea">
            </div>
            <div class="campo enviar">
                <input type="hidden" id="id_proyecto" value="<?php echo $id_proyecto; ?>">
                <input type="submit" class="boton nueva-tarea" value="Agregar">
            </div>
        </form>
     <?php else:
          // Si no hay proyectos seleccionados
          echo "<p>Selecciona un proyecto de la izquierda </p>";
     endif; ?>


        <h2>Listado de tareas:</h2>

        <div class="listado-pendientes">
            <ul>
                 <?php
                    //Obtiene las tareas del proyecto actual
                    $tareas = obtenerTareasProyecto($id_proyecto);
                    /*// pruebo y valido con bd que sí tenga tareas asociadas
                    echo "<pre>";
                         var_dump($tareas);
                    echo "</pre>"; */
                    if ($tareas->num_rows > 0) {
                         // si hay tareas

                         foreach ($tareas as $tarea): ?>
                              <li id="tarea:<?php echo $tarea['id'] ?>" class="tarea">
                              <p><?php echo $tarea['nombre'] ?></p>
                                  <div class="acciones">
                                      <i class="far fa-check-circle <?php echo ($tarea['estado'] === '1' ? 'completo' : '') ?>"></i>
                                      <i class="fas fa-trash"></i>
                                  </div>
                              </li>
                         <?php endforeach;
                    } else {
                         // no hay tareas
                         echo "<p class='lista-vacia'>No hay tareas en este proyecto</p>";
                    }



                  ?>

            </ul>
        </div>
     </main>
</div><!--.contenedor-->

<?php
     include 'inc/templates/footer.php';
 ?>
