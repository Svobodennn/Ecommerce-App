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
function _sessionSet($name,$value){
    \Core\Session::setSession($name,$value);
}

function debug($data){
    echo "<pre style='width: 100%; height: 100%; background: #0a0e14; color: limegreen; z-index: 9999'>";
   print_r($data);
    echo "</pre>";
}

function _date($date)
{
    $date_en = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];
    $date_tr = [
        'Ocak',
        'Şubat',
        'Mart',
        'Nisan',
        'Mayıs',
        'Haziran',
        'Temmuz',
        'Ağustos',
        'Eylül',
        'Ekim',
        'Kasım',
        'Aralık'
    ];

    return str_replace($date_en, $date_tr, $date);
}

