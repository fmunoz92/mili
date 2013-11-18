<?php

include "../../vendor/autoload.php";

$config = Config::singleton();
$config->set("translateDir", "");
$config->set("defaultLang", Languages::SPANISH);

echo __("Hola","Facundo");
echo "<br>";
echo __("Chau","Facundo");
echo "<br>";
echo __("Market Publication");
echo "<br>";

Session::set("lang", Languages::ENGLISH);

echo __("Hola","Facundo");
echo "<br>";
echo __("Chau","Facundo");
echo "<br>";
echo __("Market Publication");
echo "<br>";

?>