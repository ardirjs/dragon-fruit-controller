<div class="container-fluid pt-2">
  <div class="row pt-0 pl-2 pr-2 pb-0">
    <div class="p-2 col-sm-12 col-md-6 col-lg-3">
      <div class="pt-2 pl-3 pr-3 pb-2 bg-white rounded border shadow-sm">
        <span class="h5 font-weight-bolder underline-prepend">Volt&nbsp;</span>
        <hr class="underline">
        <div class="p-0 m-0 w-100" style="height: 90px;" id="meter-volts"></div>
      </div>
    </div>
    <div class="p-2 col-sm-12 col-md-6 col-lg-3">
      <div class="pt-2 pl-3 pr-3 pb-2 bg-white rounded border shadow-sm">
        <span class="h5 font-weight-bolder underline-prepend">Ampere&nbsp;</span>
        <hr class="underline">
        <div class="p-0 m-0 w-100" style="height: 90px;" id="meter-amper"></div>
      </div>
    </div>
    <div class="p-2 col-sm-12 col-md-6 col-lg-3">
      <div class="pt-2 pl-3 pr-3 pb-2 bg-white rounded border shadow-sm">
        <span class="h5 font-weight-bolder underline-prepend">Watt&nbsp;</span>
        <hr class="underline">
        <div class="p-0 m-0 w-100" style="height: 90px;" id="meter-watts"></div>
      </div>
    </div>
    <div class="p-2 col-sm-12 col-md-6 col-lg-3">
      <div class="pt-2 pl-3 pr-3 pb-2 bg-white rounded border shadow-sm">
        <span class="h5 font-weight-bolder underline-prepend">Switch&nbsp;</span>
        <hr class="underline">
        <div class="p-0 m-0 w-100 d-flex" style="height: 90px;">
          <div class="p flex-fill text-center">
            <button class="btn p-0 shadow-none" type="button" name="button" id="turn-lamp-a">
              <i class="fa fa-lightbulb-o status-lamp-a text-secondary" style="font-size: 4rem;" id="lamp-a"></i>
            </button>
            <br>
            <span class="small font-weight-bolder">Lamp Ch A</span>
          </div>
          <div class="p flex-fill text-center">
            <button class="btn p-0 shadow-none" type="button" name="button" id="turn-lamp-b">
              <i class="fa fa-lightbulb-o status-lamp-b text-secondary" style="font-size: 4rem;" id="lamp-b"></i>
            </button>
            <br>
            <span class="small font-weight-bolder">Lamp Ch B</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

$(document).ready(function() {
  $("#modal-loading").modal("hide");
  <?php
  if ($login) {
    ?>
    $('#turn-lamp-a').click(function() {
      $("#modal-loading").modal("show");
      $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>', success: function(result) {
        if (result != "") {
          var res = JSON.parse(result);
          var value = parseInt(res.lamp_a) ? 0 : 1;
          $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>&a=' + parseInt(value), success: function(result) {
            if (result != "") {
              res = JSON.parse(result);
              if (parseInt(res.lamp_a)) {$('.status-lamp-a').removeClass('text-danger').addClass('text-success');}
              else {$('.status-lamp-a').removeClass('text-success').addClass('text-danger');}
              $("#modal-loading").modal("hide");
            }
          }});
        }
      }});
      $("#modal-loading").modal("hide");
    });
    $('#turn-lamp-b').click(function() {
      $("#modal-loading").modal("show");
      $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>', success: function(result) {
        if (result != "") {
          var res = JSON.parse(result);
          var value = parseInt(res.lamp_b) ? 0 : 1;
          $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>&b=' + parseInt(value), success: function(result) {
            if (result != "") {
              var res = JSON.parse(result);
              if (parseInt(res.lamp_b)) {$('.status-lamp-b').removeClass('text-danger').addClass('text-success');}
              else {$('.status-lamp-b').removeClass('text-success').addClass('text-danger');}
              $("#modal-loading").modal("hide");
            }
          }});
        }
      }});
      $("#modal-loading").modal("hide");
    });
    <?php
  }
  ?>
});

