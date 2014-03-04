  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-align-left fa-fw"></i> Statistics
      </div>
      <div class="panel-body">
        <ul class="sparklines-stats list-justified">
          <li class="bg-default">
            <div class="sparklines-stats-showcase">
              <span>My Hashrate {$GLOBAL.hashunits.personal}</span>
              <h2 id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"0"}</h2>
            </div>
            <div class="personal-hashrate-bar chart"></div>
          </li>
          <li class="bg-default">
            <div class="sparklines-stats-showcase">
              <span>My Sharerate</span>
              <h2 id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</h2>
            </div>
            <div class="personal-sharerate-bar chart"></div>
          </li>
          <li class="bg-default">
            <div class="sparklines-stats-showcase">
              <span>Pool Hashrate {$GLOBAL.hashunits.pool}</span>
              <h2 id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"0"}</h2>
            </div>
            <div class="pool-hashrate-bar chart"></div>
          </li>
          <li class="bg-default">
            <div class="sparklines-stats-showcase">
              <span>Pool Workers</span>
              <h2 id="b-poolhashrate">{$GLOBAL.workers}</h2>
            </div>
            <div class="pool-workers-bar chart"></div>
          </li>
          {if $GLOBAL.config.price.currency != ""}
          <li class="bg-default">
            <div class="sparklines-stats-showcase">
              <span>{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}</span>
              <h2 id="b-price">{$GLOBAL.price|default:"0"|number_format:"8"}</h2>
            </div>
            <div class="coin-price-line chart"></div>
          </li>
          {/if}
        </ul>
      </div>
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Estimates, based on shares submitted in the past {$INTERVAL|default:"5"} minutes.
      </div>
    </div>
  </div>
