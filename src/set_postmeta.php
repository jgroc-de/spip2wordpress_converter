<?php

function attached_file($param, $db, $idpost, $img)
{
    $request = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?,?,?)";
    $result = $db['wp']->prepare($request);
    return $result->execute(array(
        $idpost,
        '_wp_attached_file',
        $img[0]));
}

function attachement_metadata($param, $db, $idpost, $img)
{
    $data = $img[1];
    $url = $img[0];
    $url2 = explode('/', $url);
    $url2 = array_pop($url2);
    $metadata = 'a:5:{
        s:5:"width";i:'.$data['largeur'].';
        s:6:"height";i:'.$data['hauteur'].';
        s:4:"file";s:28:"'.$url.'";
        s:5:"sizes";
        a:4:{
            s:9:"thumbnail";
            a:4:{
                s:4:"file";s:28:"";
                s:5:"width";i:;
                s:6:"height";i:;
                s:9:"mime-type";s:10:"image/jpeg";
                }
            s:6:"medium";
            a:4:{
                s:4:"file";s:28:;
                s:5:"width";i:;
                s:6:"height";i:;
                s:9:"mime-type";s:10:"image/jpeg";
                }
            s:12:"medium_large";
            a:4:{
                s:4:"file";s:28:"";
                s:5:"width";i:;
                s:6:"height";i:;
                s:9:"mime-type";s:10:"image/jpeg";
                }
            s:32:"twentyseventeen-thumbnail-avatar";
            a:4:{
                s:4:"file";
                s:28:"";
                s:5:"width";i:;
                s:6:"height";i:;
                s:9:"mime-type";
                s:10:"image/jpeg";
                }
            }
        s:10:"image_meta";
        a:12:{
            s:8:"aperture";s:1:"0";
            s:6:"credit";s:0:"";
            s:6:"camera";s:0:"";
            s:7:"caption";s:0:"";
            s:17:"created_timestamp";s:1:"0";
            s:9:"copyright";s:0:"";
            s:12:"focal_length";s:1:"0";
            s:3:"iso";s:1:"0";
            s:13:"shutter_speed";s:1:"0";
            s:5:"title";s:0:"";
            s:11:"orientation";s:1:"0";
            s:8:"keywords";a:0:{}
            }
    }';
    $metadata = str_replace(' ', '', $metadata);
    $metadata = str_replace('\n', '', $metadata);
    $request = "INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?,?,?)";
    $result = $db['wp']->prepare($request);
    return $result->execute(array(
        $idpost,
        '_wp_attachement_metadata',
        $metadata)
    );
}

function set_postmeta(array $article, array $param, array $db, int $idpost)
{
    foreach ($article['img'] as $img)
    {
        //attached_file($param, $db, $idpost, $img);
        //attachement_metadata($param, $db, $idpost, $img);
        
        echo ' ----------- post meta -------';
        print_r($article['img']);
        //$filename should be the path to a file in the upload directory.
        $filename = ABSPATH.'wp_content/uploads/'.$article['img'][0];

        //The ID of the post this attachment is for.
        $parent_post_id = 37;

        //Check the type of file. We'll use this as the 'post_mime_type'.
        $filetype = wp_check_filetype( basename( $filename ), null );

        //Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();

        //Prepare an array of post data for the attachment.
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        //Insert the attachment.
        $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

        //Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        //Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        set_post_thumbnail( $parent_post_id, $attach_id );
    }
    return $article;
}

