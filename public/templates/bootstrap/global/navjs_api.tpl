<script>
{literal}
$(document).ready(function(){
  var g1, g2;

  // Ajax API URL
  var url = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getnavbardata{literal}";

  g1 = new JustGage({
    id: "mr",
    value: parseFloat({/literal}{$GLOBAL.workers}{literal}).toFixed(0),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.workers}{literal} * 2),
    title: "Miners",
    gaugeColor: '#6f7a8a',
    labelFontColor: '#555',
    titleFontColor: '#555',
    valueFontColor: '#555',
    label: "Active Miners",
    relativeGaugeSize: true,
    showMinMax: true,
    shadowOpacity : 0.8,
    shadowSize : 0,
    shadowVerticalOffset : 10
  });

  g2 = new JustGage({
    id: "hr",
    value: parseFloat({/literal}{$GLOBAL.hashrate}{literal}).toFixed(2),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.hashrate}{literal} * 2),
    title: "Pool Hashrate",
    gaugeColor: '#6f7a8a',
    labelFontColor: '#555',
    titleFontColor: '#555',
    valueFontColor: '#555',
    label: "{/literal}{$GLOBAL.hashunits.pool}{literal}",
    relativeGaugeSize: true,
    showMinMax: true,
    shadowOpacity : 0.8,
    shadowSize : 0,
    shadowVerticalOffset : 10
  });

  // Helper to refresh graphs
  function refreshInformation(data) {
    g1.refresh(parseFloat(data.getnavbardata.data.pool.workers).toFixed(0));
    g2.refresh(parseFloat(data.getnavbardata.data.pool.hashrate).toFixed(2));
  }

  // Our worker process to keep gauges and graph updated
  (function worker() {
    $.ajax({
      url: url,
      dataType: 'json',
      success: function(data) {
        refreshInformation(data);
      },
      complete: function() {
        setTimeout(worker, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"1000"}{literal})
      }
  });
 })();
});
{/literal}
</script>
