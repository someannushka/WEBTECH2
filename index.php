<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tests</title>

    <!-- JS and CSS libraries and plugins -->

      <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

      <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.6.21/dist/css/uikit.min.css" />

      <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.21/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.21/dist/js/uikit-icons.min.js"></script>

      <!-- MathJax -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>


      <!-- Document master JS -->
    <script src="js/master.js"></script>

      <!-- Document master CSS -->
    <link rel="stylesheet" href="css/master.css">



  </head>
  <body>
    <?php
      include_once('includes/db_handler.php');
      include_once('includes/api_handler.php');
      include_once('includes/pages_handler.php');

      $db = new DB_HANDLE($db_host, $db_name, $db_user, $db_password);
      $api = new API_HANDLE($db);
      $pages = new PAGES_HANDLE($db, $api);

      $pages->run();

     ?>
  </body>
</html>