var optionsChartsMeter = {
  chart: {
    type: 'gauge', plotBorderWidth: 0, plotBackgroundColor: {linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 }, stops: [[0, '#fff']]},
    plotBackgroundImage: false, backgroundColor: {linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 }, stops: [[0, '#fff']]}, height: 100
  },
  title: false, credits: false,
  pane: [{startAngle: -45, endAngle: 45, background: null, center: ['50%', '145'], size: 250}],
  exporting: {enabled: false}, tooltip: {enabled: false},
  plotOptions: {gauge: {dataLabels: {enabled: false}, dial: {radius: '100%'}}}
}

var meterVolts = Highcharts.chart('meter-volts', Highcharts.merge(optionsChartsMeter, {
  yAxis: [{min: 0, max: 250, minorTickPosition: 'outside', tickPosition: 'outside', labels: {rotation: 'auto', distance: 20},
    plotBands: [{from: 230, to: 250, color: '#C02316', innerRadius: '100%', outerRadius: '107.5%'}],
    pane: 0, title: {text: '<span class="meter-volts-value" style="font-size: 18px; font-weight: 900;">0 V</span>', y: -25}
  }],
  series: [{name: 'volts', data: [0], yAxis: 0}]
}));

var meterAmper = Highcharts.chart('meter-amper', Highcharts.merge(optionsChartsMeter, {
  yAxis: [{min: 0, max: 30, minorTickPosition: 'outside', tickPosition: 'outside', labels: {rotation: 'auto', distance: 20},
    plotBands: [{from: 20, to: 30, color: '#C02316', innerRadius: '100%', outerRadius: '107.5%'}],
    pane: 0, title: {text: '<span class="meter-amper-value" style="font-size: 18px; font-weight: 900;">0 A</span>', y: -25}
  }],
  series: [{name: 'amper', data: [0], yAxis: 0}]
}));

var meterWatts = Highcharts.chart('meter-watts', Highcharts.merge(optionsChartsMeter, {
  yAxis: [{min: 0, max: 10000, minorTickPosition: 'outside', tickPosition: 'outside', labels: {rotation: 'auto', distance: 20},
    plotBands: [{from: 4000, to: 10000, color: '#C02316', innerRadius: '100%', outerRadius: '107.5%'}],
    pane: 0, title: {text: '<span class="meter-watts-value" style="font-size: 18px; font-weight: 900;">0 W</span>', y: -25}
  }],
  series: [{name: 'watts', data: [0], yAxis: 0}]
}));

setInterval(function () {
  $.ajax({url: 'get/lamp.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>', success: function(result) {
    if (result != "") {
      res = JSON.parse(result);
      if (parseInt(res.lamp_a)) {$('.status-lamp-a').removeClass('text-danger').addClass('text-success');}
      else {$('.status-lamp-a').removeClass('text-success').addClass('text-danger');}
      if (parseInt(res.lamp_b)) {$('.status-lamp-b').removeClass('text-danger').addClass('text-success');}
      else {$('.status-lamp-b').removeClass('text-success').addClass('text-danger');}
    }
  }});

  $.ajax({url: 'get/watt.php?page=<?=$page?>&user=<?=$user?>&keys=<?=$keys?>', success: function(result) {
    if (result != "") {
      var res = JSON.parse(result);
      if (meterVolts) {
        var meter = meterVolts.series[0].points[0].update(parseInt(res.volts), false); meterVolts.redraw();
        if (res.volts) {var rest = res.volts + ' V'; $('.meter-volts-value').html(rest);}
      }
      if (meterAmper) {
        var meter = meterAmper.series[0].points[0].update(parseInt(res.amper), false); meterAmper.redraw();
        if (res.amper) {var rest = res.amper + ' A'; $('.meter-amper-value').html(rest);}
      }
      if (meterWatts) {
        var meter = meterWatts.series[0].points[0].update(parseInt(res.watts), false); meterWatts.redraw();
        if (res.watts) {var rest = res.watts + ' W'; $('.meter-watts-value').html(rest);}
      }
    }
  }});
}, 1000);
</script>
