<?php

include('inc/config.php');
include('inc/db.php');


header('Content-type: text/html; charset=utf-8');

error_reporting(E_ALL);


$cat_id = 1; //Shooting
$scat_id = 1; //War

$xml = simplexml_load_file('http://publishers.spilgames.com/ru/rss-3?limit=100&format=xml&category=War');
 

foreach ($xml->entries->entry as $entry) {

	try {

	$b = $db->prepare("
		INSERT INTO games (
			title,
			import_id,
			category_id,
			subcategory_id,
			description,
			player_url,
			player_width,
			player_height,
			thumbnail_small
		) VALUES (
			:entry,
			$entry->id,
			$cat_id,
			$scat_id,
			:description,
			:url,
			:width,
			:height,
			:thumb_small
		)");


	$b -> execute(array(

		':entry' => $entry->title, 
		':description' => $entry->description,
		':url' => $entry->player->url,
		':width' => $entry->player->width,
		':height' => $entry->player->height,
		':thumb_small' => $entry->thumbnails->small[0]->attributes()

		));

	} catch (PDOException $e) {
	    echo 'Connection failed: ' . $e->getMessage();
	    exit;
	}

	   
}

?>