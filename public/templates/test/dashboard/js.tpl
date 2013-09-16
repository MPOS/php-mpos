<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.json2.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.highlighter.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.trendline.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.enhancedLegendRenderer.min.js"></script>

<script>
{literal}
$(document).ready(function(){
  var g1, g2, g3, g4, g5;

  // Ajax API URL
  var url = "{/literal}{$smarty.server.PHP_SELF}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";

  // Enable all included plugins
  $.jqplot.config.enablePlugins = true;

  // Store our data globally
  var storedPersonalHashrate=[];
  var storedPoolHashrate=[];

  // jqPlit defaults
  var jqPlotOptions = {
    grid: { drawBorder: false, background: '#fbfbfb', shadow: false },
    stackSeries: false,
    seriesColors: [ '#26a4ed', '#ee8310' ],
    seriesDefaults:{
      lineWidth: 4, shadow: false,
      lineAlpha: 0.3,
      fill: false, fillAndStroke: true, fillAlpha: 0.3,
      trendline: { color: '#be1e2d', lineWidth: 1.0, label: 'Your Average', shadow: true },
      markerOptions: { show: true, size: 8},
      rendererOptions: { smooth: true }
    },
    series: [ {label: 'Own', fill: true}, {label: 'Pool', trendline: { show: false } } ],
    legend: { show: true, location: 'sw', renderer: $.jqplot.EnhancedLegendRenderer, rendererOptions: { seriesToggleReplot: { resetAxes: true } } },
    title: 'Hashrate',
    axes: {
      yaxis: { padMin: 0, padMax: 1.05, label: 'Hashrate', labelRenderer: $.jqplot.CanvasAxisLabelRenderer},
      xaxis:  { tickInterval: {/literal}{$GLOBAL.config.statistics_ajax_refresh_interval}{literal}, label: 'Time', labelRenderer: $.jqplot.CanvasAxisLabelRenderer, renderer: $.jqplot.DateAxisRenderer, tickOptions: { angle: 30, formatString: '%T' } },
    },
  };

  // Init empty graph with 0 data
  var plot1 = $.jqplot('hashrategraph', [[storedPersonalHashrate], [storedPoolHashrate]], jqPlotOptions);

  // Helper to initilize gauges
  function initGauges(data) {
    g1 = new JustGage({id: "nethashrate", value: parseFloat(data.getdashboarddata.network.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.network.hashrate * 2), title: "Net Hashrate", label: "{/literal}{$GLOBAL.hashunits.network}{literal}"});
    g2 = new JustGage({id: "poolhashrate", value: parseFloat(data.getdashboarddata.pool.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.pool.hashrate * 2), title: "Pool Hashrate", label: "{/literal}{$GLOBAL.hashunits.pool}{literal}"});
    g3 = new JustGage({id: "hashrate", value: parseFloat(data.getdashboarddata.personal.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.personal.hashrate * 2), title: "Hashrate", label: "{/literal}{$GLOBAL.hashunits.personal}{literal}"});
    g4 = new JustGage({id: "sharerate", value: parseFloat(data.getdashboarddata.personal.sharerate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.personal.sharerate * 2), title: "Sharerate", label: "shares/s"});
    g5 = new JustGage({id: "sharerate", value: parseFloat(data.getdashboarddata.runtime).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.runtime * 3), title: "Querytime", label: "ms"});
  }

  // Helper to refresh gauges
  function refreshGauges(data) {
    g1.refresh(parseFloat(data.getdashboarddata.network.hashrate).toFixed(2));
    g2.refresh(parseFloat(data.getdashboarddata.pool.hashrate).toFixed(2));
    g3.refresh(parseFloat(data.getdashboarddata.personal.hashrate).toFixed(2));
    g4.refresh(parseFloat(data.getdashboarddata.personal.sharerate).toFixed(2));
    g5.refresh(parseFloat(data.getdashboarddata.runtime).toFixed(2));
  }

  // Helper to refresh graphs
  function refreshGraph(data) {
    if (storedPersonalHashrate.length > 20) { storedPersonalHashrate.shift(); }
    if (storedPoolHashrate.length > 20) { storedPoolHashrate.shift(); }
    timeNow = new Date().getTime();
    storedPersonalHashrate[storedPersonalHashrate.length] = [timeNow, data.getdashboarddata.raw.personal.hashrate];
    storedPoolHashrate[storedPoolHashrate.length] = [timeNow, data.getdashboarddata.raw.pool.hashrate];
    replotOptions = {
      data: [storedPersonalHashrate, storedPoolHashrate],
      series: [{show: plot1.series[0].show}, {show: plot1.series[1].show} ]
    };
    if (typeof(plot1) != "undefined") plot1.replot(replotOptions);
  }

  // Fetch initial data via Ajax, starts proper gauges to display
  $.ajax({
    url: url,
    async: false,           // Run all others requests after this only if it's done
    dataType: 'json',
    success: function (data) { initGauges(data); }
  });

  // Our worker process to keep gauges and graph updated
  (function worker() {
    $.ajax({
      url: url,
      dataType: 'json',
      success: function(data) { refreshGauges(data); refreshGraph(data) },
      complete: function() {
        setTimeout(worker, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();
});
{/literal}
</script>
