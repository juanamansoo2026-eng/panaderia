<?php
require_once __DIR__ . '/auth.php';
?>
<?php
    $fecha_actual = date('Y-m-d');

    require_once("conexion.php");

    $registros = mysqli_query($conex, "SELECT id_inventario, materia_prima_id,
    materia_prima.nombre_mp, marca_mp.nombre_marca,
    cantidad_ingreso_mp, cantidad_salida_mp, stock_total,
    fecha_modi, baja
    FROM inventario
    INNER JOIN marca_mp ON marca_mp.id_marca = inventario.marca_id
    INNER JOIN materia_prima ON materia_prima.id_materia_prima = inventario.materia_prima_id
    ORDER BY baja DESC, id_inventario ASC")
    or die("Problema en la consulta " . mysqli_error($conex));

    // BUSCADOR Y FILTRO    
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : ''; // isset() comprueba si existe, $_GET obtiene el texto que pusimos, ? indica si existe se ejecuta antes de los :, trim() elimina los espacios, : indica falso y '' deja vacío, : si no pongo nada, '' muestra vacio.
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos'; // isset() comprueba si existe el estado, $_GET obtiene el estado seleccionado que pusimos, ? indica verdadero, : indica falso y 'todos' establece el estado predeterminado.

    mysqli_close($conex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panaderia-moderna.css">
    <title>Inventario de Materia Prima - Panadería M</title>
</head>
<body>
    <?php include("menu.php"); ?>

    <div class="table-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Inventario de Materia Prima - Panadería M</h2>
            
            <!-- BUSCAR Y FILTRAR -->
            <div>
                <form method="GET" action="inventario_panel.php">
                    <div class="div-filtros">

                        <!-- BUSCAR MATERIA PRIMA -->
                        <div>
                            <label for="buscar">Buscar materia prima:</label>
                            <input
                                type="text"
                                name="buscar"
                                id="buscar"
                                placeholder="Buscar materia prima..."
                                value="<?php echo htmlspecialchars($buscar); ?>"
                            >
                        </div>

                        <!-- FILTRAR ESTADO -->
                        <div>
                            <label for="estado">Estado:</label>

                            <select name="estado" id="estado">
                                <option value="todos" <?php if($estado == 'todos') echo 'selected'; ?>>
                                    Todos
                                </option>
                                <option value="1" <?php if($estado == '1') echo 'selected'; ?>>
                                    Activos
                                </option>
                                <option value="0" <?php if($estado == '0') echo 'selected'; ?>>
                                    Inactivos
                                </option>
                            </select>
                        </div>

                        <!-- BOTONES -->
                        <div style="padding-top: 30px;">
                            <button type="submit" class="button">
                                BUSCAR
                            </button>
                            <a href="inventario_panel.php" class="button button-outline">
                                LIMPIAR
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="div-mayor">
                <div class="b1">
                    <a class="button" href="inventario_alta.php">Nuevo Registro</a>
                </div>
                <div class="b1">
                    <a class="button button-outline" href="page1.php">Volver al Menú</a>
                </div>
            </div>

        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Materia Prima</th>
                    <th>Marca</th>
                    <th>Ingresos</th>
                    <th>Salidas</th>
                    <th>Stock Total</th>
                    <th>Última Modificación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php /* while($row_registros= mysqli_fetch_assoc($registros)) { ?>
                <tr align="center">   
                    <td><?php echo htmlspecialchars($row_registros['id_inventario']);?></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre_mp']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre_marca']);?></td>
                    <td style="color:#2E7D32;">+<?php echo htmlspecialchars($row_registros['cantidad_ingreso_mp']);?></td>
                    <td style="color:#C62828;">-<?php echo htmlspecialchars($row_registros['cantidad_salida_mp']);?></td>
                    <td><strong style="color:var(--bakery-primary); font-size:1.05rem;"><?php echo htmlspecialchars($row_registros['stock_total']);?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha_modi']);?></td>
                    <td> 
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_inventario']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>No Activo</option>
                        </select>
                    </td> 
                    <td>
                        <form action="inventario_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_inventario" value="<?php echo $row_registros['id_inventario']; ?>">
                            <button type="submit" class="table-action" title="Editar inventario" aria-label="Editar inventario">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr> 
            <?php } */ ?>
             <?php while($row_registros = mysqli_fetch_assoc($registros)) { ?>
                <?php
                // COMPROBAR BUSCADOR Y FILTRO
                if (
                    ($buscar == '' || stripos($row_registros['nombre_mp'], $buscar) === 0) && // == compara si está vacío el campo, || significa "o", stripos() busca el texto, [] accede al elemento del array o contiene lo que pusimos y === 0 comprueba que comience al principio.
                    ($estado == 'todos' || $row_registros['baja'] == $estado) // "todos" comprueba si el estado es "todos", || significa "o", [] accede al elemento del array de la bd y comprueba si el estado $estado coincide con el que elegeimo.
                ) { // Si se cumplen las condiciones, se muestra el registro en la tabla.
                ?>
                <tr align="center">
                    <td><?php echo htmlspecialchars($row_registros['id_inventario']); ?></td>
                    <td><strong><?php echo htmlspecialchars($row_registros['nombre_mp']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['nombre_marca']); ?></td>
                    <td style="color:#2E7D32;">+<?php echo htmlspecialchars($row_registros['cantidad_ingreso_mp']); ?></td>
                    <td style="color:#C62828;">-<?php echo htmlspecialchars($row_registros['cantidad_salida_mp']); ?></td>
                    <td><strong style="color:var(--bakery-primary); font-size:1.05rem;"><?php echo htmlspecialchars($row_registros['stock_total']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row_registros['fecha_modi']); ?></td>
                    <td>
                        <select onchange="actualizarBaja(<?php echo $row_registros['id_inventario']; ?>, this.value)" style="margin-bottom:0; padding: 4px 8px; font-size:0.85rem;">
                            <option value="1" <?php echo ($row_registros['baja'] == 1) ? 'selected' : ''; ?>>
                                Activo
                            </option>
                            <option value="0" <?php echo ($row_registros['baja'] == 0) ? 'selected' : ''; ?>>
                                No Activo
                            </option>
                        </select>
                    </td>
                    <td>
                        <form action="inventario_modi.php" method="post" style="margin:0;">
                            <input type="hidden" name="id_inventario" value="<?php echo $row_registros['id_inventario']; ?>">
                            <button type="submit" class="table-action" title="Editar inventario" aria-label="Editar inventario">
                                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>

            </tbody>
            </tbody>
        </table>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>