<?php

/*-----------------------------------------------------------------------------------*/
/*	Adding items to template
/*-----------------------------------------------------------------------------------*/

function addListItems($array){
    $str='';
    $array = explode("|", $array);
    foreach ($array as $p){
        $str.='<li>'.$p.'</li>';
    }
    return $str;
}

?>