<?php

function author($article, $db)
{
    $request = "SELECT * FROM spip_auteurs, spip_auteurs_liens WHERE spip_auteurs_liens.objet = 'article' AND spip_auteurs_liens.id_objet = '".$article['id_article']."' AND spip_auteurs.id_auteur = spip_auteurs_liens.id_auteur;";
    $result = $db['spip']->query($request);
    return $result->fetch();
}

function wp_user($param, $db, $author)
{
    $name = $author['nom'];
    $request = "SELECT * FROM ".$param['wp']['prefix']."users WHERE user_login = '".$name."'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function add_wp_user($author, $param, $db)
{
    $name = convertir_bd($author['nom']);
    $email = '';
    if (array_key_exists('email', $author))
        $email = $author['email'];
    //$date = "2018-07-16 10:06:50";
    $date = date('Y-m-d h:i:s');

    $request = "INSERT INTO ".$param['wp']['prefix']."users (user_login,user_nicename,user_email,display_name, user_registered) VALUES ('".$name."','".$name."','".$email."','".$name."','".$date."')";
    $db['wp']->exec($request);
}

function wp_author($author, $param, $db)
{
    $name = $author['nom'];
    echo $name;
    $request = "SELECT * FROM ".$param['wp']['prefix']."users WHERE user_login = '".$name."'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function set_author(array $article, array $param, array $db)
{
    $author = author($article, $db);
    if (!wp_user($param, $db, $author))
    {
        echo 'unknow';
        add_wp_user($author, $param, $db);
    }
    else
        echo 'exist!';
    $wp_author = wp_author($author, $param, $db);
    //echo 'user in spip :';
    //print_r($author);
    //echo 'user in wp :';
    //print_r($wp_author);
    return $wp_author['ID'];
}
