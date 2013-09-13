<article class="module width_half">
  <header><h3>Gauges</h3></header>
  <div class="module_content">
  <div id="hashrate" style="width:150px; height:120px; float: left;"></div>
  <div id="sharerate" style="width:150px; height:120px; float: right;"></div>
  </div>
</article>
<script>
{literal}
var g = new JustGage({
  id: "hashrate",
  value: {/literal}{$GLOBAL.userdata.hashrate}{literal},
  min: 0,
  max: 2000,
  title: "Hashrate"
});
{/literal}
</script>
<script>
{literal}
var g = new JustGage({
  id: "sharerate",
  value: {/literal}{$GLOBAL.userdata.sharerate}{literal},
  min: 0,
  max: 150,
  title: "Sharerate"
});
{/literal}
</script>
