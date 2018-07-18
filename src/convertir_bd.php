<?php

// conversion pour défaut de compatibilité UTF-8
function convertir_bd($vartxt)
{
    $result = "";
    $content = trim($vartxt);
    $content = preg_replace('#&nbsp;#', '', $content);
    $content = preg_replace('/\[([^->]*?)\]/is','<em>($1)</em>',$content);
    $content = str_replace("{{","<strong>",$content);
    $content = str_replace("}}","</strong>",$content);
    $content = str_replace("{","<em>",$content);
    $content = str_replace("}","</em>",$content);
    $content = str_replace("[[","(<em>",$content);
    $content = str_replace("]]","</em>)",$content);
    $content = str_replace("<quote>","<blockquote>",$content);
    $content = str_replace("</quote>","</blockquote>",$content);
    $content = preg_replace('/\[(.*?)->(.*?)\]/is','<a href = "$2">$1</a>',$content);

    // à modifier selon le codage de vos bases de données
    for ($i = 0; $i <= strlen($content); $i++) {
        $car1 = substr($content,$i,1);
        if (ord($car1)==192) $car1="À";
        if (ord($car1)==193) $car1="Á";
        if (ord($car1)==194) $car1="Â";
        if (ord($car1)==195) $car1="Ã";
        if (ord($car1)==196) $car1="Ä";
        if (ord($car1)==198) $car1="Æ";
        if (ord($car1)==199) $car1="Ç";
        if (ord($car1)==200) $car1="È";
        if (ord($car1)==201) $car1="É";
        if (ord($car1)==202) $car1="Ê";
        if (ord($car1)==203) $car1="Ë";
        if (ord($car1)==206) $car1="Î";
        if (ord($car1)==207) $car1="Ï";
        if (ord($car1)==212) $car1="Ô";
        if (ord($car1)==214) $car1="Ö";
        if (ord($car1)==219) $car1="Û";
        if (ord($car1)==220) $car1="Ü";
        if (ord($car1)==224) $car1="à";
        if (ord($car1)==225) $car1="á";
        if (ord($car1)==226) $car1="â";
        if (ord($car1)==228) $car1="ä";
        if (ord($car1)==230) $car1="æ";
        if (ord($car1)==231) $car1="ç";
        if (ord($car1)==232) $car1="è";
        if (ord($car1)==233) $car1="é";
        if (ord($car1)==234) $car1="ê";
        if (ord($car1)==235) $car1="ë";
        if (ord($car1)==238) $car1="î";
        if (ord($car1)==239) $car1="ï";
        if (ord($car1)==242) $car1="ò";
        if (ord($car1)==243) $car1="ó";
        if (ord($car1)==244) $car1="ô";
        if (ord($car1)==246) $car1="ö";
        if (ord($car1)==249) $car1="ù";
        if (ord($car1)==250) $car1="ú";
        if (ord($car1)==251) $car1="û";
        if (ord($car1)==252) $car1="ü";
        if ($car1=='"') $car1='\"';
        if ($car1=="'") $car1="\'";
        $result=$result.$car1;
    }
    return $content;
}
