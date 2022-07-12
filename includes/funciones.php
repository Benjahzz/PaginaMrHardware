<?php

define('TEMPLATES_URL', __DIR__ .  '/templates');

function estaAutenticado(){

    session_start();


    if(!$_SESSION["login"]){
        header("location: /");
    }
}
function debugear($informacion){
    echo "<pre>";
    var_dump($informacion);
    echo "</pre>";
}


// sanitizar el html
function s($html) :string {
     $s = htmlspecialchars($html);
     return $s;
}

function buscarProductoArray($array,$id){
    $i = 0;
    foreach($array as $value){
        if($value == $id){
            return $i;
        }
        $i++;
    }
    return null;
}
