<?php

function wp_terms(array $param, array $db, string $id_rubrique)
{
    $request = "SELECT * FROM ".$param['wp']['prefix']."terms WHERE slug = '".$id_rubrique."'";
    $result = $db['wp']->query($request);
    return $result->fetch();
}

function set_tag($article, $param, $db)
{
    //est-ce que la catégorie existe déjà?
    $titre_rubrique = convertir_bd($article['titrerub']);
    $id_rubrique = $article['id_rubrique'];

    $wp_terms = wp_terms($param, $db, $id_rubrique);
    print_r($wp_terms);
    return ;
    $wp_term = $wp_terms['term_id'];
    if ($wp_terms['slug'] == $id_rubrique)
    {
        $termid = $wp_terms['term_id'];
        // rajouter +1 au compteur
        $request = "UPDATE ".$param['wp']['prefix']."term_taxonomy SET count = count+1 WHERE term_id = '".$termid."' and taxonomy = 'category' ";
        $db['wp']->query($request);

        $request = "SELECT * FROM ".$param['wp']['prefix']."term_taxonomy WHERE term_id = '".$termid."' and taxonomy = 'category'";
        $result = $db['wp']->query($request);
        $ligneT3 = $result->fetch();
        $taxonomy = $ligneT3['term_taxonomy_id'];
    }
    else
    {
        $request = "INSERT INTO ".$param['wp']['prefix']."terms (name,slug) VALUES ('".$titre_rubrique."','".$id_rubrique."')";
        $db['wp']->query($request);
        $request = "SELECT * FROM ".$param['wp']['prefix']."terms WHERE slug = '".$id_rubrique."'";
        $result = $db['wp']->query($request);
        $ligne8 = $result->fetch();
        $termid = $ligne8['term_id'];

        //création de ".$param['wp']['prefix']."term taxonomy
        $request = "INSERT INTO ".$param['wp']['prefix']."term_taxonomy (term_id,taxonomy,count,description) VALUES (".$termid.",'category',1,'')";
        $db['wp']->query($request);
        $request = "SELECT * FROM ".$param['wp']['prefix']."term_taxonomy WHERE term_id = '".$termid."' and taxonomy = 'category'";
        $result = $db['wp']->query($request);
        $ligneT3 = $result->fetch();
        $taxonomy = $ligneT3['term_taxonomy_id'];
    }
    //créer la relation du mot clé
    if (($taxonomy > 0) and ($idpost))
    {
        $request = "INSERT INTO ".$param['wp']['prefix']."term_relationships (object_id,term_taxonomy_id) VALUES (".$idpost.",'".$taxonomy."')";
        $db['wp']->query($request);
    }
}
