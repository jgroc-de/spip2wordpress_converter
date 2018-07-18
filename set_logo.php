<?php

function select_postmeta($param, $article, $db)
{
    $codeart = $article['id_article'];
    $filename = 'arton'.$codeart;
    
    $request = "SELECT * from ".$param['wp']['prefix']."postmeta WHERE meta_value LIKE '%".$filename."%' AND meta_key = '_wp_attached_file'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function insert_postmeta($param, $db, $idpost, $idpostL)
{
    $request = "INSERT INTO ".$param['wp']['prefix']."postmeta (post_id,meta_key,meta_value) VALUES (".$idpost.",'_thumbnail_id','".$idpostL."')";
    $result = $db['wp']->query($request);
}

function insert_postmeta2($param, $db, $article, $idpost)
{
    // utiliser le postmeta de la rubrique s'il existe
    $id_rubrique = $article['id_rubrique'];
    $filename = 'rubon'.$id_rubrique;

    //est-ce que le postmeta de la rubrique existe (a été importé avec Add From Server)
    $request = "SELECT * from ".$param['wp']['prefix']."postmeta WHERE meta_value LIKE '%".$filename."%' AND meta_key = '_wp_attached_file'";
    $result = $db['wp']->query($request);
    $postmetar = $result->fetch();
    $idpostL = $postmetar['post_id'];
    if ($idpostL>0)
    {
        $request = "INSERT INTO ".$param['wp']['prefix']."postmeta (post_id,meta_key,meta_value) VALUES (".$idpost.",'_thumbnail_id','".$idpostL."')";
        $db['wp']->query($request);
    }
}

function set_logo($article, $param, $db, $idpost)
{
    //est-ce que le postmeta existe (a été importé avec Add From Server)
    $postmeta = select_postmeta($param, $article,$db);
    $idpostL = $postmeta['post_id'];
    if ($idpostL > 0)
    {
        insert_postmeta($param, $db, $idpost, $idpostL);
    }
    else
    {
        insert_postmeta2($param, $db, $article, $idpost);
    }
}
