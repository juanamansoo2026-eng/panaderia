<?php
require_once("conexion.php"); 
require_once ('vendor/autoload.php');
date_default_timezone_set('America/Argentina/Salta');


$id_venta = $_REQUEST['id_venta'];

// Consulta para obtener datos de la factura
$registro = mysqli_query($conex, "SELECT id_venta,cliente_id,empleado_id,subtotal,descuento,total,forma_pago,fecha,clien.nombre,
clien.apellido,clien.direccion,clien.telefono, CONCAT (clien.apellido,' ',clien.nombre, '  -  Dirección: ',clien.direccion) AS razon_social,
emple.nombre,emple.apellido, CONCAT(emple.apellido,' ',emple.nombre) AS datos_empleado
FROM ventas 
LEFT JOIN clientes as clien ON clien.id_cliente = ventas.cliente_id
LEFT JOIN empleados as emple ON emple.id_empleado = ventas.empleado_id
WHERE id_venta = '$id_venta'") 	or die('Problemas con la consulta');
$row_registro = mysqli_fetch_array($registro);
		
// consulta para detalle de ventas 
	$detalleVta = mysqli_query($conex,"SELECT id_detalle,venta_id,producto_id,cantidad,precio_unitario,nombre
	FROM detalle_venta
	LEFT JOIN productos ON productos.id_producto = detalle_venta.producto_id
	WHERE venta_id = '$id_venta'") or die('Problemas con familiar'.mysqli_error($conex));

	$rellenocuadro = '';
			
			while ($row_detalleVta = mysqli_fetch_array($detalleVta)){
			$subtotal = $row_detalleVta['cantidad'] * $row_detalleVta['precio_unitario'];
			$rellenocuadro = $rellenocuadro.'<tr>
				<td style="text-align:left;">'.strtoupper($row_detalleVta['nombre']).'</td>
				<td>'.$row_detalleVta['cantidad'].'</td>
				<td>$ '.$row_detalleVta['precio_unitario'].'</td>
				<td style="text-align:right;">$ '.$subtotal.'</td>
				</tr>';
			}

	mysqli_free_result($detalleVta);
		
			
		
					$html ='

						<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml">
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>Factura Digital</title>
							<style type="text/css">
								body {
									font-family:Arial;
									font-size:0.9em;
								}
								.head1{
									padding-top:10px;
									margin-left:33px;
									text-decoration:underline;
									line-height:150%;
									
								}
								.head2{
									text-align:center;
									text-decoration:underline;
									font-weight:bold;
								}
								.parrafo{
									white-space:pre-line;
									line-height:154%;
									text-align:justify;
								}
								.negrita{
									font-weight:bold;
								}

							</style>
						</head>

						<body>
							<table width="100%" border=0>
								<tr>
									
									<td width="80%" style="font-size:29px;" align="right">
										<div>PANADERIA M</div>
										<div style="font-size:17px;">Dirección:</div>
										<div style="font-size:17px;">Telefóno: </div>
									</td>
								</tr>
							</table>
							<div style="padding-left:0px;">
								<p align="right" ><div class="negrita" style="text-align:right;">FACTURA Nº '.$row_registro['id_venta'].'</div></p>
								<p class="head2">______________________________________________________________________________________________________</p>
								
								<table border=0 width="100%">
									<tr>
										<td class="negrita">Fecha:  '.date('d/m/Y', strtotime($row_registro['fecha'])).'</td>
									</tr>
									<tr>
										<td class="negrita">Cliente: '.$row_registro['razon_social'].'</td>
									</tr>
									<tr>
										<td class="negrita">Vendedor: '.$row_registro['datos_empleado'].'</td>
									</tr>
								</table>
								<table border=0 width="100%" style="text-align:center;">
								<tr>
									<td colspan=4 style="background:silver;">DETALLE DE LA VENTA</td>
								</tr>
								<tr>
								<td width="43%">PRODUCTO</td>
								<td width="10%">CANTIDAD</td>
								<td width="15%">PRECIO UNITARIO</td>
								<td width="15%">SUBTOTAL</td>
								</tr>
								<tr>
										'.$rellenocuadro.'
									</tr>
								</table>
								
								<p class="head2">______________________________________________________________________________________________________</p>
								<table border=0 width="100%" style="text-align:right;">
									<tr>
										<td class="negrita">Subtotal: $'.$row_registro['subtotal'].'</td>
									</tr>
									<tr>
										<td class="negrita">Descuento: $'.$row_registro['descuento'].'</td>
									</tr>
									<tr>
										<td class="negrita">Total: $'.$row_registro['total'].'</td>
									</tr>
									<tr>
										<td class="negrita">Forma de Pago: '.$row_registro['forma_pago'].'</td>
									</tr>
								</table>
								
								
							</div><br>
							
							
						</body>
							
						</html>	
						';


						$mpdf = new \Mpdf\Mpdf([ 'format' => 'A4', 'margin_left' => '30', 'margin_right' =>'20', 'margin_top'=>'15', 'margin_bottom'=>'20']);
						$mpdf ->setAutoBottomMargin = 'stretch';

						$mpdf -> writeHTML($html);


					$mpdf -> Output ('Factura_digital'.$row_registro['id_venta'].'.pdf', 'I');
		
	
						$mpdf = new \Mpdf\Mpdf([ 'format' => 'A4', 'margin_left' => '25', 'margin_right' =>'15', 'margin_top'=>'10', 'margin_bottom'=>'20']);
						$mpdf ->setAutoBottomMargin = 'stretch';

						$mpdf -> writeHTML($html);


					
	
	
	
	
	
?>