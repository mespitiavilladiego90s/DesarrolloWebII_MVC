<?php

class Router
{
    public array $routes = [];

    // Método para agregar una ruta GET
    public function get($url, $controllerMethod)
    {
        $this->routes['GET'][$url] = $controllerMethod;
    }

    // Método para agregar una ruta POST
    public function post($url, $controllerMethod)
    {
        $this->routes['POST'][$url] = $controllerMethod;
    }

    // Método para agregar una ruta PUT
    public function put($url, $controllerMethod)
    {
        $this->routes['PUT'][$url] = $controllerMethod;
    }

    // Método para agregar una ruta DELETE
    public function delete($url, $controllerMethod)
    {
        $this->routes['DELETE'][$url] = $controllerMethod;
    }

    // Método para ejecutar la ruta actual
    public function comprobarRutas()
    {
        $currentUrl = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        // Verificar si la ruta actual coincide con alguna ruta definida
        foreach ($this->routes[$method] as $urlPattern => $controllerMethod) {
            if (preg_match($this->patternToRegex($urlPattern), $currentUrl, $matches)) {
                // Llamar al método del controlador asociado a la ruta
                $this->callControllerMethod($controllerMethod, $matches);
                return;
            }
        }

        // Si no se encuentra ninguna ruta coincidente
        echo "Página No Encontrada o Ruta no válida";
    }

    // Método para convertir un patrón de ruta en una expresión regular
    private function patternToRegex($pattern)
    {
        return '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $pattern) . '/?$#';
    }

    // Método para llamar al método del controlador
    private function callControllerMethod($controllerMethod, $matches)
    {
        // Separamos el nombre del controlador y el método
        [$controllerName, $methodName] = explode('@', $controllerMethod);

        // Incluimos el archivo del controlador
        require_once "Controllers/$controllerName.php";

        // Creamos una instancia del controlador
        $controller = new $controllerName();

        // Llamarmos al método del controlador, pasando los parámetros coincidentes
        $controller->$methodName($this, ...array_slice($matches, 1));
    }

    // Método para renderizar una vista
    public function render($view, $data = [])
    {
        // Ruta completa del archivo de la vista
        $viewFile = "./views/$view.php";

        // Verificamos si el archivo de la vista existe
        if (file_exists($viewFile)) {
            // Extraer los datos para hacerlos disponibles en la vista
            extract($data);

            // Incluimos el archivo de la vista
            include $viewFile;
        } else {
            // Si la vista no existe, mostrar un mensaje de error
            echo "Vista no encontrada";
        }
    }
}
