<script>
{literal}
$(document).ready(function(){
  var g1, g2, g3, g4, g5;

  // Ajax API URL
  var url = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";

  // Store our data globally
  var storedPersonalHashrate=[];
  var storedPoolHashrate=[];
  var storedPersonalSharerate=[];

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
      success: function(data) {
        refreshInformation(data);
      },
      complete: function() {
        setTimeout(worker, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();
});
{/literal}
</script>
