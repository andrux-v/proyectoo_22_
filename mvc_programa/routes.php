<?php
/**
 * Sistema de Routing Centralizado
 * Define todas las rutas del sistema
 */

class Router
{
    private $routes = [];
    private $basePath;

    public function __construct($basePath = '')
    {
        $this->basePath = $basePath;
    }

    /**
     * Agregar una ruta GET
     */
    public function get($path, $controller, $action)
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    /**
     * Agregar una ruta POST
     */
    public function post($path, $controller, $action)
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    /**
     * Agregar una ruta que acepta GET y POST
     */
    public function any($path, $controller, $action)
    {
        $this->addRoute('GET', $path, $controller, $action);
        $this->addRoute('POST', $path, $controller, $action);
    }

    /**
     * Agregar ruta al array
     */
    private function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Ejecutar el router
     */
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Obtener la URL desde el parámetro GET
        $requestUri = $_GET['url'] ?? '/';
        
        // Asegurar que empiece con /
        if ($requestUri === '' || $requestUri[0] !== '/') {
            $requestUri = '/' . $requestUri;
        }

        // Buscar ruta coincidente
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $pattern = $this->convertToRegex($route['path']);
                
                if (preg_match($pattern, $requestUri, $matches)) {
                    array_shift($matches); // Remover el match completo
                    
                    // Filtrar solo los matches nombrados
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    
                    $this->dispatch($route['controller'], $route['action'], $params);
                    return;
                }
            }
        }

        // Ruta no encontrada
        $this->notFound();
    }

    /**
     * Convertir path a regex
     */
    private function convertToRegex($path)
    {
        // Convertir :param a regex
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Despachar la petición al controlador
     */
    private function dispatch($controllerName, $action, $params = [])
    {
        $controllerFile = __DIR__ . '/controller/' . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            die("Controlador no encontrado: $controllerName");
        }

        require_once $controllerFile;
        
        if (!class_exists($controllerName)) {
            die("Clase del controlador no encontrada: $controllerName");
        }

        $controller = new $controllerName();
        
        if (!method_exists($controller, $action)) {
            die("Acción no encontrada: $action en $controllerName");
        }

        // Llamar al método del controlador
        call_user_func_array([$controller, $action], $params);
    }

    /**
     * Página 404
     */
    private function notFound()
    {
        http_response_code(404);
        echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: #f3f4f6;
        }
        .error-container {
            text-align: center;
            padding: 40px;
        }
        h1 {
            font-size: 72px;
            margin: 0;
            color: #ef4444;
        }
        p {
            font-size: 18px;
            color: #6b7280;
            margin: 20px 0;
        }
        a {
            display: inline-block;
            padding: 12px 24px;
            background: #39A900;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        a:hover {
            background: #007832;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <p>Página no encontrada</p>
        <a href="/proyectoo_22_/mvc_programa/">Volver al inicio</a>
    </div>
</body>
</html>';
        exit;
    }
}

// Definir las rutas del sistema
$router = new Router();

// ============================================
// RUTA PRINCIPAL
// ============================================
$router->get('/', 'HomeController', 'index');

// ============================================
// RUTAS DE INSTRUCTOR
// ============================================
$router->get('/instructor', 'InstructorController', 'index');
$router->get('/instructor/crear', 'InstructorController', 'showCreate');
$router->post('/instructor/crear', 'InstructorController', 'create');
$router->get('/instructor/editar/:id', 'InstructorController', 'showEdit');
$router->post('/instructor/editar/:id', 'InstructorController', 'update');
$router->get('/instructor/ver/:id', 'InstructorController', 'show');
$router->post('/instructor/eliminar', 'InstructorController', 'delete');

// ============================================
// RUTAS DE PROGRAMA
// ============================================
$router->get('/programa', 'ProgramaController', 'index');
$router->get('/programa/crear', 'ProgramaController', 'showCreate');
$router->post('/programa/crear', 'ProgramaController', 'create');
$router->get('/programa/editar/:id', 'ProgramaController', 'showEdit');
$router->post('/programa/editar/:id', 'ProgramaController', 'update');
$router->get('/programa/ver/:id', 'ProgramaController', 'show');
$router->post('/programa/eliminar', 'ProgramaController', 'delete');

