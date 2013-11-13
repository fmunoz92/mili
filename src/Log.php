<?php 

class Log {
    static function msg($msj) {
        if(is_array($msj))
            $msj = serialize($msj);

        if(Config::singleton()->get("debug"))
            echo "<strong>LOG:</strong> ". $msj . "<br>\n";
    }


    static function console($name, $data = NULL, $jsEval = FALSE)
    {
        if(!Config::singleton()->get("debug") || !$name)
            return false;
        if(is_array($name))
            $name = serialize($name);
        $name = addslashes($name);

$js = <<<JSCODE
\n<script>
     if (! window.console) console = {};
     console.log = console.log || function(name, data){};
     console.log('$name');
     console.log('------------------------------------------');
</script>
JSCODE;
 
          echo $js;
    }

    static function end() {
        if(Config::singleton()->get("debug"))
            die();
    }
}

?>