<?php

function wp_article($param, $article, $db)
{
    $request = "SELECT * FROM ".$param['wp']['prefix']."posts WHERE guid = '".$article['id_article']."'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function wp_post($param, $article, $db, $postauteur)
{
    $urlsite = $article['url_site'];
    $titre = convertir_bd($article['titre']);
    $id_rubrique = $article['id_rubrique'];
    $date = $article['date_modif']; 
    $chapo = $article['chapo'];
    $postdate = $article['date'];
    $codeart = $article['id_article'];
    $content = convertir_bd($article['texte']);
    switch($article['statut'])
    {
    case 'publie':
        $status = 'publish';
        break;
    case 'poubelle':
        $status = 'trash';
        break;
    default:
        $status = 'private';
    }

    if (strlen($urlsite) > 2)
    {
        $content = "<hr><a href = \'".$urlsite."\' target = \'_blank\'>Lien externe</a><hr>".$content;
    }
    if (in_array($id_rubrique, $param['rub_page']))
        $post_type = "page";
    else
        $post_type = "post";
    print_r($postdate);
    if ($postdate == '0000-00-00 00:00:00')
        $postdate = '2000-01-01 00:00:00';
    $request = $db['wp']->prepare("
        INSERT INTO ".$param['wp']['prefix']."posts (
            post_author,
            post_title,
            post_status,
            post_name,
            guid,
            post_content,
            post_date,
            post_date_gmt,
            post_type,
            post_modified,
            post_modified_gmt,
            post_excerpt,
            to_ping,
            pinged,
            post_content_filtered)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $request->execute(array(
        $postauteur,
        $titre,
        $status,
        $titre,
        $codeart,
        $content,
        $postdate,
        $postdate,
        $post_type,
        $date,
        $date,
        $chapo,
        '',
        '',
        '')
    );
    $request = "SELECT * FROM ".$param['wp']['prefix']."posts WHERE guid = '".$article['id_article']."'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function set_article(array $article, array $param, array $db, int $postauteur)
{
    $wp_article = wp_article($param, $article, $db);
    $wp_posts_ID = $wp_article['ID'];
    $wp_post = wp_post($param, $article, $db, $postauteur);
    print_r($article);
    print_r($wp_post);
    return $wp_post['ID'];
}
