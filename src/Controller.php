<?php

/*
 * get   (urlPattern, Controller, method)
 * put   (urlPattern, Controller, method)
 * post  (urlPattern, Controller, method)
 * delete(urlPattern, Controller, method)
 * 
 * urlPattern: /x/y/z...
 * donde los literales pueden ser una constante o bien una variable si se le 
 * antepone el ":", ejemplo: /persona/:dni, luego podra ser invocada como
 * /persona/234242 y el controlador debera aceptar parametros por cada variable y 
 * podra utilizarlos, si el controlador fuera PersonaController y el metodo para tal 
 * url sea verPersona, el metodo deberia ser function verPersona($dni) { echo $dni; }
 *
 **/

/*
 * Mostrado de errores
 *
 **/

if(Config::singleton()->get("debug")){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
else {
    ini_set('display_errors', '0');     # don't show any errors...
    error_reporting(E_ALL | E_STRICT);  # ...but do log them
}

abstract class Controller
{
    static $router;
    static $entityManager = null;
    function __construct()
    {
        $this->data = array();
        $this->req   = self::$router->request();
    }

    function redirect($url)
    {
        $dir = 'Location:'. $url;
        header($dir);
        exit();
    }

    function return_json()
    {
        header('Content-Type: application/json');
        echo json_encode($this->data);
        $this->data = array();
    }

    function return_simple_var_json($var)
    {
        header('Content-Type: application/json');
        echo json_encode($var);
        $this->data = array();
    }

    protected function preCall() {
        //Default
    }
    //TODO:
    function __call($method, $args) {
        if (isset($this->$method) && is_callable($method)) {
            $this->preCall($args);
            $closure = $this->$method;
            call_user_func_array($closure, $args);
        } else {
            trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()', E_USER_ERROR);
        }
    }    

    function return_html($template)
    {
        header('Content-Type: text/html');
        $smarty = new Smarty();
        
        $config = Config::singleton();
        $root = $config->get('rootAbsolute')."/app/views/";

        /* Set config */
        $smarty->template_dir = $root;
        $smarty->compile_dir  = $root .'templates_c/';
        $smarty->config_dir   = $root .'configs/';
        $smarty->cache_dir    = $root .'cache/';

        /* Assign smarty vars */
        foreach($this->data as $name => $value)
            $smarty->assign($name, $value);
        
        $smarty->display($template);
        
        /* Clean data */
        $this->data = array();
        exit();
    }

    function return_full($template)
    {
        if($this->isAjax())
            $this->return_json();
        else
            $this->return_html($template);  
    }
    

    function isAjax()
    {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        return $isAjax;
    }
}

?>
