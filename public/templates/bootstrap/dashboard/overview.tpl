  <div class="col-lg-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">Overview {if $GLOBAL.config.price.currency}{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}: <span id="b-price">{$GLOBAL.price|number_format:"8"|default:"0"}</span>{/if} / Pool Workers: <span id="b-dworkers">{$GLOBAL.workers}</span></h4>
      </div>
      <div class="panel-body">
          <center>
          <div style="display: inline-block;">
            <div id="poolhashrate" style="width:120px; height:90px;"></div>
            <div id="sharerate" style="width:120px; height:90px;"></div>
          </div>
          <div style="display: inline-block;">
            <div id="hashrate" style="width:220px; height:180px;"></div>
          </div>
          <div style="display: inline-block;">
            <div id="nethashrate" style="width:120px; height:90px;"></div>
            <div id="querytime" style="width:120px; height:90px;"></div>
          </div>
          </center>
          {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
          <div class="flot-chart">
              <div class="flot-chart-content" id="flot-line-chart"></div>
          </div>
          {/if}
      </div>
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.
      </div>
    </div>
    <div class="row">
      {include file="dashboard/round_data.tpl"}
      {include file="dashboard/account_data.tpl"}
    </div>
  </div>