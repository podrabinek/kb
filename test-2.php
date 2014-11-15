<?php

include('config.php');

header('Content-type: text/html; charset=utf-8');

error_reporting(E_ALL);


/*
/  Переменные 
*/

// Категория
$cat_id = 2; //Best Games

// XML
$xml_path = 'http://publishers.softgames.de/categories/best_games.xml';

/*
/  ---------------------- 
*/


// Расчленяем УРЛ картинки
function filePath($filePath) {

	$fileParts = pathinfo($filePath);

	if(!isset($fileParts['filename'])) {
		$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
	}
	  
	return $fileParts;
}

// Читаем XML
$xml = simplexml_load_file($xml_path);

// Соединяемся с БД
try {
    $a = new PDO(DSN, USER, PASS);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}



$k=0;

// Запускаем цикл обработки и сохранения в БД и в FS
foreach ($xml->item as $item) {

	// Картинку берем <thumbBig>
	$filePath = filePath($item->thumbBig);

	// Уникальное имя картинки-файла
	$filename = md5(date(time())).$k++.'.'.$filePath["extension"];

	// Сохраняем картинку в FS
	$image = file_get_contents($item->thumbBig);
	file_put_contents("img/".$filename, $image);

	// Сохраняем в БД данные 
	$b = $a->prepare("
		INSERT INTO games (
			title,
			category_id,
			description,
			player_url,
			thumbnail_large
		) VALUES (
			:item,
			$cat_id,
			:description,
			:url,
			:filename
		)");


	$b -> execute(array(

		':item' => $item->title, 
		':description' => $item->description,
		':url' => $item->link,
		':filename' => $filename

		));



		   
}







/*
			thumbnail_small,
			thumbnail_medium,
			thumbnail_large

			$entry->thumbnails->small,
			$entry->thumbnails->medium,
			$entry->thumbnails->large



			*/


		  // -- player_width,
		  // -- player_height,
		  // -- thumbnail_small,
		  // -- thumbnail_medium,
		  // -- thumbnail_large


 // echo '<pre>';
 // print_r($xml);
 // echo '</pre>';

//echo $xml->entries->entry[0]->id;

 //  foreach ($xml->item as $item) {
 //    echo $item->description.'<br>';
 // }



?>