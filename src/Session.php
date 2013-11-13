<?php

class Session {
    static function init() {
		session_start();
    }

    static function destroy() {
        unset($_SESSION);
        session_destroy();
    }

    static function get($name) {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    static function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    static function del($name) {
        if(isset($_SESSION[$name]))
            unset($_SESSION[$name]);
    }
}



?>