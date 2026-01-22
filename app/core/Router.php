<?php

namespace App\Core;

class Router
{
    /**
     * array for storage the url
     */
    private static array $routes = [];
    /**
    * This is a core part of the Singleton pattern to ensure only one
    */ 
    private static  $router;

    private function __construct() {}

    /***
     * for make sure there is a connection.
     */
    public static function getRouter(): Router
    {
        if (!isset(self::$router)) {
            self::$router = new self();
        }
        return self::$router;
    }

    private function register(string $route, string $method, array|callable $action)
    {
        $route = trim($route, '/');

        /**
         * Assign action to the passed route
         */
        self::$routes[$method][$route] = $action;
    }

    public function get(string $route, array|callable $action)
    {
        $this->register($route, 'GET', $action);
    }

    public function post(string $route, array|callable $action)
    {
        $this->register($route, 'POST', $action);
    }

    public function put(string $route, array|callable $action)
    {
        $this->register($route, 'PUT', $action);
    }

    public function delete(string $route, array|callable $action)
    {
        $this->register($route, 'DELETE', $action);
    }

    /**
     *  Resolve the current request to the corresponding route action.
     */

    public function dispatch()
    {
        // 1. Get the full path (e.g., /Project/public/annonces)
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // 2. Get the path to index.php (e.g., /Project/public/index.php)
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    
    // 3. Remove the base path from the URI to get only the route
    $requestRoute = str_replace($scriptName, '', $uri);
    $requestRoute = trim($requestRoute, '/');

    // Default to empty string for root
    if ($requestRoute === '') {
        $requestRoute = '';
    }

    // DEBUG: Uncomment the line below to see what the router is looking for
    // die("Router is looking for: " . $requestRoute);

    $method = $_SERVER['REQUEST_METHOD'];
    if (!isset(self::$routes[$method])) {
        return $this->abort('Method not allowed', 405);
    }

    $routes = self::$routes[$method];
   

        foreach ($routes as $route => $action) {
            $routeRegex = preg_replace_callback('/{\w+(:([^}]+))?}/', function ($matches) {
                return isset($matches[2]) ? '(' . $matches[2] . ')' : '([a-zA-Z0-9_-]+)';
            }, $route);

            /**
             * Add the start and end delimiters.
             */
            $routeRegex = '@^' . $routeRegex . '$@';

            /**
             * Check if the requested route matches the current route pattern.
             */
            if (preg_match($routeRegex, $requestRoute, $matches)) {
                array_shift($matches);

                /**
                 * Get all user requested path params values after removing the first matches.  
                 */
                $routeParamsValues = $matches;

                /**
                 * Find all route params names from route and save in $routeParamsNames
                 */
                $routeParamsNames = [];
                if (preg_match_all('/{(\w+)(:[^}]+)?}/', $route, $matches)) {
                    $routeParamsNames = $matches[1];
                }

                /**
                 *  Combine between route parameter names and user provided parameter values.
                 */
                $routeParams = array_combine($routeParamsNames, $routeParamsValues);

                return $this->resolveAction($action, $routeParams);
            }
        }
        return $this->abort('404 Page not found');
    }

    private function resolveAction($action, $routeParams)
    {
        if (is_callable($action)) {
            return call_user_func_array($action, $routeParams);
        } else if (is_array($action)) {
            $controllerClass = $action[0];
            $method = $action[1];

            if (!class_exists($controllerClass) || !is_subclass_of($controllerClass, 'App\\Core\\Controller')) {
                throw new \Exception('Invalid controller class');
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                throw new \Exception('Method not found');
            }

            return call_user_func_array([$controller, $method], $routeParams);
        }
    }
    private function abort(string $message, int $code = 404)
    {
        http_response_code($code);
        throw new \Exception($message, $code);
    }
}
