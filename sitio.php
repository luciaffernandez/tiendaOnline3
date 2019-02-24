<?php

error_reporting(0);

//Añadimos las clases
require_once "Smarty.class.php";
spl_autoload_register(function($clase) {
    include "$clase.php";
});

session_start();

//establecemos conexion
$conexion = new BD();
//Creamos un objeto para gestionar plantillas
$smarty = new Smarty();
//Configuramos los directorios
$smarty->template_dir = "./template";
$smarty->compile_dir = "./template_c";

//si tenemos guardados las variables de sesion usuario y contraseña 
if (isset($_SESSION['user']) && isset($_SESSION['pass'])) {
    //las guardamos en variables
    $nombre = $_SESSION['user'];
    $pass = $_SESSION['pass'];
} else {
    //sino, esque no nos hemos legueado y nos devuelve al login con un error
    header("Location:login.php?error");
}

//recojo el resultado de la funcion que creara la lista de Productos y lo muestra
$listado = obtenerListado($conexion);
$smarty->assign('listado', $listado);

//se muestra la plantilla del sitio 
$smarty->display("sitio.tpl");

/** Funcion que ejecuta un select recogiendo los productos de la base de datos y va recogiendo sus datos
 * @param type $conexion le pasamos la conexion a la base de datos
 * @return string. Devuelve un string con el html del listado de productos
 */
function obtenerListado($conexion) {
    $listado = "";
    $datos = $conexion->seleccion("SELECT * FROM producto");
    foreach ($datos as $dato) {
        $n_corto = $dato['nombre_corto'];
        $precio = $dato['PVP'];
        $codigo = $dato['cod'];
        $listado .= "<form action='sitio.php' method='post'>"
                . " <input type='submit' value='Añadir' name='cestaAccion'>"
                . " <input type='hidden' value='$precio' name='precio'>"
                . " <input type='hidden' value='$codigo' name='codigo'>"
                . "  " . $n_corto . " - " . $precio
                . "</form>";
    }
    return $listado;
}

?>