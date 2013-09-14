<article class="module width_half">
  <header><h3>Gauges</h3></header>
  <div class="module_content">
    <div id="hashrate" style="width:150px; height:120px; float: left;"></div>
    <div id="poolhashrate" style="width:150px; height:120px; float: left;"></div>
    <div id="nethashrate" style="width:150px; height:120px; float: left;"></div>
    <div id="sharerate" style="width:150px; height:120px; float: left;"></div>
    <div id="querytime" style="width:150px; height:120px; float: left;"></div>
  </div>
</article>

<script>
{literal}
$(document).ready(function(){
  var g1, g2, g3, g4, g5;

  // Fetch initial data via Ajax
  $.ajax({
    url: '{/literal}{$smarty.server.PHP_SELF}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}',
    dataType: 'json',
    success: function (data) {
      g1 = new JustGage({id: "nethashrate", value: parseFloat(data.getdashboarddata.network.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.network.hashrate * 2), title: "Net Hashrate", label: "{/literal}{$GLOBAL.hashunits.network}{literal}"});
      g2 = new JustGage({id: "poolhashrate", value: parseFloat(data.getdashboarddata.pool.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.pool.hashrate * 2), title: "Pool Hashrate", label: "{/literal}{$GLOBAL.hashunits.pool}{literal}"});
      g3 = new JustGage({id: "hashrate", value: parseFloat(data.getdashboarddata.personal.hashrate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.personal.hashrate * 2), title: "Hashrate", label: "{/literal}{$GLOBAL.hashunits.personal}{literal}"});
      g4 = new JustGage({id: "sharerate", value: parseFloat(data.getdashboarddata.personal.sharerate).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.personal.sharerate * 2), title: "Sharerate", label: "shares/s"});
      g5 = new JustGage({id: "sharerate", value: parseFloat(data.getdashboarddata.datatime).toFixed(2), min: 0, max: Math.round(data.getdashboarddata.datatime * 3), title: "Querytime", label: "ms"});
    }
  });

  // Our reload and refresh gauges handler
  setInterval(function() {
    $.ajax({
      url: '{/literal}{$smarty.server.PHP_SELF}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}',
      dataType: 'json',
      success: function (data) {
        g1.refresh(parseFloat(data.getdashboarddata.network.hashrate).toFixed(2));
        g2.refresh(parseFloat(data.getdashboarddata.pool.hashrate).toFixed(2));
        g3.refresh(parseFloat(data.getdashboarddata.personal.hashrate).toFixed(2));
        g4.refresh(parseFloat(data.getdashboarddata.personal.sharerate).toFixed(2));
        g5.refresh(parseFloat(data.getdashboarddata.datatime).toFixed(2));
      }
    });
  }, 10000);
});
{/literal}
</script>
