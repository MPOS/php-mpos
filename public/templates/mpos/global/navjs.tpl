<script>
{literal}
$(document).ready(function(){
  var g1, g2;

  // Ajax API URL
  var url = "{/literal}{$smarty.server.PHP_SELF}?page=api&action=getnavbardata{literal}";

  // Store our data globally
  var storedHashrate=[];
  var storedWorkers=[];

  // Helper to initilize gauges
  function initGauges(data) {

        g1 = new JustGage({
            id: "mr",
            value: parseFloat(data.getnavbardata.data.pool.workers).toFixed(0),
            min: 0,
            max: Math.round(data.getnavbardata.data.pool.workers * 4),
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
            value: parseFloat(data.getnavbardata.data.pool.hashrate).toFixed(2),
            min: 0,
            max: Math.round(data.getnavbardata.data.pool.hashrate * 4),
            title: "Pool Hasrate",
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
  }

  // Helper to refresh graphs
  function refreshInformation(data) {
    g1.refresh(parseFloat(data.getnavbardata.data.pool.workers).toFixed(0));
    g2.refresh(parseFloat(data.getnavbardata.data.pool.hashrate).toFixed(2));
    if (storedWorkers.length > 20) { storedWorkers.shift(); }
    if (storedHashrate.length > 20) { storedHashrate.shift(); }
    timeNow = new Date().getTime();
    storedWorkers[storedWorkers.length] = [timeNow, data.getnavbardata.data.raw.pool.workers];
    storedHashrate[storedHashrate.length] = [timeNow, data.getnavbardata.data.raw.pool.hashrate];
  }

  // Fetch initial data via Ajax, starts proper gauges to display
  $.ajax({
    url: url,
    async: false, // Run all others requests after this only if it's done
    dataType: 'json',
    success: function (data) { initGauges(data); }
  });

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
