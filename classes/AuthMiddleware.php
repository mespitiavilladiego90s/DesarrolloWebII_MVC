<?php
class AuthMiddleware {
    public function handle() {
        session_start();
        if (!isset($_SESSION['token'])) {
            // El token no está presente en la sesión, redirige al usuario a la página de inicio de sesión
            header('Location: /login');
            exit;
        }

        // Si el token está presente, decodifícalo y verifica su validez
        $jwt = new JWT();
        $payload = $jwt->decode($_SESSION['token']);

        if (!$payload) {
            // Si el token no es válido, redirige al usuario a la página de inicio de sesión
            header('Location: /login');
            exit;
        }

        // Verifica si el usuario tiene el rol de 'Admin' para acceder a la URL protegida
        if ($payload['rol'] !== 'Admin') {
            // Si el usuario no tiene los permisos necesarios, redirige a una página de acceso denegado o muestra un mensaje de error
            echo "Acceso denegado. No tienes los permisos necesarios para acceder a esta página.";
            exit;
        }

        // Si el usuario tiene un token válido y los permisos necesarios, permite el acceso a la URL protegida
    }
}