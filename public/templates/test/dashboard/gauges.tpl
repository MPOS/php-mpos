<article class="module width_3_quarter">
  <header><h3>Gauges</h3></header>
  <div class="module_content">
    <div style="display: inline-block; min-height: 250px;">
      <div id="hashrate" style="width:150px; height:120px; float: left;"></div>
      <div id="poolhashrate" style="width:150px; height:120px; float: left;"></div>
      <div id="nethashrate" style="width:150px; height:120px; float: left;"></div>
      <div id="sharerate" style="width:150px; height:120px; float: left;"></div>
      <div id="querytime" style="width:150px; height:120px; float: left;"></div>
    </div>
  </div>
  <footer><p style="margin-left: 25px">Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.</p></footer>
</article>

<script>
{literal}
$(document).ready(function(){
  var g1, g2, g3, g4, g5;

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

  // Fetch initial data via Ajax
  $.ajax({
    url: '{/literal}{$smarty.server.PHP_SELF}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}',
    async: false,           // Run all others requests after this only if it's done
    dataType: 'json',
    success: function (data) { initGauges(data); }
  });

  // Our worker process to keep gauges updated
  (function worker() {
    $.ajax({
      url: '{/literal}{$smarty.server.PHP_SELF}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}',
      dataType: 'json',
      success: function(data) { refreshGauges(data); },
      complete: function() {
        setTimeout(worker, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();
});
{/literal}
</script>
