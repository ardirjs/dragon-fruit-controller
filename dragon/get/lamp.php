<?php
include_once '../server.php';
include_once '../library.php';

$host = new server();
$json = new stdClass();
$base = new database($host->host, $host->user, $host->pass, $host->base);

if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"]) && isset($_GET["a"])) {
  $data = $_GET["a"];
  $user = $_GET["user"];
  $page = $_GET["page"];
  $keys = $_GET["keys"];

  if ($base->read_product($user, $keys)) {
    $base->query("UPDATE $base->table SET $base->lamp_a='$data' WHERE $base->username='$user'");
  }
}

if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"]) && isset($_GET["b"])) {
  $data = $_GET["b"];
  $user = $_GET["user"];
  $page = $_GET["page"];
  $keys = $_GET["keys"];

  if ($base->read_product($user, $keys)) {
    $base->query("UPDATE $base->table SET $base->lamp_b='$data' WHERE $base->username='$user'");
  }
}

if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"]) && isset($_GET["seta"])) {
  $data = $_GET["seta"];
  $user = $_GET["user"];
  $page = $_GET["page"];
  $keys = $_GET["keys"];

  if ($base->read_product($user, $keys)) {
    $base->query("UPDATE $base->table SET $base->lamp_a_data='$data' WHERE $base->username='$user'");
  }
}

if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"]) && isset($_GET["setb"])) {
  $data = $_GET["setb"];
  $user = $_GET["user"];
  $page = $_GET["page"];
  $keys = $_GET["keys"];

  if ($base->read_product($user, $keys)) {
    $base->query("UPDATE $base->table SET $base->lamp_b_data='$data' WHERE $base->username='$user'");
  }
}

if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"])) {
  $user = $_GET["user"];
  $page = $_GET["page"];
  $keys = $_GET["keys"];

  if ($base->read_product($user, $keys)) {
    $data = $base->query("SELECT * FROM $base->table WHERE $base->username='$user'");
    if (mysqli_num_rows($data)) {
      $data = mysqli_fetch_assoc($data);
      $json->lamp_a = $data[$base->lamp_a];
      $json->lamp_b = $data[$base->lamp_b];
      $json->lamp_a_data = $data[$base->lamp_a_data];
      $json->lamp_b_data = $data[$base->lamp_b_data];
      $object = json_encode($json);
      echo $object;
    }
  }
}
?>
