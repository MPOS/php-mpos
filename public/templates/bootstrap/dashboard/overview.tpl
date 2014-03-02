  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title">Overview {if $GLOBAL.config.price.currency}{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}: <span id="b-price">{$GLOBAL.price|number_format:"8"|default:"0"}</span>{/if} / Pool Workers: <span id="b-dworkers">{$GLOBAL.workers}</span></h4>
      </div>
      <div class="panel-body">
        <div>My Hashrate <span id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</span></span>&nbsp;&nbsp;<span class="personal-hashrate-bar"></div>
        <br>
        <div>My Sharerate <span id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</span></span>&nbsp;&nbsp;<span class="personal-sharerate-bar"></div>
        <br>
        <div><span>Pool Hashrate <span id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</span></span>&nbsp;&nbsp;<span class="pool-hashrate-bar"></div>
        <br>
        <div><span>Pool Workers <span id="b-poolworkers">{$GLOBAL.workers}</span></span>&nbsp;&nbsp;<span class="pool-workers-bar"></div>
      </div>
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.
      </div>
    </div>
    <div class="row">
      {include file="dashboard/account_data.tpl"}
    </div>
  </div>
