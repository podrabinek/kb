<?php

require_once('../inc/config.php');
require_once('../inc/func.php');


if (isset($_GET["id"])) {
  $game_id = intval($_GET["id"]);
  $game = get_game_single($game_id);
}

if (empty($game)) {
  // header("Location: " . BASE_URL);
  // exit;
}




?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body style="padding-top: 120px">

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?= BRAND ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <div class="row"> 

        <div class="col-xs-12 col-sm-7">        
          <iframe src="<?=$game["player_url"]?>" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" msallowfullscreen="true" width="600" height="600"></iframe>
        </div>

        <?php

        //Получаем список названий категорий
        $cat_names = get_games_category_list($game["category_id"]);

        // Формируем список ссылок
        $list = array();
        foreach($cat_names as $cat) {
          $list[] = '<li><a href="/catalog/'.$cat["id"].'/">'.$cat["name"].'</a></li>';
        }

        // Выводим список категорий ссылками, через запятую
        echo '<ul class="list-unstyled list-inline">'.implode(" | ", $list).'</ul>';

        ?>



        <div class="col-sm-5">
          <img src="<?=BASE_URL?>img/<?=$game["thumbnail_large"]?>">
          <h3><?=$game["title"]?></h3>
          <p><em><?=$game["category"]?></em></p>
          <p><?=$game["description"]?></p>
          <p><a href="" class="btn btn-primary" role="button">Играть</a></p>
          
        </div>
       </div>


    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

  </body>
</html>