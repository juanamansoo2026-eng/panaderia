<?php 
    $fecha_actual = date('Y-m-d');

    require_once("../../conexion.php");
    $registros = mysqli_query($conex, "SELECT empleados.*, roles.nombre_rol
    FROM empleados
    INNER JOIN roles ON roles.id_rol = empleados.id_rol 
    WHERE baja = '0'
    ORDER BY empleados.id_empleado ASC")
        or die("Problema en la consulta: " . mysqli_error($conex));
    mysqli_close($conex);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empleados Inactivos</title>
    <style>
        .tachado {
            text-decoration: line-through;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #c561ff; 
            color: white;  
            text-decoration: none; 
            border-radius: 5px; 
        }
        .button:hover {
            background-color: #89CFF0; 
        }
        .table-container {
        max-width: 100%; /* Hace que ocupe todo el ancho disponible */
        }
        table {
            width: 100%; /* La tabla ocupará el 100% del ancho disponible dentro del contenedor */
            table-layout: fixed; /* Hace que las celdas tengan un ancho fijo y no se deformen */
            border-collapse: collapse; /* Para que las celdas estén pegadas */
        }
        th, td {
            padding: 5px;
            text-align: center;
            border: 1px solid;
            word-wrap: break-word; /* Evita que las celdas se desborden */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Función para actualizar el estado de baja en la base de datos
        function actualizarBaja(id_empleado, baja) {
            $.ajax({
                url: 'actualizar_baja.php', // El archivo PHP que va a manejar la actualización
                type: 'POST',
                data: {
                    id_empleado: id_empleado,
                    baja: baja  // Solo enviamos el estado de baja (0 o 1)
                },
                // success: function(response) {
                //     // Si la actualización es exitosa, actualizar visualmente el estado
                //     if (response == 'success') {
                //         // Actualizar la clase tachado si baja es 0 (No Activo)
                //         if (baja == 0) {
                //             $('#fila_' + id_empleado).addClass('tachado');
                //             // Mostrar la fecha de baja actualizada en la tabla
                //             $('#fecha_baja_' + id_empleado).text('<?php echo $fecha_actual; ?>');
                //         } else {
                //             $('#fila_' + id_empleado).removeClass('tachado');
                //             // Si se activa, poner "0000-00-00" o "NULL" en lugar de la fecha
                //             $('#fecha_baja_' + id_empleado).text('0000-00-00');  // O también puedes usar 'NULL'
                //         }
                //     } else {
                //         console.error("Error al actualizar el estado.");
                //     }
                // }
            });
        }
    </script>
</head>
<body>
    <h2>Lista de Empleados Inactivos- Panadería M</h2>
    <div>
        <a class="button" href="nuevo_emple.php">Nuevo Registro</a>
        <a class="button" href="turno_caja_panel.php">Panel Turnos</a>
        <a class="button" href="empleados_panel.php">Panel de Empleados</a>
    </div>
    <!-- Contenedor para la tabla con desplazamiento horizontal -->
    <div class="table-container">
        <table>
            <tr style="background:#c561ff;">
                <th>Id</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Rol</th>
                <th>Telefono</th>
                <th>Usuario</th>
                <th>Contraseña</th>
                <th>Email</th>
                <th>Fecha Alta</th>
                <th>Fecha Modi</th>
                <th>Fecha Baja</th>
                <th>Baja</th>
                <th>Acciones</th>
            </tr>
            <?php 
            while ($row_registros = mysqli_fetch_assoc($registros)) {
                // Definir si la fila está tachada o no (según el valor de 'baja')
                //$clase_tachado = ($row_registros['baja'] == 0) ? 'tachado' : ''; // Si baja es 0, tacha la fila
            ?>
                <tr id="fila_<?php echo $row_registros['id_empleado']; ?>" class="<?php echo $clase_tachado; ?>">
                    <td align="center"> <?php echo $row_registros['id_empleado']; ?></td>
                    <td> <?php echo $row_registros['nombre']; ?></td>
                    <td> <?php echo $row_registros['apellido']; ?></td>
                    <td> <?php echo $row_registros['dni']; ?></td>
                    <td> <?php echo $row_registros['nombre_rol']; ?></td>    
                    <td> <?php echo $row_registros['telefono']; ?></td>
                    <td> <?php echo $row_registros['username']; ?></td>
                    <td> <?php echo $row_registros['password']; ?></td>
                    <td> <?php echo $row_registros['email']; ?></td>
                    <td> <?php echo $row_registros['fecha_alta']; ?></td>
                    <td> <?php echo $row_registros['fecha_modi']; ?></td>
                    <td id="fecha_baja_<?php echo $row_registros['id_empleado']; ?>"> 
                        <?php echo $row_registros['fecha_baja']; ?>
                    </td>
                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_empleado']; ?>, this.value)">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td>   
                    <td align="center">
                        <form action="empleados_modi.php" method="post">
                            <input type="hidden" name="id_empleado" value="<?php echo $row_registros['id_empleado']; ?>">
                            <input type="image" name="editar" src="img/lapiz2.png" width="20" title="Editar">
                        </form> 
                    </td>
                 </tr>
            <?php  
            } ?>
        </table>
    </div>
</body>
</html>
