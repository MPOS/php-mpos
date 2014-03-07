  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-align-left fa-fw"></i> Overview</span></h4>
      </div>
      <div class="panel-body text-center {if !$GLOBAL.config.price.enabled}col-md-offset-2{/if}">
        <div class="row show-grid ">
          <div class="col-md-spark">
              <b>My Hashrate {$GLOBAL.hashunits.personal}</b>
              <span id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</span>
              <br>
              <span class="personal-hashrate-bar"></span>
          </div>
          <div class="col-md-spark">
              <b>My Sharerate</b>
              <span id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</span>
              <br>
              <span class="personal-sharerate-bar"></span>
          </div>
          <div class="col-md-spark">
              <b>Pool Hashrate {$GLOBAL.hashunits.pool}</b>
              <span id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</span>
              <br>
              <span class="pool-hashrate-bar"></span>
          </div>
          <div class="col-md-spark">
              <b>Pool Workers</b>
              <span id="b-poolhashrate">{$GLOBAL.workers}</span>
              <br>
              <span class="pool-workers-bar"></span>
          </div>
          {if $GLOBAL.config.price.enabled}
          <div class="col-md-spark">
              <b>{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}</b>
              <span id="b-price">{$GLOBAL.price|default:"0"|number_format:"8"}</span>
              <br>
              <span class="coin-price-line"></span>
          </div>
          {/if}
        </div>
      </div>
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.
      </div>
    </div>
  </div>
