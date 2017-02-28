<script>
$(function () {
  var hashChart = Morris.Line({
    element: 'hashrate-area-chart',
    data: {$YOURMININGSTATS},
    xkey: 'time',
    ykeys: ['hashrate'],
    labels: ['Hashrate'],
    pointSize: 1,
    hideHover: 'auto',
    resize: true,
    fillOpacity: 1.00,
    lineColors: ['#24A665'],
    pointFillColors: ['#24A665'],
    pointStrokeColors: ['#24A665']
  });

  var workersChart = Morris.Line({
    element: 'workers-area-chart',
    data: {$YOURMININGSTATS},
    xkey: 'time',
    ykeys: ['workers'],
    labels: ['Workers'],
    pointSize: 1,
    hideHover: 'auto',
    resize: true,
    fillOpacity: 1.00,
    lineColors: ['#24A665'],
    pointFillColors: ['#24A665'],
    pointStrokeColors: ['#24A665']
  });

  var shareCharts= Morris.Line({
    element: 'sharerate-area-chart',
    data: {$YOURMININGSTATS},
    xkey: 'time',
    ykeys: ['sharerate'],
    labels: ['Sharerate'],
    pointSize: 1,
    hideHover: 'auto',
    resize: true,
    fillOpacity: 1.00,
    lineColors: ['#24A665'],
    pointFillColors: ['#24A665'],
    pointStrokeColors: ['#24A665']
  });
});
</script>

<div class="row">
  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Average Hashrate past 24h
        </div>
        <span class="tools">
          <i class="fa fa-signal"></i>
        </span>
      </div>
      <div class="widget-body">
        <div id="hashrate-area-chart"></div>
      </div>
      <div class="widget-footer">
        Your average hashrate per hour, updated every backend cron run.
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Average Workers past 24h
        </div>
        <span class="tools">
          <i class="fa fa-signal"></i>
        </span>
      </div>
      <div class="widget-body">
        <div id="workers-area-chart"></div>
      </div>
      <div class="widget-footer">
        Your average active workers per hour, updated every backend cron run.
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Average Sharerate past 24h
        </div>
        <span class="tools">
          <i class="fa fa-signal"></i>
        </span>
      </div>
      <div class="widget-body">
        <div id="sharerate-area-chart"></div>
      </div>
      <div class="widget-footer">
        Your share rate per hour, updated every backend cron run.
      </div>
    </div>
  </div>
</div>
