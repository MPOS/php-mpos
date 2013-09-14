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
window.onload = function(){
  var g1 = new JustGage({
    id: "nethashrate",
    value: parseFloat({/literal}{$GLOBAL.nethashrate}{literal}).toFixed(2),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.nethashrate}{literal} * 2),
    title: "Net Hashrate",
    label: "{/literal}{$GLOBAL.hashunits.network}{literal}"
  });

  var g2 = new JustGage({
    id: "poolhashrate",
    value: parseFloat({/literal}{$GLOBAL.hashrate}{literal}).toFixed(2),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.hashrate}{literal} * 2),
    title: "Pool Hashrate",
    label: "{/literal}{$GLOBAL.hashunits.pool}{literal}"
  });

  var g3 = new JustGage({
    id: "hashrate",
    value: parseFloat({/literal}{$GLOBAL.userdata.hashrate}{literal}).toFixed(2),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.userdata.hashrate}{literal} * 2),
    title: "Hashrate",
    label: "{/literal}{$GLOBAL.hashunits.personal}{literal}"
  });

  var g4 = new JustGage({
    id: "sharerate",
    value: parseFloat({/literal}{$GLOBAL.userdata.sharerate}{literal}).toFixed(2),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.userdata.sharerate}{literal} * 2),
    title: "Sharerate",
    label: "shares/s"
  });
  
  var g5 = new JustGage({
    id: "sharerate",
    value: parseFloat(0.00).toFixed(2),
    min: 5,
    max: 50,
    title: "Querytime",
    label: "ms"
  });


  // Our reload and refresh gauges handler
  setInterval(function() {
    $.ajax({
      url: '{/literal}{$smarty.server.PHP_SELF}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}',
      dataType: 'json',
      async: false,
      success: function (data) {
        g1.refresh(parseFloat(data.getdashboarddata.network.hashrate).toFixed(2));
        g2.refresh(parseFloat(data.getdashboarddata.pool.hashrate).toFixed(2));
        g3.refresh(parseFloat(data.getdashboarddata.personal.hashrate).toFixed(2));
        g4.refresh(parseFloat(data.getdashboarddata.personal.sharerate).toFixed(2));
        g5.refresh(parseFloat(data.getdashboarddata.datatime).toFixed(2));
      }
    });
  }, 2000);
};
{/literal}
</script>
