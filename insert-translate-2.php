<?php

include('inc/config.php');
include('inc/db.php');
include('inc/func.php');

include('feed.php');

header('Content-type: text/html; charset=utf-8');

error_reporting(E_ALL);


/*
/  Переменные 
*/

// Категория
//$cat_id = 2; //Best Games

// XML
//$xml_path = 'http://publishers.softgames.de/categories/best_games.xml';

/*
/  ---------------------- 
*/


// Читаем XML
//$xml = simplexml_load_file($xml_path);


$k=0;

// Запускаем цикл обработки и сохранения в БД и в FS
foreach ($fgd_games as $item) {


	// Картинку берем thumb_filename
	$filePath = filePath($item['thumb_filename']);

	// Уникальное имя картинки-файла
	$filename = md5(date(time())).$k++.'.'.$filePath["extension"];

	// Сохраняем картинку в FS
	$image = file_get_contents($item['thumb_filename']);
	file_put_contents("img/".$filename, $image);

	// Картинка может не успеть скачаться
	sleep(1);


	// Автозамена названий категорий

	$cat_before = array("Action","Arcade","Sports","Puzzle","Other","Platform","Gadgets","Strategy","Shooter","Fighting","Adventure","Defense","Driving","Multiplayer","Boys Games","Rhythm","Casino","RPG");
	$cat_after   = array("3", "4", "5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20");

	$cat_id = str_replace($cat_before, $cat_after, $item['genres']);


	//Сохраняем в БД данные 
	$b = $db->prepare("
		INSERT INTO games (
			import_id,
			title,
			description,
			player_url,
			thumbnail_large,
			category_id
		) VALUES (
			:game_id,
			:item,
			:description,
			:url,
			:filename,
			:cat_id
		)");


	$b -> execute(array(
		':game_id' => $item['game_id'],
		':item' => $item['title'], 
		':description' => $item['full_description'],
		':url' => $item['swf_filename'],
		':filename' => $filename,
		':cat_id' => $cat_id

		));


	   
}


?>