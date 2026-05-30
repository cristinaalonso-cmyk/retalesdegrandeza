<?php
header('Content-Type: application/json');

// Habilitar CORS si es necesario
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $nombre = htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
    $mensaje = htmlspecialchars($_POST['mensaje'] ?? '', ENT_QUOTES, 'UTF-8');

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email inválido']);
        exit;
    }

    // Validar campos requeridos
    if (empty($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email requerido']);
        exit;
    }

    // Crear datos del registro
    $nuevo_registro = [
        "id" => date("YmdHis"),
        "email" => $email,
        "nombre" => $nombre,
        "mensaje" => $mensaje,
        "fecha" => date("Y-m-d H:i:s"),
        "ip" => $_SERVER['REMOTE_ADDR'],
        "tipo" => !empty($mensaje) ? 'contacto' : 'newsletter'
    ];

    // Ruta del archivo JSON
    $archivo = 'datos.json';

    // Leer datos existentes
    $datos = [];
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        $datos = json_decode($contenido, true) ?: [];
    }

    // Verificar si el email ya existe (para newsletter)
    $emailExiste = false;
    foreach ($datos as $registro) {
        if ($registro['email'] === $email && $registro['tipo'] === 'newsletter') {
            $emailExiste = true;
            break;
        }
    }

    if ($emailExiste && empty($mensaje)) {
        http_response_code(400);
        echo json_encode(['error' => 'Este email ya está suscrito']);
        exit;
    }

    // Agregar nuevo registro
    $datos[] = $nuevo_registro;

    // Guardar en archivo JSON
    if (file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        http_response_code(200);

        // Enviar email de confirmación (opcional)
        $asunto = !empty($mensaje) ? 'Hemos recibido tu mensaje' : 'Bienvenido a nuestra newsletter';
        $body = !empty($mensaje) ? 'Gracias por contactarnos. Te responderemos pronto.' : 'Gracias por suscribirte a nuestro boletín.';

        @mail($email, $asunto, $body, "From: info@mateodelossantos.com\r\nContent-Type: text/html; charset=UTF-8");

        echo json_encode([
            'success' => true,
            'mensaje' => 'Registro completado correctamente',
            'tipo' => !empty($mensaje) ? 'contacto' : 'newsletter'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar los datos']);
    }
    exit;
}
?>
