<?php
require_once './Models/UsuarioModel.php';
require_once './includes/ActiveRecord.php';
require_once './includes/router.php';

class LoginController
{
    public static function login(Router $router)
    {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new UsuarioModel($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = UsuarioModel::where('email', $auth->email);

                if ($usuario) {
                     $usuarioAux = new UsuarioModel($usuario);
                    // Verificar el password
                    if ($usuarioAux->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuarioAux->id;
                        $_SESSION['nombre'] = $usuarioAux->nombre . " " . $usuarioAux->apellido;
                        $_SESSION['email'] = $usuarioAux->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($usuarioAux->admin === "1") {
                            $_SESSION['admin'] = $usuarioAux->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /index');
                        }
                    }
                } else {
                    UsuarioModel::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = UsuarioModel::getAlertas();

        $router->render('layout', [
            'alertas' => $alertas
        ]);
    }
}
