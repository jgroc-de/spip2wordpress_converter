<?php

function new_pdo(array $wp)
{
    $pdo = new PDO('mysql:host=' . $wp['server'] . ';dbname=' . $wp['db_name'], $wp['user'], $wp['password'], array (PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}

function articles(array $param, array $db)
{
    $request = "SELECT spip_articles.*, spip_rubriques.titre as titrerub FROM spip_articles, spip_rubriques WHERE spip_articles.id_article >= ".$param['DEB']." AND spip_articles.id_article <= ".$param['FIN']." AND spip_articles.id_rubrique = spip_rubriques.id_rubrique ORDER BY id_article ";
    $result = $db['spip']->query($request);
    return $result->fetchAll();
}

function spip2wp(array $param)
{
    $db['wp'] = new_pdo($param['wp']);
    $db['spip'] = new_pdo($param['spip']);

    // lire les articles de SPIP
    $articles = articles($param, $db);
    foreach ($articles as $article)
    {
        //echo 'spip article :';
        //print_r($article);
        // LES AUTEURS, ON SUPPOSE QU'IL N'Y EN A QU'UN PAR ARTICLE
        $postauteur = set_author($article, $param, $db);

        // MIGRER L'ARTICLE
        $idpost = set_article($article, $param, $db, $postauteur);
        
        // Le Tag pour la rubrique
        set_tag($article, $param, $db);
        
        // LES MOTS CLES
        set_keywords($article, $param, $db);

        //Gérer le logo de l'article
        set_logo($article, $param, $db, $idpost);

        //Gérer les documents et images de l'article (portfolio SPIP)
        set_docs($article, $param, $db, $idpost);
    }
}
