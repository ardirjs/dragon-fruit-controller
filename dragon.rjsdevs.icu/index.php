<?php
include_once 'server.php';
include_once 'library.php';

$host = new server();
$alert = new notification();
$time = new timeSelected();
$validation = new validation($host->host, $host->user, $host->pass, $host->base);
$base = new database($host->host, $host->user, $host->pass, $host->base);
$base->createTable();
ini_set('date.timezone', 'Asia/Jakarta');
if($_SERVER['HTTPS']!="on") {
  ?><script type="text/javascript">
    location.href = "https://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>";
  </script><?php
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <?php
  $hostdist = "https://ardirjs.github.io/distribution/";
  //$hostdist = "../github/distribution/";
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="theme-color" content="#28a745">
  <meta name="msapplication-navbutton-color" content="#28a745">
  <meta name="apple-mobile-web-app-status-bar-style" content="#28a745">
  <meta name="programmer" content="https://github.com/ardirjs">
  <meta name="description" content="https://github.com/ardirjs">
  <meta name="keywords" content="https://github.com/ardirjs">
  <meta name="author" content="https://github.com/ardirjs">
  <meta name="title" content="https://github.com/ardirjs">
  <meta name="copyright" content="https://github.com/ardirjs">
  <meta Http-Equiv="Expires" content="0">
  <meta Http-Equiv="Pragma" content="No-Cache">
  <meta Http-Equiv="Content-type" content="Text/Html">
  <meta Http-Equiv="Cache-Control" content="No-Cache">
  <meta Http-Equiv="Cache-Control" content="No-Store">
  <meta Http-Equiv="Content-Language" content="En-Us">
  <meta Http-Equiv="X-UA-Compatible" content="IE=Edge">
  <link href="asset/icon.png" rel="shortcut icon">
  <link href="asset/icon.png" rel="apple-touch-icon">
  <link href="asset/icon.png" rel="apple-touch-icon-precomposed">
  <title>Dragon Fruit Control</title>
  <link rel="stylesheet" href="<?=$hostdist?>/plugin/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=$hostdist?>/plugin/fontawesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="asset/index.css">
  <script src="<?=$hostdist?>/plugin/jquery/jquery.min.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/popper/umd/popper.min.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/bootstrap/js/bootstrap.min.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/highcharts/highcharts.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/highcharts/highcharts-more.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/highcharts/modules/exporting.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/highcharts/modules/export-data.js" charset="utf-8"></script>
  <script src="<?=$hostdist?>/plugin/highcharts/modules/accessibility.js" charset="utf-8"></script>
</head>
<body class="bg-background">
  <nav class="navbar navbar-expand-md bg-success navbar-dark shadow-sm">
    <a class="navbar-brand font-weight-bolder text-light" href="" onclick="location.reload()">
      <span class="" style="font-variant: small-caps;">Dragon Fruit Control</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapse-navbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapse-navbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link text-light" href="" onclick="location.reload()"><i class="fa fa-home"></i>&nbsp;Home</a></li>
        <li class="nav-item"><a class="nav-link text-light" href="https://github.com/ardirjs/dragon-fruit-controller" target="_blank"><i class="fa fa-github"></i>&nbsp;Github</a></li>
        <li class="nav-item"><a class="nav-link text-light" href="?page=login"><i class="fa fa-sign-in"></i>&nbsp;Login</a></li>
      </ul>
    </div>
  </nav>
<?php
if (isset($_GET['page'])) {
  switch ($_GET['page']) {
    case 'home': include_once 'home.php' ;break;
    case 'login': include_once 'login.php'; break;
    case 'register': include_once 'register.php'; break;
    default: include_once 'home.php'; break;
  }
} else {
  include_once 'home.php';
}
?>
</body>
</html>
