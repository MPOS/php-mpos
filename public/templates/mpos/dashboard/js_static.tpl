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

  // Init shares graph
  var plot2 = $.jqplot('shareinfograph', [[{/literal}{$GLOBAL.userdata.shares.valid}{literal},{/literal}{$GLOBAL.userdata.shares.invalid}{literal}],[{/literal}{$GLOBAL.roundshares.valid}{literal},{/literal}{$GLOBAL.roundshares.invalid}{literal}]], jqPlotShareinfoOptions);

  g1 = new JustGage({id: "nethashrate", value: parseFloat({/literal}{$GLOBAL.nethashrate}{literal}).toFixed(2), min: 0, max: Math.round({/literal}{$GLOBAL.nethashrate}{literal} * 2), title: "Net Hashrate", gaugeColor: '#6f7a8a', valueFontColor: '#555', shadowOpacity : 0.8, shadowSize : 0, shadowVerticalOffset : 10, label: "{/literal}{$GLOBAL.hashunits.network}{literal}"});
  g2 = new JustGage({id: "poolhashrate", value: parseFloat({/literal}{$GLOBAL.hashrate}{literal}).toFixed(2), min: 0, max: Math.round({/literal}{$GLOBAL.hashrate}{literal}* 2), title: "Pool Hashrate", gaugeColor: '#6f7a8a', valueFontColor: '#555', shadowOpacity : 0.8, shadowSize : 0, shadowVerticalOffset : 10, label: "{/literal}{$GLOBAL.hashunits.pool}{literal}"});
  g3 = new JustGage({id: "hashrate", value: parseFloat({/literal}{$GLOBAL.userdata.hashrate}{literal}).toFixed(2), min: 0, max: Math.round({/literal}{$GLOBAL.userdata.hashrate}{literal} * 2), title: "Hashrate", gaugeColor: '#6f7a8a', valueFontColor: '#555', shadowOpacity : 0.8, shadowSize : 0, shadowVerticalOffset : 10, label: "{/literal}{$GLOBAL.hashunits.personal}{literal}"});
  if ({/literal}{$GLOBAL.userdata.sharerate}{literal} > 1) {
    initSharerate = {/literal}{$GLOBAL.userdata.sharerate}{literal} * 2
  } else {
    initSharerate = 1
  }
  g4 = new JustGage({id: "sharerate", value: parseFloat({/literal}{$GLOBAL.userdata.sharerate}{literal}).toFixed(2), min: 0, max: Math.round(initSharerate), gaugeColor: '#6f7a8a', valueFontColor: '#555', shadowOpacity : 0.8, shadowSize : 0, shadowVerticalOffset : 10, title: "Sharerate", label: "shares/s"});
});
{/literal}
</script>
