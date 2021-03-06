<?php


// Расчленяем УРЛ картинки
function filePath($filePath) {

	$fileParts = pathinfo($filePath);

	if(!isset($fileParts['filename'])) {
		$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
	}
	  
	return $fileParts;
}



/* 
 * Returns an array of game information for the game which matches the id;
 * returns a boolean false if no matches
 */
function get_games_list() {

	require(ROOT_PATH . "inc/db.php");

	try {
		$results = $db->prepare("SELECT id,title,description,description_rus,thumbnail_large FROM games ORDER BY id DESC");
		$results->execute();
	} catch (PDOException $e) {
	    echo 'Connection failed: ' . $e->getMessage();
	    exit;
	}

	$games = $results->fetchAll(PDO::FETCH_ASSOC);	

	  // echo "<pre>";
	  // var_dump($games);
	  // exit;

	return $games;

}



/* 
 * Returns an array of categories names for the matching game 
 */
function get_games_category_list($ids) {

	require(ROOT_PATH . "inc/db.php");

  try {
    $results = $db->prepare("SELECT id,name FROM games_category WHERE id IN ($ids)");
    $results->execute();
  } catch (PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage();
      exit;
  }

  $names = $results->fetchAll(PDO::FETCH_ASSOC);

	  // echo "<pre>";
	  // var_dump($names);
	  // exit;

  return $names;

}



/* 
 * Returns total number of selected games
 */
function get_games_count() {
    return count(get_games_list());
}


/*
 * Returns a specified subset of products, based on the values received,
 * using the order of the elements in the array .
 * @param    int             the position of the first product in the requested subset 
 * @param    int             the position of the last product in the requested subset 
 * @return   array           the list of products that correspond to the start and end positions
 */
function get_games_subset($positionStart, $positionEnd) {
    $subset = array();
    $all = get_games_list();

    $position = 0;
    foreach($all as $product) {
        $position += 1;
        if ($position >= $positionStart && $position <= $positionEnd) {
            $subset[] = $product;
        }
    }

    return $subset;
}


/* 
 * Returns an array of game information for the game whcih matches the id;
 * returns a boolean false if no matches
 */
function get_game_single($id) {

	require(ROOT_PATH . "inc/db.php");

	try {
		$results = $db->prepare("
			SELECT g.id, category_id, title, description, player_url, thumbnail_large, gc.name as category
			FROM games g
			INNER JOIN games_category gc ON g.category_id = gc.id
			WHERE g.id = ?
			");
		$results->bindParam(1,$id);
		$results->execute();
	} catch (PDOException $e) {
	    echo 'Connection failed: ' . $e->getMessage();
	    exit;
	}

	$game = $results->fetch(PDO::FETCH_ASSOC);

	// echo "<pre>";
	//  var_dump($game);
	//  exit;

	return $game;

}


/* 
 * Clip text
 */
function get_clipped_text($string, $long = 350) {

	$string = substr($string, 0, $long);
	$string = substr($string, 0, strrpos($string, ' ')) . " &#8230;";
	
	return $string;
}




/* 
 * Translates given text
 */
   
function translateTo($value, $language_key = ru) {


	$url = 'https://www.googleapis.com/language/translate/v2/languages?key=' . $apiKey;
    $api_key = 'AIzaSyCPgQsg8ZHVX4neM9GcpufZWu6wEAjBLWs';

    $value = rawurlencode($value);

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





?>