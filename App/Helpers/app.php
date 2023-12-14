<?php
function asset($assetName){
    return URL.'public/'.$assetName;
}
function redirect($url){
    header('Location:'.URL.$url);
}

function _link($url = null) {
    return URL.$url;
}

function _session($name){
    return \Core\Session::getSession($name);
}

function debug($data){
    echo "<pre style='width: 100%; height: 100%; background: #0a0e14; color: limegreen; z-index: 9999'>";
   print_r($data);
    echo "</pre>";
}