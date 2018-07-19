<?php

function select_img($param, $db, $img)
{
    $match = array();
    if(preg_match("/[0-9]+/", $img, $match))
    {
        $request = "SELECT * FROM spip_documents WHERE id_document = ".$match['0'];
        $result = $db['spip']->query($request);
        return $result->fetch();
    }
    return false;
}

function create_dir($data, $param, $article)
{
    $postdate = $article['date'];
    if ($postdate == '0000-00-00 00:00:00')
    {
        $postdate = '2000-01-01 00:00:00';
    }
    $date = explode('-', $postdate);
    $dir = '/wp-content/uploads/'.$date[0].'/'.$date[1].'/';
    print_r($dir);
    mkdir('..'.$dir, 0744, true);
    $fichiers = explode('/', $data['fichier']);
    $fichier = array_pop($fichiers);
    if (!copy('../IMG/' . $data['fichier'], '..' . $dir.$fichier))
        echo 'copy fail!';
    return $param['url'] . $dir . $fichier;
}

function replace($param, $article, $img, $data)
{
    $content = $article['texte'];
    $src = create_dir($data, $param, $article);
    $replacement = '<img src="' . $src . '" alt="" width="' . $data['largeur'] . '" height="' . $data['hauteur'] . '" class="alignnone size-full wp-image-5" />';
    $article['texte'] = str_replace($img, $replacement, $content);
    $src = str_replace($param['url'].'/wp-content/uploads/', '', $src);
    $article['img'][] = [$src, $data];
    return $article;
}

function set_image(array $article, array $param, array $db)
{
    $amtches = array();
    print_r($article['titre']);
    preg_match_all("/<img[0-9]*\|[a-z]*>/",$article['texte'], $matches);
    $article['img'] = array();
    foreach($matches as $imgs)
    {
        print_r($imgs);
        foreach($imgs as $img)
        {
            if($data = select_img($param, $db, $img))
            {
                print_r($data);
                $article = replace($param, $article, $img, $data);
            }
        }
    }
    return $article;
}
