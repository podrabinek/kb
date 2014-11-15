<?php

include('inc/config.php');
include('inc/db.php');
include('inc/func.php');

header('Content-type: text/html; charset=utf-8');

error_reporting(E_ALL);

$apiKey = 'AIzaSyCPgQsg8ZHVX4neM9GcpufZWu6wEAjBLWs';
$url = 'https://www.googleapis.com/language/translate/v2/languages?key=' . $apiKey;

   
function translateTo($value, $language_key) {

    $api_key = 'AIzaSyCPgQsg8ZHVX4neM9GcpufZWu6wEAjBLWs';
    $value = urlencode($value);

    $url ="https://www.googleapis.com/language/translate/v2?key=$api_key&q=$value&source=en&target=$language_key";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $body = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($body);

    return $json->data->translations[0]->translatedText;
}




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

// Читаем XML
$xml = simplexml_load_file($xml_path);


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
	$b = $db->prepare("
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
		':description' => mb_convert_encoding(translateTo($item->description,'ru'),'UTF-8','windows-1252'),
		':url' => $item->link,
		':filename' => $filename

		));



		   
}



?>