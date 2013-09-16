<article class="module width_3_quarter">
  <header><h3>Gauges</h3></header>
  <div class="module_content">
    <div style="display: inline-block; width: 103px;">
      <div id="poolhashrate" style="width:100px; height:80px;"></div>
      <div id="sharerate" style="width:100px; height:80px;"></div>
    </div>
    <div style="display: inline-block;">
      <div id="hashrate" style="width:200px; height:160px;"></div>
    </div>
    <div style="display: inline-block; width: 103px;">
      <div id="nethashrate" style="width:100px; height:80px;"></div>
      <div id="querytime" style="width:100px; height:80px;"></div>
    </div>
  </div>
  <footer><p style="margin-left: 25px">Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.</p></footer>
</article>
