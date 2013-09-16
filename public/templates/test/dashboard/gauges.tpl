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
