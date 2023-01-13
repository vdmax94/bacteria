<?php

namespace Core;

class Router
{
    protected array $routes = [];
    protected array $route = [];

    protected array $params = [];

    public function add($regexp, $route = []){
        $this->routes[$regexp] = $route;
    }

/*    public function getRoutes():array
    {
        return $this->routes;
    }
    public function getRoute(): array
    {
        return $this->route;
    }*/


    public function dispatch(string $url){
        $url = $this->removeQueryVariables($url);
        $url = trim($url,"/");

        if($this->match($url)){

            $controller = $this->getController();
            $action = $this->getAction($controller);
            if ($controller->before($action)) {
                call_user_func_array([$controller, $action], $this->params);
                $controller->after($action);
            } else {
                dd('error');
            }
        }else{
            throw new \Exception('Page is not found', 404);
        }

    }

    public function match(string $url):bool
    {
       // d($this->routes);
        foreach ($this->routes as $regexp => $routeParams){
            if(preg_match("#{$regexp}#", $url, $matches)){
                if(!($route = $this->removeExtraArrayElement($matches))){
                    $this->route = $routeParams;
                    return true;
                }

                //d($route);

                if(empty($route['action']) && empty($route['id'])){
                    unset($route['id']);
                    $route['action'] = 'index';
                }elseif(isset($route['supertaxon'])){
                    $route['action'] = 'index';
                }elseif(empty($route['action'])){
                    $route['action'] = 'show';
                }elseif($route['id'] == 0){
                    unset($route['id']);
                }

                //d($route);
                $this->route = array_merge($route, $routeParams);

                $this->params = $this->route;

                return true;
            }
        }

        return false;
    }

    protected function getController(): Controller
    {
        $nameController = $this->upperFirstLiteral($this->route['controller']);
        $controller = "App\Controllers\\".$nameController."Controller";
        if(!class_exists($controller)){
            throw new \Exception("Controller {$controller} is not found!");
        }
        unset($this->params['controller']);
        //d($this->params);
        return new $controller;
    }

    protected function getAction(Controller $controller): string
    {
        $nameAction = $this->route['action'];
        if(!method_exists($controller,$nameAction)){
            throw new \Exception("Action {$nameAction} in ". $controller::class." is not found!");
        }
        unset($this->params['action']);
        //d($this->params);

        return $nameAction;
    }

    protected function removeQueryVariables(string $url){
        return preg_replace('/([\w\/]+)\?([\w\/=\d]+)/i','$1', $url);
    }

    protected function removeExtraArrayElement(array $matches): mixed
    {
        foreach ($matches as $key => $value){
            if(is_string($key)){
                $route[$key] = $this->intId($key, $value);
            }
        }
        if(isset($route)){
            return $route;
        }

        return null;

    }

    protected function intId ($key, $value): mixed
    {
        if($key == 'id'){
            return (int)$value;
        }else{
            return $value;
        }
    }

    protected function upperFirstLiteral($nameController):string
    {
        return ucwords($nameController);
    }

}