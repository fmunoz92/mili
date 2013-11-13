<?php

function primerBarra($dir)
{
  $origlen = strlen($dir);
  $i = $origlen - 1;
  while($dir[$i] !== '/')
    $i--;
  return $i;
}

function backDir($dir)
{
  return substr($dir, 0, primerBarra($dir));
}

function getDir($dir)
{
  $len = strlen($dir) - primerBarra($dir);;
  return substr($dir, -$len);
}

$config = Config::singleton();
$dir = getDir(backDir(backDir(__DIR__)));
$config->set('root', $dir);

function inrootabsolute($url)
{
    $root = backDir(backDir(__DIR__));
    if(!empty($url))
    {
      if($url[0] == "/")
        return $root.$url;
      else
        return $root."/".$url;
    }
    else
      return $root."/".$url;
}

function inroot($url)
{
    global $config;
    $root = $config->get('root');
    if(!empty($url))
    {
      if($url[0] == "/")
        return $root.$url;
      else
        return $root."/".$url;
    }
    else
      return $root."/".$url;
}


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
