<?php

$config = Config::singleton();
$dir = dirname(dirname(dirname(dirname(__DIR__))));

$base = dirname($dir);
$root = str_replace($base, '', $dir);
$config->set('rootAbsolute', $dir);
$config->set('root', $root);

class Router extends \Slim\Slim
{
    static function url($url)
    {
        $root = Config::singleton()->get('root')."/index.php";
        if($url[0] == "/")
          return $root.$url;
        else
          return $root."/".$url;
    }
    static function absUrl($url)
    {
        $root = Config::singleton()->get('url');
        if($url[0] == "/")
          return $root.$url;
        else
          return $root."/".$url;
    }

    static function assets($url)
    {
        $root = Config::singleton()->get('root')."/assets";
        if($url[0] == "/")
          return $root.$url;
        else
          return $root."/".$url;
    }

    function get($pattern, $controller, $method, $filter = null)
    {
        if(!is_callable($filter))
            $filter = function() {};
        return parent::get($pattern, $filter, function() use ($controller, $method) 
        {
            $instance = new $controller();
            $args = func_get_args();
            call_user_func_array(array($instance, $method), $args);
        });
    }

    function put($pattern, $controller, $method, $filter = null)
    {
        if(!is_callable($filter))
            $filter = function() {};
        return parent::put($pattern, $filter, function() use ($controller, $method) 
        {
            $instance = new $controller();
            $args = func_get_args();
            call_user_func_array(array($instance, $method), $args);
        });
    }

    function pageNotFound($controller, $method)
    {
        return parent::notFound(function () use ($controller, $method) 
        {
            $instance = new $controller();
            call_user_func_array(array($instance, $method), array());
        });
    }

    function post($pattern, $controller, $method, $filter = null)
    {
        if(!is_callable($filter))
            $filter = function() {};
        return parent::post($pattern, $filter, function() use ($controller, $method) 
        {
            $instance = new $controller();
            $args = func_get_args();
            call_user_func_array(array($instance, $method), $args);
        });
    }

    function delete($pattern, $controller, $method, $filter = null)
    {
        if(!is_callable($filter))
            $filter = function() {};
        return parent::delete($pattern, $filter, function() use ($controller, $method) 
        {
            $instance = new $controller();
            $args = func_get_args();
            call_user_func_array(array($instance, $method), $args);
        });
    }
};

?>
