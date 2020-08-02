<?php
$lamp_a_on_h = $lamp_a_off_h = $lamp_a_on_m = $lamp_a_off_m = NULL;
$lamp_b_on_h = $lamp_b_off_h = $lamp_b_on_m = $lamp_b_off_m = NULL;
$statistics_update = $lamp_a_date = $lamp_b_date = '-';
$statistics_data = $statistics_date = 0;

if (isset($_GET["page"]) && isset($_GET["user"]) && isset($_GET["keys"])) {
  $user = $_GET["user"];
  $page = $_GET["page"];
  $keys = $_GET["keys"];

  if ($base->read_product($user, $keys)) {
    $statistics_update = $lamp_a_date = $lamp_b_date = NULL;
    $statistics_data = $statistics_date = NULL;
    $data = $base->query("SELECT * FROM $base->table WHERE $base->username='$user'");
    if (mysqli_num_rows($data)) {
      $data = mysqli_fetch_assoc($data);

      $json = $data[$base->lamp_a_data];
      $object = json_decode($json);
      $lamp_a_on_h = $object->hour_on;
      $lamp_a_off_h = $object->hour_off;
      $lamp_a_on_m = $object->minute_on;
      $lamp_a_off_m = $object->minute_off;
      $lamp_a_date = $object->date;

      $json = $data[$base->lamp_b_data];
      $object = json_decode($json);
      $lamp_b_on_h = $object->hour_on;
      $lamp_b_off_h = $object->hour_off;
      $lamp_b_on_m = $object->minute_on;
      $lamp_b_off_m = $object->minute_off;
      $lamp_b_date = $object->date;

      $statistics_update = $data[$base->statistics_times];
      $json = json_decode($data[$base->statistics]);
      foreach ($json->data as $value) {
        $statistics_data .= $value.',';
      }

      $json = json_decode($data[$base->statistics]);
      foreach ($json->date as $value) {
        $statistics_date .= '"'.$value.'",';
      }
    }
  }
}
?>
<div class="container-fluid pt-0">
  <div class="row pt-0 pl-2 pr-2 pb-0">
    <div class="p-2 col-sm-12 col-md-6 col-lg-3">
      <div class="pt-2 pl-3 pr-3 pb-2 bg-white rounded border shadow-sm">
        <span class="h5 font-weight-bolder underline-prepend">Timer Lamp A&nbsp;</span>
        <hr class="underline">
        <div class="p-0 m-0 w-100" style="height: 215px;">
          <div class="d-flex align-items-end">
            <div class="p flex-fill">
              <div class="small font-weight-bolder">Last Update : <span id="timeUpdateLampA"><?=$lamp_a_date?></span></div>
            </div>
            <div class="p auto ml-2">
              <button class="p-0 btn shadow-none" type="button" name="button" id="updateSetLampA">
                <i class="fa fa-send fa-success"></i>
              </button>
            </div>
          </div>
          <hr class="mt-0 mb-2">
          <div class="input-group input-group-sm pb-2">
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded mr-2 fa fa-lightbulb-o text-success"></span></div>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">M</span></div>
            <select class="custom-select shadow-none mr-2 rounded-right" name="" id="timeMinuteOnA">
              <option selected><?=sprintf("%02d", $lamp_a_on_m)?></option><?=$time->getMinute()?>
            </select>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">H</span></div>
            <select class="custom-select shadow-none mr-0 rounded-right" name="" id="timeHourOnA">
              <option selected><?=sprintf("%02d", $lamp_a_on_h)?></option><?=$time->getHour()?>
            </select>
          </div>
          <div class="input-group input-group-sm pb-2">
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded mr-2 fa fa-lightbulb-o text-danger"></span></div>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">M</span></div>
            <select class="custom-select shadow-none mr-2 rounded-right" name="" id="timeMinuteOffA">
              <option selected><?=sprintf("%02d", $lamp_a_off_m)?></option><?=$time->getMinute()?>
            </select>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">H</span></div>
            <select class="custom-select shadow-none mr-0 rounded-right" name="" id="timeHourOffA">
              <option selected><?=sprintf("%02d", $lamp_a_off_h)?></option><?=$time->getHour()?>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="p-2 col-sm-12 col-md-6 col-lg-3">
      <div class="pt-2 pl-3 pr-3 pb-2 bg-white rounded border shadow-sm">
        <span class="h5 font-weight-bolder underline-prepend">Timer Lamp B&nbsp;</span>
        <hr class="underline">
        <div class="p-0 m-0 w-100" style="height: 215px;">
          <div class="d-flex align-items-end">
            <div class="p flex-fill">
              <div class="small font-weight-bolder">Last Update : <span id="timeUpdateLampB"><?=$lamp_b_date?></span></div>
            </div>
            <div class="p auto ml-2">
              <button class="p-0 btn shadow-none" type="button" name="button" id="updateSetLampB">
                <i class="fa fa-send fa-success"></i>
              </button>
            </div>
          </div>
          <hr class="mt-0 mb-2">
          <div class="input-group input-group-sm pb-2">
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded mr-2 fa fa-lightbulb-o text-success"></span></div>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">M</span></div>
            <select class="custom-select shadow-none mr-2 rounded-right" name="" id="timeMinuteOnB">
              <option selected><?=sprintf("%02d", $lamp_b_on_m)?></option><?=$time->getMinute()?>
            </select>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">H</span></div>
            <select class="custom-select shadow-none mr-0 rounded-right" name="" id="timeHourOnB">
              <option selected><?=sprintf("%02d", $lamp_b_on_h)?></option><?=$time->getHour()?>
            </select>
          </div>
          <div class="input-group input-group-sm pb-2">
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded mr-2 fa fa-lightbulb-o text-danger"></span></div>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">M</span></div>
            <select class="custom-select shadow-none mr-2 rounded-right" name="" id="timeMinuteOffB">
              <option selected><?=sprintf("%02d", $lamp_b_off_m)?></option><?=$time->getMinute()?>
            </select>
            <div class="input-group-prepend"><span class="input-group-text font-weight-bolder rounded-left">H</span></div>
            <select class="custom-select shadow-none mr-0 rounded-right" name="" id="timeHourOffB">
              <option selected><?=sprintf("%02d", $lamp_b_off_h)?></option><?=$time->getHour()?>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="p-2 col-sm-12 col-md-12 col-lg-6">
      <div class="pt-2 pl-3 pr-3 pb-2 bg-white rounded border shadow-sm">
        <span class="h5 font-weight-bolder underline-prepend">Statistics&nbsp;</span>
        <hr class="underline">
        <div class="p-0 m-0 w-100" style="height: 215px;">
          <div class="d-flex align-items-end">
            <div class="p flex-fill">
              <div class="small font-weight-bolder">Last Update : <span id="timeUpdateLampB"><?=$statistics_update?></span></div>
            </div>
            <div class="p auto ml-2">
              <button class="p-0 btn shadow-none" type="button" name="button" id="updateStatistics">
                <i class="fa fa-refresh fa-success"></i>
              </button>
            </div>
          </div>
          <hr class="mt-0 mb-2">
          <div class="p-0 m-0 w-100" id="lines-stats"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
