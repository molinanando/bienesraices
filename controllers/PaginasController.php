<?php 

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index (Router $router) {
        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros (Router $router) {
        
        $router->render('paginas/nosotros');
    }

    public static function propiedades (Router $router) {
        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad (Router $router) {
        $id = validarORedireccionar('/propiedades');
        
        // Buscar la propiedad por su ID
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog (Router $router) {
        
        $router->render('/paginas/blog');
    }

    public static function entrada (Router $router) {
        
        $router->render('/paginas/entrada');
    }

    public static function contacto (Router $router) {
        
        $mensaje = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $respuestas = $_POST['contacto'];

            // Crear una instancia de PHPMailer
            $mail = new PHPMailer();

            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '562d9f2af0251e';
            $mail->Password = 'ab97be7654ad49';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // Configurar el contenido del email
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un nuevo mensaje';

            // Habilitar el HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            // Definir el contenido
            $contenido = '<html>';
            $contenido .= '<p> Tienes un nuevo mensaje </p>';
            $contenido .= '<p>Nombre: </p>' . $respuestas['nombre'] . '</p>';

            // Enviar de forma condicional algunos campos de email o telefono
            if($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Eligió ser contactado por teléfono:</p>';
                $contenido .= '<p>Teléfono: </p>' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p>Fecha Contacto: </p>' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p>Hora: </p>' . $respuestas['hora'] . '</p>';

            } else {
                // Es email, entonces agregamos el campo email
                $contenido .= '<p>Eligió ser contactado por email:</p>';
                $contenido .= '<p>Email: </p>' . $respuestas['email'] . '</p>';
            }

            $contenido .= '<p>Mensaje: </p>' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p>Vende o Compra: </p>' . $respuestas['tipo'] . '</p>';
            $contenido .= '<p>Precio o Presupuesto: </p>' . $respuestas['precio'] . '</p>';
            $contenido .= '<p>Prefiere ser contactado por: </p>' . $respuestas['contacto'] . '</p>';
            $contenido .= '</html>';

            $mail->Body= $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            // Enviar el email
            if($mail->send()) {
                $mensaje = "Mensaje enviado correctamente";
            } else {
                $mensaje = "El mensaje no se pudo enviar";
            }
        }

        $router->render('/paginas/contacto', [ 
            'mensaje' => $mensaje
        ]);
    }
}