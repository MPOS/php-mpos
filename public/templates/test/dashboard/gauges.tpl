<article class="module width_half">
  <header><h3>Gauges</h3></header>
  <div class="module_content">
    <div id="hashrate" style="width:150px; height:120px; float: left;"></div>
    <div id="poolhashrate" style="width:150px; height:120px; float: left;"></div>
    <div id="nethashrate" style="width:150px; height:120px; float: left;"></div>
    <div id="sharerate" style="width:150px; height:120px; float: left;"></div>
  </div>
</article>

<script>
{literal}
window.onload = function(){
  // Auto-adjust max value
  if ({/literal}{$GLOBAL.nethashrate}{literal} < 20000000000) {
    maxVal = 20;
  } else if ({/literal}{$GLOBAL.nethashrate}{literal} < 40000000000) {
    maxVal = 40;
  } else if ({/literal}{$GLOBAL.nethashrate}{literal} < 80000000000) {
    maxVal = 80;
  } else if ({/literal}{$GLOBAL.nethashrate}{literal} < 160000000000) {
    maxVal = 160;
  } else if ({/literal}{$GLOBAL.nethashrate}{literal} < 320000000000) {
    maxVal = 320;
  } else {
    maxVal = 1000;
  }
  var g1 = new JustGage({
    id: "nethashrate",
    value: {/literal}{($GLOBAL.nethashrate / 1000 / 1000 / 1000)|number_format:"2"}{literal},
    min: 0,
    max: maxVal,
    title: "Net Hashrate",
    label: "ghash/s"
  });

  // Auto-adjust max value
  if ({/literal}{$GLOBAL.hashrate}{literal} < 5000) {
    maxVal = 5;
  } else if ({/literal}{$GLOBAL.hashrate}{literal} < 10000) {
    maxVal = 10;
  } else if ({/literal}{$GLOBAL.hashrate}{literal} < 20000) {
    maxVal = 20;
  } else if ({/literal}{$GLOBAL.hashrate}{literal} < 40000) {
    maxVal = 40;
  } else if ({/literal}{$GLOBAL.hashrate}{literal} < 80000) {
    maxVal = 80;
  } else if ({/literal}{$GLOBAL.hashrate}{literal} < 160000) {
    maxVal = 160;
  } else if ({/literal}{$GLOBAL.hashrate}{literal} < 320000) {
    maxVal = 320;
  } else {
    maxVal = 1000;
  }
  var g2 = new JustGage({
    id: "poolhashrate",
    value: {/literal}{$GLOBAL.hashrate / 1000}{literal},
    min: 0,
    max: maxVal,
    title: "Pool Hashrate",
    label: "mhash/s"
  });

  // Auto-adjust max value
  if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 1000) {
    maxVal = 1;
  } else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 2000) {
    maxVal = 2;
  } else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 5000) {
    maxVal = 5;
  } else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 10000) {
    maxVal = 10;
  } else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 20000) {
    maxVal = 20;
  } else {
    maxVal = 150;
  }
  var g3 = new JustGage({
    id: "hashrate",
    value: {/literal}{$GLOBAL.userdata.hashrate / 1000}{literal},
    min: 0,
    max: maxVal,
    title: "Hashrate",
    label: "mhash/s"
  });

  // Auto-adjust max value
  if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 0.5) {
    maxVal = 0.5;
  } else if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 1.0) {
    maxVal = 1.0;
  } else if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 2.0) {
    maxVal = 2.0;
  } else if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 5.0) {
    maxVal = 5.0;
  } else if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 10.0) {
    maxVal = 10.0;
  } else if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 20.0) {
    maxVal = 20.0;
  } else {
    maxVal = 100.0;
  }
  var g4 = new JustGage({
    id: "sharerate",
    value: {/literal}{$GLOBAL.userdata.sharerate|number_format:"2"}{literal},
    min: 0,
    max: maxVal,
    title: "Sharerate",
    label: "shares/s"
  });

  // Our reload and refresh gauges handler
  setInterval(function() {
    $.getJSON('{/literal}{$smarty.server.PHP_SELF}?page=api&action=getpoolstatus&api_key={$GLOBAL.userdata.api_key}{literal}', function (data) {
      g1.refresh(parseFloat(data.getpoolstatus.nethashrate / 1000 / 1000 / 1000).toFixed(2));
      g2.refresh(parseFloat(data.getpoolstatus.hashrate / 1000).toFixed(3));
    });
    $.getJSON('{/literal}{$smarty.server.PHP_SELF}?page=api&action=getuserstatus&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}', function (data) {
      g3.refresh(parseFloat(data.getuserstatus.hashrate / 1000).toFixed(3));
      g4.refresh(parseFloat(data.getuserstatus.sharerate).toFixed(2));
    });
  }, 2000);
};
{/literal}
</script>
