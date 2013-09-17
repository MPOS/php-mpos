<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.json2.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.highlighter.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.trendline.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.enhancedLegendRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.pointLabels.js"></script>

<script>
{literal}
$(document).ready(function(){
  var g1, g2, g3, g4, g5;

  // Ajax API URL
  var url = "{/literal}{$smarty.server.PHP_SELF}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";

  // Enable all included plugins
  //  $.jqplot.config.enablePlugins = true;

  // Store our data globally
  var storedPersonalHashrate=[];
  var storedPoolHashrate=[];
  var storedPersonalSharerate=[];

  // jqPlit defaults
  var jqPlotOverviewOptions = {
    highlighter: { show: true },
    grid: { drawBorder: false, background: '#fbfbfb', shadow: false },
    stackSeries: false,
    seriesColors: [ '#26a4ed', '#ee8310', '#e9e744' ],
    seriesDefaults:{
      lineWidth: 4, shadow: false,
      fill: false, fillAndStroke: true, fillAlpha: 0.3,
      trendline: { show: true, color: '#be1e2d', lineWidth: 1.0, label: 'Your Average', shadow: true },
      markerOptions: { show: true, size: 6 },
      rendererOptions: { smooth: true }
    },
    series: [
      { yaxis: 'yaxis', label: 'Own',    fill: true                                            },
      { yaxis: 'yaxis', label: 'Pool',   fill: false, trendline: { show: false }, lineWidth: 2, markerOptions: { show: true, size: 4 }},
      { yaxis: 'y3axis', label: 'Sharerate', fill: false, trendline: { show: false }              },
    ],
    legend: { show: true, location: 'sw', renderer: $.jqplot.EnhancedLegendRenderer, rendererOptions: { seriesToggleReplot: { resetAxes: true } } },
    axes: {
      yaxis:  { min: 0, pad: 1.25, label: 'Hashrate' , labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
      y3axis: { min: 0, pad: 1.25, label: 'Sharerate', labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
      xaxis:  { tickInterval: {/literal}{$GLOBAL.config.statistics_ajax_refresh_interval}{literal}, labelRenderer: $.jqplot.CanvasAxisLabelRenderer, renderer: $.jqplot.DateAxisRenderer, angle: 30, tickOptions: { formatString: '%T' } },
    },
  };

  var jqPlotShareinfoOptions = {
    title: 'Shares',
    highlighter: { show: false },
    grid: { drawBorder: false, background: '#fbfbfb', shadow: false },
    seriesColors: [ '#26a4ed', '#ee8310', '#e9e744' ],
    seriesDefaults: {
      pointLabels: { show: true },
      renderer: $.jqplot.BarRenderer,
      shadowAngle: 135,
      rendererOptions: {
        barWidth: 5,
        barDirection: 'horizontal'
      },
      trendline: { show: false },
    },
    axesDefaults: {
        autoscale: true,
        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
    },
    series: [
      {label: 'Own', }, {label: 'Pool'}
    ],
    legend: { show: true, location: 'ne', renderer: $.jqplot.EnhancedLegendRenderer, rendererOptions: { seriesToggleReplot: { resetAxes: true } } },
    axes: {
      yaxis: { tickOptions: { angle: -90 }, ticks:  [ 'valid', 'invalid' ], renderer: $.jqplot.CategoryAxisRenderer },
      xaxis: { tickOptions: { angle: -17 }, pointLabels: { show: true } }
    }
  };

  // Init empty graph with 0 data, otherwise some plugins fail
  var plot1 = $.jqplot('hashrategraph', [[storedPersonalHashrate], [storedPoolHashrate], [[0, 0.0]]], jqPlotOverviewOptions);
  var plot2 = $.jqplot('shareinfograph', [[[0]]], jqPlotShareinfoOptions);

  // Helper to initilize gauges
  function initGauges(data) {
    g1 = new JustGage({id: "nethashrate", value: parseFloat(data.getdashboarddata.data.network.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.data.network.hashrate * 2), title: "Net Hashrate", label: "{/literal}{$GLOBAL.hashunits.network}{literal}"});
    g2 = new JustGage({id: "poolhashrate", value: parseFloat(data.getdashboarddata.data.pool.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.data.pool.hashrate * 2), title: "Pool Hashrate", label: "{/literal}{$GLOBAL.hashunits.pool}{literal}"});
    g3 = new JustGage({id: "hashrate", value: parseFloat(data.getdashboarddata.data.personal.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.data.personal.hashrate * 2), title: "Hashrate", label: "{/literal}{$GLOBAL.hashunits.personal}{literal}"});
    g4 = new JustGage({id: "sharerate", value: parseFloat(data.getdashboarddata.data.personal.sharerate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.data.personal.sharerate * 2), title: "Sharerate", label: "shares/s"});
    g5 = new JustGage({id: "querytime", value: parseFloat(data.getdashboarddata.runtime).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.runtime * 3), title: "Querytime", label: "ms"});
  }

  // Helper to refresh graphs
  function refreshInformation(data) {
    g1.refresh(parseFloat(data.getdashboarddata.data.network.hashrate).toFixed(2));
    g2.refresh(parseFloat(data.getdashboarddata.data.pool.hashrate).toFixed(2));
    g3.refresh(parseFloat(data.getdashboarddata.data.personal.hashrate).toFixed(2));
    g4.refresh(parseFloat(data.getdashboarddata.data.personal.sharerate).toFixed(2));
    g5.refresh(parseFloat(data.getdashboarddata.runtime).toFixed(2));
    if (storedPersonalHashrate.length > 20) { storedPersonalHashrate.shift(); }
    if (storedPoolHashrate.length > 20) { storedPoolHashrate.shift(); }
    if (storedPersonalSharerate.length > 20) { storedPersonalSharerate.shift(); }
    timeNow = new Date().getTime();
    storedPersonalHashrate[storedPersonalHashrate.length] = [timeNow, data.getdashboarddata.data.raw.personal.hashrate];
    storedPersonalSharerate[storedPersonalSharerate.length] = [timeNow, parseFloat(data.getdashboarddata.data.personal.sharerate)];
    storedPoolHashrate[storedPoolHashrate.length] = [timeNow, data.getdashboarddata.data.raw.pool.hashrate];
    tempShareinfoData = [
        [parseInt(data.getdashboarddata.data.personal.shares.valid), parseInt(data.getdashboarddata.data.personal.shares.invalid)],
        [parseInt(data.getdashboarddata.data.pool.shares.valid), parseInt(data.getdashboarddata.data.pool.shares.invalid)]
    ];
    replotOverviewOptions = {
      data: [storedPersonalHashrate, storedPoolHashrate, storedPersonalSharerate],
      series: [ {show: plot1.series[0].show}, {show: plot1.series[1].show}, {show: plot1.series[2].show} ]
    };
    replotShareinfoOptions= {
      data: tempShareinfoData
    };
    if (typeof(plot1) != "undefined") plot1.replot(replotOverviewOptions);
    if (typeof(plot2) != "undefined") plot2.replot(replotShareinfoOptions);
  }

  // Fetch initial data via Ajax, starts proper gauges to display
  $.ajax({
    url: url,
    async: false,           // Run all others requests after this only if it's done
    dataType: 'json',
    success: function (data) { initGauges(data); }
  });

  function refreshStaticData(data) {
    $('#b-confirmed').html(data.getdashboarddata.data.personal.balance.confirmed);
    $('#b-unconfirmed').html(data.getdashboarddata.data.personal.balance.unconfirmed);
  }

  // Our worker process to keep gauges and graph updated
  (function worker() {
    $.ajax({
      url: url,
      dataType: 'json',
      success: function(data) {
        refreshInformation(data);
        refreshStaticData(data);
      },
      complete: function() {
        setTimeout(worker, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();
});
{/literal}
</script>
