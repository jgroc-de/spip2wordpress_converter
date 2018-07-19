<?php

function select_docs($article, $db)
{
    $codeart = $article['id_article'];
    $request = "SELECT * FROM `spip_documents`,`spip_documents_liens`  WHERE spip_documents_liens.id_objet = ".$codeart." and spip_documents_liens.objet = 'article' and spip_documents_liens.id_document = spip_documents.id_document";
    $result = $db['spip']->query($request);
    return $result->fetchAll();
}

function search($doc, $param, $db)
{
    $filename = $doc['fichier'];
    //est-ce que le document existe (a été importé avec Add From Server)
    $recherche = substr($filename, 4);
    $l = strlen($recherche) - 4;
    $recherche = substr($recherche, 0, $l);
    $request = "SELECT * from ".$param['wp']['prefix']."postmeta WHERE meta_value LIKE '%".$recherche."%' AND meta_key = '_wp_attached_file'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function set_docs($article, $param, $db, $idpost)
{
    // tant qu'il y a un auteur, les instructions dans la boucle s'exécutent
    $premier = 0;
    $docs = select_docs($article, $db);
    foreach ($docs as $doc)
    {
        echo 'doc: ';
        print_r($doc);
        $ligneL = search($doc, $param, $db);
        $idpostL = $ligneL['post_id'];
        if ($idpostL > 0)
        {
            //si première fois, rajouter [gallery] au post
            if ($premier == 0)
            {
                $request = "UPDATE  ".$param['wp']['prefix']."posts SET post_content = CONCAT(post_content,'[gallery]') WHERE ID = ".$idpost." ";
                $db['wp']->query($request);
                $premier = 1;
            }
            $request = "UPDATE  ".$param['wp']['prefix']."posts SET post_parent = ".$idpost." WHERE ID = ".$idpostL." ";
            $db['wp']->query($request);
            $request = "INSERT INTO ".$param['wp']['prefix']."postmeta (post_id,meta_key,meta_value) VALUES (".$idpost.",'_thumbnail_id','".$idpostL."')";
            $db['wp']->query($request);
        }
    }
}
