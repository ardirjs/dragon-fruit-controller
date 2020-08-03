<?php
include_once '../server.php';
include_once '../library.php';

$host = new server();
$base = new database($host->host, $host->user, $host->pass, $host->base);
ini_set('date.timezone', 'Asia/Jakarta');

if (isset($_GET["id"]) && isset($_GET["la"]) && isset($_GET["lb"]) && isset($_GET["vv"]) && isset($_GET["va"])) {
  $id = $_GET["id"];
  $la = $_GET["la"];
  $lb = $_GET["lb"];
  $vv = $_GET["vv"];
  $va = $_GET["va"];
  $data = $base->query("SELECT * FROM $base->table WHERE $base->reproduct='$id'");
  if (mysqli_num_rows($data)) {
    $data = mysqli_fetch_assoc($data);
    $date = date("n/j/Y, h:i:s A");

    $dataArr = $dateArr = array();
    $json = json_decode($data[$base->statistics]);
    foreach ($json->data as $key => $value) {
      if ($key) {
        array_push($dataArr, $value);
      }
    }
    foreach ($json->date as $key => $value) {
      if ($key) {
        array_push($dateArr, $value);
      }
    }
    array_push($dataArr, $vv * $va);
    array_push($dateArr, date("h A"));
    $json = new stdClass();
    $json->data = $dataArr;
    $json->date = $dateArr;
    $object = json_encode($json);

    if ($base->query("UPDATE $base->table SET
      $base->lamp_a = '$la',
      $base->lamp_b = '$lb',
      $base->volt = '$vv',
      $base->ampere = '$va',
      $base->statistics = '$object',
      $base->statistics_times = '$date'
      WHERE $base->reproduct = '$id'
    ")) {
      $data = $base->query("SELECT * FROM $base->table WHERE $base->reproduct='$id'");
      if (mysqli_num_rows($data)) {
        $json_result = new stdClass();
        $data = mysqli_fetch_assoc($data);

        $json_result->a = $data[$base->lamp_a];
        $json_result->b = $data[$base->lamp_b];

        $json_parse = json_decode($data[$base->lamp_a_data]);
        $json_stringify = new stdClass();
        $json_stringify->hn = $json_parse->hour_on;
        $json_stringify->hf = $json_parse->hour_off;
        $json_stringify->mn = $json_parse->minute_on;
        $json_stringify->mf = $json_parse->minute_off;
        $json_result->da = json_encode($json_stringify);

        $json_parse = json_decode($data[$base->lamp_b_data]);
        $json_stringify = new stdClass();
        $json_stringify->hn = $json_parse->hour_on;
        $json_stringify->hf = $json_parse->hour_off;
        $json_stringify->mn = $json_parse->minute_on;
        $json_stringify->mf = $json_parse->minute_off;
        $json_result->db = json_encode($json_stringify);

        $object_result = json_encode($json_result);
        echo $object_result;
      }
    }
  }
}
?>
