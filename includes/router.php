<?php
class Router
{
    public array $routes = [];

    // Método para agregar una ruta GET
    public function get($url, $controllerMethod, $options = [])
    {
        $this->routes['GET'][$url] = array_merge(['controller' => $controllerMethod], $options);
    }

    // Método para agregar una ruta POST
    public function post($url, $controllerMethod, $options = [])
    {
        $this->routes['POST'][$url] = array_merge(['controller' => $controllerMethod], $options);
    }

    // Método para agregar una ruta PUT
    public function put($url, $controllerMethod, $options = [])
    {
        $this->routes['PUT'][$url] = array_merge(['controller' => $controllerMethod], $options);
    }

    // Método para agregar una ruta DELETE
    public function delete($url, $controllerMethod, $options = [])
    {
        $this->routes['DELETE'][$url] = array_merge(['controller' => $controllerMethod], $options);
    }

    // Método para ejecutar la ruta actual
    public function comprobarRutas()
    {
        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Obtener la ruta de la URL actual
        $method = $_SERVER['REQUEST_METHOD'];

        // Verificar si la ruta actual coincide con alguna ruta definida
        foreach ($this->routes[$method] as $urlPattern => $route) {
            // Dividir la URL en ruta y parámetros de consulta
            $urlParts = explode('?', $urlPattern, 2);
            $urlPath = $urlParts[0];

            if (preg_match($this->patternToRegex($urlPath), $currentUrl, $matches)) {
                // Verificar si hay middleware definido para esta ruta
                if (isset($route['middleware'])) {
                    // Instanciar el middleware
                    $middleware = new $route['middleware']();
                    // Ejecutar el middleware
                    $middleware->handle();
                }

                // Llamar al método del controlador asociado a la ruta
                $this->callControllerMethod($route['controller'], $matches);
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
            // Extraemos los datos para hacerlos disponibles en la vista
            extract($data);

            // Almacenamos el contenido de la vista en un buffer de salida
            ob_start();
            include $viewFile;
            $content = ob_get_clean();

            // Incluimos el layout y pasar el contenido de la vista como parte de los datos
            include "./views/layout.php";
        } else {
            // Si la vista no existe, mostrar un mensaje de error
            echo "Vista no encontrada";
        }
    }
}
