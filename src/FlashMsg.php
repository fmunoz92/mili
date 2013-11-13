<?php

class MsgType {
    const Warning = 2;    
    const Error = 3;    
    const Successful = 4;    
}

class FlashMsg {

    static function add($type, $msg) {
        $lastMsg = Session::get("FlashMsg");
        $msj = array(array("type" => $type, "msg" => $msg));

        if(!is_null($lastMsg))
            $msj = array_merge($lastMsg, $msj);

        Session::set("FlashMsg", $msj);
    }

    static function get($type) {
        $result = array();
        $lastMsg = Session::get("FlashMsg");
        $pending = array();
        if(!is_null($lastMsg)) {
            foreach ($lastMsg as $key => $value) {
                if($value["type"] === $type)
                    $result[] = $value["msg"];
                else
                    $pending[] = $value;
            }
            Session::set("FlashMsg", $pending);   
        }
        return $result;
    }

    static function clear($type = null) {
        $result = array();
        $lastMsg = Session::get("FlashMsg");
        $pending = array();
        if(!is_null($lastMsg)) {
            foreach ($las as $key => $value) {
                if($value["type"] !== $type)
                    $pending[] = $value;
            }
            Session::set("FlashMsg", $pending);   
        }
    }
}
?>