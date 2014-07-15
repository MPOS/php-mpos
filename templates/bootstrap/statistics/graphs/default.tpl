<script>
$(function () {

  // needed for automatic activation of first tab
  $(function () {
    $('#hashrategraph a:first').tab('show')
  })

  // You can't draw here chart directly, because it's on hidden tab, instead let's do the workaround
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    if ($(e.target).attr('href') == '#mine' && $('#mine-area-chart').html().length == 0) {
      var chart = Morris.Line({
        // ID of the element in which to draw the chart.
        element: 'mine-area-chart',
        data: {$YOURHASHRATES},
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
    }
    if ($(e.target).attr('href') == '#pool' && $('#pool-area-chart').html().length == 0) {
      var chart = Morris.Line({
        // ID of the element in which to draw the chart.
        element: 'pool-area-chart',
        data: {$POOLHASHRATES},
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
    }
  });
});
</script>

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-signal fa-fw"></i> Stats
      </div>
      <div class="panel-body">
        <ul class="nav nav-pills" id="hashrategraph">
          <li><a href="#mine" data-toggle="tab">Mine</a></li>
          <li><a href="#pool" data-toggle="tab">Pool</a></li>
        </ul>
        <div class="tab-content">
          {include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/mine.tpl"}
          {include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/pool.tpl"}
        </div>
      </div>
    </div>
  </div>
</div>
