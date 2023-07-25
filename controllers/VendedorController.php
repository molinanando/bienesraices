<?php 

namespace Controllers;

use Model\Vendedor;
use MVC\Router;

class VendedorController {
    public static function crear (Router $router) {
        $errores=Vendedor::getErrores();

        $vendedor = new Vendedor;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Crear una nueva instancia (objeto)
            $vendedor = new Vendedor($_POST['vendedor']);
        
        
            // Validar que no haya campos vacíos
            $errores = $vendedor->validar();
        
            // No hay errores, lo guardamos
            if(empty($errores)) {
                $vendedor->guardar();
            }      
        }

        $router->render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
        
    }

    public static function actualizar (Router $router) {

        $errores=Vendedor::getErrores();
        $id = validarORedireccionar('/admin');

        // Obtener datos del vendedor actualizado
        $vendedor = Vendedor::find($id);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Asignar los atributos
            $args = $_POST['vendedor'];
    
            $vendedor->sincronizar($args);
    
            // Validación
            $errores = $vendedor->validar();
        
            if(empty($errores)) {
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function eliminar () {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validar por ID
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {
                // Valida el tipo a eliminar
                $tipo = $_POST['tipo'];

                if(validarTipoContenido($tipo)) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
            }
        }
    }
}