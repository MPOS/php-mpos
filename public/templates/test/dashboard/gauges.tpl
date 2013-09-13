<article class="module width_half">
  <header><h3>Gauges</h3></header>
  <div class="module_content">
  <div id="hashrate" style="width:150px; height:120px; float: left;"></div>
  <div id="sharerate" style="width:150px; height:120px; float: left;"></div>
  </div>
</article>
<script>
{literal}
var g1, g2;
window.onload = function(){
if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 1000) {
  maxVal = 1000;
} else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 2000) {
  maxVal = 2000;
} else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 5000) {
  maxVal = 5000;
} else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 10000) {
  maxVal = 10000;
} else if ({/literal}{$GLOBAL.userdata.hashrate}{literal} < 20000) {
  maxVal = 20000;
}
var g1 = new JustGage({
  id: "hashrate",
  value: {/literal}{$GLOBAL.userdata.hashrate}{literal},
  min: 0,
  max: maxVal,
  title: "Hashrate",
  label: "ghash/s"
});

if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 0.5) {
  maxVal = 0.5;
} else if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 1.0) {
  maxVal = 1.0;
} else if ({/literal}{$GLOBAL.userdata.sharerate}{literal} < 2.0) {
  maxVal = 2.0;
}
var g2 = new JustGage({
  id: "sharerate",
  value: {/literal}{$GLOBAL.userdata.sharerate}{literal},
  min: 0,
  max: maxVal,
  title: "Sharerate",
  label: "shares/s"
});
  setInterval(function() {
    $.getJSON('{/literal}{$smarty.server.PHP_SELF}?page=api&action=getuserstatus&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}', function (data) {
      g1.refresh(data.getuserstatus.hashrate);
      g2.refresh(data.getuserstatus.sharerate);
    });
  }, 2500);
};
{/literal}
</script>
