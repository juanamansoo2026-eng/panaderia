<?php
/*
 * Funciones comunes para el Alta, Modificación, Baja y Restablecimiento
 * de clave de los usuarios (tabla empleados). No modifica la base de datos.
 *
 * ¿Cómo sabemos que el usuario debe cambiar la clave en el próximo login
 * sin agregar una columna nueva?
 * El hash de password_hash() guarda adentro el "costo" con el que se generó
 * (por ejemplo $2y$11$...). Las claves temporales (alta y restablecimiento)
 * se cifran con costo 11 y las claves que elige el propio usuario con costo 10.
 * En el login, si el hash tiene costo 11, se le exige cambiar la clave.
 */

define('COSTO_CLAVE_TEMPORAL', 11);
define('COSTO_CLAVE_USUARIO', 10);

// Saca acentos, espacios y caracteres raros, y pasa a minúsculas
function limpiarTextoUsuario($texto)
{
    $texto = mb_strtolower(trim($texto), 'UTF-8');
    $buscar = ['á', 'é', 'í', 'ó', 'ú', 'à', 'è', 'ì', 'ò', 'ù', 'ä', 'ë', 'ï', 'ö', 'ü', 'â', 'ê', 'î', 'ô', 'û', 'ñ', 'ç'];
    $poner = ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n', 'c'];
    $texto = str_replace($buscar, $poner, $texto);
    return preg_replace('/[^a-z0-9]/', '', $texto);
}

// Usuario = Apellido + primera letra del primer nombre (ej: Pérez, Juan Carlos -> perezj)
// Si ya existe, le agrega un número al final (perezj2, perezj3, ...)
function generarUsername($conex, $apellido, $nombre)
{
    $primer_nombre = explode(' ', trim($nombre))[0];
    $base = limpiarTextoUsuario($apellido) . substr(limpiarTextoUsuario($primer_nombre), 0, 1);

    if ($base === '') {
        return '';
    }

    $username = $base;
    $numero = 2;

    $verificar = $conex->prepare("SELECT id_empleado FROM empleados WHERE username = ?");
    while (true) {
        $verificar->bind_param("s", $username);
        $verificar->execute();
        $verificar->store_result();
        if ($verificar->num_rows == 0) {
            break;
        }
        $username = $base . $numero;
        $numero++;
    }
    $verificar->close();

    return $username;
}

// Genera una clave temporal al azar de 8 caracteres (sin letras que se confunden: 0/O, 1/l/I)
function generarClaveTemporal($largo = 8)
{
    $caracteres = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $clave = '';
    for ($i = 0; $i < $largo; $i++) {
        $clave .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }
    return $clave;
}

// Hash de una clave temporal (alta o restablecimiento): obliga a cambiarla en el próximo login
function hashClaveTemporal($clave)
{
    return password_hash($clave, PASSWORD_BCRYPT, ['cost' => COSTO_CLAVE_TEMPORAL]);
}

// Hash de una clave elegida por el usuario
function hashClaveUsuario($clave)
{
    return password_hash($clave, PASSWORD_BCRYPT, ['cost' => COSTO_CLAVE_USUARIO]);
}

// true si el hash guardado corresponde a una clave temporal
function esClaveTemporal($hash)
{
    $info = password_get_info($hash);
    return isset($info['options']['cost']) && (int) $info['options']['cost'] === COSTO_CLAVE_TEMPORAL;
}
