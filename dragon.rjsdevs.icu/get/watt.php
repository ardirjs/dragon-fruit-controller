<?php
include_once '../server.php';
include_once '../library.php';

$host = new server();
$json = new stdClass();
$base = new database($host->host, $host->user, $host->pass, $host->base);

if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"])) {
  $user = $_GET["user"];
  $page = $_GET["page"];
  $keys = $_GET["keys"];

  if ($base->read_product($user, $keys)) {
    $data = $base->query("SELECT * FROM $base->table WHERE $base->username='$user'");
    if (mysqli_num_rows($data)) {
      $data = mysqli_fetch_assoc($data);
      $json->volts = $data[$base->volt];
      $json->amper = $data[$base->ampere];
      $json->watts = $data[$base->volt] * $data[$base->ampere];
      $object = json_encode($json);
      echo $object;
    } else {
      echo NULL;
    }
  } else {
    echo NULL;
  }
}
?>