Highcharts.chart('lines-stats', {
  chart: {type: 'line', backgroundColor: {linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 }, stops: [[0, '#fff']]}, height: 185},
  title: false, subtitle: false, xAxis: {categories: [<?=$statistics_date?>]}, yAxis: {title: false},
  plotOptions: {line: {dataLabels: {enabled: true}, enableMouseTracking: false}},
  exporting: {enabled: false}, credits: false, series: [{name: 'WATT', data: [<?=$statistics_data?>]}]
});

function getTimes() {
  var date = new Date();
  return date.toLocaleString();
}

$('#updateSetLampA').click(function() {
  $("#modal-loading").modal("show");
  $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>', success: function(result) {
    if (result != "") {
      var object = {
        hour_on: $('#timeHourOnA').val(),
        hour_off: $('#timeHourOffA').val(),
        minute_on: $('#timeMinuteOnA').val(),
        minute_off: $('#timeMinuteOffA').val(),
        date: getTimes()
      }
      var json = JSON.stringify(object);
      $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>&seta=' + json, success: function(result) {
        if (result != "") {
          var res = JSON.parse(result);
          res = JSON.parse(res.lamp_a_data);
          $('#timeMinuteOnA [selected]').html(res.minute_on);
          $('#timeMinuteOffA [selected]').html(res.minute_off);
          $('#timeHourOnA [selected]').html(res.hour_on);
          $('#timeHourOffA [selected]').html(res.hour_off);
          $('#timeUpdateLampA').html(res.date);
          $("#modal-loading").modal("hide");
        }
      }});
    }
  }});
  $("#modal-loading").modal("hide");
});

$('#updateSetLampB').click(function() {
  $("#modal-loading").modal("show");
  $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>', success: function(result) {
    if (result != "") {
      var object = {
        hour_on: $('#timeHourOnB').val(),
        hour_off: $('#timeHourOffB').val(),
        minute_on: $('#timeMinuteOnB').val(),
        minute_off: $('#timeMinuteOffB').val(),
        date: getTimes()
      }
      var json = JSON.stringify(object);
      $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>&setb=' + json, success: function(result) {
        if (result != "") {
          var res = JSON.parse(result);
          res = JSON.parse(res.lamp_b_data);
          $('#timeMinuteOnB [selected]').html(res.minute_on);
          $('#timeMinuteOffB [selected]').html(res.minute_off);
          $('#timeHourOnB [selected]').html(res.hour_on);
          $('#timeHourOffB [selected]').html(res.hour_off);
          $('#timeUpdateLampB').html(res.date);
          $("#modal-loading").modal("hide");
        }
      }});
    }
  }});
  $("#modal-loading").modal("hide");
});

$('#updateStatistics').click(function() {
  location.reload();
});
</script>
