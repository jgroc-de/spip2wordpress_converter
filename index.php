<?php
// ---------------------------------------------------------------------------------
// Migration d'un site SPIP vers WP par Jonathan Bersot (c) 2012-10 V 1.0
//
// Pour adapter cette routine à vos besoins, modifier les variables ci dessous.
//
// Source : http://www.wordpress-fr.net/support/sujet-73045-migration-spip-vers-word-press
//
// Modifications :
// - 2014-09-06 Mathieu MD : Compatible Spip 3.0.17 (et testé vers Wordpress 4.0)
// - 2018-07-19 jgroc-de : Compatible Spip 3.0.X (et testé vers Wordpress 4.9)
//
// ----------------------------------------------------------------------------------
date_default_timezone_set('UTC');
define('ABSPATH', '/home/user/wp/wordpress');
define('WPINC', 'wp-includes');

require './src/convertir_bd.php';
require './src/set_author.php';
require './src/set_article.php';
require './src/set_docs.php';
require './src/set_image.php';
require './src/set_keywords.php';
require './src/set_logo.php';
require './src/set_postmeta.php';
require './src/set_tag.php';
require './spip2wp.php';

/*require '../wp-includes/functions.php';
require '../wp-includes/post.php';
require '../wp-includes/plugin.php';
require '../wp-includes/load.php';
require '../wp-includes/cache.php';
require '../wp-admin/includes/image.php';*/

// Variables de paramètrage général
$param = array();
$param['spip'] = array(
    'server' => "localhost",
    'db_name' => "oweia_10",
    'user' => "root",
    'password' => "root00");

$param['wp'] = array(
    'server' => "localhost",
    'db_name' => "wp_oweia_10",
    'user' => "root",
    'password' => "root00",
    'prefix' => "wp_");

//url du site final
$param['url'] = 'http://localhost:8080';
//liste des rubriques à exclure de l'importation
//$param['rub_exclure'] = "42";
$param['rub_exclure'] = "";

//liste des rubriques dont les articles seront des pages sous WP et non des post
//$param_rub_page            = array("42", "123");
$param['rub_page'] = array();

// récupération des ID d'Articles (SPIP) à traiter : début et fin
$param['DEB'] = 0;
$param['FIN'] = 900;

spip2wp($param);
