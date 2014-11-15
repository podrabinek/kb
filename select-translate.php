<?php

error_reporting(E_ALL);

require_once('inc/config.php');
require_once('inc/func.php');

require(ROOT_PATH . "inc/db.php");

header('Content-type: text/html; charset=utf-8');

try {
	$results = $db->prepare("SELECT id,description FROM games ORDER BY id ASC");
	$results->execute();
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

$game = $results->fetchAll();

// echo "<pre>";
// var_dump($game);
// exit;

foreach($game as $row) {


	$a = translateTo($row['description']);

	try {
		$results = $db->prepare("UPDATE games SET description_rus = '".$a."' WHERE id = '".$row['id']."'");
		$results->execute();
	} catch (PDOException $e) {
	    echo 'Connection failed: ' . $e->getMessage();
	    exit;
	 }

	$i++;

	sleep(1);

}


?>