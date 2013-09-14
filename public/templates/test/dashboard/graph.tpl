<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.json2.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.highlighter.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="{$PATH}/js/plugins/jqplot.trendline.min.js"></script>

<article class="module width_full">
  <header><h3>Graphs</h3></header>
  <div class="module_content">
	  <div id="hashrategraph" style="height:200px; width: 95%;"></div>
  </div>
  <footer>
    <p style="margin-left: 25px">Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.</p>
  </footer>
</article>

<script>{literal}
$(document).ready(function(){
  $.jqplot.config.enablePlugins = true;

  var url = "{/literal}{$smarty.server.PHP_SELF}?page=api&action=getuserhashrate&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";
  var storedData = Array();
  var jqPlotOptions = {
    series:[{ label: 'hashrate', trendline: { color: '#d30000', lineWidth: 1.0, label: 'average' } }],
    legend: { show: true },
    title: 'Hashrate',
    
    axes: {
      yaxis:{ min:0, padMin: 0, padMax: 1.5, label: '{/literal}{$GLOBAL.hashunits.personal}{literal}', labelRenderer: $.jqplot.CanvasAxisLabelRenderer},
      xaxis:{ min:0, max: 60, tickInterval: 10, padMax: 0, label: 'Minutes', labelRenderer: $.jqplot.CanvasAxisLabelRenderer}
    },
  };
  // Init empty graph with 0 data
  for (var i = 0; i < 60; i++) { storedData[i] = [i, 0] }
  $.jqplot('hashrategraph', [storedData], jqPlotOptions);

  // Fetch current datapoint as initial data
  var d = new Date();
  $.ajax({
    url: url,
    dataType: "json",
    success: function(data) {
      storedData[d.getMinutes()] = [d.getMinutes(), data.getuserhashrate.hashrate];
      $.jqplot('hashrategraph', [storedData], jqPlotOptions).replot();
    }
  });

  // Update graph
  setInterval(function() {
    var d = new Date();
    $.ajax({
      url: url,
      dataType: "json",
      success: function(data) {
        storedData[d.getMinutes()] = [d.getMinutes(), data.getuserhashrate.hashrate];
        $.jqplot('hashrategraph', [storedData], jqPlotOptions).replot();
      }
    });
  }, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal});
});
{/literal}</script>
