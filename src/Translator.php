<?php
/*
// Translate string "Fox=FOX %s %s"
$e = new Example();
// Translated string with substituted arguments
$s = printf($e->__('Fox'),'arg 1','arg 2');
*/
class Languages {
    const SPANISH = "spanish";
    const ENGLISH = "english";
}
class Translator {
    private static $instance;

    private function __construct() {
    }

    public static function getInstance() {
        if(is_null(self::$instance))
            self::$instance = new Translator();
        return self::$instance;
    }

    public static function load() {
    }

    private $lang = array();

    private function findString($str,$lang) {
        if (array_key_exists($str, $this->lang[$lang])) {
            return $this->lang[$lang][$str];
        }
        return $str;
    }

    private function splitStrings($str) {
        return explode('=',trim($str));
    }

    public function get($str, $lang) {
        if (!array_key_exists($lang, $this->lang)) {

            $config = Config::singleton();
            if (file_exists($config->get("translateDir").$lang.'.txt')) {
                $strings = array_map(array($this,'splitStrings'),file($config->get("translateDir").$lang.'.txt'));

                foreach ($strings as $k => $v) {
                    $this->lang[$lang][$v[0]] = $v[1];
                }
                
                return $this->findString($str, $lang);
            }
            else {
                return $str;
            }
        }
        else {
            return $this->findString($str, $lang);
        }
    }
}


function __() {
    if (func_num_args() < 1) {
        return false;
    }
    $translator = Translator::getInstance();
    $config = Config::singleton();

    $lang = is_null(Session::get("lang")) ? $config->get("defaultLang") : Session::get("lang");
    $args = func_get_args();
    $str = array_shift($args);

    if (count($args)) {
        return vsprintf($translator->get($str, $lang), $args);
    }
    else {
        return $translator->get($str, $lang);
    }
}

?>