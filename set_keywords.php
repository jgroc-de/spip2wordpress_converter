<?php

function links(array $db, $article)
{
    $codeart = $article['id_article'];
    $request = "SELECT * FROM spip_mots_liens, spip_mots WHERE spip_mots_liens.objet = 'article' AND spip_mots_liens.id_objet = '".$codeart."' AND spip_mots.id_mot = spip_mots_liens.id_mot";
    $result = $db['spip']->query($request);
    return $result->fetchAll();
}

function wp_terms2($link, $db, $param)
{
    $id_rubrique = $link['id_mot'];
    $request = "SELECT * FROM ".$param['wp']['prefix']."terms WHERE slug = '".$id_rubrique."'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function up_term_taxonomy($wp_terms, $param, $db)
{
    $termid = $wp_terms['term_id'];
    // rajouter +1 au compteur
    $request = "UPDATE ".$param['wp']['prefix']."term_taxonomy SET count = count+1 WHERE term_id = '".$termid."' and taxonomy = 'post_tag' ";
    $db['wp']->query($request);
    
    $request = "SELECT * FROM ".$param['wp']['prefix']."term_taxonomy WHERE term_id = '".$termid."' and taxonomy = 'post_tag'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function term_relatiorships($param, $idpost, $db, $taxonomy)
{
    $request = "INSERT INTO ".$param['wp']['prefix']."term_relationships (object_id,term_taxonomy_id) VALUES (".$idpost.",'".$taxonomy."')";
    $db['wp']->query($request);
}

function terms($param, $db, $id_rubrique, $titre_rubrique)
{
    $request = "INSERT INTO ".$param['wp']['prefix']."terms (name,slug) VALUES ('".$titre_rubrique."','".$id_rubrique."')";
    $result = $db['wp']->query($request);
    $request = "SELECT * FROM ".$param['wp']['prefix']."terms WHERE slug = '".$id_rubrique."'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function term_taxonomy($param, $db, $term)
{
    print_r($term);
    $termid = $term['term_id'];
    $request = "INSERT INTO ".$param['wp']['prefix']."term_taxonomy (term_id, taxonomy, count, description) VALUES (".$termid.",'post_tag',1, '')";
    $result = $db['wp']->query($request);
    $request = "SELECT * FROM ".$param['wp']['prefix']."term_taxonomy WHERE term_id = '".$termid."' and taxonomy = 'post_tag'";
    $result = $db['wp']->query($request);
    $ligneT3 = $result->fetch();
}

function set_keywords(array $article, array $param, array $db)
{
    $links = links($db, $article);
    print_r($links);
    foreach ($links as $link)
    {
        //est-ce que le mot existe déjà?
        $id_rubrique = $link['id_mot'];
        $titre_rubrique = convertir_bd($link['titre']);
        $wp_terms = wp_terms2($link, $db, $param);
        $wp_term = $wp_terms['term_id'];
        if ($wp_terms['slug'] == $id_rubrique)
        {
            $term_tax = up_term_taxonomy($wp_terms, $param, $db);
            $taxonomy = $term_tax['term_taxonomy_id'];
        }
        else
        {
            $term = terms($param, $db, $id_rubrique, $titre_rubrique);
            //création de ".$param['wp']['prefix']."term taxonomy
            $term_tax = term_taxonomy($param, $db, $term);
            $taxonomy = $term_tax['term_taxonomy_id'];
        }

        //créer la relation du mot clé
        if (($taxonomy > 0) and $idpost)
        {
            term_relatiorships($param, $idpost, $db, $taxonomy);
        }
    }
}