// ============================================
// RUTAS DE COMPETENCIA
// ============================================
$router->get('/competencia', 'CompetenciaController', 'index');
$router->get('/competencia/crear', 'CompetenciaController', 'showCreate');
$router->post('/competencia/crear', 'CompetenciaController', 'create');
$router->get('/competencia/editar/:id', 'CompetenciaController', 'showEdit');
$router->post('/competencia/editar/:id', 'CompetenciaController', 'update');
$router->get('/competencia/ver/:id', 'CompetenciaController', 'show');
$router->post('/competencia/eliminar', 'CompetenciaController', 'delete');

// ============================================
// RUTAS DE AMBIENTE
// ============================================
$router->get('/ambiente', 'AmbienteController', 'index');
$router->get('/ambiente/crear', 'AmbienteController', 'showCreate');
$router->post('/ambiente/crear', 'AmbienteController', 'create');
$router->get('/ambiente/editar/:id', 'AmbienteController', 'showEdit');
$router->post('/ambiente/editar/:id', 'AmbienteController', 'update');
$router->get('/ambiente/ver/:id', 'AmbienteController', 'show');
$router->post('/ambiente/eliminar', 'AmbienteController', 'delete');

// ============================================
// RUTAS DE COORDINACION
// ============================================
$router->get('/coordinacion', 'CoordinacionController', 'index');
$router->get('/coordinacion/crear', 'CoordinacionController', 'showCreate');
$router->post('/coordinacion/crear', 'CoordinacionController', 'create');
$router->get('/coordinacion/editar/:id', 'CoordinacionController', 'showEdit');
$router->post('/coordinacion/editar/:id', 'CoordinacionController', 'update');
$router->get('/coordinacion/ver/:id', 'CoordinacionController', 'show');
$router->post('/coordinacion/eliminar', 'CoordinacionController', 'delete');

// ============================================
// RUTAS DE FICHA
// ============================================
$router->get('/ficha', 'FichaController', 'index');
$router->get('/ficha/crear', 'FichaController', 'showCreate');
$router->post('/ficha/crear', 'FichaController', 'create');
$router->get('/ficha/editar/:id', 'FichaController', 'showEdit');
$router->post('/ficha/editar/:id', 'FichaController', 'update');
$router->get('/ficha/ver/:id', 'FichaController', 'show');
$router->post('/ficha/eliminar', 'FichaController', 'delete');

// ============================================
// RUTAS DE ASIGNACION
// ============================================
$router->get('/asignacion', 'AsignacionController', 'index');
$router->any('/asignacion/crear', 'AsignacionController', 'showCreate');
$router->any('/asignacion/editar/:id', 'AsignacionController', 'showEdit');
$router->get('/asignacion/ver/:id', 'AsignacionController', 'show');
$router->post('/asignacion/eliminar', 'AsignacionController', 'delete');

// ============================================
// RUTAS DE CENTRO DE FORMACION
// ============================================
$router->get('/centro-formacion', 'CentroFormacionController', 'index');
$router->get('/centro-formacion/crear', 'CentroFormacionController', 'showCreate');
$router->post('/centro-formacion/crear', 'CentroFormacionController', 'create');
$router->get('/centro-formacion/editar/:id', 'CentroFormacionController', 'showEdit');
$router->post('/centro-formacion/editar/:id', 'CentroFormacionController', 'update');
$router->get('/centro-formacion/ver/:id', 'CentroFormacionController', 'show');
$router->post('/centro-formacion/eliminar', 'CentroFormacionController', 'delete');

// ============================================
// RUTAS DE SEDE
// ============================================
$router->get('/sede', 'SedeController', 'index');
$router->get('/sede/crear', 'SedeController', 'showCreate');
$router->post('/sede/crear', 'SedeController', 'create');
$router->get('/sede/editar/:id', 'SedeController', 'showEdit');
$router->post('/sede/editar/:id', 'SedeController', 'update');
$router->get('/sede/ver/:id', 'SedeController', 'show');
$router->post('/sede/eliminar', 'SedeController', 'delete');

// ============================================
// RUTAS DE TITULO PROGRAMA
// ============================================
$router->get('/titulo-programa', 'TituloProgramaController', 'index');
$router->get('/titulo-programa/crear', 'TituloProgramaController', 'showCreate');
$router->post('/titulo-programa/crear', 'TituloProgramaController', 'create');
$router->get('/titulo-programa/editar/:id', 'TituloProgramaController', 'showEdit');
$router->post('/titulo-programa/editar/:id', 'TituloProgramaController', 'update');
$router->get('/titulo-programa/ver/:id', 'TituloProgramaController', 'show');
$router->post('/titulo-programa/eliminar', 'TituloProgramaController', 'delete');

// Ejecutar el router
$router->run();
?>
