<?php 
    $fecha_actual = date('Y-m-d');

    require_once("../../conexion.php");
    $registros = mysqli_query($conex, "SELECT empleados.*, roles.nombre_rol
    FROM empleados
    INNER JOIN roles ON roles.id_rol = empleados.id_rol 
    WHERE baja = '1'
    ORDER BY empleados.id_empleado ASC")
        or die("Problema en la consulta: " . mysqli_error($conex));
    mysqli_close($conex);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../../css/menu.css"> -->
    <link rel="stylesheet" href="../../css/footer.css">
    <title>Panel de Empleados</title>
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
    <?php include("../../menu.php"); ?>
    <h2>Lista de Empleados - Panadería M</h2>
    <div>
        <a class="button" href="templates/empleados/nuevo_emple.php">Nuevo Registro</a>        
        <a class="button" href="templates/empleados/empleados_panel_baja.php">Empleados Inactivos</a>
        <a class="button" href="templates/empleados/turno_caja_panel.php">Panel Turnos</a>
        <a class="button" href="../../page1.php">Volver al Menu</a>
    </div>
    <!-- Contenedor para la tabla con desplazamiento horizontal -->
    <div class="table-container">
        <table>
            <tr style="background:#c561ff;">
                <th>Id</th>
                <th>Usuario</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido</th>                
                <th>Telefono</th>
                <th>Email</th>
                <th>Rol</th>
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
                                                <td> <?php echo $row_registros['username']; ?></td>
                    <td> <?php echo $row_registros['dni']; ?></td>

                    <td> <?php echo $row_registros['nombre']; ?></td>
                    <td> <?php echo $row_registros['apellido']; ?></td>
                                        <td> <?php echo $row_registros['telefono']; ?></td>

                    <td> <?php echo $row_registros['email']; ?></td>
                                        <td> <?php echo $row_registros['nombre_rol']; ?></td>    

                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_empleado']; ?>, this.value)">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td>
                       
                    <td align="center">
                        <form action="empleados_modi.php" method="post">
                            <input type="hidden" name="id_empleado" value="<?php echo $row_registros['id_empleado']; ?>">

                            <button type="submit" style="border: none; background: none; color: #b47c04; cursor: pointer; padding: 0;">
                                <svg width="25px" height="25px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#b47c04" stroke="#b47c04"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g id="Complete"> <g id="edit"> <g> <path d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path> <polygon fill="none" points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polygon> </g> </g> </g> </g></svg>
                            </button>
                        </form>
                    </td>
                 </tr>
            <?php  
            } ?>
        </table>
    </div>
    <?php include("../../footer.php"); ?>
</body>
</html>
