<article class="module width_full">
  <header><h3>Overview</h3></header>
  <div class="module_content">
    <div style="display: inline-block;">
      <div id="poolhashrate" style="width:100px; height:80px;"></div>
      <div id="sharerate" style="width:100px; height:80px;"></div>
    </div>
    <div style="display: inline-block;">
      <div id="hashrate" style="width:200px; height:160px;"></div>
    </div>
    <div style="display: inline-block;">
      <div id="nethashrate" style="width:100px; height:80px;"></div>
      <div id="querytime" style="width:100px; height:80px;"></div>
    </div>
	  <div style="margin-left: 50px; display: inline-block; width: 70%;">
      <div id="hashrategraph" style="height: 160px; width: 100%;"></div>
    </div>
  </div>
  <footer>
    <p style="margin-left: 25px">Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.</p>
  </footer>
</article>
