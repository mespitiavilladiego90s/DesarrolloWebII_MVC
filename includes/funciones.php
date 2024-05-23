<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}



// Función que revisa que el usuario este autenticado
function checkPerm($role = null, $boolRoled = false): bool {
    $roleMap = [
        'Default' => 1,
        'Admin' => 2,
        'Meeting Creator' => 3,
        'Meeting Assistant' => 4,
        'Inform Manager' => 5
    ];

    // Si no recibimos un rol válido, verificamos si la sesión tiene configurado un rol
    if ($role === null) {
        if (!isset($_SESSION['rol'])) {
            if ($boolRoled) {
                return false;
            }
            header('Location: /login');
            exit;
        }
    } else {
        // Verificamos si el rol existe en el mapa
        if (!isset($roleMap[$role])) {
            // Rol no reconocido, redirigimos al login
            if ($boolRoled) {
                return false;
            }
            header('Location: /login');
            exit;
        }

        // Obtenemos el valor numérico del rol
        $roleValue = $roleMap[$role];

        // Verificamos si la sesión tiene el rol correcto
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] != $roleValue) {
            if ($boolRoled) {
                return false;
            }
            header('Location: /index');
            exit;
        }
    }

    // Si $boolRoled es true, retornamos true ya que el rol es correcto
    if ($boolRoled) {
        return true;
    }

    // Si no se necesita retornar un booleano, simplemente retornamos void
    return true;
}

